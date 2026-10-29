<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DailyBonusController extends Controller
{
    public function status()
    {
        $user = User::find(Auth::id());
        return response()->json(daily_login_bonus_status($user));
    }

    public function claim()
    {
        $user = User::find(Auth::id());
        $result = claim_daily_login_bonus_now($user);

        return response()->json($result);
    }
}
