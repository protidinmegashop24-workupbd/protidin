<?php

namespace App\Http\Controllers;

use App\Models\SurveyProvider;
use App\Models\SurveyProviderConversion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SurveyProviderController extends Controller
{
    // "Start" on a provider card sends the user here first (never straight
    // to the provider) so we can build the widget URL server-side with the
    // provider's own required secure_hash -- same spirit as the existing
    // Community "Buy Now" click-through, just for surveys instead of PTC.
    public function start($slug)
    {
        $provider = SurveyProvider::where('slug', $slug)->where('enabled', true)->first();
        if (!$provider || !$provider->app_id || !$provider->secret_key) {
            return redirect()->route('surveys.index')->with('error', 'This survey provider is not available right now.');
        }

        $userId = Auth::id();

        if ($slug === 'cpx-research') {
            // Widget URL + secure_hash pattern reported consistently across
            // CPX Research's own publisher integrations: secure_hash here
            // stops someone from tampering with ext_user_id in the URL bar
            // to open surveys "as" a different user. VERIFY this exact URL
            // against your own CPX Research dashboard (Publisher ->
            // Documentation) before relying on it -- this project could not
            // reach cpx-research.com directly to confirm it byte-for-byte.
            $secureHash = md5($userId . '-' . $provider->secret_key);
            $url = 'https://offers.cpx-research.com/index.php?' . http_build_query([
                'app_id' => $provider->app_id,
                'ext_user_id' => $userId,
                'secure_hash' => $secureHash,
            ]);
            return redirect()->away($url);
        }

        return redirect()->route('surveys.index')->with('error', 'Unknown survey provider.');
    }

    // Server-to-server postback -- the ONLY place a Survey Provider
    // conversion is allowed to credit the wallet. Not behind 'auth' (the
    // provider's server calls this directly, there's no logged-in user
    // session on that request) -- protected instead by the per-provider
    // hash check below.
    public function postback($slug, Request $request)
    {
        $provider = SurveyProvider::where('slug', $slug)->where('enabled', true)->first();
        if (!$provider || !$provider->secret_key) {
            return response('0', 403);
        }

        Log::info('survey-provider-postback', ['provider' => $slug, 'query' => $request->query()]);

        if ($slug === 'cpx-research') {
            return $this->handleCpxResearchPostback($provider, $request);
        }

        return response('0', 404);
    }

    // Parameter names and the secure_hash formula (md5(trans_id-secret))
    // are the pattern consistently documented in CPX Research's own
    // publisher integration guides. VERIFY against your live CPX Research
    // dashboard's Postback Settings tab once you have API access -- this
    // project's sandbox could not load cpx-research.com directly to check
    // it against their current, official copy.
    private function handleCpxResearchPostback(SurveyProvider $provider, Request $request)
    {
        $userId   = $request->query('user_id');
        $transId  = $request->query('trans_id');
        $status   = $request->query('status');
        $hash     = $request->query('hash') ?? $request->query('secure_hash');
        $amountUsd = $request->query('amount_usd');

        if (!$userId || !$transId || $hash === null) {
            return response('0', 400);
        }

        $expectedHash = md5($transId . '-' . $provider->secret_key);
        if (!hash_equals($expectedHash, (string) $hash)) {
            Log::warning('survey-provider-postback-bad-hash', ['provider' => $provider->slug, 'trans_id' => $transId]);
            return response('0', 403);
        }

        $user = User::find($userId);
        if (!$user) {
            return response('0', 404);
        }

        // status=1 approved/completed, status=2 reversed/chargeback -- the
        // standard two values documented across CPX Research integrations.
        $newStatus = ((string) $status === '2') ? 'reversed' : 'approved';

        $existing = SurveyProviderConversion::where('provider_slug', $provider->slug)
            ->where('trans_id', $transId)
            ->first();

        if ($existing) {
            // Same transaction seen again -- only act if this is a
            // reversal of a conversion we previously approved and credited.
            // A duplicate "approved" postback is never re-credited.
            if ($newStatus === 'reversed' && $existing->status === 'approved') {
                DB::transaction(function () use ($existing) {
                    $u = User::where('id', $existing->user_id)->lockForUpdate()->first();
                    if ($u) {
                        $u->earning_balance = max(0, (float) ($u->earning_balance ?? 0) - (float) $existing->amount_usd);
                        $u->save();
                    }
                    $existing->status = 'reversed';
                    $existing->save();
                });
            }
            return response('1');
        }

        $amount = round((float) $amountUsd, 4);

        $conversion = SurveyProviderConversion::create([
            'provider_slug' => $provider->slug,
            'user_id' => $user->id,
            'trans_id' => $transId,
            'status' => $newStatus,
            'amount_usd' => $amount,
            'raw_payload' => json_encode($request->query()),
        ]);

        if ($newStatus === 'approved' && $amount > 0) {
            DB::transaction(function () use ($user, $conversion, $amount) {
                $u = User::where('id', $user->id)->lockForUpdate()->first();
                $u->earning_balance = (float) ($u->earning_balance ?? 0) + $amount;
                $u->referral_activated = 1;
                $u->save();

                $conversion->credited_at = now();
                $conversion->save();
            });
        }

        return response('1');
    }
}
