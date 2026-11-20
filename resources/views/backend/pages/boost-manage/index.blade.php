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
                                        <th>Or ID</th>
                                        <th>SRV ID</th>
                                        <th width="8%">Date Time</th>
                                        <th>Link</th>
                                        <th width="8%">Charge</th>
                                        <th width="8%">Quantity</th>
                                        <th>Service</th>
                                        <th>Reason</th>
                                        <th width="8%">Status</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datas as $key => $data)
                                        <tr>
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->service_id }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y g:i A')}}
                                            </td>
                                            <td><a href="{{ $data->link }}" target="_blank">{{ $data->link }}</a></td>
                                            <td>{{ $data->order_charge }}$</td>
                                            <td>{{ $data->order_qty }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->reason }}</td>
                                            <td>
                                                @if ($data->status == 1)
                                                    <span class="badge bg-success">Process</span>
                                                @elseif ($data->status == 2)
                                                    <span class="badge bg-info">Inprocess</span>
                                                @elseif ($data->status == 3)
                                                    <span class="badge bg-danger">Reject</span>
                                                @elseif ($data->status == 4)
                                                    <span class="badge bg-success">Complete</span>
                                                @else
                                                    <span class="badge bg-danger">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($data->status == 0)
                                                    <a href="{{ route('admin.boost-package-process', $data->id) }}" onclick="return confirm(' You want to process?');" class="btn btn-sm btn-success mb-1">Process</a>
                                                    <a href="{{ route('admin.boost-package-inprocess', $data->id) }}" onclick="return confirm(' You want to inprocess?');" class="btn btn-sm btn-info mb-1">Inprocess</a>
                                                    <a href="javascript:;" class="btn btn-warning btn-sm mb-1" data-toggle="modal" data-target="#reject_package{{ $data->id }}">
                                                        Reject
                                                    </a>
                                                @endif
                                                @if ($data->status == 1)
                                                    <a href="{{ route('admin.boost-package-inprocess', $data->id) }}" onclick="return confirm(' You want to inprocess?');" class="btn btn-sm btn-info mb-1">Inprocess</a>
                                                    <a href="{{ route('admin.boost-package-complete', $data->id) }}" onclick="return confirm(' You want to status change?');" class="btn btn-sm btn-success mb-1">Complete</a>
                                                @endif
                                                @if ($data->status == 2)
                                                    <a href="{{ route('admin.boost-package-complete', $data->id) }}" onclick="return confirm(' You want to status change?');" class="btn btn-sm btn-success mb-1">Complete</a>
                                                @endif
                                                <a href="{{ route('admin.boost-package-complete', $data->id) }}" onclick="return confirm(' You want to delete?');" class="btn btn-sm btn-danger">Delete</a>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="package_status_change{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{ route('admin.boost-package-reject', $data->id) }}" method="POST" enctype="multipart/form-data">
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

                                        <div class="modal fade" id="reject_package{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{ route('admin.boost-package-reject', $data->id) }}" method="POST" enctype="multipart/form-data">
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
