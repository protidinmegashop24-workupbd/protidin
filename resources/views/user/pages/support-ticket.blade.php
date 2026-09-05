@extends('user.layouts.master')
@section('css')
    <style>
        .ads-rate-area{
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            display: flex;
            justify-content: space-between;
        }
        .ads-rate-area-active{
            border: 1px solid #31bd21 !important;
        }
    </style>
@endsection
@section('user-content')

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-6 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">New Ticket</h4>
            </div>
            <div class="card-body">
                <form id="ad-form" action="{{ route('user.support-ticket-store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="">
                        <div class="form-group">
                            <label for="title" class="form-label">Name <span class="text-red">*</span></label>
                            <input class="form-control" type="text" name="name" value="{{Auth::user()->name}}" readonly placeholder="Name" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email <span class="text-red">*</span></label>
                            <input class="form-control" type="text" name="email" value="{{Auth::user()->email}}" readonly placeholder="email">
                        </div>
                        <div class="form-group">
                            <label for="subject" class="form-label">Subject <span class="text-red">*</span></label>
                            <input class="form-control" type="text" name="subject" required placeholder="subject">
                        </div>
                        <div class="form-group">
                            <label for="priority" class="form-label">Priority <span class="text-red">*</span></label>
                            <select name="priority" class=" form-select form-control form--control" required="" id="priority">
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message" class="form-label">Message <span class="text-red">*</span></label>
                            <textarea name="message" id="inputMessage" rows="6" class="form-control form--control" required=""></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image" class="form-label">Ad Banner (500 X 250) <button type="button" class="btn btn-sm btn-info" onclick="addNewFile()">Add New</button></label>
                            <div id="ticket-file-area">
                                <input class="form-control" type="file" name="file[]">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary mt-4 mb-0" id="ad_submit_btn" onclick="adSubmit()">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
    <script>
        function adSubmit(){
            $('#ad-form').submit();
            $('#ad_submit_btn').prop('disabled', true);
        }
        
        function addNewFile(){
            $('#ticket-file-area').append('<div class="input-group mt-2"><input class="form-control" type="file" name="file[]"><div class="input-group-prepend"><span class="input-group-text c-pointer" id="basic-addon1">X</span></div></div>');
        }
    </script>
@endsection
