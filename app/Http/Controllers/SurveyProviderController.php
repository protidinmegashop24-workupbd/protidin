<?php

namespace App\Http\Controllers;

use App\Models\SurveyProvider;
use App\Models\SurveyProviderConversion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
            // Widget URL + secure_hash: confirmed directly against this
            // site's own CPX Research Postback Settings info panel.
            // secure_hash here stops someone from tampering with
            // ext_user_id in the URL bar to open surveys "as" a different user.
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

    // Returns the user's currently available individual surveys (with real
    // payout + time) as JSON, so the Surveys page can list them one by one
    // instead of a single generic "Start Surveys" button. Endpoint and
    // params are CPX Research's own "Get Surveys" API (v1.1) -- confirmed
    // directly from their official API documentation.
    public function list($slug, Request $request)
    {
        $provider = SurveyProvider::where('slug', $slug)->where('enabled', true)->first();
        if (!$provider || !$provider->app_id || !$provider->secret_key) {
            return response()->json(['status' => 'error', 'surveys' => []]);
        }

        if ($slug === 'cpx-research') {
            return response()->json($this->fetchCpxSurveyList($provider, $request));
        }

        return response()->json(['status' => 'error', 'surveys' => []]);
    }

    // CPX's docs ask publishers not to refresh more than once per 120
    // seconds and not to cache longer than that, so this caches per user
    // for exactly 120 seconds.
    private function fetchCpxSurveyList(SurveyProvider $provider, Request $request)
    {
        $userId = Auth::id();
        $cacheKey = "cpx_surveys_list_{$provider->id}_{$userId}";

        return Cache::remember($cacheKey, 120, function () use ($provider, $userId, $request) {
            $secureHash = md5($userId . '-' . $provider->secret_key);

            try {
                $response = Http::timeout(10)->get('https://live-api.cpx-research.com/api/get-surveys.php', [
                    'app_id' => $provider->app_id,
                    'ext_user_id' => $userId,
                    'subid_1' => '',
                    'subid_2' => '',
                    'output_method' => 'api',
                    'ip_user' => $request->ip(),
                    'user_agent' => (string) $request->userAgent(),
                    'limit' => 12,
                    'secure_hash' => $secureHash,
                ]);

                $data = $response->json();
                return is_array($data) ? $data : ['status' => 'error', 'surveys' => []];
            } catch (\Exception $e) {
                Log::warning('cpx-survey-list-failed', ['error' => $e->getMessage()]);
                return ['status' => 'error', 'surveys' => []];
            }
        });
    }

    // Server-to-server postback -- the ONLY place a Survey Provider
    // conversion is allowed to credit the wallet. Not behind 'auth' (the
    // provider's server calls this directly, there's no logged-in user
    // session on that request) -- protected instead by the per-provider
    // hash check below.
    public function postback($slug, Request $request)
    {
        // Deliberately NOT filtered by enabled=true here -- "enabled" only
        // controls whether the survey card/Start link is shown to users
        // (see start() above). The postback's own hash check is the real
        // security gate, so a provider can be verified/tested via a real
        // postback before flipping it on for users to see.
        $provider = SurveyProvider::where('slug', $slug)->first();
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
    // are confirmed directly from this site's own CPX Research Postback
    // Settings info panel ("secure_hash ... md5({trans_id}-yourappsecurehash)")
    // and verified end-to-end via CPX's own "Test your Postback URL" tool.
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

        // Confirmed from CPX Research's own docs: status=1 completed, status=2 canceled.
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
