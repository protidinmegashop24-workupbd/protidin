<?php

namespace App\Library;

use App\Models\Lottery;
use App\Models\LotteryTicketBook;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * The actual "Lucky Draw" mechanics -- shared by the token-protected cron
 * route (routes/web.php, hit on a schedule) and the admin's manual
 * "Draw Now" button, so both go through the exact same logic.
 */
class LotteryDrawer
{
    /**
     * Rounds whose scheduled draw time has passed and haven't been drawn
     * yet.
     */
    public static function dueRounds()
    {
        return Lottery::where('status', 'active')
            ->whereNull('drawn_at')
            ->whereNotNull('draws_at')
            ->where('draws_at', '<=', now())
            ->get();
    }

    /**
     * Draws a single round: picks up to 3 distinct winning tickets at
     * random, pays each winner their percentage of the ticket pool, marks
     * the round drawn, and (if auto_renew) creates next week's round.
     *
     * Wrapped in a row lock + drawn_at-null check so hitting the cron URL
     * twice in a row (or the cron and the admin's "Draw Now" button
     * overlapping) can never double-pay a round.
     */
    public static function draw(Lottery $lottery): array
    {
        return DB::transaction(function () use ($lottery) {
            $locked = Lottery::whereKey($lottery->id)->lockForUpdate()->first();

            if (!$locked || $locked->drawn_at) {
                return ['already_drawn' => true];
            }

            $tickets = LotteryTicketBook::where('lottery_id', $locked->id)->get();
            $pool = round($tickets->count() * (float) $locked->price, 4);

            $ranked = $tickets->shuffle()->values();
            $percents = [
                1 => (float) $locked->prize_percent_1st,
                2 => (float) $locked->prize_percent_2nd,
                3 => (float) $locked->prize_percent_3rd,
            ];

            $winners = [];
            foreach ([1, 2, 3] as $rank) {
                if (!isset($ranked[$rank - 1])) {
                    break;
                }

                $ticket = $ranked[$rank - 1];
                $prizeAmount = round($pool * ($percents[$rank] / 100), 4);

                $ticket->is_winner = true;
                $ticket->prize_rank = $rank;
                $ticket->prize_amount = $prizeAmount;
                $ticket->save();

                if ($prizeAmount > 0) {
                    $winnerUser = User::find($ticket->user_id);
                    if ($winnerUser) {
                        $winnerUser->earning_balance = (float) $winnerUser->earning_balance + $prizeAmount;
                        $winnerUser->save();
                    }
                }

                $winners[] = [
                    'rank' => $rank,
                    'user_id' => $ticket->user_id,
                    'ticket_code' => $ticket->code,
                    'prize_amount' => $prizeAmount,
                ];
            }

            // "Most tickets bought" bonus -- a separate % of the same pool,
            // paid to exactly one user (whoever holds the most tickets in
            // this round), independent of the random 1st/2nd/3rd draw above
            // (they can also win both).
            $topBuyerPercent = (float) ($locked->prize_percent_top_buyer ?? 0);
            if ($topBuyerPercent > 0 && $tickets->count() > 0) {
                $top = $tickets->groupBy('user_id')
                    ->map(function ($group) {
                        return ['count' => $group->count(), 'ticket' => $group->sortBy('id')->first()];
                    })
                    ->sort(function ($a, $b) {
                        return $a['count'] === $b['count']
                            ? $a['ticket']->id <=> $b['ticket']->id
                            : $b['count'] <=> $a['count'];
                    })
                    ->first();

                if ($top) {
                    $bonusAmount = round($pool * ($topBuyerPercent / 100), 4);
                    $ticket = $top['ticket'];

                    $ticket->is_winner = true;
                    $ticket->prize_rank = 4;
                    $ticket->prize_amount = $bonusAmount;
                    $ticket->save();

                    if ($bonusAmount > 0) {
                        $buyerUser = User::find($ticket->user_id);
                        if ($buyerUser) {
                            $buyerUser->earning_balance = (float) $buyerUser->earning_balance + $bonusAmount;
                            $buyerUser->save();
                        }
                    }

                    $winners[] = [
                        'rank' => 4,
                        'user_id' => $ticket->user_id,
                        'ticket_code' => $ticket->code,
                        'prize_amount' => $bonusAmount,
                        'ticket_count' => $top['count'],
                    ];
                }
            }

            $locked->drawn_at = now();
            $locked->save();

            $renewedId = null;
            if ($locked->auto_renew && $locked->draws_at) {
                $renewedId = self::renew($locked)->id;
            }

            return ['pool' => $pool, 'tickets_sold' => $tickets->count(), 'winners' => $winners, 'renewed_round_id' => $renewedId];
        });
    }

    /**
     * Clones a just-drawn round into next week's round (same price,
     * prize split, caps) so the weekly draw keeps going without the admin
     * having to recreate it by hand every week. The admin can still edit
     * the new round's settings any time before its own draws_at.
     */
    protected static function renew(Lottery $lottery): Lottery
    {
        $next = $lottery->replicate();
        $next->drawn_at = null;
        $next->lottery_number = (int) (Lottery::max('lottery_number') ?? 0) + 1;
        $next->draws_at = Carbon::parse($lottery->draws_at)->addDays(7);

        if ($lottery->start_time) {
            $next->start_time = Carbon::parse($lottery->start_time)->addDays(7);
        }
        if ($lottery->end_time) {
            $next->end_time = Carbon::parse($lottery->end_time)->addDays(7);
        }

        $next->save();

        return $next;
    }
}
