@extends('user.layouts.master')

@section('user-content')
<div class="card mt-4 shadow-sm">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center" style="min-height:60px; border-radius:10px 10px 0 0;">
        <h5 class="mb-0">My Marketplace Panel</h5>
        <a href="{{ route('user.marketplace.create') }}" class="btn btn-light btn-sm">Sell Service</a>
    </div>

    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card border-success shadow-sm">
                    <div class="card-body text-center">
                        <h6>Total My Services</h6>
                        <h2>{{ $myServiceCount }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card border-primary shadow-sm">
                    <div class="card-body text-center">
                        <h6>My Orders</h6>
                        <h2>{{ $myOrderCount }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card border-warning shadow-sm">
                    <div class="card-body text-center">
                        <h6>My Sales</h6>
                        <h2>{{ $mySalesCount }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="mb-3">Recent My Services</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>SL</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Delivery</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($myServices as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>{{ $service->title }}</td>
                            <td>${{ number_format($service->price, 2) }}</td>
                            <td>{{ $service->delivery_days }} day(s)</td>
                            <td>{{ ucfirst($service->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No services found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection