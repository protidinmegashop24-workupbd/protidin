@extends('backend.layouts.master')

@section('title')
    {{ $website->title }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    {{-- select2 --}}
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .new-user{
            float: right;
        }
        svg {
            width: 25px;
        }
    </style>
@endsection

@section('back-content')
@php if($pageType == 'postedJob'){ @endphp
    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-center align-items-center" style="height: 60px; border-radius: 10px 10px 0 0;">
            <h5 class="card-title mb-0">Users Posted Jobs</h5>
        </div>

        <div class="card-body">
            <!-- Notice Box -->
            <!--<div class="notice-box mb-4 mx-auto">-->
            <!--    <p class="notice-text mb-0">Dear sir, please review all submitted proof of workers within 24 hours or all proofs will be approved automatically.</p>-->
            <!--</div>-->

            <!-- Jobs Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="jobsTable">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" width="5%">SL</th>
                            <th scope="col">Job Name</th>
                            <th scope="col" width="10%">Progress</th>
                            <th scope="col" width="10%">Task Price</th>
                            <th scope="col" width="10%">Total Cost</th>
                            <th scope="col" width="10%">Status</th>
                            <th scope="col" width="25%">Action</th> <!-- Increased width for better space -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datas as $key => $data)
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>{{ $data->title }}</td>
                                <td>
                                    {{ complete_work_this_job($data->id) }} of {{ $data->worker_need }}
                                </td>
                                <td>${{ number_format($data->each_worker_earn, 2) }}</td>
                                <td>${{ number_format($data->budget, 2) }}</td>
                                <td>
                                    @if ($data->worker_need <= $data->worker_confirmed)
                                        <span class="badge bg-primary">Complete</span>
                                    @else
                                        @if ($data->status == 1)
                                            <span class="badge bg-success">Approved</span>
                                        @elseif ($data->status == 2)
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-nowrap justify-content-center gap-2">
                                        @if ($data->worker_need > $data->worker_confirmed)
                                            @if(job_ready_for_boost($data->id) == 1)
                                                <button class="btn btn-primary btn-sm btn-action" onclick="boostJob({{ $data->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Boost Job">
                                                     Boost
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm btn-action" disabled data-bs-toggle="tooltip" data-bs-placement="top" title="Boost Available in {{ remain_interval_for_boost($data->id) }} minutes">
                                                  {{ remain_interval_for_boost($data->id) }}m
                                                </button>
                                            @endif
                                        @endif

                                        @if ($data->worker_need <= $data->worker_confirmed)
                                            <button class="btn btn-warning btn-sm btn-action" onclick="updateJobWorker({{ $data->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Update Job">
                                                Update
                                            </button>
                                            <a href="{{ route('user.job-delete', $data->id) }}" class="btn btn-danger btn-sm btn-action" onclick="return confirm('Are you sure you want to delete this job?');" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Job">
                                                Delete
                                            </a>
                                        @endif

                                        <a href="{{ route('user.job-working-proves', $data->code) }}" class="btn btn-info btn-sm btn-action" data-bs-toggle="tooltip" data-bs-placement="top" title="View Proofs">
                                            Proves
                                        </a>
                                        <a href="{{ route('user.my-job-details', $data->code) }}" class="btn btn-success btn-sm btn-action" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                           Details
                                        </a>
                                    </div>
                                </td>
                            </tr>
 

                            <!-- Update Worker Need Modal -->
                            @if ($data->worker_need <= $data->worker_confirmed)
                                <div class="modal fade" id="worker_need_{{ $data->id }}" tabindex="-1" aria-labelledby="updateWorkerNeedModalLabel{{ $data->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('user.job-work-need-update', $data->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="updateWorkerNeedModalLabel{{ $data->id }}">Update Worker Requirement</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="text-danger">Update the job requirements; otherwise, it will be deleted automatically.</p>
                                                    <div class="mb-3">
                                                        <label for="worker_{{ $data->id }}" class="form-label">Number of Workers Needed</label>
                                                        <input type="number" class="form-control" id="worker_{{ $data->id }}" name="worker" value="0" min="0" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <!-- Pagination -->
{{--                <div class="d-flex justify-content-center">
                    {{ $datas->links() }}
                </div>
                --}}
            </div>
        </div>
    </div>
@php }elseif($pageType == 'appliedJob'){ @endphp

<div class="card mt-4 shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-center align-items-center" style="height: 60px; border-radius: 10px 10px 0 0;">
            <h5 class="card-title mb-0">Job Applied Report</h5>
        </div>

        <div class="card-body">
      

            <!-- Jobs Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="jobsTable">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" width="5%">SL</th>
                            <th scope="col">Job Name/Id</th>
                            <th scope="col" width="10%">Earning</th>
                            <th scope="col" width="10%">Job Owner Report</th>
                            <th scope="col" width="10%">Work Proof</th>
                            <th scope="col" width="10%">Screenshot Proof</th>
                            <th scope="col" width="25%">Reason</th> <!-- Increased width for better space -->
                            <th scope="col" width="25%">Report Of Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($datas && $datas->count() > 0)
                            @foreach($datas as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <div>{{ job_title($data->job_id) }}</div>
                                    <small class="text-muted">ID: {{ $data->job_id }}</small>
                                </td>
                                <td>{{ $data->earning }} {{ $website->currency_symbol ?? '$' }}</td>
                                <td>
                                    @if($data->status == 1)
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($data->status == 2)
                                        <span class="badge bg-danger">Rejected</span>
                                    @elseif($data->status == 5)
                                        <span class="badge bg-warning">Request For Reject</span>
                                    @else
                                        <span class="badge bg-danger">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#proofModal_{{ $data->id }}">
                                        View Proof
                                    </button>
                                </td>
                                <td>
                                    @php
                                        $s_shots = $data->screenshot_proof ? explode("|", $data->screenshot_proof) : [];
                                    @endphp
                                    @if(count($s_shots) > 0)
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#proofModal_{{ $data->id }}">
                                            View {{ count($s_shots) }} Images
                                        </button>
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $data->reason ?? 'no reason' }}
                                </td>
                                <td>
                                    {{ $data->report_reason ?? 'no report_reason' }}
                                </td>
                            </tr>
                    
                            <!-- কাজের ডিটেইলস এবং প্রুফ দেখার মোডাল -->
                            <div class="modal fade" id="proofModal_{{ $data->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Work Proof Details</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label><strong>Work Proof Text:</strong></label>
                                                <p>{{ $data->work_proof }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label><strong>Screen Shoots:</strong></label>
                                                <div class="row">
                                                    @foreach($s_shots as $s_shot)
                                                        <div class="col-6 mb-2">
                                                            <img src="{{ URL::to($s_shot) }}" class="img-fluid" alt="Proof">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">কোনো ডাটা পাওয়া যায়নি।</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@php } @endphp
@endsection

@section('js')



    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    {{-- <script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script> --}}
    <script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('backend/plugins/select2/js/select2.full.min.js') }}"></script>
    
    
    
    
    
    

    <script>
        $(function () {
          $("#example1").DataTable({
            "responsive": true,
            "autoWidth": false,
          });
          $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
          });
        });

        $(function () {
            $('.select2').select2();
        });

        // this function for image show when select image to upload database------------
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e){
                    $('#photo')
                        .attr('src', e.target.result)
                        .width(80)
                        .height(80);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(function() {
          $("#filter").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#user-table > tbody > tr").filter(function() {      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
          });
        });

    </script>
@endsection