<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin\Country;
use App\Models\User;
use App\Models\Admin\UserDailySpin;
use App\Library\EarnSocials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('user.pages.profile');
    }
    
    public function user_profile($id)
    {
        $user = User::find($id);
        return view('user.pages.user-profile', compact('user'));
    }
    
    public function add_spin_mark_to_earning(Request $request){
        $user = User::find(Auth::user()->id);
        $user->earning_balance = $user->earning_balance + $request->mark;
        $user->save();
        
        $uspin = new UserDailySpin();
        $uspin->user_id = Auth::user()->id;
        $uspin->spin_amount = $request->mark;
        $uspin->save();

        return 'Updated';
    }

    /**
     * Pays the daily "share" bonus -- rebuilt to actually verify the share
     * (a Facebook share can never be confirmed server-side, so this used
     * to just trust a client-side JS flag; this method itself didn't even
     * exist, so the Claim button fatal-errored every time). The user posts
     * their one-time daily code (earn_socials_share_code()) on
     * earnsocials.com, and this checks the BuddyPress REST API for it
     * before paying anything.
     */
    public function claim_share_bonus(Request $request)
    {
        $userId = Auth::id();
        $today = now()->toDateString();

        $alreadyClaimed = DB::table('user_share_bonuses')
            ->where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->exists();

        if ($alreadyClaimed) {
            return response()->json(['status' => false, 'message' => 'আজকের বোনাস আগেই দাবি করা হয়েছে।'], 422);
        }

        $code = earn_socials_share_code($userId, $today);

        if (!EarnSocials::codeWasPosted($code)) {
            return response()->json([
                'status' => false,
                'message' => 'earnsocials.com-এ এই কোডটা দিয়ে কোনো পোস্ট খুঁজে পাওয়া যায়নি। প্রথমে কোডটা পোস্ট করুন, তারপর আবার চেষ্টা করুন।',
            ], 422);
        }

        $bonusAmount = 0.001;

        $user = User::find($userId);
        $user->earning_balance = (float) $user->earning_balance + $bonusAmount;
        $user->save();

        DB::table('user_share_bonuses')->insert([
            'user_id' => $userId,
            'bonus_amount' => $bonusAmount,
            'share_type' => 'earnsocials',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'বোনাস যোগ হয়েছে: $' . number_format($bonusAmount, 4),
        ]);
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
    public function edit()
    {
        $countries = Country::orderBy('name', 'ASC')->get();
        return view('user.pages.profile-manage', compact('countries'));
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
            'name' => 'required|min:3|max:50',
            'phone' => 'nullable|unique:users,phone,' . $id,
        ], [
            'phone.unique' => 'এই ফোন নম্বর দিয়ে ইতিমধ্যে অন্য একটি অ্যাকাউন্ট আছে। একই ফোন নম্বর একাধিক অ্যাকাউন্টে ব্যবহার করা যাবে না।',
        ]);
        $user = User::find($id);
        if(Auth::user()->code == NULL){
            $last_ac = User::select('id')->latest()->first();
            if (isset($last_ac)) {
                $code = sprintf('%04d', $last_ac->id + 1000001);
            } else {
                $code = sprintf('%04d', 1000001);
            }
            $user->code = $code;
        }

        $user->name = $request->name;
        $user->phone = $request->phone;

        $image = $request->file('image');
        if ($image) {
            if(file_exists($user->image)){
                unlink($user->image);
            }
            $image_name = Str::random(20);
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $image_name.'.'.$ext;
            $upload_path = 'backend/img/user/';
            $image_url = $upload_path.$image_full_name;
            $success = $image->move($upload_path, $image_full_name);
            $user->image = $image_url;
        }

        if($request->password != ''){
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return redirect()->back()->with('message','Data updated Successfully!');
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
