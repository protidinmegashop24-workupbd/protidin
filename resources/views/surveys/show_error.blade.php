@extends('user.layouts.master')

@section('user-content')
<div class="container mt-4">
  <div class="alert alert-warning" style="font-weight:900;">
    {{ $message ?? 'Something went wrong.' }}
  </div>

  <a class="btn btn-dark" href="{{ route('surveys.index') }}">Back to list</a>
</div>
@endsection