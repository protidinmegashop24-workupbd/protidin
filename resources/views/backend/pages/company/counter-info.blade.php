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
          <h1 class="m-0 text-dark">Counter Info</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Counter Info</li>
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
                  <div class="col-10"><h3 class="card-title">Counter Info</h3></div>
                  <div class="col-2"></div>
              </div>
            </div>
            <div class="card-body">
              
                <form action="{{ route('admin.counter-info.update',$abouts->id) }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
        
                        <div class="form-group col-lg-3 col-md-3 col-12">
                            <label for="total_job_title">Total Job Text</label>
                            <textarea class="textarea" name="total_job_title" placeholder="Enter text." style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{!! $abouts->total_job_title !!}</textarea>
                        </div>
        
                        <div class="form-group col-lg-2 col-md-2 col-12">
                            <label for="total_job">Total Job</label>
                            <input type="number" name="total_job" class="form-control" value="{{$abouts->total_job}}">
                        </div>
        
                        <div class="form-group col-lg-2 col-md-2 col-12">
                            <label for="total_job_status">Total Job Status</label>
                            <select class="form-control" name="total_job_status" id="total_job_status">
                                <option value="0" @if($abouts->total_job_status == 0) selected @endif>Inactive</option>
                                <option value="1" @if($abouts->total_job_status == 1) selected @endif>Active</option>
                            </select>
                        </div>
        
                        <div class="form-group col-lg-2 col-md-2 col-12">
                            <label for="total_job_manual_show">Show</label>
                            <select class="form-control" name="total_job_manual_show" id="total_job_manual_show">
                                <option value="0" @if($abouts->total_job_manual_show == 0) selected @endif>Live</option>
                                <option value="1" @if($abouts->total_job_manual_show == 1) selected @endif>Manual</option>
                            </select>
                        </div>
                        
                        <div class="form-group col-lg-3 col-md-3 col-12">
                            <label for="total_job_icon">Total Job Icon</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input upload" id="total_job_icon" name="total_job_icon" type="file" accept="image/*">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                            </div>
                            <img src="{{ URL::to($abouts->total_job_icon) }}" class="thumb-lg img-thumbnail mt-1" alt="Feature Image" name="old_photo" height="100px" width="100px">
                        </div>
        
                        <div class="form-group col-lg-3 col-md-3 col-12">
                            <label for="total_user_title">Total User Text</label>
                            <textarea class="textarea" name="total_user_title" placeholder="Enter text." style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{!! $abouts->total_user_title !!}</textarea>
                        </div>
        
                        <div class="form-group col-lg-2 col-md-2 col-12">
                            <label for="total_user">Total User</label>
                            <input type="number" name="total_user" class="form-control" value="{{$abouts->total_user}}">
                        </div>
        
                        <div class="form-group col-lg-2 col-md-2 col-12">
                            <label for="total_user_status">Total User Status</label>
                            <select class="form-control" name="total_user_status" id="total_user_status">
                                <option value="0" @if($abouts->total_user_status == 0) selected @endif>Inactive</option>
                                <option value="1" @if($abouts->total_user_status == 1) selected @endif>Active</option>
                            </select>
                        </div>
        
                        <div class="form-group col-lg-2 col-md-2 col-12">
                            <label for="total_user_manual_show">Show</label>
                            <select class="form-control" name="total_user_manual_show" id="total_user_manual_show">
                                <option value="0" @if($abouts->total_user_manual_show == 0) selected @endif>Live</option>
                                <option value="1" @if($abouts->total_user_manual_show == 1) selected @endif>Manual</option>
                            </select>
                        </div>
                        
                        <div class="form-group col-lg-3 col-md-3 col-12">
                            <label for="total_user_icon">Total User Icon</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input upload" id="total_user_icon" name="total_user_icon" type="file" accept="image/*">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                            </div>
                            <img src="{{ URL::to($abouts->total_user_icon) }}" class="thumb-lg img-thumbnail mt-1" alt="Feature Image" name="old_photo" height="100px" width="100px">
                        </div>
        
                        <div class="form-group col-lg-3 col-md-3 col-12">
                            <label for="totle_work_done_title">Task Done Text</label>
                            <textarea class="textarea" name="totle_work_done_title" placeholder="Enter text." style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{!! $abouts->totle_work_done_title !!}</textarea>
                        </div>
        
                        <div class="form-group col-lg-2 col-md-2 col-12">
                            <label for="totle_work_done">Total Task Done</label>
                            <input type="number" name="totle_work_done" class="form-control" value="{{$abouts->totle_work_done}}">
                        </div>
        
                        <div class="form-group col-lg-2 col-md-2 col-12">
                            <label for="totle_work_done_status">Task Done Status</label>
                            <select class="form-control" name="totle_work_done_status" id="totle_work_done_status">
                                <option value="0" @if($abouts->totle_work_done_status == 0) selected @endif>Inactive</option>
                                <option value="1" @if($abouts->totle_work_done_status == 1) selected @endif>Active</option>
                            </select>
                        </div>
        
                        <div class="form-group col-lg-2 col-md-2 col-12">
                            <label for="totle_work_done_manual_show">Show</label>
                            <select class="form-control" name="totle_work_done_manual_show" id="totle_work_done_manual_show">
                                <option value="0" @if($abouts->totle_work_done_manual_show == 0) selected @endif>Live</option>
                                <option value="1" @if($abouts->totle_work_done_manual_show == 1) selected @endif>Manual</option>
                            </select>
                        </div>
                        
                        <div class="form-group col-lg-3 col-md-3 col-12">
                            <label for="totle_work_done_icon">Task Done Icon</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input upload" id="totle_work_done_icon" name="totle_work_done_icon" type="file" accept="image/*">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                            </div>
                            <img src="{{ URL::to($abouts->totle_work_done_icon) }}" class="thumb-lg img-thumbnail mt-1" alt="Feature Image" name="old_photo" height="100px" width="100px">
                        </div>
        
                        <div class="form-group col-lg-3 col-md-3 col-12">
                            <label for="total_withdraw_title">Paid Text</label>
                            <textarea class="textarea" name="total_withdraw_title" placeholder="Enter text." style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{!! $abouts->total_withdraw_title !!}</textarea>
                        </div>
        
                        <div class="form-group col-lg-2 col-md-2 col-12">
                            <label for="total_withdraw">Total Paid</label>
                            <input type="number" name="total_withdraw" class="form-control" value="{{$abouts->total_withdraw}}">
                        </div>
        
                        <div class="form-group col-lg-2 col-md-2 col-12">
                            <label for="total_withdraw_status">Paid Task Status</label>
                            <select class="form-control" name="total_withdraw_status" id="total_withdraw_status">
                                <option value="0" @if($abouts->total_withdraw_status == 0) selected @endif>Inactive</option>
                                <option value="1" @if($abouts->total_withdraw_status == 1) selected @endif>Active</option>
                            </select>
                        </div>
        
                        <div class="form-group col-lg-2 col-md-2 col-12">
                            <label for="paid_tast_manual_show">Show</label>
                            <select class="form-control" name="paid_tast_manual_show" id="paid_tast_manual_show">
                                <option value="0" @if($abouts->paid_tast_manual_show == 0) selected @endif>Live</option>
                                <option value="1" @if($abouts->paid_tast_manual_show == 1) selected @endif>Manual</option>
                            </select>
                        </div>
                        
                        <div class="form-group col-lg-3 col-md-3 col-12">
                            <label for="total_withdraw_icon">Paid Icon</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input upload" id="total_withdraw_icon" name="total_withdraw_icon" type="file" accept="image/*">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                            </div>
                            <img src="{{ URL::to($abouts->total_withdraw_icon) }}" class="thumb-lg img-thumbnail mt-1" alt="Feature Image" name="old_photo" height="100px" width="100px">
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
