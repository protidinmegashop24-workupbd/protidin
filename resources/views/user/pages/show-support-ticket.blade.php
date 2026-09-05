@extends('user.layouts.master')
@section('css')
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
        .pull-right{
            float: right;
        }
    </style>
@endsection
@section('user-content')
    <div class="card mt-2">
        <div class="card-header">
            <div class="card-title">
                Support Tickets
                @if($data->status != 2)
                    <a href="{{ route('user.support-ticket-close',$data->id) }}" class="btn btn-danger btn-sm pull-right"><i class="fa fa-cross"></i> Close Ticket</a>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if($data->status != 2)
                <div>
                    <form action="{{ route('user.support-ticket-replay.store') }}" method="POST" enctype="multipart/form-data">
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
                        <!--<h6>Id: {{user_code($data->user_id)}}</h6>-->
                        <!--<a href="{{route('user.support-ticket-data.delete',$ticket_data->id)}}" class="btn btn-danger btn-sm mb-2"><i class="fa fa-cross"></i> Delete</a>-->
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
@endsection
@section('js')
    <script>
        function addNewFile(){
            $('#ticket-file-area').append('<div class="input-group mt-2"><input class="form-control" type="file" name="file[]"><div class="input-group-prepend"><span class="input-group-text c-pointer" id="basic-addon1">X</span></div></div>');
        }
    </script>
@endsection
