<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin\MainWallet;
use App\Models\User;
use App\Models\Admin\DepositAccount;
use App\Models\Admin\ServiceItem;
use App\Models\Admin\ServiceItemBook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserServiceItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service_items = ServiceItem::latest()->get();
        return view('user.pages.service-item', compact('service_items'));
    }
    
    public function service_item_details($service_item_id)
    {
        $package = ServiceItem::find($service_item_id);
        return view('user.pages.service-item-details', compact('package'));
    }
    
    public function service_item_booking_list()
    {
        $datas = ServiceItemBook::where('user_id', Auth::user()->id)->latest()->get();
        return view('user.pages.service-item-booking-list', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($service_item_id)
    {
        $pay_accounts = DepositAccount::where('status', 1)->get();
        $package = ServiceItem::find($service_item_id);
        return view('user.pages.service-item-booking', compact('package', 'pay_accounts'));
    }
    
    public function service_item_buy_confirm(Request $request){
        $my_balance = 0;
        $balanceType = $request->balanceType;
        if($balanceType == 'earning'){
            $my_balance = Auth::user()->earning_balance;
        }elseif($balanceType == 'deposit'){
            $my_balance = Auth::user()->deposit_balance;
        }
        
        $service_item_id = $request->package_id;
        $service_item = ServiceItem::find($service_item_id);
        
        if($my_balance < $service_item->price){
            return response()->json(['error' => '1', 'msg' => 'Insufficicent Balance']);
        }else{
            $data = new ServiceItemBook();
            $data->service_item_id = $service_item->id;
            $data->user_id = Auth::user()->id;
            $data->price = $service_item->price;
            if($balanceType == 'earning'){
                $data->payment_type = 1;
            }elseif($balanceType == 'deposit'){
                $data->payment_type = 2;
            }
            $data->status = 1;
            $data->save();
            
            if($balanceType == 'earning'){
                $user = User::find(Auth::user()->id);
                $user->earning_balance = $user->earning_balance - $service_item->price;
                $user->save();
            }elseif($balanceType == 'deposit'){
                $user = User::find(Auth::user()->id);
                $user->deposit_balance = $user->deposit_balance - $service_item->price;
                $user->save();
            }
            
            return response()->json(['error' => '0', 'msg' => 'You have successfully paid for this service!']);
        }
    }
    
    public function service_item_from_earning($service_item_id)
    {
        $service_item = ServiceItem::find($service_item_id);
        if(Auth::user()->earning_balance < $service_item->price){
            return redirect()->back()->with('error','Insufficicent Balance');
        }
        
        $user = User::find(Auth::user()->id);
        $user->earning_balance = $user->earning_balance - $service_item->price;
        $user->save();
        
        $data = new ServiceItemBook();
        $data->service_item_id = $service_item_id;
        $data->user_id = Auth::user()->id;
        $data->price = $service_item->price;
        $data->payment_type = 1;
        $data->status = 1;
        $data->save();

        return redirect()->back()->with('message','Investment successful');
    }
    
    public function service_item_from_deposit($service_item_id)
    {
        $service_item = ServiceItem::find($service_item_id);
        if(Auth::user()->deposit_balance < $service_item->price){
            return redirect()->back()->with('error','Insufficicent Balance');
        }
        
        $data = new ServiceItemBook();
        $data->service_item_id = $service_item_id;
        $data->user_id = Auth::user()->id;
        $data->price = $service_item->price;
        $data->payment_type = 2;
        $data->status = 1;
        $data->save();
        
        $user = User::find(Auth::user()->id);
        $user->deposit_balance = $user->deposit_balance - $service_item->price;
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
            'service_item_id' => 'required',
            'deposit_account' => 'required',
            'phone' => 'required',
            'receipt' => 'required',
        ]);
        
        $service_item = ServiceItem::find($request->input('service_item_id'));

        $data = new ServiceItemBook();
        $data->service_item_id = $request->input('service_item_id');
        $data->user_id = Auth::user()->id;
        $data->price = $service_item->price;
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
