@extends('user.layouts.master')

@section('css')
<style>
    .mp-grid-wrap{
        max-width: 1250px;
        margin: 0 auto;
    }

    .mp-grid-header{
        border: 1px solid #e6edf5;
        border-radius: 20px;
        background: linear-gradient(135deg, #eef7ff 0%, #f8fbff 100%);
        box-shadow: 0 10px 26px rgba(15,23,42,.05);
        padding: 28px 24px;
        margin-bottom: 24px;
    }

    .mp-grid-header h2{
        font-size: 34px;
        font-weight: 800;
        color: #172b4d;
        margin-bottom: 10px;
    }

    .mp-grid-header p{
        color: #617288;
        font-size: 16px;
        line-height: 1.8;
        margin-bottom: 0;
        max-width: 780px;
    }

    .mp-service-grid-card{
        border: 1px solid #e6edf5;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 10px 26px rgba(15,23,42,.05);
        overflow: hidden;
        height: 100%;
        transition: .25s ease;
    }

    .mp-service-grid-card:hover{
        transform: translateY(-5px);
        box-shadow: 0 16px 34px rgba(15,23,42,.09);
    }

    .mp-service-grid-card img{
        width: 100%;
        height: 220px;
        object-fit: cover;
        display: block;
    }

    .mp-service-grid-body{
        padding: 20px;
    }

    .mp-service-grid-title{
        font-size: 24px;
        line-height: 1.35;
        font-weight: 800;
        color: #172b4d;
        margin-bottom: 10px;
    }

    .mp-service-grid-title a{
        text-decoration: none;
        color: inherit;
    }

    .mp-service-grid-title a:hover{
        color: #22ab59;
    }

    .mp-service-grid-text{
        color: #617288;
        font-size: 15px;
        line-height: 1.8;
        min-height: 82px;
        margin-bottom: 14px;
    }

    .mp-service-grid-meta{
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
    }

    .mp-grid-price{
        color: #16a34a;
        font-size: 24px;
        font-weight: 800;
    }

    .mp-grid-delivery{
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
    }

    .mp-grid-btn{
        display: inline-block;
        background: #22ab59;
        color: #fff !important;
        border-radius: 12px;
        padding: 11px 18px;
        font-weight: 800;
        text-decoration: none !important;
        box-shadow: 0 10px 24px rgba(34,171,89,.16);
    }

    .mp-grid-btn:hover{
        background: #1b8f4b;
        color: #fff !important;
    }
</style>
<style>
.wu-category-pill{
    display:inline-block;
    padding:10px 16px;
    border-radius:999px;
    background:#f3f8fc;
    border:1px solid #dce7f2;
    color:#172b4d;
    font-weight:700;
    margin:6px 6px 6px 0;
    text-decoration:none !important;
}
.wu-category-pill:hover{
    background:#eaf4ff;
    color:#172b4d;
}
.wu-category-pill.active{
    background:#22ab59;
    color:#fff;
    border-color:#22ab59;
}
.wu-search-bar{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-bottom:18px;
}
.wu-search-bar input, .wu-search-bar select{
    border:1px solid #dce7f2;
    border-radius:10px;
    padding:10px 14px;
}
.wu-search-bar input[type="text"]{ flex:1 1 220px; }
.wu-search-bar input[type="number"]{ width:120px; }
</style>
@endsection

@section('user-content')
<div class="mp-grid-wrap mt-4">
    <div class="mp-grid-header">
        <h2>Browse Marketplace Services</h2>
        <p>
            Discover active service listings, compare pricing and delivery terms, chat with sellers before ordering, and choose a service that matches your work needs more clearly.
        </p>
    </div>
    <form method="GET" action="{{ url()->current() }}" class="wu-search-bar">
        <input type="text" name="q" placeholder="Search services..." value="{{ request('q') }}">
        <input type="number" name="min_price" placeholder="Min $" step="0.01" min="0" value="{{ request('min_price') }}">
        <input type="number" name="max_price" placeholder="Max $" step="0.01" min="0" value="{{ request('max_price') }}">
        <select name="sort">
            <option value="">Newest</option>
            <option value="price_asc" @if(request('sort') == 'price_asc') selected @endif>Price: Low to High</option>
            <option value="price_desc" @if(request('sort') == 'price_desc') selected @endif>Price: High to Low</option>
        </select>
        <button type="submit" class="mp-grid-btn">Search</button>
    </form>
    <div class="mb-4">
    <a href="{{ route('user.marketplace.services') }}" class="wu-category-pill {{ !isset($category) ? 'active' : '' }}">All</a>

    @foreach($categories as $cat)
        <a href="{{ route('user.marketplace.services.category', $cat->slug) }}" class="wu-category-pill {{ (isset($category) && $category->id == $cat->id) ? 'active' : '' }}">
            {{ $cat->name }}
        </a>
    @endforeach
</div>
    <div class="row">
        @forelse($services as $service)
            <div class="col-md-6 col-xl-4 mb-4">
                <div class="mp-service-grid-card">
                    <img src="{{ wu_service_image($service->image) }}" alt="{{ $service->title }}">

                    <div class="mp-service-grid-body">
                        <div class="mp-service-grid-title">
                            <a href="{{ route('user.marketplace.service.show', $service->slug) }}">{{ $service->title }}</a>
                        </div>

                        <div class="mp-service-grid-text">
                            {{ \Illuminate\Support\Str::limit($service->short_description ?: $service->description, 120) }}
                        </div>

                        <div class="mp-service-grid-meta">
                            <div class="mp-grid-price">${{ number_format($service->price, 2) }}</div>
                            @if(($service->type ?? 'service') == 'digital_product')
                                <div class="mp-grid-delivery">⚡ Instant Delivery</div>
                            @else
                                <div class="mp-grid-delivery">Delivery: {{ $service->delivery_days }} day(s)</div>
                            @endif
                        </div>

                        <a href="{{ route('user.marketplace.service.show', $service->slug) }}" class="mp-grid-btn">View, Chat & Order</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No services available right now.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $services->links() }}
    </div>
</div>
@endsection