<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin\MainWallet;
use App\Models\User;
use App\Models\Admin\DepositAccount;
use App\Models\WebScript;
use App\Models\WebScriptBook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserWebScriptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $web_scripts = WebScript::latest()->get();
        return view('user.pages.web-script', compact('web_scripts'));
    }
    
    public function web_script_details($slug)
    {
        $package = WebScript::where('slug', $slug)->first();
        return view('user.pages.web-script-details', compact('package'));
    }
    
    public function web_script_booking_list()
    {
        $datas = WebScriptBook::where('user_id', Auth::user()->id)->latest()->get();
        return view('user.pages.web-script-booking-list', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($web_script_id)
    {
        $pay_accounts = DepositAccount::where('status', 1)->get();
        $package = WebScript::find($web_script_id);
        return view('user.pages.web-script-booking', compact('package', 'pay_accounts'));
    }
    
    public function web_script_from_earning($web_script_id)
    {
        $web_script = WebScript::find($web_script_id);
        if(Auth::user()->earning_balance < $web_script->price){
            return redirect()->back()->with('error','Insufficicent Balance');
        }
        
        $user = User::find(Auth::user()->id);
        $user->earning_balance = $user->earning_balance - $web_script->price;
        $user->save();
        
        $data = new WebScriptBook();
        $data->web_script_id = $web_script_id;
        $data->user_id = Auth::user()->id;
        $data->price = $web_script->price;
        $data->payment_type = 1;
        $data->status = 1;
        $data->save();

        return redirect()->back()->with('message','Investment successful');
    }
    
    public function web_script_from_deposit($web_script_id)
    {
        $web_script = WebScript::find($web_script_id);
        if(Auth::user()->deposit_balance < $web_script->price){
            return redirect()->back()->with('error','Insufficicent Balance');
        }
        
        $data = new WebScriptBook();
        $data->web_script_id = $web_script_id;
        $data->user_id = Auth::user()->id;
        $data->price = $web_script->price;
        $data->payment_type = 2;
        $data->status = 1;
        $data->save();
        
        $user = User::find(Auth::user()->id);
        $user->deposit_balance = $user->deposit_balance - $web_script->price;
        $user->save();

        return redirect()->back()->with('message','Investment successful');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'web_script_id' => 'required',
            'deposit_account' => 'required',
            'phone' => 'required',
            'receipt' => 'required',
        ]);
        
        $web_script = WebScript::find($request->input('web_script_id'));

        $data = new WebScriptBook();
        $data->web_script_id = $request->input('web_script_id');
        $data->user_id = Auth::user()->id;
        $data->price = $web_script->price;
        $data->account_id = $request->input('deposit_account');
        $data->phone = $request->input('phone');
        $data->transaction_id = $request->input('transaction_id');
        $image = $request->file('receipt');
        if ($image) {
            $image_name = Str::random(20);
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $image_name.'.'.$ext;
            $upload_path = 'backend/img/deposit/';
            $image_url = $upload_path.$image_full_name;
            $success = $image->move($upload_path, $image_full_name);
        }
        $data->receipt = $image_url;
        $data->status = 0;
        $data->save();

        return redirect()->back()->with('message','Investment successful');
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
