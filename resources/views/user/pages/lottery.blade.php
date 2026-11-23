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

        .ld-wheel-wrap { position: relative; width: 220px; height: 220px; margin: 10px auto 0; }
        .ld-wheel {
            width: 100%; height: 100%; border-radius: 50%; position: relative;
            border: 6px solid #222; box-shadow: 0 2px 10px rgba(0,0,0,.25);
            transform: rotate(0deg);
        }
        .ld-wheel-labels { position: absolute; inset: 0; }
        .ld-wheel-labels span {
            position: absolute; left: 50%; top: 50%; transform-origin: 0 0;
            font-size: 10px; font-weight: 800; color: #fff; white-space: nowrap;
            text-shadow: 0 1px 2px rgba(0,0,0,.5);
        }
        .ld-wheel-pointer {
            position: absolute; top: -6px; left: 50%; transform: translateX(-50%);
            font-size: 28px; color: #222; z-index: 2;
        }
        .ld-wheel-hub {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);
            width: 56px; height: 56px; border-radius: 50%; background: #222; color: #fff;
            display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 11px;
        }
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
                                <small>১ম পুরস্কার</small>
                            </div>
                            <div class="ld-prize-box">
                                <div class="rank">🥈</div>
                                <div class="pct">${{ number_format($round->pool * $round->prize_percent_2nd / 100, 4) }}</div>
                                <small>২য় পুরস্কার</small>
                            </div>
                            <div class="ld-prize-box">
                                <div class="rank">🥉</div>
                                <div class="pct">${{ number_format($round->pool * $round->prize_percent_3rd / 100, 4) }}</div>
                                <small>৩য় পুরস্কার</small>
                            </div>
                            <div class="ld-prize-box">
                                <div class="rank">🎟️</div>
                                <div class="pct">${{ number_format($round->pool * $round->prize_percent_top_buyer / 100, 4) }}</div>
                                <small>সর্বোচ্চ টিকিট ক্রেতা</small>
                            </div>
                        </div>
                        <p class="text-center text-muted" style="font-size:11px;margin-top:-6px;">টিকিট বিক্রি যত বাড়বে, পুরস্কারের টাকাও তত বাড়বে।</p>

                        <p class="text-center" style="font-weight:800;">আপনার টিকিট: {{ $round->my_tickets }}টা</p>

                        @if($round->top_buyer_name)
                            <p class="text-center" style="font-size:13px;font-weight:700;background:#fff7e6;border:1px solid #ffe2a8;border-radius:8px;padding:8px;">
                                🏆 বর্তমানে সবচেয়ে বেশি টিকিট কিনেছেন:
                                @if($round->top_buyer_is_me)
                                    <span style="color:#16a34a;">আপনি</span>
                                @else
                                    {{ $round->top_buyer_name }}
                                @endif
                                ({{ $round->top_buyer_count }}টা) — আপনি বেশি কিনলে এগিয়ে যেতে পারবেন!
                            </p>
                        @endif

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

    var LD_WHEEL_COLORS = ['#e91e63', '#9c27b0', '#3f51b5', '#009688', '#8bc34a', '#ff9800', '#f44336', '#00bcd4'];
    var LD_WHEEL_SEGMENTS = 8;

    function ldPaintWheel(wheelEl, labels) {
        var n = labels.length;
        var slice = 360 / n;
        var parts = [];
        for (var i = 0; i < n; i++) {
            parts.push(LD_WHEEL_COLORS[i % LD_WHEEL_COLORS.length] + ' ' + (i * slice) + 'deg ' + ((i + 1) * slice) + 'deg');
        }
        wheelEl.style.background = 'conic-gradient(' + parts.join(',') + ')';

        var labelsEl = wheelEl.querySelector('.ld-wheel-labels');
        labelsEl.innerHTML = '';
        labels.forEach(function (text, i) {
            var mid = slice * i + slice / 2;
            var span = document.createElement('span');
            span.textContent = text;
            span.style.transform = 'rotate(' + mid + 'deg) translate(72px) rotate(90deg)';
            labelsEl.appendChild(span);
        });
    }

    function ldSpinWheelTo(wheelEl, winIndex, segCount, callback) {
        var slice = 360 / segCount;
        var target = 1800 + (360 - (slice * winIndex + slice / 2));
        wheelEl.style.transition = 'none';
        wheelEl.style.transform = 'rotate(0deg)';
        void wheelEl.offsetWidth;
        requestAnimationFrame(function () {
            wheelEl.style.transition = 'transform 4s cubic-bezier(.17,.67,.12,.99)';
            wheelEl.style.transform = 'rotate(' + target + 'deg)';
        });
        setTimeout(callback, 4200);
    }

    function ldStartReveal(countdownEl) {
        var card = countdownEl.closest('.ld-card');
        if (!card) return;

        var buyRow = card.querySelector('.ld-buy-row');
        var reveal = card.querySelector('.ld-reveal');
        if (buyRow) buyRow.style.display = 'none';
        if (!reveal) return;

        reveal.style.display = 'block';
        reveal.innerHTML = '<div class="spin-box">🎰 ড্র চলছে, একটু অপেক্ষা করুন...</div>';

        var candidates = [];
        var statusUrl = countdownEl.getAttribute('data-status-url');

        // The wheel is only a dramatic visual -- the real winner was
        // already decided server-side, so this just spins to land on
        // whichever name the server already picked for each rank.
        function playSequence(winners) {
            var wheelSteps = [3, 2, 1]
                .map(function (rank) { return winners.filter(function (w) { return w.rank == rank; })[0]; })
                .filter(function (w) { return w; });
            var topBuyer = winners.filter(function (w) { return w.rank == 4; })[0];

            reveal.innerHTML = '<div class="ld-wheel-wrap">' +
                '<div class="ld-wheel-pointer">▼</div>' +
                '<div class="ld-wheel"><div class="ld-wheel-labels"></div><div class="ld-wheel-hub">🎯 SPIN</div></div>' +
                '</div>';
            var wheelEl = reveal.querySelector('.ld-wheel');

            var i = 0;
            function nextSpin() {
                if (i >= wheelSteps.length) {
                    finish();
                    return;
                }

                var w = wheelSteps[i];
                var winIndex = Math.floor(Math.random() * LD_WHEEL_SEGMENTS);
                var pool = candidates.filter(function (c) { return c.code !== w.code; });
                var labels = [];
                for (var s = 0; s < LD_WHEEL_SEGMENTS; s++) {
                    if (s === winIndex) {
                        labels.push(w.name + ' #' + w.code);
                    } else if (pool.length) {
                        var c = pool[Math.floor(Math.random() * pool.length)];
                        labels.push(c.name + ' #' + c.code);
                    } else {
                        labels.push('---');
                    }
                }
                ldPaintWheel(wheelEl, labels);

                ldSpinWheelTo(wheelEl, winIndex, LD_WHEEL_SEGMENTS, function () {
                    var msg = document.createElement('div');
                    msg.className = 'ld-wheel-msg';
                    msg.style.marginTop = '10px';
                    msg.innerHTML = '<div style="font-weight:900;">🎊 অভিনন্দন! ' + ldRankLabel(w.rank) + '</div>' +
                        '<div><strong>' + w.name + '</strong> (টিকিট #' + w.code + ')</div>' +
                        '<div class="text-success" style="font-weight:900;font-size:18px;">$' + w.prize_amount.toFixed(4) + '</div>';
                    reveal.appendChild(msg);

                    i++;
                    setTimeout(function () {
                        var old = reveal.querySelector('.ld-wheel-msg');
                        if (old) old.remove();
                        nextSpin();
                    }, 2500);
                });
            }

            // Top-ticket-buyer bonus isn't a random pick, so it's just
            // announced directly -- no wheel spin for it.
            function finish() {
                var html = '<div style="font-weight:900;margin-bottom:6px;">🎉 সম্পূর্ণ ফলাফল</div>';
                wheelSteps.forEach(function (w) {
                    html += '<div class="winner-row"><span>' + ldRankLabel(w.rank) + ' — ' + w.name + ' (টিকিট #' + w.code + ')</span>' +
                        '<span class="text-success">$' + w.prize_amount.toFixed(4) + '</span></div>';
                });
                if (topBuyer) {
                    html += '<div class="winner-row"><span>' + ldRankLabel(4) + ' — ' + topBuyer.name + ' (টিকিট #' + topBuyer.code + ')</span>' +
                        '<span class="text-success">$' + topBuyer.prize_amount.toFixed(4) + '</span></div>';
                }
                reveal.innerHTML = html;
                setTimeout(function () { location.reload(); }, 4000);
            }

            nextSpin();
        }

        var pollTimer = setInterval(function () {
            $.getJSON(statusUrl, function (data) {
                if (data.candidates) candidates = data.candidates;

                if (!data.drawn) return;

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
