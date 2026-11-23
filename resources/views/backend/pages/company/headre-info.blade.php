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
          <h1 class="m-0 text-dark">Header Info</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Header Info</li>
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
                  <div class="col-10"><h3 class="card-title">Header Info</h3></div>
                  <div class="col-2"></div>
              </div>
            </div>
            <div class="card-body">
              
                <form action="{{ route('admin.header-info.update',$abouts->id) }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
        
                        <div class="form-group col-lg-12 col-md-12 col-12">
                            <label for="slider_title">Header Text</label>
                            <textarea class="textarea" name="slider_title" placeholder="Enter details." style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{!! $abouts->slider_title !!}</textarea>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="form-group">
                                <label for="slider_image_one">Header Image One</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input upload" id="slider_image_one" name="slider_image_one" type="file" accept="image/*">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                </div>
                                <img src="{{ URL::to($abouts->slider_image_one) }}" class="thumb-lg img-thumbnail mt-1" alt="Feature Image" name="old_photo" height="100px" width="100px">
                            </div>
                            <div class="form-group">
                                <label for="slider_image_one_status">Header Image One Status</label>
                                <select class="form-control" name="slider_image_one_status" id="slider_image_one_status">
                                    <option value="0" @if($abouts->slider_image_one_status == 0) selected @endif>Inactive</option>
                                    <option value="1" @if($abouts->slider_image_one_status == 1) selected @endif>Active</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="form-group">
                                <label for="slider_image_two">Header Image Two</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input upload" id="slider_image_two" name="slider_image_two" type="file" accept="image/*">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                </div>
                                <img src="{{ URL::to($abouts->slider_image_two) }}" class="thumb-lg img-thumbnail mt-1" alt="Feature Image" name="old_photo" height="100px" width="100px">
                            </div>
                            <div class="form-group">
                                <label for="slider_image_two_status">Header Image Two Status</label>
                                <select class="form-control" name="slider_image_two_status" id="slider_image_two_status">
                                    <option value="0" @if($abouts->slider_image_two_status == 0) selected @endif>Inactive</option>
                                    <option value="1" @if($abouts->slider_image_two_status == 1) selected @endif>Active</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="form-group">
                                <label for="slider_image_three">Box One</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input upload" id="slider_image_three" name="slider_image_three" type="file" accept="image/*">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                </div>
                                <img src="{{ URL::to($abouts->slider_image_three) }}" class="thumb-lg img-thumbnail mt-1" alt="Feature Image" name="old_photo" height="100px" width="100px">
                            </div>
                            <div class="form-group">
                                <label for="slider_image_three_status">Box One Status</label>
                                <select class="form-control" name="slider_image_three_status" id="slider_image_three_status">
                                    <option value="0" @if($abouts->slider_image_three_status == 0) selected @endif>Inactive</option>
                                    <option value="1" @if($abouts->slider_image_three_status == 1) selected @endif>Active</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="form-group">
                                <label for="slider_image_four">Box Two</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input upload" id="slider_image_four" name="slider_image_four" type="file" accept="image/*">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                </div>
                                <img src="{{ URL::to($abouts->slider_image_four) }}" class="thumb-lg img-thumbnail mt-1" alt="Feature Image" name="old_photo" height="100px" width="100px">
                            </div>
                            <div class="form-group">
                                <label for="slider_image_four_status">Box Two Status</label>
                                <select class="form-control" name="slider_image_four_status" id="slider_image_four_status">
                                    <option value="0" @if($abouts->slider_image_four_status == 0) selected @endif>Inactive</option>
                                    <option value="1" @if($abouts->slider_image_four_status == 1) selected @endif>Active</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="form-group">
                                <label for="slider_image_five">Box Three</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input upload" id="slider_image_five" name="slider_image_five" type="file" accept="image/*">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                </div>
                                <img src="{{ URL::to($abouts->slider_image_five) }}" class="thumb-lg img-thumbnail mt-1" alt="Feature Image" name="old_photo" height="100px" width="100px">
                            </div>
                            <div class="form-group">
                                <label for="slider_image_five_status">Box Three Status</label>
                                <select class="form-control" name="slider_image_five_status" id="slider_image_five_status">
                                    <option value="0" @if($abouts->slider_image_five_status == 0) selected @endif>Inactive</option>
                                    <option value="1" @if($abouts->slider_image_five_status == 1) selected @endif>Active</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="form-group">
                                <label for="slider_image_six">Box Four</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input upload" id="slider_image_six" name="slider_image_six" type="file" accept="image/*">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                </div>
                                <img src="{{ URL::to($abouts->slider_image_six) }}" class="thumb-lg img-thumbnail mt-1" alt="Feature Image" name="old_photo" height="100px" width="100px">
                            </div>
                            <div class="form-group">
                                <label for="slider_image_six_status">Box Four Status</label>
                                <select class="form-control" name="slider_image_six_status" id="slider_image_six_status">
                                    <option value="0" @if($abouts->slider_image_six_status == 0) selected @endif>Inactive</option>
                                    <option value="1" @if($abouts->slider_image_six_status == 1) selected @endif>Active</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="form-group">
                                <label for="slider_image_seven">Box BG</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input upload" id="slider_image_seven" name="slider_image_seven" type="file" accept="image/*">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                </div>
                                <img src="{{ URL::to($abouts->slider_image_seven) }}" class="thumb-lg img-thumbnail mt-1" alt="Feature Image" name="old_photo" height="100px" width="100px">
                            </div>
                            <div class="form-group">
                                <label for="slider_image_seven_status">Bax BG Status</label>
                                <select class="form-control" name="slider_image_seven_status" id="slider_image_seven_status">
                                    <option value="0" @if($abouts->slider_image_seven_status == 0) selected @endif>Inactive</option>
                                    <option value="1" @if($abouts->slider_image_seven_status == 1) selected @endif>Active</option>
                                </select>
                            </div>
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
