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
                                <div class="col-lg-4 col-md-5 col-12 text-dark fw-700 job_title">{{$job->ptc_title}}</div>
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
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.getElementById('csrfToken').value;
        let activeTimer = null;

        document.querySelectorAll('.track-click').forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();

                const jobId = link.getAttribute('data-id');
                const waitTime = parseInt(link.getAttribute('data-time'), 10) || 10;
                const earn = link.getAttribute('data-earn');

                // Open the advertiser's link in a real new tab -- this is
                // the only "view" that actually counts for the advertiser.
                window.open(link.href, '_blank', 'noopener,noreferrer');

                showOverlay(jobId, waitTime, earn);
            });
        });

        function showOverlay(jobId, waitTime, earn) {
            const overlay = document.createElement('div');
            overlay.className = 'ptc-overlay';
            overlay.innerHTML = `
                <div class="ptc-overlay-circle" id="ptc-overlay-circle">${waitTime}</div>
                <p>অপেক্ষা করুন... এই পেজ থেকে সরে যাবেন না।</p>
                <button type="button" id="ptc-overlay-claim" class="btn btn-success btn-lg" style="display:none;">Claim $${earn}</button>
            `;
            document.body.appendChild(overlay);

            const circle = overlay.querySelector('#ptc-overlay-circle');
            let remaining = waitTime;

            function beforeUnloadHandler(e) {
                e.preventDefault();
                e.returnValue = '';
                return '';
            }
            window.addEventListener('beforeunload', beforeUnloadHandler);

            activeTimer = setInterval(function () {
                remaining--;
                if (remaining <= 0) {
                    clearInterval(activeTimer);
                    window.removeEventListener('beforeunload', beforeUnloadHandler);
                    circle.textContent = '✓';
                    circle.classList.add('done');
                    overlay.querySelector('#ptc-overlay-claim').style.display = 'inline-block';
                } else {
                    circle.textContent = remaining;
                }
            }, 1000);

            overlay.querySelector('#ptc-overlay-claim').addEventListener('click', function () {
                const btn = this;
                btn.disabled = true;
                btn.textContent = 'Claiming...';

                fetch("{{ route('user.jobSeeker') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ id: jobId })
                })
                .then(res => res.text())
                .then(data => {
                    alert(data);
                    location.reload();
                })
                .catch(() => {
                    alert('কিছু একটা সমস্যা হয়েছে, আবার চেষ্টা করুন।');
                    overlay.remove();
                });
            });
        }
    });
</script>
@endsection