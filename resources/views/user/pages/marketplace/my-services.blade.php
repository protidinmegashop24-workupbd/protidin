@extends('user.layouts.master')

@section('css')
<style>
    .mp-myservices-card{
        border: 1px solid #e6edf5;
        border-radius: 18px;
        box-shadow: 0 10px 26px rgba(15,23,42,.06);
        overflow: hidden;
        background: #fff;
    }

    .mp-myservices-header{
        background: linear-gradient(135deg, #eefaf2 0%, #f8fbff 100%);
        padding: 18px 22px;
        border-bottom: 1px solid #e8eef5;
    }

    .mp-myservices-header h4{
        margin: 0;
        font-weight: 800;
        color: #172b4d;
    }

    .mp-service-row{
        border: 1px solid #e6edf5;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 16px;
        background: #fff;
        box-shadow: 0 8px 18px rgba(15,23,42,.04);
    }

    .mp-service-thumb{
        width: 100%;
        max-width: 110px;
        height: 90px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid #e6edf5;
    }

    .mp-service-title{
        font-size: 22px;
        font-weight: 800;
        color: #172b4d;
        margin-bottom: 8px;
        line-height: 1.35;
    }

    .mp-service-meta{
        color: #64748b;
        font-size: 14px;
        line-height: 1.8;
    }

    .mp-status-badge{
        display: inline-block;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        margin-right: 8px;
        margin-bottom: 8px;
    }

    .mp-status-active{ background:#eaf8ef; color:#15803d; }
    .mp-status-pending{ background:#fff7e8; color:#b45309; }
    .mp-status-paused{ background:#eef2f7; color:#475569; }
    .mp-status-rejected{ background:#fff1f2; color:#dc2626; }

    .mp-mini-badge{
        display: inline-block;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        margin-right: 8px;
        margin-bottom: 8px;
    }

    .mp-inquiry-badge{
        background: #eef4ff;
        color: #1d4ed8;
    }

    .mp-unread-badge{
        background: #fff1f2;
        color: #dc2626;
    }

    .mp-btn{
        border-radius: 10px;
        font-weight: 800;
        padding: 9px 14px;
        margin-right: 8px;
        margin-top: 8px;
    }
</style>
@endsection

@section('user-content')
<div class="mp-myservices-card mt-4">
    <div class="mp-myservices-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h4>My Services</h4>
        <a href="{{ route('user.marketplace.create') }}" class="btn btn-success btn-sm">Add New Service</a>
    </div>

    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @forelse ($services as $service)
            <div class="mp-service-row">
                <div class="row align-items-center">
                    <div class="col-md-2 mb-3 mb-md-0">
                        <img src="{{ wu_service_image($service->image) }}" alt="{{ $service->title }}" class="mp-service-thumb">
                    </div>

                    <div class="col-md-7">
                        <div class="mp-service-title">{{ $service->title }}</div>

                        <div class="mb-2">
                            <span class="mp-mini-badge" style="background:#eef2ff;color:#4338ca;">
                                {{ ($service->type ?? 'service') == 'digital_product' ? 'Digital Product' : 'Service' }}
                            </span>

                            @if($service->status == 'active')
                                <span class="mp-status-badge mp-status-active">Active</span>
                            @elseif($service->status == 'pending')
                                <span class="mp-status-badge mp-status-pending">Pending</span>
                            @elseif($service->status == 'paused')
                                <span class="mp-status-badge mp-status-paused">Paused</span>
                            @else
                                <span class="mp-status-badge mp-status-rejected">Rejected</span>
                            @endif

                            <span class="mp-mini-badge mp-inquiry-badge">
                                Inquiries: {{ $service->inquiry_count ?? 0 }}
                            </span>

                            @if(($service->unread_inquiry_count ?? 0) > 0)
                                <span class="mp-mini-badge mp-unread-badge">
                                    New: {{ $service->unread_inquiry_count }}
                                </span>
                            @endif
                        </div>

                        <div class="mp-service-meta">
                            <div><strong>Price:</strong> ${{ number_format($service->price, 2) }}</div>
                            <div><strong>Delivery:</strong> {{ $service->delivery_days }} day(s)</div>
                            <div><strong>Category:</strong> {{ $service->category ?: 'General' }}</div>
                        </div>
                    </div>

                    <div class="col-md-3 text-md-end">
                        @if($service->status == 'active')
                            <a href="{{ route('user.marketplace.service.show', $service->slug) }}" class="btn btn-info mp-btn">View</a>
                        @endif

                        <a href="{{ route('user.marketplace.edit', $service->id) }}" class="btn btn-primary mp-btn">Edit</a>

                        <a href="{{ route('user.marketplace.delete', $service->id) }}"
                           class="btn btn-danger mp-btn"
                           onclick="return confirm('Are you sure you want to delete this service?')">
                            Delete
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info mb-0">No services found.</div>
        @endforelse

        <div class="mt-3 d-flex justify-content-center">
            {{ $services->links() }}
        </div>
    </div>
</div>
@endsection