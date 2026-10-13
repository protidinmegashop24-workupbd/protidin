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
          <h1 class="m-0 text-dark">Spin Setting</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Color Setup</li>
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
                  <div class="col-10"><h3 class="card-title">System Color Setup</h3></div>
                  <div class="col-2"></div>
              </div>
            </div>
            <div class="card-body">
              
                <form action="{{ route('admin.spin-setting.update',$data->id) }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Part One BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="part_one_bg" class="form-control" value="{{$data->part_one_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Part One Mark:</label>
                            <div class="form-group">
                                <input type="number" name="part_one_mark" class="form-control" step="0.0001" value="{{$data->part_one_mark}}">
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Part Two BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="part_two_bg" class="form-control" value="{{$data->part_two_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Part Two Mark:</label>
                            <div class="form-group">
                                <input type="number" name="part_two_mark" class="form-control" step="0.0001" value="{{$data->part_two_mark}}">
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Part Three BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="part_three_bg" class="form-control" value="{{$data->part_three_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Part Three Mark:</label>
                            <div class="form-group">
                                <input type="number" name="part_three_mark" class="form-control" step="0.0001" value="{{$data->part_three_mark}}">
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Part Four BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="part_four_bg" class="form-control" value="{{$data->part_four_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Part Four Mark:</label>
                            <div class="form-group">
                                <input type="number" name="part_four_mark" class="form-control" step="0.0001" value="{{$data->part_four_mark}}">
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Part Five BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="part_five_bg" class="form-control" value="{{$data->part_five_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Part Five Mark:</label>
                            <div class="form-group">
                                <input type="number" name="part_five_mark" class="form-control" step="0.0001" value="{{$data->part_five_mark}}">
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Part Six BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="part_six_bg" class="form-control" value="{{$data->part_six_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Part Six Mark:</label>
                            <div class="form-group">
                                <input type="number" name="part_six_mark" class="form-control" step="0.0001" value="{{$data->part_six_mark}}">
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Part Seven BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="part_seven_bg" class="form-control" value="{{$data->part_seven_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Part Seven Mark:</label>
                            <div class="form-group">
                                <input type="number" name="part_seven_mark" class="form-control" step="0.0001" value="{{$data->part_seven_mark}}">
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Daily Maximum Spin :</label>
                            <div class="form-group">
                                <input type="number" name="daily_spin" class="form-control" step="0.0001" value="{{$data->daily_spin}}">
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label for="fee">Status</label>
                            <select class="form-control" name="status">
                                <option value="1" @if($data->status == 1) selected @endif>Active</option>
                                <option value="0" @if($data->status == 0) selected @endif>Inactive</option>
                            </select>
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
        //Colorpicker
        $('.my-colorpicker1').colorpicker()
        //color picker with addon
        $('.my-colorpicker2').colorpicker()
            
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
