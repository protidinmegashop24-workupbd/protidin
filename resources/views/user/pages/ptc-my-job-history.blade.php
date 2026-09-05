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
                        <div class="card-title text-center" style="font-weight: 500; color: green; font-size: 20px;border-bottom: 1px solid #559ce3;">PTC Total Job Posted History</div>
                        <div class="card-title text-center">
                            <strong></strong>
                        </div>
                    </div>                    
                    @forelse ($jobs as $job)
                        <a href="@if($job->ptc_status == 'pending' || $job->ptc_status == 'review' || $job->ptc_status == 'req_delete' && $job->ptc_expire_day > now()) {{route('user.ptcEdit',$job->id)}} @else # @endif">
                            <div class="border p-1 mb-2 row job-area" 
                            style="
                                @if($job->ptc_status == 'review') 
                                    background-color: #47bf2930 
                                @elseif($job->ptc_status == 'pending') 
                                    background-color: #b7962078 
                                @elseif($job->ptc_status == 'done') 
                                @elseif($job->ptc_status == 'adminPending') 
                                    background-color: #c52a2a87 
                                @elseif($job->ptc_status == 'reject') 
                                    background-color: red 
                                @elseif($job->ptc_status == 'req_delete') 
                                    background-color: #fdc805 
                                @elseif($job->ptc_status == 'delete') 
                                    background-color: gray 
                                @endif">
                                <div class="col-lg-4 col-md-5 col-12 text-dark fw-700 job_title">                                    
                                    <span style="font-size: 11px;"><strong>Status : </strong>{{$job->ptc_status}}</span> <br>
                                    {{$job->ptc_title}}
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
                                    <div style="color:#15ba5a;font-size: 20px;font-weight: bolder;text-align:right;">{{$job->ptc_each_earn}}$</div>
                                    <span style="font-size: 11px;text-align:right;display:block;">
                                        <button class="btn btn-small btn-warning" onclick="window.location.href='{{ route('user.ptcEdit', $job->id) }}?expiredOnly=yes'">Update</button> || Exp: {{ Carbon::parse($job->ptc_expire_day)->format('d-M-Y') }}</span>
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

@endsection