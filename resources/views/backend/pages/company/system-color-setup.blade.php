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
          <h1 class="m-0 text-dark">System Color Setup</h1>
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
              
                <form action="{{ route('admin.system-color-setup.update',$abouts->id) }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Menubar BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="menubar_color" class="form-control" value="{{$abouts->menubar_color}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Menubar Text Color:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="menubar_text_color" class="form-control" value="{{$abouts->menubar_text_color}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Menubar Overlay BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="menubar_overlay_color" class="form-control" value="{{$abouts->menubar_overlay_color}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Menubar Overlay Text Color:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="menubar_overlay_text_color" class="form-control" value="{{$abouts->menubar_overlay_text_color}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Header BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="header_bg" class="form-control" value="{{$abouts->header_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Header Text Color:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="header_text_color" class="form-control" value="{{$abouts->header_text_color}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Footer BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="footer_bg" class="form-control" value="{{$abouts->footer_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Footer Text Color:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="footer_text_color" class="form-control" value="{{$abouts->footer_text_color}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Button:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="button_color" class="form-control" value="{{$abouts->button_color}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Button Text Color:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="button_text_color" class="form-control" value="{{$abouts->button_text_color}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Button Hover:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="button_hover_color" class="form-control" value="{{$abouts->button_hover_color}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Button Hover Text Color:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="button_hover_text_color" class="form-control" value="{{$abouts->button_hover_text_color}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Headline BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="headline_bg_color" class="form-control" value="{{$abouts->headline_bg_color}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Headline Text Color:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="headline_text_color" class="form-control" value="{{$abouts->headline_text_color}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Service BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="service_bg" class="form-control" value="{{$abouts->service_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Service Text Color:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="service_text_color" class="form-control" value="{{$abouts->service_text_color}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Service Hover BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="service_hover_bg" class="form-control" value="{{$abouts->service_hover_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Service Hover Text Color:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="service_hover_text_color" class="form-control" value="{{$abouts->service_hover_text_color}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Refer Area BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="refer_area_bg" class="form-control" value="{{$abouts->refer_area_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Refer Area Text Color:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="refer_area_text_color" class="form-control" value="{{$abouts->refer_area_text_color}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Login & Register Title Color:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="login_register_title_color" class="form-control" value="{{$abouts->login_register_title_color}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Login & Register Content BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="login_register_content_bg" class="form-control" value="{{$abouts->login_register_content_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Login & Register Content Color:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="login_register_content_color" class="form-control" value="{{$abouts->login_register_content_color}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Login & Register Form Title BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="login_register_form_title_bg" class="form-control" value="{{$abouts->login_register_form_title_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Login & Register Form Title Color:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="login_register_form_title_color" class="form-control" value="{{$abouts->login_register_form_title_color}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Login & Register Form BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="login_register_form_bg" class="form-control" value="{{$abouts->login_register_form_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>User DB Sidebar BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="user_db_sidebar_bg" class="form-control" value="{{$abouts->user_db_sidebar_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>User DB Sidebar Text Color:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="user_db_sidebar_text" class="form-control" value="{{$abouts->user_db_sidebar_text}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>User DB Sidebar Menu Active Color:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="user_db_sidebar_menu_active" class="form-control" value="{{$abouts->user_db_sidebar_menu_active}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>User DB Navbar BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="user_db_navbar_bg" class="form-control" value="{{$abouts->user_db_navbar_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>User DB Navbar Text Color:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="user_db_navbar_text" class="form-control" value="{{$abouts->user_db_navbar_text}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>User Panel BG:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="user_db_bg" class="form-control" value="{{$abouts->user_db_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Job Create Point:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="job_create_point" class="form-control" value="{{$abouts->job_create_point}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Job Create Next Button:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="job_create_next" class="form-control" value="{{$abouts->job_create_next}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Job Create Back Button:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="job_create_back" class="form-control" value="{{$abouts->job_create_back}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Disable Button Color:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="button_disable" class="form-control" value="{{$abouts->button_disable}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>User Panel Logo Area:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="user_panel_logo_area" class="form-control" value="{{$abouts->user_panel_logo_area}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Deposite Balance Area:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="deposit_balance_bg" class="form-control" value="{{$abouts->deposit_balance_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>Earning Balance Area:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="earning_balance_bg" class="form-control" value="{{$abouts->earning_balance_bg}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                        
                        <!--<div class="col-lg-3 col-md-3 col-12 form-group">-->
                        <!--    <label>Paid Ads Area:</label>-->
                        <!--    <div class="input-group my-colorpicker2">-->
                        <!--        <input type="text" name="paid_area_ad_bg" class="form-control" value="{{$abouts->paid_area_ad_bg}}">-->
                        <!--        <div class="input-group-addon">-->
                        <!--            <i></i>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->
                        
                        <div class="col-lg-3 col-md-3 col-12 form-group">
                            <label>User Panel Main Area:</label>
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="user_panel_main_area" class="form-control" value="{{$abouts->user_panel_main_area}}">
                                <div class="input-group-addon">
                                    <i></i>
                                </div>
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
