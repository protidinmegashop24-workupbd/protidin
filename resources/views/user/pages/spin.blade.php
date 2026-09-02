@extends('user.layouts.master')

@section('css')
@endsection

@section('user-content')
@php
    $spinSetting = \App\Models\Admin\SpinSetting::latest()->first();
    $dailySpinLimit = $spinSetting ? (int) $spinSetting->daily_spin : 0;

    $todaySpin = \App\Models\Admin\UserDailySpin::where('user_id', Auth::user()->id)
        ->whereDate('created_at', now()->toDateString())
        ->count();

    $remainingSpin = max(0, $dailySpinLimit - $todaySpin);
@endphp

<div class="container-fluid mt-2">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<style>
#wheelOfFortune{
    margin-top:50px;
    display:inline-block;
    position:relative;
    overflow:hidden;
}
#wheel{display:block;}
#spin{
    font:1.5em/0 sans-serif;
    user-select:none;
    cursor:pointer;
    display:flex;
    justify-content:center;
    align-items:center;
    position:absolute;
    top:50%;
    left:50%;
    width:30%;
    height:30%;
    margin:-15%;
    background:#fff;
    color:#fff;
    box-shadow:0 0 0 8px currentColor,0 0px 15px 5px rgba(0,0,0,.6);
    border-radius:50%;
    transition:.8s;
}
#spin::after{
    content:"";
    position:absolute;
    top:-17px;
    border:10px solid transparent;
    border-bottom-color:currentColor;
    border-top:none;
}
.alert{
    width:80%;
    margin:20px auto;
    padding:10px;
    position:relative;
    border-radius:5px;
    box-shadow:0 0 15px 5px #ccc;
}
.success-alert{
    background-color:#a8f0c6;
    border-left:5px solid #178344;
}
.danger-alert{
    background-color:#f7a7a3;
    border-left:5px solid #8f130c;
}
#message{display:none;}
.spin-container{
    display:flex;
    flex-direction:column;
    align-items:center;
}
</style>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-with-shadow-sm h-100">
            <div class="card-header">
                <h1 style="text-align:center;color:#2ECC71;" class="mb-0">
                    Today's Spin: {{ $todaySpin }} / {{ $dailySpinLimit }}
                </h1>
                <p class="text-center mt-2 mb-0" style="font-size:18px;font-weight:700;color:#444;">
                    Remaining Spin: {{ $remainingSpin }}
                </p>
            </div>

            <div class="card-body px-4">
                <div class="spin-container">
                    <div id="wheelOfFortune">
                        <canvas id="wheel" width="300" height="300"></canvas>
                        <div id="spin" class="btn">SPIN</div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-success"
                            @if($dailySpinLimit <= 0 || $todaySpin >= $dailySpinLimit)
                                onclick="spinAlert()"
                            @else
                                onclick="spinThisBoard()"
                            @endif>
                            Start Play
                        </button>
                    </div>

                    <div id="message" class="alert danger-alert" style="width:300px;text-align:center;color:#000;">
                        <p class="m-0" id="message_text"></p>
                    </div>
                </div>

@php
    $todayShareClaim = \Illuminate\Support\Facades\DB::table('user_share_bonuses')
        ->where('user_id', Auth::user()->id)
        ->whereDate('created_at', now()->toDateString())
        ->count();

    $shareUrl = url('/');
@endphp

<div class="card mt-4" style="width:100%; max-width:520px; border-radius:16px; border:1px solid #e5e7eb;">
    <div class="card-body text-center">
        <h4 style="font-weight:800; color:#111827;">Share Workup BD & Get Bonus</h4>
        <p style="color:#64748b; line-height:1.7;">
            Share Workup BD with your friends and claim a small daily activity bonus.
        </p>

        <button type="button"
        class="btn btn-primary"
        style="border-radius:10px; font-weight:700;"
        onclick="openFacebookShare()">
    Share on Facebook
</button>

<button type="button"
        id="claimShareBonusBtn"
        class="btn btn-success mt-2"
        style="border-radius:10px; font-weight:700;"
        @if($todayShareClaim > 0) disabled @else disabled @endif
        onclick="claimShareBonus()">
    @if($todayShareClaim > 0)
        Today Bonus Claimed
    @else
        Claim Facebook Share Bonus
    @endif
</button>

        <div id="shareBonusMessage" class="mt-3" style="font-weight:700;"></div>
    </div>
