<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Role;
use Illuminate\Http\Request;
use App\Models\Admin\Website;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Job;
use App\Models\JobWork;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $website = Website::latest()->first();
        $users = User::where('role_id', 3)->latest()->paginate(15);
        $roles = Role::all()->where('id', '!=', '3');
        $jobs = Job::all();
        $JobWork = JobWork::all();
        // TODO: PTC job/earn-history models aren't available yet -- placeholder
        // empty collections until the real PTC table/model names are known.
        $ptc_job = collect();
        $ptc_earn_history = collect();

        return view('backend.pages.usermanage.user', compact('users', 'roles', 'website', 'jobs', 'JobWork', 'ptc_job', 'ptc_earn_history'));
    }
    
    public function duplicate_users()
    {
        $website = Website::latest()->first();
        $users = User::where('role_id', 3)->where('is_new_device', 0)->latest()->paginate(15);
        $roles = Role::all()->where('id', '!=', '3');

        return view('backend.pages.usermanage.duplicate-user', compact('users', 'roles', 'website'));
    }
    
    public function user_search(Request $request)
    {
        $website = Website::latest()->first();
        $query = User::query();
        if ($request->search_data) {
            $query = $query->where('code', 'like', "%{$request->search_data}%");
            $query = $query->orWhere('name', 'like', "%{$request->search_data}%");
            $query = $query->orWhere('email', 'like', "%{$request->search_data}%");
        }
        $users = $query->where('role_id', 3)->latest()->paginate(15);
        $roles = Role::all()->where('id', '!=', '3');
        $jobs = Job::all();
        $JobWork = JobWork::all();
        // TODO: PTC job/earn-history models aren't available yet -- placeholder
        // empty collections until the real PTC table/model names are known.
        $ptc_job = collect();
        $ptc_earn_history = collect();

        return view('backend.pages.usermanage.user', compact('users', 'roles', 'website', 'jobs', 'JobWork', 'ptc_job', 'ptc_earn_history'));
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
            'name' => 'required|min:3|max:50',
            'username' => 'required|min:3|max:50',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
            'role_id' => 'required',
            'password' => 'required',
        ]);

        $user = new User();
        $user->name = Str::ucfirst($request->input('name'));
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->role_id = $request->input('role_id');
        $user->status = '0';
        $user->password = Hash::make($request->input('password'));
        $user->phone = $request->input('phone');

        $image = $request->file('image');
        if ($image) {
            $image_name = Str::random(20);
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $image_name.'.'.$ext;
            $upload_path = 'backend/img/user/';
            $image_url = $upload_path.$image_full_name;
            $image->move($upload_path, $image_full_name);
            $user->image = $image_url;
        }
        
        $user->save();
        return redirect()->back()->with('message','User added Successfully');
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
        $website = Website::latest()->first();
        $user = DB::table('users')->where('id', $id)->first();
        $roles = Role::all()->where('id', '!=', '3');
        return view('backend.pages.usermanage.useredit', compact('user', 'website', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function user_balance(Request $request, $id){
        $user = User::find($id);
        $user->deposit_balance = $request->deposit_balance;
        $user->earning_balance = $request->earning_balance;
        $user->save();

        return redirect()->back()->with('message','Data Updated Successfully!');
    }
    public function user_activity(Request $request, $id){
        $user = User::find($id);
        $user->status = $request->status;
        $user->reason = $request->reason;
        $user->save();

        return redirect()->back()->with('message','Data Updated Successfully!');
    }
    public function user_suspend(Request $request, $id){
        $user = User::find($id);
        $user->is_suspended = $request->is_suspended;
        if($request->is_suspended == 1){
            $user->suspend_reason = $request->suspend_reason;
            $user->suspend_release = date("Y-m-d H:i:s", strtotime('+'.$request->suspend_release.' hours'));
        }else{
            $user->suspend_reason = NULL;
            $user->suspend_release = NULL;
        }
        $user->save();

        return redirect()->back()->with('message','Data Updated Successfully!');
    }
    public function user_ban(Request $request, $id){
        $user = User::find($id);
        $user->is_ban = $request->is_ban;
        if($request->is_ban == 1){
            $user->ban_reason = $request->ban_reason;
        }else{
            $user->ban_reason = NULL;
        }
        $user->save();

        return redirect()->back()->with('message','Data Updated Successfully!');
    }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:3|max:50',
            'email' => 'required',
        ]);
        
        $user = User::find($id);
        $user->name = Str::ucfirst($request->input('name'));
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->role_id = $request->input('role_id');
        $user->phone = $request->input('phone');

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
            $image->move($upload_path, $image_full_name);
            $user->image = $image_url;
        }
        
        $user->save();
        return redirect()->back()->with('message','User update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if(file_exists($user->image)){
            unlink($user->image);
        }
        $user->delete();
        return redirect()->back()->with('message','User deleted Successfully!');
    }
    
    public function reactive_user_account($id)
    {
        $user = User::find($id);
        $user->is_suspended = 0;
        $user->is_ban = 0;
        $user->save();
        return redirect()->back()->with('message','User Successfully Reactive!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'is_verified' => 'required|in:0,1',
        ]);

        $user = User::find($id);
        $user->is_verified = $request->is_verified;
        $user->save();

        return redirect()->back()->with('message', 'Account status updated successfully!');
    }

    public function verify($id)
    {
        $user = User::find($id);
        $user->is_verified = 1;
        $user->save();

        return redirect()->back()->with('message', 'User verified successfully!');
    }

    public function kyc_verify_check()
    {
        $website = Website::latest()->first();
        $users = User::where('kyc_status', 'pending')->latest()->paginate(15);
        $roles = Role::all()->where('id', '!=', '3');
        return view('backend.pages.usermanage.kyc-requested', compact('users', 'website', 'roles'));
    }

    public function kyc_user_list()
    {
        $website = Website::latest()->first();
        $users = User::where('kyc_status', 'approve')->latest()->paginate(15);
        $roles = Role::all()->where('id', '!=', '3');
        return view('backend.pages.usermanage.kyc-user', compact('users', 'website', 'roles'));
    }

    public function kyc_user_unapprove()
    {
        $website = Website::latest()->first();
        $users = User::where('kyc_status', 'unapprove')->latest()->paginate(15);
        $roles = Role::all()->where('id', '!=', '3');
        return view('backend.pages.usermanage.kyc-unapprove', compact('users', 'website', 'roles'));
    }

    public function kyc_verify_check_update(Request $request)
    {
        $request->validate([
            'id'         => 'required|exists:users,id',
            'kyc_status' => 'required|in:pending,approve,unapprove',
            'kyc_notice' => 'nullable|string',
        ]);

        $user = User::find($request->id);
        $user->kyc_status = $request->kyc_status;
        $user->kyc_notice = $request->kyc_notice;
        $user->is_verified = $request->kyc_status === 'approve' ? 1 : 0;
        $user->save();

        return redirect()->back()->with('success', 'KYC status updated successfully.');
    }
}
