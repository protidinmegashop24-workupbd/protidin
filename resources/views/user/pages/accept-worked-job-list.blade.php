@extends('user.layouts.master')
@section('css')
    <style>
        .shadow-sm {
            display: none !important;
        }
    </style>
@endsection
@section('user-content')
    <div class="container-fluid mt-2">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-8 col-12">
                <div class="alert alert-success bg-warning text-white border-0">
                    <marquee scrollamount="6">
                        @foreach ($headlines as $key=>$headline)
                            <a href="{{ $headline->link }}" class="text-black" style="font-size:20px;"><i class="fe fe-link me-2 white-text" aria-hidden="true"></i>{{ $headline->title }}</a>
                        @endforeach
                    </marquee>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-2">
        <div class="card-header">
            <div class="card-title text-center">
                <strong>{{ accepted_task_note() }}</strong>
            </div>
        </div>
        <div class="card-body">
            <a href="{{route('user.all-satisfied-woked')}}" class="btn btn-sm btn-info">All Satisfied</a>
            <div class="table-responsive">
                <table class="table table-bordered text-nowrap" id="example1">
                    <thead>
                        <tr>
                            <th scope="col border-bottom-0" width="5%">ID</th>
                            <th scope="col border-bottom-0">User</th>
                            <th scope="col border-bottom-0">Title</th>
                            <!--<th scope="col border-bottom-0" width="5%">Work Proof</th>-->
                            <th scope="col border-bottom-0">Verify Work</th>
                            <th scope="col border-bottom-0" width="8%">Status</th>
                            <th scope="col border-bottom-0">Reason/Remark</th>
                            <th scope="col border-bottom-0" width="8%">Date Time</th>
                            <!--<th width="10%">Action</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datas as $key => $data)
                            @if (this_work_for_my_job($data->job_id) == 1)
                                <tr>
                                    <td>{{ $data->id }}</td>
                                    <td>{{ user_name($data->user_id) }}</td>
                                    <td>{{ job_title($data->job_id) }}</td>
                                    <!--<td>{{ $data->work_proof }}</td>-->
                                    <td>
                                        <a href="javascript:;" class="btn btn-danger btn-sm" onclick="sShoootMosdalOpen({{ $data->id }})">
                                            Verify
                                        </a>
                                    </td>
                                    <td>
                                        @if ($data->status == 1)
                                            <span class="badge bg-success">Paid</span>
                                        @elseif ($data->status == 2)
                                            <span class="badge bg-danger">Reject</span>
                                        @elseif ($data->status == 3)
                                            <span class="badge bg-warning">Reported</span>
                                        @elseif ($data->status == 4)
                                            <span class="badge bg-warning">Resume</span>
                                        @else
                                            <span class="badge bg-danger">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->status == 3)
                                            {{ $data->report_reason }}
                                        @else
                                            {{ $data->reason }}
                                        @endif
                                    </td>
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
                                                    <h5 class="modal-title" id="exampleModalLabel">Reject This Work</h5>
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
                                                    <div class="form-group col-lg-12 col-md-12 col-12">
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

                                <div class="modal fade" id="resume_job_{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="{{ route('user.job-work-resume', $data->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Resume This Work</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group col-lg-12 col-md-12 col-12">
                                                        <textarea class="form-control" name="reason" id="reason" cols="30" rows="3" required placeholder="Reason for reject">{{ $data->report_reason }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resumeMosdalClose({{ $data->id }})">Close</button>
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
                                                            <img src="{{ URL::to($s_shot) }}" class="img-fluid col-6" height="150px" width="100%" alt="Screen Shoot"><br><br>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            @endif

                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Work Proof</h5>
                                            </div>
                                            <div class="modal-body">
                                                {{ $data->work_proof }}
                                            </div>

                                            <div class="modal-footer">
                                                @if ($data->status == 0)
                                                    <a href="{{ route('user.job-work-approve', $data->id) }}" onclick="return confirm(' You want to approved?');" class="btn btn-sm btn-success">Approve</a>
                                                    <a href="javascript:;" class="btn btn-info btn-sm" onclick="rateMosdalOpen({{ $data->id }})">
                                                        Rate
                                                    </a>
                                                    <a href="javascript:;" class="btn btn-danger btn-sm" onclick="rejectMosdalOpen({{ $data->id }})">
                                                        Reject
                                                    </a>
                                                    <a href="javascript:;" class="btn btn-warning btn-sm" onclick="reportMosdalOpen({{ $data->id }})">
                                                        Report
                                                    </a>
                                                    <a href="javascript:;" class="btn btn-info btn-sm" onclick="resumeMosdalOpen({{ $data->id }})">
                                                        Resume
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
        
        function resumeMosdalOpen(id){
            $('#s_shoot_job_'+id).modal('hide');
            $('#resume_job_'+id).modal('show');
        }

        function resumeMosdalClose(id){
            $('#resume_job_'+id).modal('hide');
        }

        function sShoootMosdalOpen(id){
            $('#s_shoot_job_'+id).modal('show');
        }

        function sShoootMosdalClose(id){
            $('#s_shoot_job_'+id).modal('hide');
        }

    </script>
@endsection
