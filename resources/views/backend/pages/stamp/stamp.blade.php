@extends('backend.layouts.master')

@section('title')
    ষ্ট্যাম্প তালিকা
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
        svg {
            height: 27px !important;
        }
        .justify-between{
            /* display: none; */
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
            <h1 class="m-0 text-dark">ষ্ট্যাম্প</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">হোম</a></li>
                <li class="breadcrumb-item active">ষ্ট্যাম্প সমূহ</li>
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
                        <div class="col-10"><h3 class="card-title">ষ্ট্যাম্প সমূহ</h3></div>
                        <div class="col-2">
                            <button type="button" class="btn btn-default btn-sm pull-right new-user" data-toggle="modal" data-target="#modal-lg"><i class="fas fa-plus"></i> নতুন যুক্ত</button>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="" class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="2%">#</th>
                                <th width="20%">চালান নং</th>
                                <th width="20%">সিরিয়াল</th>
                                <th width="15%">মূল্যায়ন</th>
                                <th width="20%">তারিখ</th>
                                <th width="18%">একশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stamps as $key=>$stamp)
                                <tr>
                                    <td>{{ Converter::en2bn($key+1) }}</td>
                                    <td>{{ Converter::en2bn($stamp->calan) }}</td>
                                    <td>{{ $stamp->prefix }}-{{ Converter::en2bn($stamp->serial) }}</td>
                                    <td>
                                        @php
                                            $category = DB::table('categories')->where('slug', $stamp->cat_slug)->first();
                                        @endphp
                                        {{ Converter::en2bn($category->name) }} টাকা
                                    </td>
                                    <td>{{ Converter::en2bn(\Carbon\Carbon::parse($stamp->date)->format('d-m-Y')) }}</td>
                                    <td>
                                        <a href="" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit_{{$key}}"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('admin.stamp.delete',$stamp->id) }}" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                @include('backend.pages.stamp.partials.edit-stamp')

                            @endforeach
                        </tbody>
                    </table>
                    {{$stamps->onEachSide(2)->links()}}
                </div>
            </div>
        </div><!-- /.container-fluid -->
        @include('backend.pages.stamp.partials.add-stamp')
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
            $( "#calan_date" ).datepicker();
        } );

        $( function() {
            $( "#receive_date" ).datepicker();
        } );

        // datepicker
        $( function() {
            $( "#calan_date_edit" ).datepicker();
        } );

        $( function() {
            $( "#receive_date_edit" ).datepicker();
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
