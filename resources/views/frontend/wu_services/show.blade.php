@extends('frontend.layouts.master')

@section('front-content')
<div class="container py-5">

<h2>{{ $service->title }}</h2>
<p>${{ $service->price }}</p>
<p>{{ $service->delivery_days }} days delivery</p>

<p>{{ $service->description }}</p>

</div>
@endsection