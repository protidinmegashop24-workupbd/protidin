<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Website;
use App\Models\Lottery;
use App\Models\LotteryTicketBook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LotteryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $website = Website::latest()->first();
        $datas = Lottery::orderBy('id', 'ASC')->get();
        $title = 'Lottery';

        return view('backend.pages.lottery.index', compact('website', 'datas', 'title'));
    }
    
    public function service_item_booking_list()
    {
        $datas = LotteryTicketBook::latest()->get();
        $website = Website::latest()->first();
        $title = 'Service Booking List';
        return view('backend.pages.lottery.lottery-booking-list', compact('title', 'datas', 'website'));
    }

    public function pending_service_item_booking()
    {
        $datas = LotteryTicketBook::where('status', 0)->latest()->get();
        $website = Website::latest()->first();
        $title = 'Pending Purchase List';
        return view('backend.pages.lottery.lottery-booking-list', compact('title', 'datas', 'website'));
    }

    public function service_item_booking_approved(Request $request, $id)
    {
        $deposit = LotteryTicketBook::find($id);
        $msg_user_id = $deposit->user_id;

        if($request->approval == 1){
            $user = User::find($deposit->user_id);
            // $user->deposit_balance = $user->deposit_balance + $deposit->amount;

            // $website = Website::latest()->first();
            // if($website->referral_deposit_commission > 0){
            //     $deposit_commission = ($website->referral_deposit_commission * $deposit->amount) / 100;

            //     $refered_by = User::find($user->rfered_by);
            //     if($refered_by){
            //         $refered_by->deposit_balance = $refered_by->deposit_balance + $deposit_commission;
            //         $refered_by->save();
    
            //         $user->deposit_commision_from_refer = $user->deposit_commision_from_refer + $deposit_commission;
            //     }
            // }

            // $user->save();
        
            // $data = new UserMessage();
            // $data->user_id = $msg_user_id;
            // $data->message_title = 'Deposite';
            // $data->message = 'Your last deposit approved.';
            // $data->save();
        }elseif($request->approval == 2){
            $deposit->reason = $request->reason;
        
            // $data = new UserMessage();
            // $data->user_id = $msg_user_id;
            // $data->message_title = 'Deposite';
            // $data->message = 'Your last deposit rejected.';
            // $data->save();
        }
        
        $deposit->status = $request->approval;
        $deposit->save();

        return redirect()->back()->with('message','Successfully approved this deposit!');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'price' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $data = new Lottery();
        $data->title = $request->input('title');
        $data->price = $request->input('price');
        $data->winner = $request->input('winner');
        $data->start_time = $request->input('start_time');
        $data->end_time = $request->input('end_time');
        
        $image = $request->file('image');
        if (isset($image)) {
            $favicon_name = Str::random(20);
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $favicon_name.'.'.$ext;
            $upload_path = 'backend/img/investment/';
            $image_url = $upload_path.$image_full_name;
            $success = $image->move($upload_path, $image_full_name);
            $data->image = $image_url;
        }
        
        $data->save();

        return redirect()->back()->with('message','Data added Successfully');
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
        $validatedData = $request->validate([
            'price' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $data = Lottery::find($id);
        $data->title = $request->input('title');
        $data->price = $request->input('price');
        $data->winner = $request->input('winner');
        $data->start_time = $request->input('start_time');
        $data->end_time = $request->input('end_time');
        
        $image = $request->file('image');
        if (isset($image)) {
            if (file_exists($data->image)) {
                unlink($data->image);
            }
            $favicon_name = Str::random(20);
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $favicon_name.'.'.$ext;
            $upload_path = 'backend/img/investment/';
            $image_url = $upload_path.$image_full_name;
            $success = $image->move($upload_path, $image_full_name);
            $data->image = $image_url;
        }
        
        $data->save();

        return redirect()->back()->with('message','Data updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lottery_active($id)
    {
        $data = Lottery::find($id);
        $data->status = 1;
        $data->save();

        return redirect()->back()->with('message','Data Updated Successfully');
    }
    
    public function lottery_deactive($id)
    {
        $data = Lottery::find($id);
        $data->status = 0;
        $data->save();

        return redirect()->back()->with('message','Data Updated Successfully');
    }
    
    public function destroy($id)
    {
        $data = Lottery::find($id);
        if (file_exists($data->image)) {
            unlink($data->image);
        }
        $data->delete();

        return redirect()->back()->with('message','Data deleted Successfully');
    }
}
