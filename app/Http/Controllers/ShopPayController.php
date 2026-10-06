<?php

namespace App\Http\Controllers;

use App\Library\ShopPay;
use Exception;

use App\Models\Admin\Deposit;
use App\Models\DepositHeadline;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopPayController extends Controller
{
    /**
     * Show the "pay with ShopPay" page. Also re-verifies any of this
     * user's still-pending ShopPay deposits, in case the gateway's
     * webhook call never reached us (e.g. the user closed the tab
     * before being redirected back).
     */
    public function show()
    {
        $this->reverifyPendingDeposits(Auth::id());

        $headlines = DepositHeadline::all();
        return view('user.pages.shoppay.pay', compact('headlines'));
    }

    /**
     * Starts a payment and redirects the customer to ShopPay's hosted
     * payment page.
     */
    public function pay(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $user = User::find(Auth::id());

        $requestData = [
            'cus_name' => $user->name,
            'cus_email' => $user->email,
            'amount' => (string) $request->amount,
            'metadata' => [
                'user_id' => $user->id,
            ],
            'success_url' => route('user.shoppay.success'),
            'cancel_url' => route('user.shoppay.cancel'),
            'webhook_url' => route('shoppay.webhook'),
        ];

        try {
            $paymentUrl = ShopPay::init_payment($requestData);
            return redirect($paymentUrl);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'পেমেন্ট শুরু করা যায়নি: ' . $e->getMessage());
        }
    }

    /**
     * Browser returns here after payment. The gateway appends
     * ?transactionId=...&paymentMethod=...&paymentAmount=...&status=...
     * but those are client-controlled query params -- never trusted
     * directly. The transaction is always re-verified server-to-server
     * via the API before any balance is credited.
     */
    public function success(Request $request)
    {
        $transactionId = $request->query('transactionId');

        if (empty($transactionId)) {
            return view('user.pages.shoppay.cancel', ['message' => 'লেনদেনের তথ্য পাওয়া যায়নি।']);
        }

        $message = $this->verifyAndCredit($transactionId, Auth::id());

        return view('user.pages.shoppay.success', compact('message'));
    }

    public function cancel()
    {
        return view('user.pages.shoppay.cancel', ['message' => 'পেমেন্ট বাতিল করা হয়েছে।']);
    }

    /**
     * Server-to-server callback from the ShopPay panel -- the robust
     * confirmation path that works even if the customer's browser never
     * makes it back to success(). No auth here (the gateway has no
     * session), so the user is identified from the verified metadata.
     */
    public function webhook(Request $request)
    {
        $transactionId = $request->input('transactionId') ?? $request->query('transactionId');

        if (empty($transactionId)) {
            return response('Missing transactionId', 400);
        }

        $this->verifyAndCredit($transactionId, null);

        return response('OK', 200);
    }

    /**
     * Verifies a transaction with ShopPay and credits the deposit
     * exactly once. Safe to call repeatedly (from both success() and
     * webhook()) for the same transaction_id -- only the first
     * COMPLETED call credits anything.
     *
     * @param string $transactionId
     * @param int|null $fallbackUserId used only if the verify response's
     *                  metadata doesn't carry a user_id (shouldn't
     *                  normally happen, but guards against it anyway)
     * @return string a human-readable Bengali status message
     */
    private function verifyAndCredit($transactionId, $fallbackUserId)
    {
        $existing = Deposit::where('transaction_id', $transactionId)->first();
        if ($existing && $existing->approval == 1) {
            return 'এই পেমেন্ট আগেই সফলভাবে যুক্त হয়ে গেছে।';
        }

        $data = ShopPay::verify_payment($transactionId);

        if (empty($data) || empty($data['status'])) {
            return 'লেনদেন যাচাই করা যায়নি। কিছুক্ষণ পর আবার চেক করুন বা সাপোর্টে যোগাযোগ করুন।';
        }

        $userId = $data['metadata']['user_id'] ?? $fallbackUserId;
        if (empty($userId)) {
            return 'ইউজার সনাক্ত করা যায়নি।';
        }

        if ($data['status'] !== 'COMPLETED') {
            if (!$existing) {
                $this->storeDeposit($transactionId, $userId, (float) ($data['amount'] ?? 0), 0);
            }
            return $data['status'] === 'PENDING'
                ? 'আপনার পেমেন্ট এখনো প্রসেসিং-এ আছে।'
                : 'পেমেন্ট ব্যর্থ হয়েছে।';
        }

        $depositAmount = (float) ($data['amount'] ?? 0);
        $user = User::find($userId);
        if (!$user) {
            return 'ইউজার সনাক্ত করা যায়নি।';
        }

        if ($existing) {
            $existing->approval = 1;
            $existing->amount = $depositAmount;
            $existing->save();
        } else {
            $this->storeDeposit($transactionId, $userId, $depositAmount, 1);
        }

        $user->deposit_balance = $user->deposit_balance + $depositAmount;
        $user->referral_activated = 1;
        $user->save();

        credit_referral_deposit_commission($user, $depositAmount);

        return 'আপনার ডিপোজিট সফল হয়েছে: $' . number_format($depositAmount, 2);
    }

    private function storeDeposit($transactionId, $userId, $amount, $approval)
    {
        $deposit = new Deposit();
        $deposit->account_id = ShopPay::depositAccountId();
        $deposit->amount = $amount;
        $deposit->transaction_id = $transactionId;
        $deposit->user_id = $userId;
        $deposit->approval = $approval;
        $deposit->save();

        return $deposit;
    }

    /**
     * Re-checks this user's still-pending ShopPay deposits (account_id
     * scoped so manually-typed bKash/Nagad transaction IDs are never
     * sent to this API by mistake).
     */
    private function reverifyPendingDeposits($userId)
    {
        if (empty($userId)) {
            return;
        }

        $pending = Deposit::where('user_id', $userId)
            ->where('approval', 0)
            ->where('account_id', ShopPay::depositAccountId())
            ->whereNotNull('transaction_id')
            ->get();

        foreach ($pending as $deposit) {
            $this->verifyAndCredit($deposit->transaction_id, $userId);
        }
    }
}
