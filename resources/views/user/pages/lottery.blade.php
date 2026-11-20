@extends('user.layouts.master')
@section('css')
    <link href="{{ asset('frontend/user/assets/css/nucleo.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/fontawesome-all.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/argon.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/myapp.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <style>
        .container-fluid { padding-right: 0px; padding-left: 0px; }

        .ld-card { border-radius: 1rem; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,.1); }
        .ld-head {
            background: #007991;
            background: linear-gradient(to right, #007991, #78ffd6);
            color: #fff;
            padding: 18px 20px;
        }
        .ld-head h4 { font-weight: 900; margin: 0; }
        .ld-body { background: #fff; padding: 18px 20px; }

        .countdown { text-transform: uppercase; font-weight: bold; text-align: center; }
        .countdown span { display: block; font-size: 1.8rem; }
        .countdown small { font-size: 11px; opacity: .85; }
        .countdown .cd-row { display: flex; justify-content: center; gap: 18px; margin-top: 6px; }

        .ld-prizes { display: flex; gap: 10px; margin: 14px 0; flex-wrap: wrap; }
        .ld-prize-box { flex: 1; min-width: 90px; text-align: center; background: #f8f9fe; border: 1px solid #eee; border-radius: 10px; padding: 10px 6px; }
        .ld-prize-box .rank { font-size: 20px; }
        .ld-prize-box .pct { font-weight: 900; color: #0d6efd; }

        .ld-stats { display: flex; justify-content: space-between; font-size: 13px; font-weight: 700; color: #555; margin-bottom: 10px; }

        .winner-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f0f0f0; font-weight: 700; font-size: 14px; }
        .winner-row:last-child { border-bottom: none; }

        .ld-reveal { text-align: center; padding: 10px 0; }
        .ld-reveal .spin-box {
            font-weight: 900; font-size: 18px; padding: 14px;
            background: #111; color: #0f0; border-radius: 10px; letter-spacing: 1px;
            animation: ldPulse 0.6s infinite alternate;
        }
        @keyframes ldPulse { from { opacity: 1; } to { opacity: .6; } }
        .ld-reveal .winner-row { animation: ldReveal .4s ease-out; }
        @keyframes ldReveal { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }
    </style>
@endsection
@section('user-content')

<div class="container-fluid mt-2">
    <div class="card card-body mb-3" style="background-color: rgb(248, 249, 254);">
        <div class="card-title text-center mb-0" style="font-weight: 900; color: #16a34a; font-size: 22px;">
            🎟️ Lucky Draw
        </div>
        <p class="text-center text-muted mb-0" style="font-size: 13px;">
            Earning balance দিয়ে টিকিট কিনুন, প্রতি সপ্তাহে ড্র হবে, ১ম/২য়/৩য় বিজয়ীরা পুরস্কার পাবেন।
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="font-weight:800;">{{ session('success') }}</div>
    @endif

    <div class="row justify-content-center">
        @forelse($openRounds as $round)
            <div class="col-md-6 mb-4">
                <div class="ld-card">
                    <div class="ld-head">
                        <h4>{{ $round->title }}</h4>
                        <div class="countdown" data-draw-at="{{ $round->draws_at ? $round->draws_at->toIso8601String() : '' }}" data-status-url="{{ route('user.lottery-draw-status', $round->id) }}">
                            <div class="cd-row">
                                <div><span class="cd-d">--</span><small>Day</small></div>
                                <div><span class="cd-h">--</span><small>Hr</small></div>
                                <div><span class="cd-m">--</span><small>Min</small></div>
                                <div><span class="cd-s">--</span><small>Sec</small></div>
                            </div>
                        </div>
                    </div>
                    <div class="ld-body">
                        <div class="ld-stats">
                            <span>টিকিট দাম: ${{ number_format((float)$round->price, 4) }}</span>
                            <span>মোট বিক্রি: {{ $round->total_tickets }}</span>
                        </div>

                        <div class="ld-prizes">
                            <div class="ld-prize-box">
                                <div class="rank">🥇</div>
                                <div class="pct">${{ number_format($round->pool * $round->prize_percent_1st / 100, 4) }}</div>
                                <small>১ম পুরস্কার ({{ $round->prize_percent_1st }}%)</small>
                            </div>
                            <div class="ld-prize-box">
                                <div class="rank">🥈</div>
                                <div class="pct">${{ number_format($round->pool * $round->prize_percent_2nd / 100, 4) }}</div>
                                <small>২য় পুরস্কার ({{ $round->prize_percent_2nd }}%)</small>
                            </div>
                            <div class="ld-prize-box">
                                <div class="rank">🥉</div>
                                <div class="pct">${{ number_format($round->pool * $round->prize_percent_3rd / 100, 4) }}</div>
                                <small>৩য় পুরস্কার ({{ $round->prize_percent_3rd }}%)</small>
                            </div>
                            <div class="ld-prize-box">
                                <div class="rank">🎟️</div>
                                <div class="pct">${{ number_format($round->pool * $round->prize_percent_top_buyer / 100, 4) }}</div>
                                <small>সর্বোচ্চ টিকিট ক্রেতা ({{ $round->prize_percent_top_buyer }}%)</small>
                            </div>
                        </div>
                        <p class="text-center text-muted" style="font-size:11px;margin-top:-6px;">টিকিট বিক্রি যত বাড়বে, পুরস্কারের টাকাও তত বাড়বে।</p>

                        <p class="text-center" style="font-weight:800;">আপনার টিকিট: {{ $round->my_tickets }}টা</p>

                        <div class="ld-buy-row">
                            <div class="form-row align-items-end justify-content-center">
                                <div class="col-5">
                                    <label style="font-weight:700;font-size:13px;">কয়টা টিকিট?</label>
                                    <input type="number" class="form-control ld-qty" min="1" value="1"
                                        @if($round->max_tickets_per_user) max="{{ max(1, $round->max_tickets_per_user - $round->my_tickets) }}" @endif>
                                </div>
                                <div class="col-7">
                                    <button type="button" class="btn btn-success btn-block ld-buy-btn" data-id="{{ $round->id }}">
                                        Buy Ticket(s)
                                    </button>
                                </div>
                            </div>
                            @if($round->max_tickets_per_user)
                                <p class="text-center text-muted mt-2" style="font-size:12px;">সর্বোচ্চ {{ $round->max_tickets_per_user }}টা টিকিট কেনা যাবে এই রাউন্ডে।</p>
                            @endif
                        </div>

                        <div class="alert mt-2" style="display:none;" id="ldMsg{{ $round->id }}"></div>

                        <div class="ld-reveal" id="ldReveal{{ $round->id }}" style="display:none;"></div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="p-4 bg-white text-center" style="border:1px solid rgba(0,0,0,.12);border-radius:12px;font-weight:900;">
                    এই মুহূর্তে কোনো Lucky Draw রাউন্ড চালু নেই। শীঘ্রই আবার আসবে।
                </div>
            </div>
        @endforelse
    </div>

    @if($pastResults->count())
        <div class="card card-body mt-2 mb-4">
            <div class="card-title" style="font-weight:900;">🏆 আগের ড্র-এর ফলাফল</div>
            @foreach($pastResults as $result)
                <div class="mb-3 pb-2" style="border-bottom:1px solid #eee;">
                    <div style="font-weight:800;">
                        {{ $result->title }}
                        <span class="text-muted" style="font-size:12px;font-weight:600;">— {{ $result->drawn_at->format('d M Y, h:i A') }}</span>
                    </div>
                    @forelse($result->winning_tickets as $w)
                        <div class="winner-row">
                            <span>
                                @if($w->prize_rank == 1) 🥇 ১ম
                                @elseif($w->prize_rank == 2) 🥈 ২য়
                                @elseif($w->prize_rank == 3) 🥉 ৩য়
                                @else 🎟️ সর্বোচ্চ টিকিট ক্রেতা
                                @endif
                                — {{ $w->user->name ?? 'User' }} (টিকিট #{{ $w->code }})
                            </span>
                            <span class="text-success">${{ number_format((float)$w->prize_amount, 4) }}</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0" style="font-size:13px;">এই রাউন্ডে কোনো টিকিট বিক্রি হয়নি।</p>
                    @endforelse
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
@section('js')
<script>
    // Countdown: plain JS (no extra plugin), re-ticks every second for
    // every round card found on the page.
    function ldPad(n) { return n < 10 ? '0' + n : '' + n; }

    function ldTickAll() {
        document.querySelectorAll('.countdown[data-draw-at]').forEach(function (el) {
            var target = el.getAttribute('data-draw-at');
            if (!target) return;
            var diff = new Date(target).getTime() - Date.now();

            if (diff <= 0) {
                if (!el.dataset.watching) {
                    el.dataset.watching = '1';
                    ldStartReveal(el);
                }
                diff = 0;
            }

            var d = Math.floor(diff / (1000 * 60 * 60 * 24));
            var h = Math.floor((diff / (1000 * 60 * 60)) % 24);
            var m = Math.floor((diff / (1000 * 60)) % 60);
            var s = Math.floor((diff / 1000) % 60);

            var dEl = el.querySelector('.cd-d'), hEl = el.querySelector('.cd-h'),
                mEl = el.querySelector('.cd-m'), sEl = el.querySelector('.cd-s');
            if (dEl) dEl.textContent = d;
            if (hEl) hEl.textContent = ldPad(h);
            if (mEl) mEl.textContent = ldPad(m);
            if (sEl) sEl.textContent = ldPad(s);
        });
    }
    ldTickAll();
    setInterval(ldTickAll, 1000);

    // Countdown hit zero: hide the buy form, show a spinning placeholder
    // cycling REAL ticket-holders of this round (not made-up numbers)
    // while we poll the server every few seconds for the real result (the
    // cron may take a little while after draws_at to actually run). Once
    // drawn, reveals 3rd -> 2nd -> 1st -> top-buyer-bonus one at a time
    // with a short "congratulations" pause on each, for suspense.
    function ldRankLabel(rank) {
        if (rank == 1) return '🥇 ১ম স্থান';
        if (rank == 2) return '🥈 ২য় স্থান';
        if (rank == 3) return '🥉 ৩য় স্থান';
        return '🎟️ সর্বোচ্চ টিকিট ক্রেতা বোনাস';
    }

    function ldStartReveal(countdownEl) {
        var card = countdownEl.closest('.ld-card');
        if (!card) return;

        var buyRow = card.querySelector('.ld-buy-row');
        var reveal = card.querySelector('.ld-reveal');
        if (buyRow) buyRow.style.display = 'none';
        if (!reveal) return;

        reveal.style.display = 'block';
        reveal.innerHTML = '<div class="spin-box">🎰 ড্র চলছে... <span class="spin-name">...</span></div>';

        var candidates = [];
        var spinNameEl = reveal.querySelector('.spin-name');
        var spinTimer = setInterval(function () {
            if (spinNameEl && candidates.length) {
                var c = candidates[Math.floor(Math.random() * candidates.length)];
                spinNameEl.textContent = c.name + ' (#' + c.code + ')';
            }
        }, 150);

        var statusUrl = countdownEl.getAttribute('data-status-url');

        function playSequence(winners) {
            var order = [3, 2, 1, 4];
            var steps = order
                .map(function (rank) { return winners.filter(function (w) { return w.rank == rank; })[0]; })
                .filter(function (w) { return w; });

            var i = 0;
            function next() {
                if (i >= steps.length) {
                    var html = '<div style="font-weight:900;margin-bottom:6px;">🎉 সম্পূর্ণ ফলাফল</div>';
                    steps.forEach(function (w) {
                        html += '<div class="winner-row"><span>' + ldRankLabel(w.rank) + ' — ' + w.name + ' (টিকিট #' + w.code + ')</span>' +
                            '<span class="text-success">$' + w.prize_amount.toFixed(4) + '</span></div>';
                    });
                    reveal.innerHTML = html;
                    setTimeout(function () { location.reload(); }, 4000);
                    return;
                }

                var w = steps[i];
                reveal.innerHTML = '<div class="winner-row" style="justify-content:center;gap:10px;">' +
                    '<span>🎊 অভিনন্দন! ' + ldRankLabel(w.rank) + ' — <strong>' + w.name + '</strong> (টিকিট #' + w.code + ')</span>' +
                    '</div><div class="text-success" style="font-weight:900;font-size:18px;">$' + w.prize_amount.toFixed(4) + '</div>';

                i++;
                setTimeout(next, 2500);
            }
            next();
        }

        var pollTimer = setInterval(function () {
            $.getJSON(statusUrl, function (data) {
                if (data.candidates) candidates = data.candidates;

                if (!data.drawn) return;

                clearInterval(spinTimer);
                clearInterval(pollTimer);

                if (!data.winners.length) {
                    reveal.innerHTML = '<p class="text-muted" style="font-size:13px;">এই রাউন্ডে কোনো টিকিট বিক্রি হয়নি।</p>';
                    setTimeout(function () { location.reload(); }, 4000);
                    return;
                }

                playSequence(data.winners);
            });
        }, 3000);
    }

    $(document).on('click', '.ld-buy-btn', function () {
        var btn = $(this);
        var id = btn.data('id');
        var card = btn.closest('.ld-card');
        var qty = parseInt(card.find('.ld-qty').val(), 10) || 1;
        var msgBox = $('#ldMsg' + id);

        btn.prop('disabled', true).text('অপেক্ষা করুন...');

        $.ajax({
            url: "{{ route('user.lottery-buy-confirm') }}",
            type: "POST",
            data: { _token: '{{ csrf_token() }}', lottery_id: id, quantity: qty },
            success: function (data) {
                msgBox.removeClass('alert-success alert-danger')
                    .addClass(data.error == '0' ? 'alert-success' : 'alert-danger')
                    .text(data.msg).show();
                if (data.error == '0') {
                    setTimeout(function () { location.reload(); }, 1500);
                } else {
                    btn.prop('disabled', false).text('Buy Ticket(s)');
                }
            },
            error: function () {
                msgBox.removeClass('alert-success').addClass('alert-danger')
                    .text('কিছু একটা সমস্যা হয়েছে, আবার চেষ্টা করুন।').show();
                btn.prop('disabled', false).text('Buy Ticket(s)');
            }
        });
    });
</script>
@endsection
