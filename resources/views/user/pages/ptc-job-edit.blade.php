@extends('user.layouts.master')
@section('css')
    <style>
        .ads-rate-area{
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            display: flex;
            justify-content: space-between;
        }
        .ads-rate-area-active{
            border: 1px solid #31bd21 !important;
        }
    </style>
@endsection
@section('user-content')

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-6 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit PTC Job</h4>
            </div>
            @if($job->ptc_status == 'review')            
                <h4 class="badge" style="color: red;padding-top: 11px;">{{$job->ptc_reject_notice}}</h4>
            @endif            
            <div class="card-body">
                <form id="ad-form" action="{{ route('user.ptcEditStore') }}" method="POST">
                    <input type="hidden" name="id" value="{{$job->id}}">
                    @csrf
                    <div class="">
                        <div class="form-group">
                            <label for="ptc_title" class="form-label">জব টাইটেল <span class="text-red">*</span></label>
                            <input class="form-control" type="text" name="ptc_title" placeholder="Job Title" required id="ptc_title" value="{{$job->ptc_title}}">
                            @if ($errors->has('ptc_title'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('ptc_title') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="ptc_jobLink" class="form-label">এড এর লিংক <span class="text-red">*</span></label>
                            <input class="form-control" type="text" name="ptc_jobLink" placeholder="https://www.google.com" required id="ptc_jobLink" value="{{$job->ptc_jobLink}}">
                            @if ($errors->has('ptc_jobLink'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('ptc_jobLink') }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label for="package_name" class="form-label">প্রতিটা ক্লিক এর প্রদত্ত মূল্য পরিবর্তন গ্রহণযোগ্য নয় <span class="text-red">*</span></label>    
                        </div>
                        
                        <div class="form-group">
                            <label for="ptc_worker_needed" class="form-label">কতটি ক্লিক পরিবর্তন গ্রহণযোগ্য নয়?<span class="text-red">*</span></label>
                        </div>
                        <div class="form-group">
                            <label for="ptc_expire_day" class="form-label">কত তারিখ(দিন)পর্যন্ত কাজটি মার্কেটে থাকবে?<span class="text-red">*</span></label>
                            <input class="form-control" type="date" name="ptc_expire_day" required id="ptc_expire_day" min="<?php date('Y-m-d'); ?>" value="{{ \Carbon\Carbon::parse($job->ptc_expire_day)->format('Y-m-d') }}">
                            @if ($errors->has('ptc_expire_day'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('ptc_expire_day') }}
                                </div>
                            @endif
                        </div>   
                        @if(request()->query('expiredOnly'))
                            <input type="hidden" name="expiredOnly" value="yes">
                        @endif
                        <div class="form-group">
                            <label for="ptc_status"></label>
                            <select class="form-control" name="ptc_status" id="ptc_status">
                                @if(request()->query('expiredOnly'))
                                <option value="running">running</option>
                                @endif
                                <option value="pending">Pending</option>
                                <option value="review">Review</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ptc_job_details" class="form-label">Message <span class="text-red">(ঐচ্ছিক)</span></label>
                            <textarea name="ptc_job_details" id="ptc_job_details" rows="6" class="form-control form--control">
                                {{$job->ptc_job_details}}
                            </textarea>
                        </div>                        
                    </div>
                    <button type="submit" class="btn btn-primary mt-4 mb-0">আপডেট করুন</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')



@endsection