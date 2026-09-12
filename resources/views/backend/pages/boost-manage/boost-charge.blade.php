@extends('backend.layouts.master')

@section('title')
    Boost Charge
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
                    <h1 class="m-0 text-dark">Charge</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">All Charge</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-7 col-7">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">All Charge</h3>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Duration</th>
                                        <th>Charge</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datas as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->duration }} Minutes</td>
                                            <td>${{ $data->charge }}</td>
                                            <td>
                                                <a href="javascript:;" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit_{{ $key }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.boost-charge.delete', $data->id) }}" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="edit_{{ $key }}" tabindex="-1" role="dialog"
                                            aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{ route('admin.boost-charge.update', $data->id) }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Update Charge</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="duration">Duration(Minute)</label>
                                                                <input type="number" class="form-control" id="duration" step="0.01" name="duration" required value="{{ $data->duration }}" min="0">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="charge">Charge($)</label>
                                                                <input type="number" class="form-control" id="charge" step="0.01" name="charge" required value="{{ $data->charge }}" min="0">
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
                <div class="col-lg-5 col-5">
                    <form action="{{ route('admin.boost-charge.store') }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                        @csrf
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">New Charge</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="duration">Duration(Minute)</label>
                                    <input type="number" class="form-control" id="duration" step="0.01" name="duration" required value="0" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="charge">Charge($)</label>
                                    <input type="number" class="form-control" id="charge" step="0.01" name="charge" required value="0" min="0">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </section>
@endsection

@section('js')
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
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

    </script>
@endsection
