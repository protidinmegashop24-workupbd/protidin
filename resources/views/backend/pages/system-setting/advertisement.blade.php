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
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="10%">User</th>
                                    <th width="15%">Banner</th>
                                    <th>Title</th>
                                    <th width="8%">Post Date</th>
                                    <th width="8%">Duration</th>
                                    <th width="8%">Cost</th>
                                    <th width="8%">Status</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $key => $data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ user_name($data->user_id) }}</td>
                                    <td><img src="{{ URL::to($data->image) }}" width="120" alt=""></td>
                                    <td>{{ $data->title }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y g:i A')}}</td>
                                    <td>{{ $data->duration }} Days</td>
                                    <td>{{ $data->cost }}$</td>
                                    <td>
                                        @if ($data->exp_date < date('Y-m-d'))
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            @if($data->approval == 1)
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($data->approval == 2)
                                                <span class="badge bg-danger">Reject</span>
                                            @else
                                                <span class="badge bg-danger">Pending</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($data->approval == 0)
                                        <a href="javascript:;" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit_{{ $data->id }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif

                                        <a href="javascript:;" class="btn btn-success btn-sm" data-toggle="modal" data-target="#exp_date_edit_{{ $data->id }}">
                                            <i class="fas fa-calendar"></i>
                                        </a>


 
                                        <button type="button" onclick="deleteData({{ $data->id }})" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        
                                        
                                        <form id="delete-form-{{ $data->id }}" action="{{ route('admin.advertisement.delete', $data->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </td>
                                </tr>
                                <div class="modal fade" id="edit_{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="{{ route('admin.advertisement.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Approval Status Change</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="form-group col-lg-12 col-md-12 col-12">
                                                    <label for="status">Status</label>
                                                    <select class="form-control" name="approval" id="approval">
                                                        <option value="1" @if($data->approval == 1) selected @endif>Approved</option>
                                                        <option value="0" @if($data->approval == 0) selected @endif>Pending</option>
                                                        <option value="2" @if($data->approval == 2) selected @endif>Reject</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-12 col-md-12 col-12">
                                                    <label for="reason">Reason</label>
                                                    <textarea class="form-control" name="reason" id="reason" cols="30" rows="3">{{ $data->reason }}</textarea>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal fade" id="exp_date_edit_{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="{{ route('admin.advertisement-exp-dade.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Update Expire Date</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body row">
                                                    <div class="form-group col-lg-12 col-md-12 col-12">
                                                        <label for="sxp_date">Expire Date</label>
                                                        <input type="date" class="form-control" name="exp_date" value="{{ $data->exp_date }}">
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
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
            "responsive": true
            , "autoWidth": false
        , });
        $('#example2').DataTable({
            "paging": true
            , "lengthChange": false
            , "searching": false
            , "ordering": true
            , "info": true
            , "autoWidth": false
            , "responsive": true
        , });
    });

    function deleteData(id) {
        if (confirm("Are you sure?")) {
            document.getElementById('delete-form-' + id).submit();
        }
        return false;
    }

</script>
@endsection
