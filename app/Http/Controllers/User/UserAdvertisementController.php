<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin\Advertisement;
use App\Models\Admin\MainWallet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserAdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.pages.ads');
    }

    public function advertisement_list()
    {
        $datas = Advertisement::where('user_id', Auth::user()->id)->latest()->get();
        return view('user.pages.ads-list', compact('datas'));
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
        $request->validate([
            'title' => 'required',
            'image' => 'required',
            'days' => 'required',
        ]);

        $days = $request->days;
        if(Auth::user()->deposit_balance < $days){
            return redirect()->back()->with('error','You have no sufficient balance for ad.');
        }else{
            $user_balance = User::find(Auth::user()->id);
            $user_balance->deposit_balance = $user_balance->deposit_balance - $days;
            $user_balance->save();

            $main_wallet = MainWallet::latest()->first();
            $main_wallet->amount = $main_wallet->amount + $days;
            $main_wallet->save();
        }

        $today = date("Y-m-d");
        $exp_date = date( "Y-m-d", strtotime( "$today +$days day" ) );

        $category = new Advertisement();
        $category->title = $request->input('title');
        $category->link = $request->input('link');

        $image = $request->file('image');
        if ($image) {
            $image_name = Str::random(20);
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $image_name.'.'.$ext;
            $upload_path = 'backend/img/ads/';
            $image_url = $upload_path.$image_full_name;
            $success = $image->move($upload_path, $image_full_name);
        }
        $category->image = $image_url;
        $category->exp_date = $exp_date;
        $category->duration = $days;
        $category->cost = $days;
        $category->user_id = Auth::user()->id;
        $category->save();

        return redirect()->back()->with('message','Ad posted successfully');
    }

    // Video Ad: a separate ad type shown in the Reels feed, paid per-view
    // instead of per-day like the banner flow above. The advertiser
    // deposits the full budget up front (same "pay from deposit_balance"
    // pattern as banners); it's spent down as real views get rewarded
    // (ReelController-equivalent postback in socialEarnController), and
    // whatever's left is refunded if admin rejects it.
    public function storeVideo(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'nullable|url',
            'video' => 'required|mimes:mp4,mov,webm|max:51200',
            'budget_total' => 'required|numeric|min:1',
            'reward_per_view' => 'required|numeric|min:0.0001|max:1',
        ]);

        $budget = (float) $request->budget_total;
        if (Auth::user()->deposit_balance < $budget) {
            return redirect()->back()->with('error', 'You have no sufficient balance for this ad budget.');
        }

        $user = User::find(Auth::id());
        $user->deposit_balance = $user->deposit_balance - $budget;
        $user->save();

        $mainWallet = MainWallet::latest()->first();
        if ($mainWallet) {
            $mainWallet->amount = $mainWallet->amount + $budget;
            $mainWallet->save();
        }

        $video = $request->file('video');
        $videoName = Str::random(20) . '.' . strtolower($video->getClientOriginalExtension());
        $uploadPath = 'backend/img/ads/';
        $video->move($uploadPath, $videoName);

        $ad = new Advertisement();
        $ad->user_id = Auth::id();
        $ad->ad_type = 'video';
        $ad->title = $request->title;
        $ad->link = $request->link;
        $ad->video_path = $uploadPath . $videoName;
        // The banner columns are NOT NULL on this table -- 'image' gets a
        // harmless placeholder (never rendered for a video ad) and
        // exp_date is set far out since a video ad runs until its budget
        // is spent, not until a fixed date like banners.
        $ad->image = $uploadPath . $videoName;
        $ad->exp_date = now()->addYear()->toDateString();
        $ad->duration = 0;
        $ad->cost = 0;
        $ad->budget_total = $budget;
        $ad->budget_spent = 0;
        $ad->reward_per_view = (float) $request->reward_per_view;
        // No advertiser-facing margin for now -- the platform passes the
        // reward through at cost. Add a markup here later if desired.
        $ad->cost_per_view = (float) $request->reward_per_view;
        $ad->approval = 0;
        $ad->save();

        return redirect()->back()->with('message', 'Video ad submitted for approval.');
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
