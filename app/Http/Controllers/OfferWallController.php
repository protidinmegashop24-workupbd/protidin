<?php

namespace App\Http\Controllers;

use App\Models\OfferWallConversion;
use App\Models\OfferWallProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OfferWallController extends Controller
{
    // Unlike CPX Research's widget URL (confirmed directly from their own
    // dashboard), none of these 3 providers' entry/widget URLs have been
    // confirmed yet. So the URL lives in the admin-editable
    // widget_url_template field (with a {user_id} placeholder) instead of
    // being hardcoded here -- fill it in from each provider's own
    // dashboard/docs once you have an account, no code change needed.
    public function start($slug)
    {
        $provider = OfferWallProvider::where('slug', $slug)->where('enabled', true)->first();
        if (!$provider || !$provider->widget_url_template) {
            return redirect()->route('find-job')->with('error', 'This offer wall is not connected yet.');
        }

        $userId = Auth::id();

        if ($slug === 'cpagrip') {
            // CPAGrip's Offer Wall isn't a page we redirect to -- their
            // Code Generator gives a JS "Wall Locker" <script> tag that
            // renders the offer list once embedded on an actual page
            // (confirmed from their own Code Generator dialog). So we
            // render our own minimal host page and embed it there, with
            // tracking_id appended to the script src the same way
            // CPAGrip's own docs describe appending it to a monetization
            // tool's src URL.
            $scriptSrc = str_replace('{user_id}', (string) $userId, $provider->widget_url_template);
            return view('user.pages.offerwall-embed', compact('scriptSrc'));
        }

        $url = $provider->widget_url_template;

        if (strpos($url, '{hash}') !== false) {
            $hash = $this->computeHash($slug, $userId, $provider);
            if ($hash === null) {
                // We don't yet know this provider's required hash formula --
                // sending an unsigned/garbage hash would just get the user
                // an error on the provider's side, so refuse instead.
                return redirect()->route('find-job')->with('error', 'This offer wall is not fully configured yet.');
            }
            $url = str_replace('{hash}', $hash, $url);
        }

        $url = str_replace('{user_id}', (string) $userId, $url);

        return redirect()->away($url);
    }

    // No provider's secure_hash formula for the widget URL is confirmed
    // yet (see class docblock above) -- add a case here once one is,
    // sourced from that provider's own docs/dashboard, the same way CPX
    // Research's md5(user_id-secret) was confirmed before use.
    private function computeHash($slug, $userId, OfferWallProvider $provider)
    {
        return null;
    }

    // Server-to-server postback for all 3 Offer Wall providers. None of
    // their exact postback parameter names or signature formulas are
    // confirmed yet (BitLabs' and Lootably's docs domains are unreachable
    // from here, and AdGate Media's postback has no signature at all per
    // their own public SDK source, which makes guessing field names
    // actively risky -- a wrong guess could credit the wrong amount to the
    // wrong user). So for now this only logs the raw request and records
    // it as "unverified" for inspection -- it does NOT credit any wallet.
    // Once you have a real account and can trigger a real test postback
    // (like we did for CPX Research), send me: (1) that test postback's
    // full request as logged here or shown in the provider's own test
    // tool, and (2) their Postback Settings/docs page. Then I'll implement
    // real hash verification + crediting for that provider specifically.
    public function postback($slug, Request $request)
    {
        $provider = OfferWallProvider::where('slug', $slug)->first();
        if (!$provider) {
            return response('0', 404);
        }

        if ($slug === 'cpagrip') {
            return $this->cpagripPostback($provider, $request);
        }

        $all = array_merge($request->query(), $request->post());

        Log::info('offer-wall-postback-unverified', ['provider' => $slug, 'params' => $all]);

        $userId = $all['user_id'] ?? $all['userID'] ?? $all['ext_user_id'] ?? $all['sub_id'] ?? null;
        $transId = $all['trans_id'] ?? $all['transaction_id'] ?? $all['tx_id'] ?? $all['click_id'] ?? null;
        $amount = $all['amount_usd'] ?? $all['payout'] ?? $all['amount'] ?? $all['reward'] ?? $all['revenue'] ?? null;

        OfferWallConversion::create([
            'provider_slug' => $provider->slug,
            'user_id' => is_numeric($userId) ? (int) $userId : null,
            'trans_id' => $transId,
            'status' => 'unverified',
            'amount_usd' => is_numeric($amount) ? round((float) $amount, 4) : 0,
            'raw_payload' => json_encode($all),
        ]);

        // Acknowledge receipt so the provider's own test tool shows
        // success -- this is intentionally separate from crediting, which
        // is not happening yet (see docblock above).
        return response('1');
    }

    // CPAGrip's Global Postback confirmed straight from their own
    // dashboard (Postback Tools -> Global Postback): [POST] password,
    // payout, offer_id, tracking_id. tracking_id is whatever we appended
    // to the offer/offerwall link -- here, our own user id (see start()'s
    // widget_url_template, which appends &tracking_id={user_id}).
    //
    // CPAGrip sends no unique conversion/lead id, so trans_id is
    // synthesized as "user{id}-offer{offer_id}": a retried postback for
    // the same conversion is recognized and not double-credited, at the
    // cost of not crediting the same user for the same offer a second
    // time even if that were legitimate (rare enough to accept).
    private function cpagripPostback(OfferWallProvider $provider, Request $request)
    {
        $all = array_merge($request->query(), $request->post());

        if (!$provider->secret_key || ($all['password'] ?? null) !== $provider->secret_key) {
            Log::warning('offer-wall-postback-bad-password', ['provider' => 'cpagrip', 'params' => $all]);
            return response('0', 403);
        }

        $userId = $all['tracking_id'] ?? null;
        $offerId = $all['offer_id'] ?? null;
        $payout = $all['payout'] ?? null;

        if (!is_numeric($userId) || !is_numeric($payout)) {
            Log::warning('offer-wall-postback-bad-params', ['provider' => 'cpagrip', 'params' => $all]);
            return response('0', 400);
        }

        $transId = 'user' . $userId . '-offer' . $offerId;

        if (OfferWallConversion::where('provider_slug', 'cpagrip')->where('trans_id', $transId)->exists()) {
            // Already recorded (and credited) -- ack without double crediting.
            return response('1');
        }

        $amount = round((float) $payout, 4);

        $conversion = OfferWallConversion::create([
            'provider_slug' => 'cpagrip',
            'user_id' => (int) $userId,
            'trans_id' => $transId,
            'status' => 'approved',
            'amount_usd' => $amount,
            'raw_payload' => json_encode($all),
            'credited_at' => now(),
        ]);

        $user = User::find($userId);
        if ($user) {
            $user->earning_balance = (float) $user->earning_balance + $amount;
            $user->referral_activated = 1;
            $user->save();

            credit_referral_earning_commission($user, $amount);
        } else {
            // Postback for a user id that doesn't exist on this site --
            // keep the record (for audit) but don't pretend it was credited.
            $conversion->status = 'unverified';
            $conversion->credited_at = null;
            $conversion->save();
        }

        return response('1');
    }
}
