@extends('user.layouts.master')

@section('user-content')
<div class="container mt-4">
  <div class="alert alert-warning" style="font-weight:900;">
    {{ $message ?? 'Something went wrong.' }}
  </div>

  @if(!empty($gatewayUrl))
    <a class="btn btn-success" href="{{ $gatewayUrl }}" target="_blank">আবার চেষ্টা করুন (Start Survey)</a>
  @endif
  <a class="btn btn-dark" href="{{ route('surveys.index') }}">Back to list</a>
</div>
@endsection