</div>

                <br><br>

                <script>
                    var spinClickFlag = false;
                    var spinClicked = false;

                    const sectors = [
                        {color:"{{ $spinSetting->part_one_bg ?? '#16a34a' }}", label:"{{ $spinSetting->part_one_mark ?? 0 }}"},
                        {color:"{{ $spinSetting->part_two_bg ?? '#2563eb' }}", label:"{{ $spinSetting->part_two_mark ?? 0 }}"},
                        {color:"{{ $spinSetting->part_three_bg ?? '#dc2626' }}", label:"{{ $spinSetting->part_three_mark ?? 0 }}"},
                        {color:"{{ $spinSetting->part_four_bg ?? '#f59e0b' }}", label:"{{ $spinSetting->part_four_mark ?? 0 }}"},
                        {color:"{{ $spinSetting->part_five_bg ?? '#7c3aed' }}", label:"{{ $spinSetting->part_five_mark ?? 0 }}"},
                        {color:"{{ $spinSetting->part_six_bg ?? '#0891b2' }}", label:"{{ $spinSetting->part_six_mark ?? 0 }}"},
                        {color:"{{ $spinSetting->part_seven_bg ?? '#111827' }}", label:"{{ $spinSetting->part_seven_mark ?? 0 }}"},
                    ];

                    var mark = -1;

                    const rand = (m, M) => Math.random() * (M - m) + m;
                    const tot = sectors.length;
                    const EL_spin = document.querySelector("#spin");
                    const ctx = document.querySelector("#wheel").getContext('2d');
                    const dia = ctx.canvas.width;
                    const rad = dia / 2;
                    const PI = Math.PI;
                    const TAU = 2 * PI;
                    const arc = TAU / sectors.length;

                    const friction = 0.991;
                    let angVel = 0;
                    let ang = 0;

                    const getIndex = () => Math.floor(tot - ang / TAU * tot) % tot;

                    function drawSector(sector, i){
                        const ang = arc * i;
                        ctx.save();

                        ctx.beginPath();
                        ctx.fillStyle = sector.color;
                        ctx.moveTo(rad, rad);
                        ctx.arc(rad, rad, rad, ang, ang + arc);
                        ctx.lineTo(rad, rad);
                        ctx.fill();

                        ctx.translate(rad, rad);
                        ctx.rotate(ang + arc / 2);
                        ctx.textAlign = "right";
                        ctx.fillStyle = "#fff";
                        ctx.font = "bold 30px sans-serif";
                        ctx.fillText(sector.label, rad - 10, 10);

                        ctx.restore();
                    }

                    function rotate(){
                        const sector = sectors[getIndex()];
                        ctx.canvas.style.transform = `rotate(${ang - PI / 2}rad)`;
                        EL_spin.textContent = !angVel ? "SPIN" : "Go";
                        EL_spin.style.background = sector.color;
                    }

                    function frame(){
                        if(!angVel){
                            if(spinClickFlag === true){
                                spinClickFlag = false;

                                const sector = sectors[getIndex()];
                                mark = sector.label;

                                $("#message_text").html('Processing your reward...');
                                $("#message").removeClass("danger-alert").addClass("success-alert").show();

                                $.ajax({
                                    url: "{{ route('user.add-spin-mark-to-earning') }}",
                                    type:"POST",
                                    data:{
                                        mark: mark,
                                        _token: '{{ csrf_token() }}',
                                    },
                                    success:function(data){
                                        $("#message_text").html('Reward added successfully! Reloading...');
                                    },
                                    error:function(xhr){
                                        let msg = 'Spin submitted. Reloading...';

                                        if(xhr.responseJSON && xhr.responseJSON.message){
                                            msg = xhr.responseJSON.message;
                                        }

                                        $("#message_text").html(msg);
                                        $("#message").removeClass("success-alert").addClass("danger-alert").show();
                                    },
                                    complete:function(){
                                        setTimeout(function(){
                                            window.location.replace(window.location.href.split('#')[0]);
                                        }, 1200);
                                    }
                                });
                            }
                            return;
                        }

                        angVel *= friction;
                        if(angVel < 0.002) angVel = 0;

                        ang += angVel;
                        ang %= TAU;

                        rotate();
                    }

                    function engine(){
                        frame();
                        requestAnimationFrame(engine);
                    }

                    sectors.forEach(drawSector);
                    rotate();
                    engine();

                    function spinThisBoard(){
                        if(!spinClicked){
                            if(!angVel) angVel = rand(0.25, 0.35);
                            spinClickFlag = true;
                            spinClicked = true;
                        }
                    }

                    function spinAlert(){
                        $("#message_text").html('You reached daily spin limit!');
                        $("#message").removeClass("success-alert").addClass("danger-alert").show();
                    }
                    
                    var shareClicked = false;
var fbPopup = null;
var fbPopupTimer = null;

function openFacebookShare() {
    var shareUrl = "{{ urlencode(url('/')) }}";
    var fbUrl = "https://www.facebook.com/sharer/sharer.php?u=" + shareUrl;

    fbPopup = window.open(fbUrl, "facebookShare", "width=600,height=500");

    fbPopupTimer = setInterval(function () {
        if (fbPopup && fbPopup.closed) {
            clearInterval(fbPopupTimer);
            shareClicked = true;

            $("#shareBonusMessage").html("Facebook share completed. Now claim your reward.");
            $("#claimShareBonusBtn")
                .prop("disabled", false)
                .text("Claim Facebook Share Bonus");
        }
    }, 700);
}

function claimShareBonus() {
    if (!shareClicked) {
        $("#shareBonusMessage").html("Please share on Facebook first.");
        return;
    }

    $("#claimShareBonusBtn").prop("disabled", true).text("Processing...");

    $.ajax({
        url: "{{ route('user.claim-share-bonus') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function(data) {
            $("#shareBonusMessage").html("Facebook share bonus added. Reloading...");
            setTimeout(function(){
                window.location.reload();
            }, 1000);
        },
        error: function(xhr) {
            let msg = "Bonus claim failed.";

            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }

            $("#shareBonusMessage").html(msg);
            $("#claimShareBonusBtn").prop("disabled", false).text("Claim Facebook Share Bonus");
        }
    });
}
                </script>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection