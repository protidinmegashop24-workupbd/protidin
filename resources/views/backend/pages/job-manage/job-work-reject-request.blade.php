@extends('backend.layouts.master')

@section('title')
    {{ $title }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('back-content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">All {{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">All {{ $title }}</h3>
                        </div>
                        <div class="card-body overflow-x">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col border-bottom-0" width="5%">#ID</th>
                                        <th scope="col border-bottom-0">User</th>
                                        <th scope="col border-bottom-0">Title</th>
                                        <th scope="col border-bottom-0">Verify Work</th>
                                        <th scope="col border-bottom-0" width="8%">Status</th>
                                        <th scope="col border-bottom-0">Reason</th>
                                        <th scope="col border-bottom-0" width="8%">Date Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datas as $key => $data)
                                        <tr>
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->user_id }}</td>
                                            <td>{{ job_title($data->job_id) }}</td>
                                            <td>
                                                <a href="javascript:;" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#edit_{{ $data->id }}">
                                                    Verify
                                                </a>
                                                <a href="javascript:;" class="btn btn-info btn-sm" data-toggle="modal" data-target="#job_requirements{{ $data->id }}">
                                                    Job Requirement
                                                </a>
                                            </td>
                                            <td>
                                                @if ($data->status == 1)
                                                    <span class="badge bg-success">Paid</span>
                                                @elseif ($data->status == 2)
                                                    <span class="badge bg-danger">Reject</span>
                                                @elseif ($data->status == 5)
                                                    <span class="badge bg-warning">Request For Reject</span>
                                                @else
                                                    <span class="badge bg-danger">Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $data->reason }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y g:i A')}}
                                            </td>
                                        </tr>
                                        
                                        <div class="modal fade" id="edit_{{ $data->id }}" tabindex="-1" role="dialog"
                                            aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Work Verify</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body row">
                                                        <div class="form-group col-lg-12 col-md-12 col-12 row m-0">
                                                            <label for="categoryName">Screen Shoots</label>
                                                            <div>
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
                                                        <div class="form-group col-lg-12 col-md-12 col-12">
                                                            <label for="categoryName">Work Proof</label>
                                                            <div>
                                                                {{ $data->work_proof }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <a href="{{ route('admin.job-work-approve', $data->id) }}" onclick="return confirm(' You want to approved?');" class="btn btn-sm btn-success">Approve</a>
                                                        <a href="{{ route('admin.job-work-final-reject', $data->id) }}" onclick="return confirm(' Are you unsatisfy?');" class="btn btn-sm btn-danger">Unsatisfy</a>
                                                        <a href="{{ route('admin.job-work-delete', $data->id) }}" onclick="return confirm(' You want to delete?');" class="btn btn-sm btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="modal fade" id="job_requirements{{ $data->id }}" tabindex="-1" role="dialog"
                                            aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Job Requirement</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if(find_job($data->job_id))
                                                            <div>
                                                                <h3><strong>Specific Task</strong></h3>
                                                                <p>{{find_job($data->job_id)->specific_task}}</p>
                                                            </div>
                                                            <div>
                                                                <h3><strong>Required Proof</strong></h3>
                                                                <p>{{find_job($data->job_id)->required_proof}}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection

@section('js')
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    {{-- <script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script> --}}
    <script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        $(function() {
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

        function depositApproved(id) {
            if (confirm("Are you sure?")) {
                document.getElementById('deposit-approved-'+id).submit();
            }
            return false;
        }
    </script>
@endsection
