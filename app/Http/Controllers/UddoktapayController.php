<?php

namespace App\Http\Controllers;

use App\Library\UddoktaPay;
use Exception;
use Illuminate\Http\Request;

use App\Models\Admin\Deposit;
use App\Models\Admin\DepositAccount;
use App\Models\DepositHeadline;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UddoktapayController extends Controller
{
    
    /**
     * Show the payment view
     *
     * @return void
     */
    public function show()
    {
        $userId = Auth::user()->id;
        $userInfo = User::find(Auth::user()->id);
        $deposits = Deposit::where(['user_id' => $userId, 'approval' => 0])->get();
        
        foreach($deposits as $deposit) {
            $data = UddoktaPay::verify_payment($deposit->invoice_id);
            if (isset($data['status']) && $data['status'] == 'COMPLETED') {
                $userInfo->deposit_balance = $userInfo->deposit_balance + ($data['amount'] / 100);
                $userInfo->save();
                
                $deposit_update = Deposit::find($deposit->id);
                $deposit_update->approval = 1;
                $deposit_update->save();
            }
        }
        
        $headlines = DepositHeadline::all();
        return view( 'user.pages.uddoktapay.pay', compact('headlines') );
    }

    /**
     * Initializes the payment
     *
     * @param Request $request
     * @return void
     */
    public function pay(Request $request)
    {
        $validatedData = $request->validate([
            'amount'    => ['required', 'integer'],
        ]);
        
        $user_id = Auth::user()->id;
        $user_info = User::find($user_id);

        $requestData = [
            'full_name'    => $user_info['name'],
            'email'        => $user_info['email'],
            'amount'       => $validatedData['amount'] * 100,
            'metadata'     => [
                'user_id'   => $user_id,
                'metadata_1' => 'foo',
                'metadata_2' => 'bar',
            ],
            'redirect_url'  => route( 'user.success' ),
            'return_type'   => 'GET',
            'cancel_url'    => route( 'user.cancel' ),
            'webhook_url'   => route('uddoktapay.webhook'),
        ];

        try {
            $paymentUrl = UddoktaPay::init_payment($requestData);
            return redirect($paymentUrl);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Reponse from sever
     *
     * @param Request $request
     * @return void
     */
    public function webhook(Request $request)
    {

        $headerAPI = isset($_SERVER['HTTP_RT_UDDOKTAPAY_API_KEY']) ? $_SERVER['HTTP_RT_UDDOKTAPAY_API_KEY'] : NULL;

        if (empty($headerAPI)) {
            return response("Api key not found", 403);
        }

        if ($headerAPI != env("UDDOKTAPAY_API_KEY")) {
            return response("Unauthorized Action", 403);
        }

        $bodyContent = trim($request->getContent());
        $bodyData = json_decode($bodyContent);
        $data = UddoktaPay::verify_payment($bodyData->invoice_id);
        if (isset($data['status']) && $data['status'] == 'COMPLETED') {
            // Do action with $data
        }
    }

    /**
     * Success URL
     *
     * @return void
     */
    public function success(Request $request)
    {
        if (empty($request->invoice_id)) {
            die('Invalid Request');
        }
        
        $data = UddoktaPay::verify_payment($request->invoice_id);
        
        $transaction_check = Deposit::where('transaction_id', $data['transaction_id'])->get();
        if(count($transaction_check) == 0) {
            // do action with $data
            if($data['payment_method'] == 'bkash') {
                $account_id = 6;
            } elseif($data['payment_method'] == 'nagad') {
                $account_id = 11;
            } else {
                $account_id = 5;
            }
            $deposit = new Deposit();
            $deposit->account_id = $account_id;
            $deposit->amount = $data['amount'] / 100;
            $deposit->phone = $data['sender_number'];
            $deposit->transaction_id = $data['transaction_id'];
            $deposit->invoice_id = $data['invoice_id'];
            $deposit->user_id = Auth::user()->id;
            
            if (isset($data['status']) && $data['status'] == 'COMPLETED') {
                $user = User::find(Auth::user()->id);
                $user->deposit_balance = $user->deposit_balance + ($data['amount'] / 100);
                $user->save();
                
                $message = 'Deposit success.';
                
                $deposit->approval = 1;
            } else {
                $message = 'Deposit pending.';
                
                $deposit->approval = 0;
            }
            
            $deposit->save();
        } else {
            
            $userId = Auth::user()->id;
            $userInfo = User::find(Auth::user()->id);
            $deposits = Deposit::where(['user_id' => $userId, 'approval' => 0])->get();
            
            foreach($deposits as $deposit) {
                $data = UddoktaPay::verify_payment($deposit->invoice_id);
                if (isset($data['status']) && $data['status'] == 'COMPLETED') {
                    $userInfo->deposit_balance = $userInfo->deposit_balance + ($data['amount'] / 100);
                    $userInfo->save();
                    
                    $deposit_update = Deposit::find($deposit->id);
                    $deposit_update->approval = 1;
                    $deposit_update->save();
                }
            }
            
            $message = 'Deposit already exists.';
        }
        
        return view( 'user.pages.uddoktapay.success', compact('message') );
    }

    /**
     * Cancel URL
     *
     * @return void
     */
    public function done(Request $request) {
        if (empty($request->invoice_id)) {
            die('Invalid Request');
        }
        
        $data = UddoktaPay::verify_payment($request->invoice_id);
        
        $transaction_check = Deposit::where('transaction_id', $data['transaction_id'])->get();
        if(!$transaction_check) {
            // do action with $data
            if($data['payment_method'] == 'bkash') {
                $account_id = 6;
            } elseif($data['payment_method'] == 'nagad') {
                $account_id = 11;
            } else {
                $account_id = 5;
            }
            $deposit = new Deposit();
            $deposit->account_id = $account_id;
            $deposit->amount = $data['amount'] / 90;
            $deposit->phone = $data['sender_number'];
            $deposit->transaction_id = $data['transaction_id'];
            $deposit->user_id = Auth::user()->id;
            
            if (isset($data['status']) && $data['status'] == 'COMPLETED') {
                $user = User::find(Auth::user()->id);
                $user->deposit_balance = $user->deposit_balance + ($data['amount'] / 50);
                $user->save();
                
                $message = 'Deposit success.';
                
                $deposit->approval = 1;
            } else {
                $message = 'Deposit pending.';
                
                $deposit->approval = 0;
            }
            
            $deposit->save();
        } else {
            $message = 'Deposit already exists.';
        }
        
        return view( 'user.pages.uddoktapay.success', compact('message') );
    }
    
    public function cancel() {
        return view( 'user.pages.uddoktapay.cancel' );
    }
}