@extends('user.layouts.master')
@section('css')
    <link href="{{ asset('frontend/user/assets/css/custom.css') }}" rel="stylesheet" />
@endsection
@section('user-content')
    <div class="row justify-content-center pt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body text-center">

                    <h4 class="text-success text-center">Successfully Submited Your Job.</h4>
                    
                    <a href="{{ route('user.job') }}" class="btn btn-primary btn-lg mt-4 mb-0">My Jobs</a>

                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script src="{{ asset('frontend/user/assets/js/my_custom.js') }}"></script>

@endsection
