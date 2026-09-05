<title>Prooves - ACT UP JOB</title>
@extends('user.layouts.master')
@section('css')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" href="https://actupjob.com/resources/css/argon.css" type="text/css">
    <link rel="stylesheet" href="https://actupjob.com/resources/css/myapp.css" type="text/css">
    <style>
        .shadow-sm {
            display: none !important;
        }
        .table th {
            background-color: rgba(250, 250, 250, 1); /* White faded background */
            color: #000066; /* Black font color */
            font-size: 12px;
        }
/* Remove vertical borders for table cells and adjust horizontal border color */
    .table-no-vertical-border th,
    .table-no-vertical-border td {
        border-left: none;  /* Remove left border */
        border-right: none; /* Remove right border */
        border-color: rgba(0, 0, 0, 0.1); /* Adjust the border color (0.1 for a very faint line) */
        }
        .table td {
        font-size: 14px;
        color: #000;
    }
    </style>
@endsection
@section('user-content')
<section>
    <div class="card mt-2">
        <div class="card-header">
    <div class="card-title text-center" style="font-weight: 500; color: green; font-size: 20px;">Task Proves</div>
        </div>
        <div class="card-body">
            <div class="notice-box mb-4" style="text-align: center; padding-top: 10px;">
    <marquee bgcolor="green" style="border: 2px solid blue; background-color: white; padding: 2px; overflow: hidden; white-space: nowrap; border-radius: 10px; display: inline-block; height: 45px;" behavior="scroll" direction="left" scrollamount="5">
        <p class="notice-text" style="color: #000066; margin: 0; font-size: 16px; margin-top: 7px;">Please review all proof carefully</p>
    </marquee>
