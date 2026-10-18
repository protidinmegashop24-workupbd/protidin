@extends('backend.layouts.master')

@section('title')
    Deposit Bonus
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
    </style>
@endsection

@section('back-content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Deposit Bonus</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">All Bonus</li>
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
                    <div class="col-10"><h3 class="card-title">All Bonus</h3></div>
                    <div class="col-2">
                        <button type="button" class="btn btn-default btn-sm pull-right new-user" data-toggle="modal" data-target="#modal-lg"><i class="fas fa-plus"></i> New Bonus</button>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th width="2%">#SL</th>
                    <th width="20%">Deposit Amount</th>
                    <th width="20%">Bonus</th>
                    <th>Type</th>
                    <th width="18%">Action</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $key=>$data)
                        <tr>
                            <td>{{ $loop->index+1 }}</td>
                            <td>{{ $data->deposit_amount }}$</td>
                            <td>{{ $data->bonus }}$</td>
                            <td>
                                @if($data->type == 0)
                                    Manual Payment
                                @else
                                    Auto Payment
                                @endif
                            </td>
                            <td>
                                <a href="" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit_{{$data->id}}"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('admin.auto-deposit-bonus.delete',$data->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        <div class="modal fade" id="edit_{{$data->id}}">
                            <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                <div class="modal-header bg-success">
                                  <h4 class="modal-title"><i class="fas fa-plus"></i> Update Deposit Bonus</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('admin.auto-deposit-bonus.update',$data->id) }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="$data">Type</label>
                                            <select class="form-control" name="type" required>
                                                <option value="0" @if($data->type == 0) selected @endif>Manual Payment</option>
                                                <option value="1" @if($data->type == 1) selected @endif>Auto Payment</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="deposit_amount">Deposit Amount</label>
                                            <input type="number" class="form-control" name="deposit_amount" required value="{{$data->deposit_amount}}" min="0">
                                        </div>
                        
                                        <div class="form-group">
                                            <label for="bonus">Bonus</label>
                                            <input type="number" class="form-control" name="bonus" required value="{{$data->bonus}}" min="0">
                                        </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
                                </div>
                                </form>
                              </div>
                            </div>
                        </div>

                    @endforeach
                </tbody>
              </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header bg-success">
              <h4 class="modal-title"><i class="fas fa-plus"></i> New Deposit Bonus</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.auto-deposit-bonus.store') }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="$data">Type</label>
                        <select class="form-control" name="type" required>
                            <option value="0" selected>Manual Payment</option>
                            <option value="1">Auto Payment</option>
                        </select>
                    </div>
    
                    <div class="form-group">
                        <label for="deposit_amount">Deposit Amount</label>
                        <input type="number" class="form-control" name="deposit_amount" required value="0" min="0">
                    </div>
    
                    <div class="form-group">
                        <label for="bonus">Bonus</label>
                        <input type="number" class="form-control" name="bonus" required value="0" min="0">
                    </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
            </div>
            </form>
          </div>
        </div>
    </div>

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
            // $('.textarea').summernote()
        })

        // datepicker
        $( function() {
            $( "#event_date" ).datepicker();
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
