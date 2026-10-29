<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin\MainWallet;
use App\Models\User;
use App\Models\Admin\DepositAccount;
use App\Models\Lottery;
use App\Models\LotteryTicketBook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserLotteryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Lottery::latest()->get();
        return view('user.pages.lottery', compact('datas'));
    }
    
    public function lottery_list()
    {
        $datas = LotteryTicketBook::where('user_id', Auth::user()->id)->latest()->get();
        return view('user.pages.user-lottery-list', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($lottery_id)
    {
        
    }
    
    public function lottery_buy_confirm(Request $request){
        $my_balance = 0;
        $balanceType = $request->balanceType;
        if($balanceType == 'earning'){
            $my_balance = Auth::user()->earning_balance;
        }elseif($balanceType == 'deposit'){
            $my_balance = Auth::user()->deposit_balance;
        }
        
        $lottery_id = $request->lottery_id;
        $lottery = Lottery::find($lottery_id);
        
        if($my_balance < $lottery->price){
            return response()->json(['error' => '1', 'msg' => 'Insufficicent Balance']);
        }else{
            $last_ac = LotteryTicketBook::select('id')->latest()->first();
            if (isset($last_ac)) {
                $code = sprintf('%04d', $last_ac->id + 1000001);
            } else {
                $code = sprintf('%04d', 1000001);
            }
        
            $data = new LotteryTicketBook();
            $data->user_id = Auth::user()->id;
            $data->lottery_id = $lottery_id;
            $data->code = $code;
            $data->save();
            
            if($balanceType == 'earning'){
                $user = User::find(Auth::user()->id);
                $user->earning_balance = $user->earning_balance - $lottery->price;
                $user->save();
            }elseif($balanceType == 'deposit'){
                $user = User::find(Auth::user()->id);
                $user->deposit_balance = $user->deposit_balance - $lottery->price;
                $user->save();
            }
            
            return response()->json(['error' => '0', 'msg' => 'You have successfully invested!']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
