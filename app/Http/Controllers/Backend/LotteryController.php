<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Library\LotteryDrawer;
use App\Models\Admin\Website;
use App\Models\Lottery;
use App\Models\LotteryTicketBook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LotteryController extends Controller
{
    public function index()
    {
        $website = Website::latest()->first();
        $datas = Lottery::orderBy('id', 'DESC')->get()->map(function ($lottery) {
            $lottery->tickets_sold = LotteryTicketBook::where('lottery_id', $lottery->id)->count();
            $lottery->pool = round($lottery->tickets_sold * (float) $lottery->price, 4);
            return $lottery;
        });
        $title = 'Lucky Draw';

        return view('backend.pages.lottery.index', compact('website', 'datas', 'title'));
    }

    /**
     * Ticket holders + (after the draw) winners for one round. The route
     * for this already existed (lottery-bought-user-{id}) but called a
     * method that was never actually implemented.
     */
    public function lottery_bought_user($id)
    {
        $lottery = Lottery::findOrFail($id);
        $website = Website::latest()->first();
        $title = 'Tickets - ' . $lottery->title;

        $tickets = LotteryTicketBook::where('lottery_id', $id)
            ->with('user')
            ->orderByDesc('is_winner')
            ->orderBy('prize_rank')
            ->latest()
            ->get();

        return view('backend.pages.lottery.bought-users', compact('website', 'title', 'lottery', 'tickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0.0001',
            'draws_at' => 'required|date',
            'prize_percent_1st' => 'required|numeric|min:0|max:100',
            'prize_percent_2nd' => 'required|numeric|min:0|max:100',
            'prize_percent_3rd' => 'required|numeric|min:0|max:100',
            'prize_percent_top_buyer' => 'required|numeric|min:0|max:100',
            'max_tickets_per_user' => 'nullable|integer|min:1',
        ]);

        $data = new Lottery();
        $data->user_id = Auth::id();
        $data->lottery_number = (int) (Lottery::max('lottery_number') ?? 0) + 1;
        $data->title = $request->input('title');
        $data->price = $request->input('price');
        $data->prize_percent_1st = $request->input('prize_percent_1st');
        $data->prize_percent_2nd = $request->input('prize_percent_2nd');
        $data->prize_percent_3rd = $request->input('prize_percent_3rd');
        $data->prize_percent_top_buyer = $request->input('prize_percent_top_buyer');
        $data->max_tickets_per_user = $request->input('max_tickets_per_user') ?: null;
        $data->auto_renew = $request->boolean('auto_renew');
        $data->draws_at = $request->input('draws_at');
        $data->start_time = now();
        $data->end_time = $request->input('draws_at');
        $data->status = 'active';

        $image = $request->file('image');
        if (isset($image)) {
            $favicon_name = Str::random(20);
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $favicon_name . '.' . $ext;
            $upload_path = 'backend/img/investment/';
            $image_url = $upload_path . $image_full_name;
            $image->move($upload_path, $image_full_name);
            $data->image = $image_url;
        }

        $data->save();

        return redirect()->back()->with('message', 'Lucky Draw round created!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0.0001',
            'draws_at' => 'required|date',
            'prize_percent_1st' => 'required|numeric|min:0|max:100',
            'prize_percent_2nd' => 'required|numeric|min:0|max:100',
            'prize_percent_3rd' => 'required|numeric|min:0|max:100',
            'prize_percent_top_buyer' => 'required|numeric|min:0|max:100',
            'max_tickets_per_user' => 'nullable|integer|min:1',
        ]);

        $data = Lottery::findOrFail($id);
        $data->title = $request->input('title');
        $data->price = $request->input('price');
        $data->prize_percent_1st = $request->input('prize_percent_1st');
        $data->prize_percent_2nd = $request->input('prize_percent_2nd');
        $data->prize_percent_3rd = $request->input('prize_percent_3rd');
        $data->prize_percent_top_buyer = $request->input('prize_percent_top_buyer');
        $data->max_tickets_per_user = $request->input('max_tickets_per_user') ?: null;
        $data->auto_renew = $request->boolean('auto_renew');
        $data->draws_at = $request->input('draws_at');
        $data->end_time = $request->input('draws_at');

        $image = $request->file('image');
        if (isset($image)) {
            if ($data->image && file_exists($data->image)) {
                unlink($data->image);
            }
            $favicon_name = Str::random(20);
            $ext = strtolower($image->getClientOriginalExtension());
            $image_full_name = $favicon_name . '.' . $ext;
            $upload_path = 'backend/img/investment/';
            $image_url = $upload_path . $image_full_name;
            $image->move($upload_path, $image_full_name);
            $data->image = $image_url;
        }

        $data->save();

        return redirect()->back()->with('message', 'Round updated!');
    }

    /**
     * Manual fallback for the automatic (cron-driven) draw -- lets the
     * admin force a round's draw right now, e.g. if the cPanel cron job
     * isn't set up yet or a round needs to close early.
     */
    public function draw_now($id)
    {
        $lottery = Lottery::findOrFail($id);

        if ($lottery->drawn_at) {
            return redirect()->back()->with('message', 'This round was already drawn.');
        }

        $result = LotteryDrawer::draw($lottery);

        return redirect()->back()->with('message', 'Draw complete! ' . count($result['winners'] ?? []) . ' winner(s) paid from a $' . number_format($result['pool'] ?? 0, 4) . ' pool.');
    }

    public function lottery_active($id)
    {
        $data = Lottery::find($id);
        $data->status = 'active';
        $data->save();

        return redirect()->back()->with('message', 'Data Updated Successfully');
    }

    public function lottery_deactive($id)
    {
        $data = Lottery::find($id);
        $data->status = 'inactive';
        $data->save();

        return redirect()->back()->with('message', 'Data Updated Successfully');
    }

    public function destroy($id)
    {
        $data = Lottery::find($id);
        if ($data->image && file_exists($data->image)) {
            unlink($data->image);
        }
        $data->delete();

        return redirect()->back()->with('message', 'Data deleted Successfully');
    }
}
