@extends('user.layouts.master')

@section('css')
<style>
    .mp-page-card{border:1px solid #e6edf5;border-radius:18px;box-shadow:0 10px 26px rgba(15,23,42,.06);overflow:hidden}
    .mp-page-header{background:linear-gradient(135deg,#1d4ed8 0%,#2563eb 100%);color:#fff;padding:18px 22px}
    .mp-order-card{border:1px solid #e6edf5;border-radius:16px;padding:18px;background:#fff;box-shadow:0 8px 20px rgba(15,23,42,.05);height:100%}
    .mp-order-card img{width:100%;height:180px;object-fit:cover;border-radius:12px;margin-bottom:14px}
    .mp-order-title{font-size:20px;font-weight:800;color:#172b4d;line-height:1.35;margin-bottom:10px}
    .mp-order-meta{color:#5f6f86;font-size:14px;line-height:1.8;margin-bottom:12px}
    .mp-price{color:#16a34a;font-size:24px;font-weight:800;margin-bottom:8px}
    .mp-badge{display:inline-block;padding:6px 12px;border-radius:999px;font-size:12px;font-weight:700;margin-bottom:12px}
    .mp-badge-progress{background:#e8f1ff;color:#1d4ed8}
    .mp-badge-delivered{background:#e8fff1;color:#15803d}
    .mp-badge-completed{background:#eefcf0;color:#15803d}
    .mp-badge-cancelled{background:#fff1f2;color:#dc2626}
    .mp-btn{border-radius:10px;font-weight:700;padding:10px 14px}
</style>
@endsection

@section('user-content')
<div class="mp-page-card mt-4">
    <div class="mp-page-header">
        <h4 class="mb-0">My Orders</h4>
    </div>

    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            @forelse ($orders as $order)
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="mp-order-card">
                        <img src="{{ $order->image_url }}" alt="{{ $order->service_title }}">

                        <div class="mp-order-title">{{ $order->service_title }}</div>
                        <div class="mp-price">${{ number_format($order->price, 2) }}</div>

                        <div class="mb-2">
                            @if($order->status == 'in_progress')
                                <span class="mp-badge mp-badge-progress">In Progress</span>
                            @elseif($order->status == 'delivered')
                                <span class="mp-badge mp-badge-delivered">Delivered</span>
                            @elseif($order->status == 'completed')
                                <span class="mp-badge mp-badge-completed">Completed</span>
                            @else
                                <span class="mp-badge mp-badge-cancelled">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                            @endif
                        </div>

                        <div class="mp-order-meta">
                            <div><strong>Order ID:</strong> #{{ $order->id }}</div>
                            <div><strong>Deadline:</strong> {{ $order->delivery_deadline }}</div>
                            <div><strong>Payment:</strong> {{ ucfirst($order->payment_status ?? 'held') }}</div>
                            <div><strong>Escrow:</strong> ${{ number_format($order->escrow_amount ?? $order->price, 2) }}</div>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('user.marketplace.order_chat', $order->id) }}" class="btn btn-info mp-btn">Open Order</a>

                            @if($order->service_type == 'digital_product' && in_array($order->status, ['delivered', 'completed']))
                                <a href="{{ route('user.marketplace.download_product', $order->id) }}" class="btn btn-primary mp-btn">Download</a>
                            @endif

                            @if($order->status == 'delivered')
                                <form action="{{ route('user.marketplace.complete', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success mp-btn">Complete</button>
                                </form>
                            @endif

                            @if(in_array($order->status, ['in_progress', 'revision_requested', 'delivered']) && ($order->payment_status ?? 'held') == 'held')
                                <form action="{{ route('user.marketplace.cancel', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger mp-btn" onclick="return confirm('Cancel this order and refund?')">Cancel</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info mb-0">No orders found.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection