@extends('user.layouts.master')
@section('css')
<link href="{{ asset('frontend/user/assets/css/custom.css') }}" rel="stylesheet" />
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


<div class="row justify-content-center">
        
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
                        <div class="card-title text-center" style="font-weight: 500; color: green; font-size: 20px;border-bottom: 1px solid #559ce3;">Your Job History</div>
                        <div class="card-title text-center">
                            <strong></strong>
                        </div>
                    </div>                  
                    
                        @forelse ($historys as $history)
                            <a href="#">
                                <div class="border p-1 mb-2 row job-area">
                                    <div class="col-lg-4 col-md-5 col-12 text-dark fw-700 job_title">{{$history->history->ptc_title}}</div>
                                    <div class="col-lg-6 col-md-5 col-8">
                                        <div class="row pt-1 m-0 justify-content-end">
                                            <div class="col-lg-6 col-md-5 col-7">
                                                <b> <h6 style="width: 100%;text-align:center;font-size: .625rem;">1 OF 1</h6></b>
                                                <div class="progress progress-md p-0">
                                                    <div class="progress-bar progress-bar-animated bg-success" style="width: 100%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-4">
                                        <div class="text-center" style="color:#15ba5a;ont-size: 20px;font-weight: bolder;">{{$history->history->ptc_each_earn}}$</div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <h1 style="text-align: center;padding-top:20vh;padding-bottom:20vh;">No Job History available</h1>
                        @endforelse
                    



                    
                    
       
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
@section('js')



@endsection