@extends('frontend.layouts.master')

@section('front-content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Marketplace Panel</h2>
        <a href="{{ route('user.marketplace.create') }}" class="btn btn-success">Sell Service</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card p-4">
                <h4>Total Services</h4>
                <h2>{{ $myServiceCount }}</h2>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <h4 class="mb-3">Recent My Services</h4>

        <div class="row">
            @forelse($myServices as $service)
                <div class="col-md-4 mb-3">
                    <div class="border rounded p-3 h-100">
                        <h5>{{ $service->title }}</h5>
                        <p>${{ number_format($service->price, 2) }}</p>
                        <p>Status: {{ ucfirst($service->status) }}</p>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">You have not created any service yet.</div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection