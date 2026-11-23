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
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <style>
        .new-user{
            float: right;
        }
        .btn-text-color{
            color: #000 !important;
        }
    </style>
@endsection

@section('back-content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Refer Info Update</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Refer Info Update</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
        <div class="card card-success">
            <div class="card-header">
              <div class="row">
                  <div class="col-10"><h3 class="card-title">Refer Info Update</h3></div>
                  <div class="col-2"></div>
              </div>
            </div>
            <div class="card-body">
              
                <form action="{{ route('admin.refer-info.update',$abouts->id) }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
        
                        <div class="form-group col-lg-12 col-md-12 col-12">
                            <label for="refer_title">Title</label>
                            <input type="text" class="form-control" id="refer_title" name="refer_title" value="{{$abouts->refer_title}}" placeholder="Title">
                        </div>
    
                        <div class="form-group col-lg-12 col-md-12 col-12">
                            <label for="refer_details">Details</label>
                            <textarea class="textarea" name="refer_details" placeholder="Enter details." style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"> {!! $abouts->refer_details !!}</textarea>
                        </div>
                        
                        <div class="form-group col-12">
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
  </section>
@endsection

@section('js')
<script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('backend/plugins/select2/js/select2.full.min.js') }}"></script>

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

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
