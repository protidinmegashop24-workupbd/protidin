@extends('backend.layouts.master')

@section('title')
    Support Tickets
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <style>
        .border {
            border: 1px solid #dee2e6!important;
        }
        .border--primary {
            border-color: #4634ff !important;
        }
        .border-end {
            border-right: 1px solid #dee2e6!important;
        }
        .c-pointer{
            cursor: pointer;
        }
    </style>
@endsection

@section('back-content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Support Tickets</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">{{$title}}</h3>
                            @if($data->status != 2)
                                <a href="{{ route('admin.close-support-ticket',$data->id) }}" class="btn btn-danger btn-sm pull-right"><i class="fa fa-cross"></i> Close Ticket</a>
                            @endif
                        </div>
                        <div class="card-body">
                            @if($data->status != 2)
                                <div>
                                    <form action="{{ route('admin.support-ticket-replay.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input class="form-control" type="hidden" name="ticket_id" value="{{$data->id}}">
                                        <div class="form-group">
                                            <label for="reason">Subject</label>
                                            <input class="form-control" type="text" readonly value="{{$data->subject}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="reason">Message</label>
                                            <textarea class="form-control" name="message" id="message" cols="30" rows="3" required></textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="image" class="form-label">Ad Banner (500 X 250) <button type="button" class="btn btn-sm btn-info" onclick="addNewFile()">Add New</button></label>
                                            <div id="ticket-file-area">
                                                <input class="form-control" type="file" name="file[]">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success btn-sm my-3"><i class="fa fa-save"></i> Replay</button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                            <h3>{{$data->subjet}}</h3>
                            @foreach($ticket_datas as $ticket_data)
                                <div class="row border border--primary mt-2">
                                    <div class="col-md-4 col-12 border-end">
                                        <h4>{{user_name($data->user_id)}}</h4>
                                        <h6>Id: {{user_code($data->user_id)}}</h6>
                                        <a href="{{route('admin.support-ticket-data.delete',$ticket_data->id)}}" class="btn btn-danger btn-sm mb-2"><i class="fa fa-cross"></i> Delete</a>
                                    </div>
                                    <div class="col-md-8 col-12">
                                        <h3><strong>Posted on {{ \Carbon\Carbon::parse($ticket_data->created_at)->format('d F, Y h:i A')}}</strong></h3>
                                        <p>
                                            {{$ticket_data->message}}
                                        </p>
                                        <div>
                                            @if($ticket_data->file)
                                                @php
                                                    $files = explode("|",$ticket_data->file);
                                                @endphp
                                                @foreach($files as $file)
                                                    <img src="{{URL::to($file)}}" width="100px" height="100px" class="c-pointer" onclick="viewImage('{{URL::to($file)}}')"/>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
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

    <script>
        $(function() {
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
        
        function addNewFile(){
            $('#ticket-file-area').append('<div class="input-group mt-2"><input class="form-control" type="file" name="file[]"><div class="input-group-prepend"><span class="input-group-text c-pointer" id="basic-addon1">X</span></div></div>');
        }

    </script>
@endsection
