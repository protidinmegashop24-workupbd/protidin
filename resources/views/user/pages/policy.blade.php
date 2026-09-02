@extends('user.layouts.master')
@section('css')
@endsection
@section('user-content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="page-title text-center">{{ $policy->title }}</h3>

                    <p>{!! $policy->details !!}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@endsection
