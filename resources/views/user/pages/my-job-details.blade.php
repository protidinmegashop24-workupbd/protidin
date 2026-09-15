@extends('user.layouts.master')
@section('css')
    <link href="{{ asset('frontend/user/assets/css/nucleo.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/fontawesome-all.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/argon.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/myapp.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <style>
        .container-fluid {
            padding-right: 0px;
            padding-left: 0px;
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
    </style>
@endsection
@section('user-content')


    <div class="row card-wrapper mt-4">
        <div class="col-lg-8">
            <div class="row card-wrapper">
                <div class="col-sm-3">
                    <div class="card card-pricing border-0 text-center mb-4">
                        <div class="card-body">
                            <div class="display-4">ID: {{$job->id}}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- Job Done Stats -->
                    <div class="card card-stats">
                        <div class="card-body" style="padding: 10px 10px 10px 20px !important">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">DONE</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ complete_work_this_job($job->id) }} of {{ $job->worker_need }}</span>
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
                <div class="col-sm-3">
                    <!-- Earning $$ -->
                    <div class="card card-pricing border-0 text-center mb-4">
                        <div class="card-body">
                            <div>
                                <h5><span class="text-blue font-weight-bold" style="font-size: 22px;"><span style="color:#1D8348;">${{ $job->each_worker_earn }}</span></span></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="h3 mb-0" style="color: black;">
                        {{ $job->title }}
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
                  <p class="card-text text-uppercase text-muted font-weight-800"><b> </b></p>
                  <h5><b><i class="fas fa-tasks"></i> What is expected from workers?</b></h5>
                  <p></p>
                  <div style="background:#f5f7fa;padding: 5px;border-radius:4px;" class="drk-bg-second">
                     <p class="card-text font-weight-500">{{ specific_task($job->id) }}</p>
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
                <div class="card-body drk-bg-second">
                    <div class="d-flex gap-2  mb-4">
                        <!--<a href="{{ route('user.job-working-proves', $job->code) }}" class="btn btn-lg btn-info">Proves</a>-->
                        <!--@if ($job->worker_need > $job->worker_confirmed)-->
                        <!--    @if(job_ready_for_boost($job->id) == 1)-->
                        <!--        <a href="javascript:;" class="btn btn-lg btn-primary" onclick="boostJob({{ $job->id }})">Boost</a>-->
                        <!--    @else-->
                        <!--        <a href="javascript:;" class="btn btn-lg btn-primary" onclick="boostJob({{ $job->id }})">{{remain_interval_for_boost($job->id)}}m</a>-->
                        <!--    @endif-->
                        <!--@endif-->
                        <a href="{{route('user.job-delete',$job->id)}}" class="btn btn-lg btn-danger">Delete</a>
                        @if ($job->worker_need <= $job->worker_confirmed)
                            <a href="javascript:;" class="btn btn-lg btn-info" onclick="updateJobWorker({{ $job->id }})">Update</a>
                        @else
                            <a href="{{ route('user.job-edit', $job->id) }}" class="btn btn-lg btn-success">Edit</a>
                        @endif
                        
                        @if($job->worker_need != $job->worker_confirmed)
                            @if($job->pause == 0)
                                <a href="{{route('user.pause-job',$job->id)}}" class="btn btn-lg btn-danger">Pause</a>
                            @else
                                <a href="{{route('user.start-job',$job->id)}}" class="btn btn-lg btn-success">Resume</a>
                            @endif
                        @endif
                    </div>
                    
                    @if(job_ready_for_boost($job->id) == 1)
                        <div class="modal fade" id="boost_job_{{ $job->id }}" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="{{ route('user.job-boosting-update', $job->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Boost For "{{ $job->title }}"</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <select class="form-control" name="boost_charge" required>
                                                    <option value="">Select One</option>
                                                    @foreach(boost_charges() as $boost_charge)
                                                        <option value="{{$boost_charge->id}}">{{$boost_charge->duration}} Minutes - ${{$boost_charge->charge}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
    
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="boostJobModalClose({{ $job->id }})">Close</button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
    
                    @if ($job->worker_need <= $job->worker_confirmed)
                        <div class="modal fade" id="worker_need_{{ $job->id }}" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="{{ route('user.job-work-need-update', $job->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update Worker Need</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group col-lg-12 col-md-12 col-12">
                                                <input type="number" class="form-control" name="worker" value="0" min="0" required>
                                            </div>
                                        </div>
    
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="updateJobWorkerMosdalClose({{ $job->id }})">Close</button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

@endsection
@section('js')
    <script>
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
        
        function updateJobWorker(id){
            $('#worker_need_'+id).modal('show');
        }

        function updateJobWorkerMosdalClose(id){
            $('#worker_need_'+id).modal('hide');
        }
        
        function boostJob(id){
            $('#boost_job_'+id).modal('show');
        }

        function boostJobModalClose(id){
            $('#boost_job_'+id).modal('hide');
        }
    </script>
@endsection
