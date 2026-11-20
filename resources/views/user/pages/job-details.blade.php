@extends('user.layouts.master')
@section('css')
    <link href="{{ asset('frontend/user/assets/css/nucleo.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/fontawesome-all.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/argon.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/myapp.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <style>
     body {
            font-family: 'noto serif bengali', sans-serif;
        }
        .gallery-img {
            display: flex;
            align-items: center;
            justify-content: center;
            height: calc(100% - 64px);
            padding-top: 0;
            width: 100%;
            padding: 0 50px;
        }
        .gallery-img img {
            width: 100%;
            height: auto;
            max-height: 250px;
        }
        

        ::selection {
            color: #fff;
            background: #ffad29;
        }
        
        .post_thumb_class {
            width: 90%;
            margin-bottom: 12px;
            border-radius: 4px;
            max-height: 400px;
        }
        .bg-csyellow {
            background:#17a2b8;
        }
        
        .bg-csgreen {
            background:#22ab59;
        }
        
        
        </style>
@endsection
@section('user-content')

    <!-- Row -->
    <div class="row card-wrapper mt-4">
        <div class="col-lg-8">
            <div class="row card-wrapper">
                <div class="col-sm-7">
                    <!-- Job Done Stats -->
                    <div class="card card-stats">
                        <div class="card-body" style="padding: 10px 10px 10px 20px !important">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">DONE</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ complete_work_this_job($job->id) }} of {{ $job->worker_need }}</span>
                                    <div class="progress progress-xs mb-0" style="width: 70%;margin-top: 5px">
                                        <div class="progress-bar bg-csgreen" role="progressbar" aria-valuenow="{{ complete_work_this_job($job->id) }}" aria-valuemin="0" aria-valuemax="{{ $job->worker_need }}" style="width: {{ this_job_complet_rate($job->id) }}%;"></div>
                                    </div>
                                    <p class="mt-3 mb-0 text-sm"></p>
                                </div>
                                <div class="col-auto" style="line-height: 73px;">
                                    <div class="icon icon-shape text-white rounded-circle shadow" style="background: #fec30f;">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <!-- Earning $$ -->
                    <div class="card card-pricing border-0 text-center mb-4">
                        <div class="card-body">
                            <div>
                                <h5><span class="text-blue font-weight-bold" style="font-size: 22px;">YOU CAN EARN &nbsp;<span style="color:#1D8348;">${{ $job->each_worker_earn }}</span></span></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('user.job-work-post') }}" method="POST" enctype="multipart/form-data" style="padding-bottom: 150px;">
                @csrf
                
                <!-- Job Details -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="h3 mb-0" style="color: black;">
                            {{ $job->title }}
                            <div class="text-right">
                                <a class="btn btn-sm btn-outline-danger" href="{{ route('user.job-hide',$job->id) }}">Hide</a>
                            </div>
                        </h5>
                    </div>
                   <div class="card-body">
                      <div style="text-align:center">
                         <img class="post_thumb_class" src="{{ URL::to($job->thumbnail_image) }}" width="100%">
                      </div>
                      <div class="row">
                         <div class="col-sm-6">
                            <b>
                               <h5>
                                  <span style="padding: 5px" class="card-text text-muted text-green">
                                  <i class="fa fa-briefcase" aria-hidden="true"></i> {{ $job->id }}</span>
                               </h5>
                            </b>
                         </div>
                         <div class="col-sm-6">
                            <b>
                               <h5>
                                  <span style="padding: 5px" class="card-text text-muted">
                                  <i class="fas fa-globe"></i> {{ continent($job->continent_id) }}</span>
                               </h5>
                            </b>
                         </div>
                         <div class="col-sm-6">
                            <b>
                               <h5>
                                  <span style="padding: 5px" class="card-text text-muted"> <i class="fas fa-cogs"></i> {{ sub_category($job->sub_category) }}</span>
                               </h5>
                            </b>
                         </div>
                         <div class="col-sm-6">
                            <b>
                               <h5>
                                  <span style="padding: 5px" class="card-text text-muted"> <i class="fas fa-clock"></i> Time {{$job->estimited_day}} Day</span>
                               </h5>
                            </b>
                         </div>
                         <div class="col-sm-6">
                            <b>
                               <h5>
                                  <span style="padding: 5px" class="card-text text-muted">Last Updated- {{ \Carbon\Carbon::parse($job->created_at)->format('d/m/Y g:i A') }}</span>
                               </h5>
                            </b>
                         </div>
                      </div>
                      <br>
                      <b> </b>
                      <h5>
                         <b>
                            <marquee style="color:red ;padding: 5px;border-radius: 5px;border:1px solid white" behavior="scroll">Hello Sir, Please read carefully. Only accept this task if you understand the requirements 100% !</marquee>
                         </b>
                      </h5>
                      <p class="card-text text-uppercase text-muted font-weight-800"><b> </b></p>
                      <h5><b><i class="fas fa-tasks"></i> কাজটি করার ধাপসমূহ! </b></h5>
                      <p></p>
                      <div style="background:#f5f7fa;padding: 5px;border-radius:4px;" class="drk-bg-second">
                         <p class="card-text font-weight-500">{{ specific_task($job->id) }}</p>
                      </div>
                   </div>
                   <div class="text-right">
                      <div>
                         <a class="btn btn-outline-danger m-3 mr-4" style="border-radius:5px;padding: 8px 15px;" href="javascript:;" onclick="reportThisJob()">Report</a>
                      </div>
                   </div>
                </div>
                <br>
                
                <div class="card">
                    <div class="card-header">
                        <b>
                           <h5 class="h5 mb-0 text-uppercase text-muted font-weight-900">Required proof that task was
                              finished?
                           </h5>
                        </b>
                    </div>
                    <div class="card-body drk-bg-second">
                        <p>{{ $job->required_proof }}</p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h4 class="h4 mb-0 text-uppercase text-muted font-weight-900">Submit required work
                            Prove  <i class="fas fa-sort-alpha-down"></i>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input type="hidden" name="job_id" value="{{ $job->id }}">
                            <textarea class="form-control drk-bg-second" maxlength="200" placeholder="Maximum 200 characters" name="work_proof" id="exampleFormControlTextarea1" rows="3" style="height: 100px; overflow: hidden;" required oninput="autoResize(this)"></textarea>

                        </div>
                    </div>
                </div>

                @for ($i = 1; $i <= $job->required_screenshots; $i++)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="h5 mb-0 text-uppercase text-muted font-weight-900"><span class="badge badge-dark text-white">#{{ $i }}</span> Upload Screenshot Prove  <i class="fas fa-file-download"></i></h5>
                        </div>
                        <div class="card-body">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="screen_shot_select_image_{{ $i }}" name="screenshot_proof[]" accept="image/x-png,image/jpg,image/jpeg" onchange="readURL('{{ $i }}');" lang="en">
                                <label class="custom-file-label" for="sshotUpload1"><i class="far fa-images"></i></label>
                            </div>
                            <div style="text-align: center;"><img class="rounded border-dark" style="margin-top: 10px;max-height: 200px;" width="150px" id="screen_shot_show_image_{{ $i }}"></div>
                        </div>
                    </div>
                @endfor
                
                
                

              
                {{-- @if(site_info()->instanat_verify_active == 1 && Auth::user()->is_verified == 0)
                    <div class="text-center mb-2">
                        <a href="{{ route('user.account-instant-verify') }}" class="btn btn-icon text-white" style="border-radius:5px;background:#ff0000; width:100%;">
                            <span class="btn-inner--icon"><i class="fas fa-envelope"></i></span>
                            <span class="btn-inner--text">Please First verify your account</span>
                        </a>
                    </div>
                @else 
                --}}
                    <div class="text-center mb-2">
                        <button class="btn btn-icon text-white" type="submit" name="submitProve" style="border-radius:5px;background:#27954f; width:100%;">
                            <span class="btn-inner--icon"><i class="fas fa-check"></i></span>
                            <span class="btn-inner--text" >Submit</span>
                        </button>
                    </div>
                {{-- @endif --}}

                
                
                
            </form>
            
            
            
            
            <form action="{{ route('user.report-this-job') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="job_id" value="{{ $job->id }}">
                <div class="modal fade" id="reportThisJob" tabindex="-1" role="dialog" aria-labelledby="reportThisJobTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Report This Job</h5>
                                <button type="button" class="close" onclick="reportThisJobHide()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Do you have any issue/problem with this job?<br> Write down below about your problem/reason</p>
                                <div class="form-group">
                                    <label class="form-control-label" for="exampleFormControlTextarea1">Describe your issue/reason</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="report_describe" placeholder="Write your reason here..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="reportThisJobHide()">Cancel</button>
                                <button type="submit" class="btn btn-primary">Submit Report</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <a href="{{route('user.user-profile', $job->user_id)}}">
                        @if(file_exists(user_image($job->user_id)))
                            <img src="{{ URL::to(user_image($job->user_id)) }}" class="rounded-circle img-center img-fluid shadow shadow-lg--hover" style="width:100px;height:100px">
                        @else
                            <img src="{{ asset('frontend/img/user.png') }}" class="rounded-circle img-center img-fluid shadow shadow-lg--hover" style="width:100px;height:100px">
                        @endif
                        
                    </a>
                    <div class="pt-4 text-center">
                        <h5 class="h3 title">
                            <a href="{{route('user.user-profile', $job->user_id)}}"><span class="d-block mb-1">
                                {{ user_name($job->user_id) }}
                                @if(user_activity($job->user_id) == 1)
                                    <small class="text-muted"><i><span style="color:#239B56; ">I am online </span></i></small> </span>
                                @else
                                    <small class="text-muted"><i><span style="color:#239B56; ">I am Offline </span></i></small> </span>
                                @endif
                            </a>
                          <p class="jobholder-rating"><span style="background: #eaeaea;border-radius:5px;padding:2px 5px 2px 5px">0 Reviews (0) </span></p>
                          <small class="h4 font-weight-light text-muted">Since {{ \Carbon\Carbon::parse(find_user($job->user_id)->created_at)->format('d M y')}}</small>
                        </h5>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection
@section('js')
    <script>
        function reportThisJob() {
            $('#reportThisJob').modal('show');
        }
        
        function reportThisJobHide() {
            $('#reportThisJob').modal('hide');
        }
        
        
        function setAccount(account_id) {
            $('#deposit_account').val(account_id);

            $.ajax({
                url: "{{ route('user.deposit-account-info') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    account_id: account_id,
                },
                success: function(data) {
                    $('#deposit_area').show();
                    $('#deposit_account_text').html('Account No: ' + data['account_no']);
                    $('#deposit_account_guideline').html(data['guideline']);
                },
            });
        }

        function readURL(id){
            var file = $('#screen_shot_select_image_'+id).get(0).files[0];
            console.log(file);
            if(file){
                $('#screen_shot_show_image_'+id).show();
                var reader = new FileReader();

                reader.onload = function(){
                    $('#screen_shot_show_image_'+id).attr("src", reader.result);
                }

                reader.readAsDataURL(file);
            }
        }

    </script>
    <script>
    function autoResize(textarea) {
        // বক্সের উচ্চতা রিসেট করে তার পরে নতুন উচ্চতা নির্ধারণ করা হবে
        textarea.style.height = 'auto'; 
        // ScrollHeight এর উপর ভিত্তি করে নতুন উচ্চতা নির্ধারণ করা হচ্ছে
        textarea.style.height = textarea.scrollHeight + 'px';
    }
</script>
@endsection