</div>
            <a href="{{route('user.all-satisfied-job-woked', $job->id)}}" class="btn btn-sm btn-info mb-2">All Satisfied</a>
            <div class="table-responsive">
                <table class="table table-bordered text-nowrap table-no-vertical-border" id="example1">
                   
                    <thead>
                        <tr>
                            <th scope="col border-bottom-0" width="5%"> JOB ID</th>
                            <th scope="col border-bottom-0">JOB NAME</th>
                            <th scope="col border-bottom-0">USER</th>
                            <th scope="col border-bottom-0">TASK PROVES</th>
                            
                            <th scope="col border-bottom-0" width="8%">STATUS</th>
                            <th scope="col border-bottom-0">TASK PRICE</th>
                            
                            <th scope="col border-bottom-0" width="8%">DATE & TIME</th>
                            <!--<th width="10%">Action</th>-->
                        </tr>
                    </thead>
                    <tbody>
                    
                        @foreach ($datas as $key => $data)
                            @if (this_work_for_my_job($data->job_id) == 1)
                                <tr>
                                    <td>{{ $data->id }}</td>
                                    <td>{{ job_title($data->job_id) }}</td>
                                    <td>{{ user_name($data->user_id) }}</td>
                                    <td>
                                        <a href="javascript:;" class="badge bg-primary" onclick="sShoootMosdalOpen({{ $data->id }})">
                                            Proves
                                        </a>
                                    </td>
                                    <td>
                                        @if ($data->status == 1)
                                            <span class="badge bg-success">Satisfied</span>
                                        @elseif ($data->status == 2)
                                            <span class="badge bg-warning">Reject Under Review</span>
                                        @elseif ($data->status == 5)
                                            <span class="badge bg-danger">Admin Review</span>
                                        @elseif ($data->status == 3)
                                            <span class="badge bg-primary">Reported</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $job->each_worker_earn }}$</td>
                                    
                                    <td>
                                        {{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y g:i A')}}
                                    </td>
                                    <!--<td>-->
                                    <!--    @if ($data->status == 0)-->
                                    <!--        <a href="{{ route('user.job-work-approve', $data->id) }}" onclick="return confirm(' You want to approved?');" class="btn btn-sm btn-success">Approve</a>-->
                                    <!--        <a href="javascript:;" class="btn btn-danger btn-sm" onclick="rejectMosdalOpen({{ $data->id }})">-->
                                    <!--            Reject-->
                                    <!--        </a>-->
                                    <!--    @endif-->
                                    <!--</td>-->
                                </tr>

                                <div class="modal fade" id="reject_job_{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="{{ route('user.job-work-reject', $data->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Reject This Work?</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group col-lg-12 col-md-12 col-12">
                                                        <textarea class="form-control" name="reason" id="reason" cols="30" rows="3" required placeholder="Reason for reject">{{ $data->reason }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="rejectMosdalClose({{ $data->id }})">Close</button>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="modal fade" id="report_job_{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="{{ route('user.job-work-report', $data->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Report This Work</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="report_to_admin" class="form-label">Report To Admin</label>
                                                        <select class="form-control" name="report_to_admin" aria-label="Default select example">
                                                          <option value="0" @if($data->report_to_admin == 0) selected @endif>No</option>
                                                          <option value="1" @if($data->report_to_admin == 1) selected @endif>Yes</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="report_reason" class="form-label">Report Reason</label>
                                                        <textarea class="form-control" name="reason" id="reason" cols="30" rows="3" required placeholder="Reason for reject">{{ $data->report_reason }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="reportMosdalClose({{ $data->id }})">Close</button>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="modal fade" id="rate_job_{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="{{ route('user.job-work-rate', $data->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Rate This Work</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" id="work_rate_{{ $data->id }}" name="rate" value="0"/>
                                                    <div class="form-group">
                                                        <label for="rate">Work Rateing: </label>
                                                        <i class="far fa-star rate-star" id="reate_1_{{ $data->id }}" onclick="workRated('{{ $data->id }}', '1')"></i>
                                                        <i class="far fa-star rate-star" id="reate_2_{{ $data->id }}" onclick="workRated('{{ $data->id }}', '2')"></i>
                                                        <i class="far fa-star rate-star" id="reate_3_{{ $data->id }}" onclick="workRated('{{ $data->id }}', '3')"></i>
                                                        <i class="far fa-star rate-star" id="reate_4_{{ $data->id }}" onclick="workRated('{{ $data->id }}', '4')"></i>
                                                        <i class="far fa-star rate-star" id="reate_5_{{ $data->id }}" onclick="workRated('{{ $data->id }}', '5')"></i>
                                                    </div>
                                                    @if ($data->screenshot_proof != NULL)
                                                        <div class="form-group">
                                                            <label for="rate">Screen Shoots: </label>
                                                            <div class="row">
                                                                @php
                                                                    $s_shots = explode("|",$data->screenshot_proof);
                                                                @endphp
                                                                @if ($s_shots)
                                                                    @foreach ($s_shots as $key=>$s_shot)
                                                                        <img src="{{ URL::to($s_shot) }}" class="img-fluid col-6" height="150px" width="100%" alt="Screen Shoot"><br><br>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="form-group">
                                                        <label for="rate">Work Proves: </label>
                                                        <div>
                                                            {{ $data->work_proof }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="rateMosdalClose({{ $data->id }})">Close</button>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="modal fade" id="s_shoot_job_{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">
                                                    <div class="form-group">
                                                        <label for="rate">Work Rateing: </label>
                                                        <i class="@if($data->rating >= 1) fas @else far @endif fa-star"></i>
                                                        <i class="@if($data->rating >= 2) fas @else far @endif fa-star"></i>
                                                        <i class="@if($data->rating >= 3) fas @else far @endif fa-star"></i>
                                                        <i class="@if($data->rating >= 4) fas @else far @endif fa-star"></i>
                                                        <i class="@if($data->rating >= 5) fas @else far @endif fa-star"></i>
                                                        
                                                        <a href="javascript:;" class="btn btn-info btn-sm" onclick="rateMosdalOpen({{ $data->id }})">
                                                            Rate
                                                        </a>
                                                    </div>
                                                </h5>
                                            </div>
                                            @if ($data->screenshot_proof != NULL)
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Screen Shoots</h5>
                                                </div>
                                                <div class="modal-body row">
                                                    @php
                                                        $s_shots = explode("|",$data->screenshot_proof);
                                                    @endphp
                                                    @if ($s_shots)
                                                        @foreach ($s_shots as $key=>$s_shot)
                                                            <img src="{{ URL::to($s_shot) }}" class="img-fluid c-pointer @if(count($s_shots) > 1) col-3 @endif" height="190px" width="100%" alt="Screen Shoot" onclick="viewImage('{{URL::to($s_shot)}}')"><br><br>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            @endif

                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Submitted Proof</h5>
                                            </div>
                                            <div class="modal-body">
                                                {{ $data->work_proof }}
                                            </div>
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Required Proof Was</h5>
                                            </div>
                                            <div class="modal-body">
                                                {{ $job->required_proof }}
                                            </div>

                                            <div class="modal-body">
                                                @if ($data->status == 3)
                                                    {{ $data->report_reason }}
                                                @else
                                                    <p style="color: red">{{ $data->reason }}</p>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                {{-- @if ($data->status == 0) --}}
                                                @if (in_array($data->status, [0, 1, 2, 3, 5]))
                                                    <a href="{{ route('user.job-work-approve', $data->id) }}" onclick="return confirm(' You want to approved?');" class="btn btn-sm btn-success">Satisfied</a>
                                                    <a href="javascript:;" class="btn btn-danger btn-sm" onclick="rejectMosdalOpen({{ $data->id }})">
                                                        Unsatisfied
                                                    </a>
                                                    <a href="javascript:;" class="btn btn-warning btn-sm" onclick="reportMosdalOpen({{ $data->id }})">
                                                        Report
                                                    </a>
                                                @endif
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="sShoootMosdalClose({{ $data->id }})">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endif
                        @endforeach
                   
                    </tbody>
                </table>
                {{ $datas->links() }}
            </div>
        </div>
    </div>
</section>
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

        function rejectMosdalOpen(id){
            $('#s_shoot_job_'+id).modal('hide');
            $('#reject_job_'+id).modal('show');
        }

        function rejectMosdalClose(id){
            $('#reject_job_'+id).modal('hide');
        }
        
        function reportMosdalOpen(id){
            $('#s_shoot_job_'+id).modal('hide');
            $('#report_job_'+id).modal('show');
        }

        function reportMosdalClose(id){
            $('#report_job_'+id).modal('hide');
        }
        
        function rateMosdalOpen(id){
            $('#s_shoot_job_'+id).modal('hide');
            $('#rate_job_'+id).modal('show');
        }

        function rateMosdalClose(id){
            $('#rate_job_'+id).modal('hide');
        }

        function sShoootMosdalOpen(id){
            $('#s_shoot_job_'+id).modal('show');
        }

        function sShoootMosdalClose(id){
            $('#s_shoot_job_'+id).modal('hide');
        }

        function workRated(work_id, rate){
            $('.rate-star').removeClass('fas');
            $('.rate-star').addClass('far');
            $('#work_rate_'+work_id).val(rate);
            
            for (let i = 1; i <= rate; i++) {
                $('#reate_'+i+'_'+work_id).removeClass('far');
                $('#reate_'+i+'_'+work_id).addClass('fas');
            }
        }

    </script>
@endsection
