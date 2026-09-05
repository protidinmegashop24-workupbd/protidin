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
                                        <th width="5%">ID</th>
                                        <th>Title</th>
                                        <th width="8%">Location</th>
                                        <th width="8%">Country</th>
                                        <th width="8%">Category</th>
                                        <th width="8%">Sub Category</th>
                                        <th width="8%">Specific Task</th>
                                        <th width="8%">Reqired Proof</th>
                                        <th width="8%">Worker</th>
                                        <th width="8%">Worker Cost</th>
                                        <th width="8%">Screenshot</th>
                                        <th width="8%">Dueration</th>
                                        <th width="8%">Budget</th>
                                        <th width="8%">Pending</th>
                                        <th width="8%">Complete</th>
                                        <th width="8%">Reject</th>
                                        <th>Posted By</th>
                                        <th width="8%">Reason/Remark</th>
                                        <th width="8%">Status</th>
                                        <th width="8%">Date Time</th>
                                        <th width="8%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datas as $key => $data)
                                        <tr>
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->title }}</td>
                                            <td>{{ location_zone($data->location_zone_id) }}</td>
                                            <td>{{ country($data->location_zone_country) }}</td>
                                            <td>{{ category($data->category_id) }}</td>
                                            <td>{{ sub_category($data->sub_category) }}</td>
                                            <td>{{ specific_task($data->id) }}</td>
                                            <td>{{ $data->required_proof }}</td>
                                            <td>{{ $data->worker_need }}</td>
                                            <td>{{ $data->each_worker_earn }}$</td>
                                            <td>{{ $data->required_screenshots }}</td>
                                            <td>{{ $data->estimited_day }} Days</td>
                                            <td>{{ $data->budget }}$</td>
                                            <td>
                                                @if($data->worker_confirmed < $data->worker_need )
                                                    {{ pending_work_for_job($data->id) }}
                                                @else
                                                    0
                                                @endif
                                            </td>
                                            <td>
                                                @if($data->worker_confirmed > $data->worker_need )
                                                    {{ $data->worker_need }}
                                                @else
                                                    {{ $data->worker_confirmed }}
                                                @endif
                                            </td>
                                            <td>{{ reject_work_for_job($data->id) }}</td>
                                            <td>{{ $data->user_id }}</td>
                                            <td>{{ $data->reason }}</td>
                                            <td>
                                                @if($data->worker_confirmed >= $data->worker_need )
                                                    <span class="badge bg-success">Complete</span>
                                                @else
                                                    @if ($data->status == 1)
                                                        <span class="badge bg-success">Approved</span>
                                                    @elseif ($data->status == 2)
                                                        <span class="badge bg-danger">Reject</span>
                                                    @else
                                                        <span class="badge bg-danger">Pending</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y g:i A')}}
                                            </td>
                                            <td>
                                                @if ($data->status == 0 || $data->status == 1)
                                                    <a href="{{ route('admin.job-edit', $data->id) }}" class="btn btn-sm btn-info">Edit</a>
                                                @endif

                                                @if ($data->status == 0)
                                                    <a href="{{ route('admin.job-approve', $data->id) }}" onclick="return confirm(' You want to approved?');" class="btn btn-sm btn-success">Approve</a>
                                                    <a href="javascript:;" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#reject_job{{ $data->id }}">
                                                        Reject
                                                    </a>
                                                @endif
                                                <a href="{{ route('admin.job-delete', $data->id) }}" onclick="return confirm(' You want to delete?');" class="btn btn-sm btn-danger">Delete</a>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="reject_job{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{ route('admin.reject-job', $data->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Reject This Job</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="form-group col-lg-12 col-md-12 col-12">
                                                            <label for="reason">Reason</label>
                                                            <textarea class="form-control" name="reason" id="reason" cols="30" rows="3">{{ $data->reason }}</textarea>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </div>
                                                </form>
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
