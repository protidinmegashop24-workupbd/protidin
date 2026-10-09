@extends('backend.layouts.master')

@section('title')
    {{ $website->title }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('back-content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Banner</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">All Banners</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-7 col-7">
          <!-- small box -->
          <div class="card card-success">
            <div class="card-header">
              <h3 class="card-title">All Banners</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#SL</th>
                  <th>Image</th>
                  <th>Text</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($sliders as $key=>$slider)
                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>
                                <img src="{{ URL::to($slider->image) }}" alt="Banner Image" style="height: 30px; width: 30px;">
                            </td>
                            <td>{{ $slider->text }}</td>
                            <td>
                                <a href="{{ route('admin.slider.delete',$slider->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
        </div>
        <!-- ./col -->
      </div>
        <div class="col-lg-5 col-5">
          <!-- small box -->
            <form action="{{ route('admin.slider.store') }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
            @csrf
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">New Banner</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="text">Banner Text</label>
                            <input type="text" class="form-control" id="text" name="text" placeholder="Enter Banner Text">
                        </div>

                        <div class="form-group">
                            <label for="link">Link</label>
                            <input type="text" class="form-control" id="link" name="link" placeholder="Enter Banner Link">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputFile">Image</label>
                            <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input upload" id="image" name="image" type="file" accept="image/*" onchange="readURL(this);">
                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text" id="">Upload</span>
                            </div>
                            </div>
                            @if ($errors->has('image'))
                                <div class="invalid-feedback" style="display: block !important;">
                                    Image is erquired.
                                </div>
                            @endif
                            <br>
                            <div class="newEmployeeUploadImg"><img id="photo" src="#" /></div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success"> <i class="fas fa-save"></i> Submit</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- ./col -->
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
