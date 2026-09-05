@extends('backend.layouts.master')

@section('title')
    ষ্ট্যাম্প বিক্রয়
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    {{-- select2 --}}
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.css') }}">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend/dist/css/datepicker.css') }}">

    <style>
        .new-user{
            float: right;
        }
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #aaa;
            border-radius: 4px;
            height: 40px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 26px;
            position: absolute;
            top: 6px;
            right: 1px;
            width: 20px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            color: #000;
        }
    </style>
@endsection

@section('back-content')
@include('backend.layouts.partials.eng-to-ban')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">ষ্ট্যাম্প বিক্রয়</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">হোম</a></li>
            <li class="breadcrumb-item active">বিক্রয় সমূহ</li>
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
                    <div class="col-10"><h3 class="card-title">বিক্রয় সমূহ</h3></div>
                    <div class="col-2">
                        <button type="button" class="btn btn-default btn-sm pull-right new-user" data-toggle="modal" data-target="#modal-lg"><i class="fas fa-plus"></i> নতুন বিক্রয়</button>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th width="8%">ক্রমিক নং</th>
                    <th width="10%">চালান</th>
                    <th width="8%">মূল্যায়ন</th>
                    <th width="7%">পরিমান</th>
                    <th width="25%">স্ট্যাম্প ক্রেতার নাম, পিতার নাম ও ঠিকানা</th>
                    {{-- <th width="15%">নাম</th>
                    <th width="15%">পিতা</th>
                    <th width="10%">গ্রাম</th>
                    <th width="8%">থানা</th>
                    <th width="8%">জেলা</th> --}}
                    <th width="8%">তারিখ</th>
                    <th width="15%">একশন</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $key=>$sale)
                        <tr>
                            <td>{{ Converter::en2bn($sale->serial) }}</td>
                            @php
                                $cat_slug = explode(",",$sale->cat_slug);
                                $quantity = explode(",",$sale->quantity);
                            @endphp
                            <td>
                                @foreach($cat_slug as $key=>$cat_slug)
                                    @if (array_key_exists($key, $quantity))
                                        @php
                                            $calan = DB::table('stamps')->where('cat_slug', $cat_slug)->first();
                                        @endphp
                                        {{ Converter::en2bn($calan->calan) }} <br>
                                    @endif
                                @endforeach
                                {{-- {{ Converter::en2bn($sale->invoice) }} --}}
                            </td>
                            <td>
                                @php
                                    $cat_slug = explode(",",$sale->cat_slug);
                                    $quantity = explode(",",$sale->quantity);
                                @endphp
                                @foreach($cat_slug as $key=>$cat_slug)
                                    @if (array_key_exists($key, $quantity))
                                        @php
                                            $category = DB::table('categories')->where('slug', $cat_slug)->first();
                                        @endphp
                                        {{ Converter::en2bn($category->name) }} টাকা <br>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @php
                                    $cat_slug = explode(",",$sale->cat_slug);
                                    $quantity = explode(",",$sale->quantity);
                                @endphp
                                @foreach($cat_slug as $key=>$cat_slug)
                                    @if (array_key_exists($key, $quantity))
                                        {{ Converter::en2bn($quantity[$key]) }} টা <br>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                নামঃ {{ $sale->name }}, <br>
                                পিতাঃ {{ $sale->father_name }}, <br>
                                গ্রামঃ {{ $sale->village }}, থানাঃ {{ $sale->thana }}, জেলাঃ {{ $sale->district }}
                            </td>
                            {{-- <td>{{ $sale->name }}</td>
                            <td>{{ $sale->father_name }}</td>
                            <td>{{ $sale->village }}</td>
                            <td>{{ $sale->thana }}</td>
                            <td>{{ $sale->district }}</td> --}}
                            <td>{{ Converter::en2bn(\Carbon\Carbon::parse($sale->date)->format('d-m-Y')) }}</td>
                            <td>
                                {{-- <a href="" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit_{{$key}}"><i class="fas fa-edit"></i></a> --}}
                                <a href="{{ route('admin.stamp-sale.print',$sale->id) }}" target="_blank" class="btn btn-success btn-sm"><i class="fas fa-print"></i></a>
                                {{-- <a href="{{ route('admin.stamp-sale.delete',$sale->id) }}" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a> --}}
                            </td>
                        </tr>

                        {{-- @include('backend.pages.stamp.partials.edit-stamp') --}}

                    @endforeach
                </tbody>
              </table>
            </div>
        </div>
    </div><!-- /.container-fluid -->
    @include('backend.pages.stamp.partials.sale-stamp')
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
    <!-- Summernote -->
    <script src="{{ asset('backend/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- bootstrap color picker -->
    <script src="{{ asset('backend/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="{{ asset('backend/dist/js/datepicker.min.js') }}"></script>

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

        // text editor----------------------------
        $(function () {
            // Summernote
            $('.textarea').summernote()
        })

        // datepicker
        $( function() {
            $( "#buy_date" ).datepicker();
        } );

        //Timepicker
        $('#start_timepicker').datetimepicker({
            format: 'LT'
        })

        $('#end_timepicker').datetimepicker({
            format: 'LT'
        })

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
