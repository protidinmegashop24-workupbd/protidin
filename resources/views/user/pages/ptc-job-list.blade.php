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
                        <a href="{{$job->ptc_jobLink}}" target="_blank" data-id="{{$job->id}}" data-time="{{($job->ptc_wait_time)}}" class="track-click">
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const links = document.querySelectorAll('.track-click'); 
        let countdownTimer;

        links.forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent the default link behavior
                const url = link.href; // Ensure the anchor `href` is correctly accessed
                const jobId = link.getAttribute('data-id');
                const ptc_time = parseInt(link.getAttribute('data-time'));

                // Open the URL in a new tab
                window.open(url, '_blank');

                window.addEventListener('focus', function onReturn() {
                    // Full-window countdown overlay
                    const countdownOverlay = document.createElement('div');
                    countdownOverlay.style.position = 'fixed';
                    countdownOverlay.style.top = '0';
                    countdownOverlay.style.left = '0';
                    countdownOverlay.style.width = '100vw';
                    countdownOverlay.style.height = '100vh';
                    countdownOverlay.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
                    countdownOverlay.style.display = 'flex';
                    countdownOverlay.style.justifyContent = 'center';
                    countdownOverlay.style.alignItems = 'center';
                    countdownOverlay.style.zIndex = '9999';
                    document.body.appendChild(countdownOverlay);

                    const countdownCircle = document.createElement('div');
                    countdownCircle.style.width = '150px';
                    countdownCircle.style.height = '150px';
                    countdownCircle.style.borderRadius = '50%';
                    countdownCircle.style.border = '10px solid #3498db';
                    countdownCircle.style.display = 'flex';
                    countdownCircle.style.justifyContent = 'center';
                    countdownCircle.style.alignItems = 'center';
                    countdownCircle.style.fontSize = '24px';
                    countdownCircle.style.color = '#fff';
                    countdownOverlay.appendChild(countdownCircle);

                    let countdown = ptc_time; // 5-second countdown
                    countdownCircle.textContent = countdown;

                    countdownTimer = setInterval(() => {
                        countdown--;
                        if (countdown <= 0) {
                            clearInterval(countdownTimer);
                            countdownCircle.remove();
                            countdownOverlay.remove();
                            showClaimButton(jobId);
                        } else {
                            countdownCircle.textContent = countdown;
                        }
                    }, 1000);

                    window.removeEventListener('focus', onReturn);
                });
            });
        });

        function showClaimButton(jobId) {
                // Full-window countdown overlay
            const countdownOverlay = document.createElement('div');
            countdownOverlay.style.position = 'fixed';
            countdownOverlay.style.top = '0';
            countdownOverlay.style.left = '0';
            countdownOverlay.style.width = '100vw';
            countdownOverlay.style.height = '100vh';
            countdownOverlay.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
            countdownOverlay.style.display = 'flex';
            countdownOverlay.style.justifyContent = 'center';
            countdownOverlay.style.alignItems = 'center';
            countdownOverlay.style.zIndex = '9999';
            document.body.appendChild(countdownOverlay);
            const claimButton = document.createElement('button');
            claimButton.textContent = 'Claim USD';
            claimButton.className = 'btn btn-success';
            claimButton.style.position = 'fixed';
            claimButton.style.zIndex = '9999';
            countdownOverlay.appendChild(claimButton);

            // Random position using vh and vw
            const randomTop = Math.random() * 80 + 'vh';
            const randomLeft = Math.random() * 80 + 'vw';
            claimButton.style.top = randomTop;
            claimButton.style.left = randomLeft;

            document.body.appendChild(claimButton);

            claimButton.addEventListener('click', function () {
                document.body.removeChild(claimButton);
                document.body.removeChild(countdownOverlay);
                console.log(jobId);
                const csrfToken = document.getElementById('csrfToken').value;
                // Make POST request to server with jobId
                fetch("{{route('user.jobSeeker')}}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ id: jobId })
                })
                
                .then(response => response.text()) // Read the plain text response
                .then(data => {
                    alert(data);
                    console.log(data); // Should display "Yes, you got the balance"
                    location.reload();
                }).catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });
        }
    });    
</script>

@endsection