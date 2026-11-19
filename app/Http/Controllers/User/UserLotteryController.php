<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Lottery;
use App\Models\LotteryTicketBook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserLotteryController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $openRounds = Lottery::where('status', 1)
            ->whereNull('drawn_at')
            ->orderBy('draws_at')
            ->get()
            ->map(function ($lottery) use ($userId) {
                $lottery->total_tickets = LotteryTicketBook::where('lottery_id', $lottery->id)->count();
                $lottery->my_tickets = LotteryTicketBook::where('lottery_id', $lottery->id)->where('user_id', $userId)->count();
                $lottery->pool = round($lottery->total_tickets * (float) $lottery->price, 4);
                return $lottery;
            });

        $pastResults = Lottery::whereNotNull('drawn_at')
            ->orderByDesc('drawn_at')
            ->limit(10)
            ->get()
            ->map(function ($lottery) {
                $lottery->winning_tickets = LotteryTicketBook::where('lottery_id', $lottery->id)
                    ->where('is_winner', 1)
                    ->with('user')
                    ->orderBy('prize_rank')
                    ->get();
                return $lottery;
            });

        return view('user.pages.lottery', compact('openRounds', 'pastResults'));
    }

    public function lottery_list()
    {
        $datas = LotteryTicketBook::where('user_id', Auth::user()->id)->latest()->get();
        return view('user.pages.user-lottery-list', compact('datas'));
    }

    public function lottery_buy_confirm(Request $request)
    {
        $request->validate([
            'lottery_id' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $lottery = Lottery::find($request->lottery_id);
        if (!$lottery || (int) $lottery->status !== 1 || $lottery->drawn_at) {
            return response()->json(['error' => '1', 'msg' => 'এই ড্র এখন আর খোলা নেই।']);
        }

        $userId = Auth::id();
        $quantity = (int) $request->quantity;

        if ($lottery->max_tickets_per_user) {
            $already = LotteryTicketBook::where('lottery_id', $lottery->id)->where('user_id', $userId)->count();
            if ($already + $quantity > $lottery->max_tickets_per_user) {
                return response()->json([
                    'error' => '1',
                    'msg' => 'এই ড্রয়ে সর্বোচ্চ ' . $lottery->max_tickets_per_user . ' টা টিকিট কেনা যাবে। আপনার কাছে ইতিমধ্যে ' . $already . ' টা আছে।',
                ]);
            }
        }

        $totalCost = round($quantity * (float) $lottery->price, 4);

        $user = User::find($userId);
        if ((float) $user->earning_balance < $totalCost) {
            return response()->json(['error' => '1', 'msg' => 'Earning balance-এ পর্যাপ্ত টাকা নেই।']);
        }

        DB::transaction(function () use ($user, $lottery, $quantity, $totalCost) {
            $user->earning_balance = (float) $user->earning_balance - $totalCost;
            $user->save();

            $last = LotteryTicketBook::select('id')->latest()->first();
            $nextSeq = $last ? $last->id + 1000001 : 1000001;

            for ($i = 0; $i < $quantity; $i++) {
                $ticket = new LotteryTicketBook();
                $ticket->user_id = $user->id;
                $ticket->lottery_id = $lottery->id;
                $ticket->code = sprintf('%04d', $nextSeq + $i);
                $ticket->save();
            }
        });

        return response()->json(['error' => '0', 'msg' => $quantity . ' টা টিকিট কেনা হয়েছে! শুভকামনা।']);
    }

    public function create($lottery_id)
    {
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
