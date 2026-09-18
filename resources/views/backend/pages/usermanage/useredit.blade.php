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
    </style>
@endsection

@section('back-content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">User Edit</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('admin.user') }}">All Users</a></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
        <div class="card card-success">
            <div class="card-header">
              <div class="row">
                  <div class="col-10"><h3 class="card-title">All Users</h3></div>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form class="row" action="{{ route('admin.user.update',$user->id) }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group col-lg-6 col-md-6 col-6">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required value="{{ $user->name }}" placeholder="Enter User Name">
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-6">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required value="{{ $user->username }}" placeholder="Enter Username">
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required value="{{ $user->email }}" placeholder="Enter Role email">
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-6">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="first_mobile" name="phone" required value="{{ $user->phone }}" placeholder="Phone number must be start  01/+8801/8801 ">
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-6">
                        <label for="role_id">Roles</label>
                        <select class="form-control select2" name="role_id" style="width: 100%;">
                            <option selected="selected" value="{{ $user->role_id }}">
                                @php
                                    $role = DB::table('roles')->where('id', $user->role_id)->first();
                                @endphp
                                {{ $role->name }}
                            </option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-12">
                        <label for="exampleInputFile">Image</label>
                        <div class="input-group">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input upload" id="image" name="image" type="file" accept="image/*" required onchange="readURL(this);">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                          </div>
                          <div class="input-group-append">
                            <span class="input-group-text" id="">Upload</span>
                          </div>
                        </div><br>
                        <div class="row">
                            <div class="col-6">
                                <div class="input-group">
                                    <img src="{{ URL::to($user->image) }}" class="thumb-lg img-circle img-thumbnail" alt="{{ $user->name }}" name="old_photo" height="100px" width="100px">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="newEmployeeUploadImg"><img id="photo" src="#" /></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div><!-- /.container-fluid -->
    @include('backend.pages.usermanage.partials.usercreatemodal')
  </section>
  <!-- /.content -->
@endsection

@section('js')
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
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

    </script>
@endsection
