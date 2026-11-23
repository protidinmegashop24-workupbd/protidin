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
            <div class="card  card-success"">
                <div class="card-header">
                    <h3 class="card-title">Update Frofile</h3>
                </div>
                <div class="card-body">
                <div class="tab-content">
                    <div class="active tab-pane" id="settings">
                        <form class="form-horizontal" action="{{ route('admin.profile.update',$profile_info->id) }}" method="POST"  novalidate="novalidate" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $profile_info->name }}" placeholder="Enter Your Name">
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback" style="display: block !important;">
                                            Name is erquired.
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $profile_info->email }}" placeholder="Enter Your Email">
                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback" style="display: block !important;">
                                            Email is erquired.
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputName2" class="col-sm-2 col-form-label">Phone</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $profile_info->phone }}" placeholder="Enter Your Phone Number">
                                    @if ($errors->has('phone'))
                                        <div class="invalid-feedback" style="display: block !important;">
                                            Mobile is erquired.
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                            <label for="inputExperience" class="col-sm-2 col-form-label">Present Address</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="present_address" name="present_address" placeholder="Persent Address">{{ $profile_info->present_address }}</textarea>
                            </div>
                            </div>
                            <div class="form-group row">
                            <label for="inputExperience" class="col-sm-2 col-form-label">Permanent Address</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="permanent_address" name="permanent_address" placeholder="Permanent Address">{{ $profile_info->permanent_address }}</textarea>
                            </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputSkills" class="col-sm-2 col-form-label">Username</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="username" name="username" value="{{ $profile_info->username }}" placeholder="Enter Your Username">
                                    @if ($errors->has('username'))
                                        <div class="invalid-feedback" style="display: block !important;">
                                            Username is erquired.
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                            <label for="inputSkills" class="col-sm-2 col-form-label">Old Image</label>
                            <div class="col-sm-10">
                                <img src="{{ URL::to($profile_info->image) }}" class="thumb-lg img-circle img-thumbnail" alt="{{ $profile_info->name }}" name="old_photo" height="100px" width="100px">
                            </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputSkills" class="col-sm-2 col-form-label">Image</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input upload" id="image" name="image" type="file" accept="image/*" required onchange="readURL(this);">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="">Upload</span>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="newEmployeeUploadImg"><img id="photo" src="#" /></div>
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
