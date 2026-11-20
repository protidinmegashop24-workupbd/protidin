@extends('frontend.layouts.master')

@section('front-content')
<div class="container py-5">

<h2>Marketplace Services</h2>

<a href="{{ route('wu.services.create') }}" class="btn btn-success mb-3">
Sell Service
</a>

<div class="row">
@foreach($services as $service)
<div class="col-md-4 mb-3">
<div class="card p-3">

<h5>{{ $service->title }}</h5>
<p>${{ $service->price }}</p>
<p>{{ $service->delivery_days }} days</p>

<a href="{{ route('wu.services.show',$service->slug) }}" class="btn btn-primary">
View
</a>

</div>
</div>
@endforeach
</div>

</div>
@endsection