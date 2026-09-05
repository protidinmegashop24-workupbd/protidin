<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Website;
use App\Models\WebScript;
use App\Models\WebScriptBook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebScriptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $website = Website::latest()->first();
        $datas = WebScript::orderBy('id', 'ASC')->get();
        $title = 'Web Script';

        return view('backend.pages.web-script.index', compact('website', 'datas', 'title'));
    }
    
    public function service_item_booking_list()
    {
        $datas = WebScriptBook::latest()->get();
        $website = Website::latest()->first();
        $title = 'Service Booking List';
        return view('backend.pages.web-script.web-script-booking-list', compact('title', 'datas', 'website'));
    }

    public function pending_service_item_booking()
    {
        $datas = WebScriptBook::where('status', 0)->latest()->get();
        $website = Website::latest()->first();
        $title = 'Pending Purchase List';
        return view('backend.pages.web-script.web-script-booking-list', compact('title', 'datas', 'website'));
    }

    public function service_item_booking_approved(Request $request, $id)
    {
        $deposit = WebScriptBook::find($id);
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
        ]);

        $data = new WebScript();
        $data->title = $request->input('title');
        $data->slug = Str::slug($request->input('title'));
        $data->price = $request->input('price');
        $data->details = $request->input('details');
        $data->live_preview_url = $request->input('live_preview_url');
        $data->download_url = $request->input('download_url');
        
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
        ]);

        $data = WebScript::find($id);
        $data->title = $request->input('title');
        $data->price = $request->input('price');
        $data->details = $request->input('details');
        $data->live_preview_url = $request->input('live_preview_url');
        $data->download_url = $request->input('download_url');
        
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
    public function destroy($id)
    {
        $data = WebScript::find($id);
        if (file_exists($data->image)) {
            unlink($data->image);
        }
        $data->delete();

        return redirect()->back()->with('message','Data deleted Successfully');
    }
}
