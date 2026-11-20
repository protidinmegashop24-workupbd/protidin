@extends('user.layouts.master')
@section('css')
    <style>
        .shadow-sm {
            display: none !important;
        }
        .card-title {
            margin-top: 8px;
        }
    </style>
@endsection
@section('user-content')

<div class="card mt-2 mb-4">
    <div class="card-header" style="height: 50px; border-radius: 0px 0px 10px 10px;">
        <div class="card-title text-center" style="font-weight: 500; color: green; font-size: 20px;">My Applied Tasks</div>
        <div class="card-title text-center">
            <strong>{{ complete_task_note() }}</strong>
        </div>
    </div>
    <div class="card-body">
        <div class="notice-box mb-2">
            <marquee bgcolor="green" style="color:white;padding: 5px;border-radius: 5px;margin-top: 5px" behavior="scroll">
                @foreach ($headlines as $key=>$headline)
                    <a href="{{ $headline->link }}" class="text-white" style="font-size:15px;">
                        <i class="fe fe-link me-2 white-text" aria-hidden="true"></i>{{ $headline->title }}
                    </a>
                    @if (!$loop->last)
                        <span class="mx-2">|</span>
                    @endif
                @endforeach
            </marquee>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered text-nowrap table-no-vertical-border" id="example1">
                <thead>
                    <tr>
                        <th scope="col border-bottom-0" width="5%">JOB ID</th>
                        <th scope="col border-bottom-0">TASK NAME</th>
                        <th scope="col border-bottom-0">VIEW TASK</th>
                        <th scope="col border-bottom-0" width="8%">STATUS</th>
                        <th scope="col border-bottom-0" width="8%">DATE & TIME</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $key => $data)
                        <tr>
                            <td>{{ $data->id }}</td>
                            <td>{{ job_title($data->job_id) }}</td>
                            <td>
                                <a href="javascript:;" class="badge bg-info" onclick="sShoootMosdalOpen({{ $data->id }})">
                                    View
                                </a>
                            </td>
                            <td>
                                {{-- 
                                @if ($data->status == 1)
                                    <span class="badge bg-success">Satisfied</span>
                                @elseif ($data->status == 2)
                                    <span class="badge bg-danger">Unsatisfied</span>
                                @elseif ($data->status == 3)
                                    <span class="badge bg-danger">Reported</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                                --}}
                                @if ($data->status == 1)
                                    <span class="badge bg-success">Satisfied</span>
                                @elseif ($data->status == 2)
                                    <span class="badge bg-warning">Reject Under Review</span>
                                @elseif ($data->status == 5)
                                    <span class="badge bg-danger">Reject By Admin Review </span>
                                @elseif ($data->status == 3)
                                    <span class="badge bg-primary">Reject Strong</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y g:i A')}}
                            </td>
                        </tr>

                        <div class="modal fade" id="s_shoot_job_{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <!-- Modal Content -->
                                    <div class="modal-header" style="color: green;">
                                        <h5 class="modal-title" id="exampleModalLabel">{{ job_title($data->job_id) }}</h5>
                                    </div>
                                    <div class="modal-body mt-2 text-center">
                                        <!-- Widget User Image and Job Details -->
                                        

                                        <p><i class="fas fa-user mr-2"></i><span style="font-weight: bold;"> {{ job_owner($data->job_id) }}</span></p>
                                        <p style="color: green; font-weight: bold;">Job-Id: {{ $data->id }}</p>
                                        <p style="color: red">{{ $data->reason }}</p>
                                    </div>
                                    <div class="modal-header">
                                        <h5 class="modal-title toggle-content" data-target="proofSentContent">Proof Sent</h5>
                                        <h5 class="modal-title toggle-content" data-target="requiredProofContent">Required Proof</h5>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Proof Content -->
                                        <div id="proofSentContent" class="content" style="margin-top: 12px">
                                            {{ $data->work_proof }}
                                        </div>
                                        <div id="requiredProofContent" class="content" style="margin-top: 12px">
                                            <!-- Required Proof Content -->
                                       
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="sShoootMosdalClose({{ $data->id }})">Close</button>
                                </div>
                            </div>
                        </div>
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

        function sShoootMosdalOpen(id){
            $('#s_shoot_job_'+id).modal('show');
        }

        function sShoootMosdalClose(id){
            $('#s_shoot_job_'+id).modal('hide');
        }
        
        function reportMosdalOpen(id){
            $('#report_work_'+id).modal('show');
        }

        function reportMosdalClose(id){
            $('#report_work_'+id).modal('hide');
        }
        
        function resubmitMosdalOpen(id){
            $('#resubmit_work_'+id).modal('show');
        }

        function resubmitMosdalClose(id){
            $('#resubmit_work_'+id).modal('hide');
        }

    </script>
@endsection
