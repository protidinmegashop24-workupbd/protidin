@extends('backend.layouts.master')

@section('title')
    Deposit Account
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
                    <h1 class="m-0 text-dark">Deposit Account</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">All Deposit Account</li>
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
                            <h3 class="card-title">All Deposit Account</h3>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Icon</th>
                                        <th>Name</th>
                                        <th>Account</th>
                                        <th>Guidline</th>
                                        <th>Status</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datas as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td><img src="{{ URL::to($data->image) }}" width="60" alt=""></td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->account_no }}</td>
                                            <td>{{ $data->guideline }}</td>
                                            <td>
                                                @if($data->status == 1)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="javascript:;" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit_{{ $data->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="edit_{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{ route('admin.deposit-account.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="form-group col-lg-12 col-md-12 col-12">
                                                            <label for="name">Name</label>
                                                            <input type="text" class="form-control" id="name" name="name" value="{{ $data->name }}" required placeholder="Name">
                                                        </div>

                                                        <div class="form-group col-lg-12 col-md-12 col-12">
                                                            <label for="account_no">Account No</label>
                                                            <input type="text" class="form-control" id="account_no" name="account_no" value="{{ $data->account_no }}" required placeholder="Account No">
                                                        </div>

                                                        <div class="form-group col-lg-12 col-md-12 col-12">
                                                            <label for="guideline">Guidline</label>
                                                            <textarea class="form-control" name="guideline" id="guideline" cols="30" rows="3">{{ $data->guideline }}</textarea>
                                                        </div>

                                                        <div class="form-group col-lg-12 col-md-12 col-12">
                                                            <label for="image">Icon</label>
                                                            <input type="file" class="form-control" id="image" name="image">
                                                            <img src="{{ URL::to($data->image) }}" width="60" alt="">
                                                        </div>

                                                        <div class="form-group col-lg-12 col-md-12 col-12">
                                                            <label for="status">Status</label>
                                                            <select class="form-control" name="status" id="status">
                                                                <option value="1" @if($data->status == 1) selected @endif>Active</option>
                                                                <option value="0" @if($data->status == 0) selected @endif>Inactive</option>
                                                            </select>
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
                    <form action="{{ route('admin.deposit-account.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">New Account</h3>
                            </div>
                            <div class="card-body row">
                                <div class="form-group col-lg-12 col-md-12 col-12">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required placeholder="Name">
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-12">
                                    <label for="account_no">Account No</label>
                                    <input type="text" class="form-control" id="account_no" name="account_no" required placeholder="Account No">
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-12">
                                    <label for="guideline">Guidline</label>
                                    <textarea class="form-control" name="guideline" id="guideline" cols="30" rows="3"></textarea>
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-12">
                                    <label for="image">Icon</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-12">
                                    <label for="status">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
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

        function deleteData(id) {
            if (confirm("Are you sure?")) {
                document.getElementById('delete-form-'+id).submit();
            }
            return false;
        }
    </script>
@endsection
