@extends('backend.layouts.master')

@section('title','Marketplace Services')
@section('back-content')

<div class="container-fluid mt-3">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Marketplace Services</h3>
  </div>

  @if(session('success'))
    <div class="alert alert-success" style="font-weight:900;">{{ session('success') }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-bordered mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Seller</th>
            <th>Title</th>
            <th>Type</th>
            <th>Category</th>
            <th>Price</th>
            <th>Delivery</th>
            <th>Status</th>
            <th style="width:220px;">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($services as $service)
            <tr>
              <td>{{ $service->id }}</td>
              <td>
                <img src="{{ $service->image_url }}" alt="{{ $service->title }}" width="70" style="border-radius:8px;">
              </td>
              <td>
                {{ $service->user_name ?? 'N/A' }}<br>
                <small>{{ $service->user_email ?? '' }}</small>
              </td>
              <td>{{ $service->title }}</td>
              <td>{{ ($service->type ?? 'service') == 'digital_product' ? 'Digital Product' : 'Service' }}</td>
              <td>{{ $service->category }}</td>
              <td>${{ number_format((float)$service->price, 2) }}</td>
              <td>{{ ($service->type ?? 'service') == 'digital_product' ? 'Instant' : (int)$service->delivery_days . ' day(s)' }}</td>
              <td>
                @if($service->status == 'active')
                  <span class="badge bg-success">Active</span>
                @elseif($service->status == 'pending')
                  <span class="badge bg-warning text-dark">Pending</span>
                @elseif($service->status == 'paused')
                  <span class="badge bg-secondary">Paused</span>
                @else
                  <span class="badge bg-danger">Rejected</span>
                @endif
              </td>
              <td>
                @if($service->status != 'active')
                  <form action="{{ route('admin.wu-marketplace-services-approve', $service->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                  </form>
                @endif

                @if($service->status != 'rejected')
                  <form action="{{ route('admin.wu-marketplace-services-reject', $service->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm">Reject</button>
                  </form>
                @endif

                <a href="{{ route('admin.wu-marketplace-services-delete', $service->id) }}"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Delete this service?');">
                  Delete
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center">No marketplace services found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $services->links() }}
  </div>
</div>

@endsection