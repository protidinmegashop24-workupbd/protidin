@extends('user.layouts.master')
@section('css')
{{-- <link href="{{ asset('frontend/user/assets/css/custom.css') }}" rel="stylesheet" /> --}}
<style>
body {
        font-family: 'noto serif bengali', sans-serif;
    }
    .job_title{
        font-weight: 600;
        font-size: 1.15rem;
    }
    .job-list-body{
        border-radius: 5px !important;
        border: 1px solid #eaeaea !important;
        background-color: #d6ebf1 !important;   
    }
    .job-area{
        background: #fff;
        margin: 0 3px;
        border-radius: 5px;
    }
    .boost-job-border{
        border-left: solid 5px;
    }
    .job-list-card-body{
        padding: 0.2rem 0.1rem 1.2rem 0.1rem;
    }
    .fs-12{
        font-size: 12px !important;
        color: #000000;
    }
    .fs-14{
        font-size: 14px !important;
    }
    .btn-sort{
        font-weight: 600;
        color: #008000;
        background-color: #d6ebf1;
        border-color: #008000;
    }
    .sm-show{
        display: none;
    }
    @media screen and (min-width: 767px) {
        .job-area{
            height: 65px;
            align-items: center;
        }
        .justify-content-end {
            -webkit-box-pack: start !important;
            -ms-flex-pack: start !important;
            justify-content: flex-start !important;
        }
        .job-list-card-body{
            padding: 0px 100px;
        }
        .lg-show{
            display: none;
        }
        .sm-show{
            display: block;
        }
    }   

    
</style>
@endsection
@section('user-content')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>


<div class="row justify-content-center">
        <?php use Carbon\Carbon; ?>
    <div class="col-12">
        <div class="card">             
            @if(site_info()->email_verification_active == 1)
                @if(!Auth::user()->hasVerifiedEmail())
                    <div class="card-header text-center justify-content-center d-flex gap-2">  <div class="text-center" style="display: block; background-color: white; width: 100%; border: 2px solid red; padding: 5px; margin-bottom: 0 auto;">
                        <a href="{{ route('verification.notice') }}" class="text-primary">
                            জব শুরু করার আগে ইমেইল ভেরিফাই করে নিন এখানে ক্লিক করে!
                        </a>
                        </div>
                    </div>
                @endif
            @endif

            <div class="card-body job-list-card-body" style="background-color: #d6ebf1;">
                <div class="job-list-body" id="job-content-area">           
                    <div class="card-header" style="height: 50px; border-radius: 0px 0px 10px 10px;">
                        <div class="card-title text-center" style="font-weight: 500; color: green; font-size: 20px;border-bottom: 1px solid #559ce3;">Available PTC Job For You</div>
                        <div class="card-title text-center">
                            <strong></strong>
                        </div>
                    </div>                    
                    @forelse ($jobs as $job)
                        @if($job->ptc_clicked >= $job->ptc_worker_needed)
                            @continue
                        @endif
                        <a href="{{ $job->ptc_jobLink }}" data-id="{{$job->id}}" data-time="{{($job->ptc_wait_time)}}" data-earn="{{ $job->ptc_each_earn }}" class="track-click">
                            <div class="border p-1 mb-2 row job-area">
                                <div class="col-lg-4 col-md-5 col-12 text-dark fw-700 job_title">
                                    {{$job->ptc_title}}
                                    <span class="badge bg-warning text-dark" style="font-size:.65rem; vertical-align:middle;">
                                        <i class="fa fa-clock"></i> {{ $job->ptc_wait_time }}s অপেক্ষা
                                    </span>
                                </div>
                                <div class="col-lg-6 col-md-5 col-8">
                                    <div class="row pt-1 m-0 justify-content-end">
                                        <div class="col-lg-6 col-md-5 col-7">
                                            <b> <h6 style="width: 100%;text-align:center;font-size: .625rem;">{{$job->ptc_clicked}} OF {{$job->ptc_worker_needed}}</h6></b>
                                            <div class="progress progress-md p-0">
                                                <div class="progress-bar progress-bar-animated bg-success" style="width: {{$job->id}}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-4">
                                    <div class="text-center" style="color:#15ba5a;ont-size: 20px;font-weight: bolder;">{{$job->ptc_each_earn}}$</div>
                                    <span style="font-size: 11px;">Exp: {{ Carbon::parse($job->ptc_expire_day)->format('d-M-Y') }}</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <h1 style="text-align: center;padding-top:20vh;padding-bottom:20vh;">No Job available</h1>
                    @endforelse


                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="csrfToken" value="{{ csrf_token() }}">

@endsection
@section('js')
<style>
    .ptc-overlay{
        position: fixed;
        top: 0; left: 0;
        width: 100vw; height: 100vh;
        background: rgba(0,0,0,.75);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        color: #fff;
        text-align: center;
        padding: 20px;
    }
    .ptc-overlay-circle{
        width: 130px;
        height: 130px;
        border-radius: 50%;
        border: 8px solid #3498db;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 16px;
    }
    .ptc-overlay-circle.done{
        border-color: #15ba5a;
    }
    .ptc-overlay-circle.failed{
        border-color: #e74c3c;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.getElementById('csrfToken').value;

        // Only one job can be "in progress" at a time -- the whole point is
        // watching whether THIS tab regains focus too early, which only
        // makes sense for a single pending claim.
        let pending = null; // { jobId, earn, waitTime, clickTime, overlay }

        // The countdown itself has to live in the browser TAB (title), not
        // just the overlay -- once the ad opens in a new tab, the user is
        // looking at that tab, not our overlay, so the tab title/favicon
        // area is the only place they can actually see time remaining.
        const originalTitle = document.title;
        let titleInterval = null;

        function startTitleCountdown() {
            updateTitle();
            titleInterval = setInterval(updateTitle, 500);
        }

        function updateTitle() {
            if (!pending) return;
            const remaining = Math.max(0, pending.waitTime - Math.floor((Date.now() - pending.clickTime) / 1000));
            document.title = remaining > 0
                ? ('⏳ ' + remaining + 's বাকি...')
                : '✅ ফিরে আসুন - রিওয়ার্ড রেডি!';
        }

        function stopTitleCountdown() {
            if (titleInterval) {
                clearInterval(titleInterval);
                titleInterval = null;
            }
            document.title = originalTitle;
        }

        document.querySelectorAll('.track-click').forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();

                if (pending) {
                    alert('আগের বিজ্ঞাপনের সময় এখনো চলছে, আগে সেটা শেষ করুন।');
                    return;
                }

                const jobId = link.getAttribute('data-id');
                const waitTime = parseInt(link.getAttribute('data-time'), 10) || 10;
                const earn = link.getAttribute('data-earn');

                // Open the advertiser's link in a real new tab -- this is
                // the only "view" that actually counts for the advertiser.
                window.open(link.href, '_blank', 'noopener,noreferrer');

                startWaiting(jobId, waitTime, earn);
            });
        });

        function startWaiting(jobId, waitTime, earn) {
            const overlay = document.createElement('div');
            overlay.className = 'ptc-overlay';
            overlay.innerHTML = `
                <div class="ptc-overlay-circle" id="ptc-overlay-circle">⏳</div>
                <p>বিজ্ঞাপনের ট্যাবে কমপক্ষে <strong>${waitTime} সেকেন্ড</strong> থাকুন।<br>
                এই পেজে খুব তাড়াতাড়ি ফিরে এলে রিওয়ার্ড পাবেন না।</p>
                <button type="button" id="ptc-overlay-cancel" class="btn btn-outline-light btn-sm">Cancel</button>
            `;
            document.body.appendChild(overlay);

            pending = {
                jobId: jobId,
                earn: earn,
                waitTime: waitTime,
                clickTime: Date.now(),
                overlay: overlay,
            };

            overlay.querySelector('#ptc-overlay-cancel').addEventListener('click', function () {
                cleanupListeners();
                overlay.remove();
                pending = null;
            });

            document.addEventListener('visibilitychange', onVisibilityChange);
            startTitleCountdown();
        }

        function onVisibilityChange() {
            if (document.visibilityState !== 'visible' || !pending) {
                return;
            }

            const elapsedMs = Date.now() - pending.clickTime;
            const requiredMs = pending.waitTime * 1000;

            if (elapsedMs < requiredMs) {
                failPending();
            } else {
                successPending();
            }
        }

        function cleanupListeners() {
            document.removeEventListener('visibilitychange', onVisibilityChange);
            stopTitleCountdown();
        }

        function failPending() {
            const p = pending;
            cleanupListeners();
            pending = null;

            p.overlay.innerHTML = `
                <div class="ptc-overlay-circle failed">✕</div>
                <p>আপনি খুব তাড়াতাড়ি ফিরে এসেছেন! বিজ্ঞাপন ট্যাবে পুরো সময় থাকতে হবে।</p>
                <button type="button" class="btn btn-danger btn-sm" id="ptc-overlay-close">বন্ধ করুন</button>
            `;
            p.overlay.querySelector('#ptc-overlay-close').addEventListener('click', function () {
                p.overlay.remove();
            });
        }

        function successPending() {
            const p = pending;
            cleanupListeners();
            pending = null;

            p.overlay.innerHTML = `
                <div class="ptc-overlay-circle done">✓</div>
                <p>সময় শেষ! এখন রিওয়ার্ড ক্লেইম করুন।</p>
                <button type="button" id="ptc-overlay-claim" class="btn btn-success btn-lg">Claim $${p.earn}</button>
            `;

            p.overlay.querySelector('#ptc-overlay-claim').addEventListener('click', function () {
                const btn = this;
                btn.disabled = true;
                btn.textContent = 'Claiming...';

                fetch("{{ route('user.jobSeeker') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ id: p.jobId })
                })
                .then(res => res.text())
                .then(data => {
                    alert(data);
                    location.reload();
                })
                .catch(() => {
                    alert('কিছু একটা সমস্যা হয়েছে, আবার চেষ্টা করুন।');
                    p.overlay.remove();
                });
            });
        }
    });
</script>
@endsection