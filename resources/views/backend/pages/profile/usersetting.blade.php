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
@endsection

@section('back-content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Profile</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">User Profile</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="card card-success card-outline">
            <div class="card-body box-profile">
              <div class="text-center">
                <img class="profile-user-img img-fluid img-circle"
                     src="{{ URL::to($profile_info->image) }}"
                     alt="User profile picture">
              </div>

              <h3 class="profile-username text-center">{{ $profile_info->name }}</h3>

              <p class="text-muted text-center">{{ $profile_info->role_name }}</p>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->

          <!-- About Me Box -->
          <div class="card card-success">
            <div class="card-header">
              <h3 class="card-title">About Me</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">


            <strong><i class="fas fa-id-badge mr-1"></i> User Name</strong>

            <p class="text-muted">{{ $profile_info->username }}</p>

            <hr>
            <strong><i class="fas fa-envelope mr-1"></i> E-mail</strong>

            <p class="text-muted">
            {{ $profile_info->email }}
            </p>

            <hr>

            <strong><i class="fas fa-phone mr-1"></i> Mobile</strong>

            <p class="text-muted">{{ $profile_info->phone }}</p>

            <hr>

            <strong><i class="fas fa-address-card mr-1"></i> Address</strong>

            <p class="text-muted">{{ $profile_info->present_address }}</p>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="card  card-success">
                <div class="card-header">
                    <h3 class="card-title">Change Password</h3>
                </div>
                <div class="card-body">
                <div class="tab-content">
                    <div class="active tab-pane" id="settings">
                        <form class="form-horizontal" action="{{ route('admin.setting.update',$profile_info->id) }}" method="POST"  novalidate="novalidate" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label for="old_password" class="col-sm-2 col-form-label">Old Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Enter Your Old Password">
                                    @if ($errors->has('old_password'))
                                        <div class="invalid-feedback" style="display: block !important;">
                                            Old password is erquired.
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-sm-2 col-form-label">New Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Your New Password">
                                    @if ($errors->has('password'))
                                        <div class="invalid-feedback" style="display: block !important;">
                                           New password is erquired.
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Confirm Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Enter Confirm Password">
                                    @if ($errors->has('password_confirmation'))
                                        <div class="invalid-feedback" style="display: block !important;">
                                            Confirm password is erquired.
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                            <div class="offset-sm-2 col-sm-10">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
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
