@extends('user.layouts.master')

@section('css')
<style>
    .mp-service-page{
        max-width: 1250px;
        margin: 0 auto;
    }

    .mp-service-main-card,
    .mp-service-side-card,
    .mp-chat-card,
    .mp-profile-card{
        background: #fff;
        border: 1px solid #e6edf5;
        border-radius: 20px;
        box-shadow: 0 12px 28px rgba(15,23,42,.06);
        overflow: hidden;
    }

    .mp-service-main-body{
        padding: 24px;
    }

    .mp-service-image{
        width: 100%;
        max-height: 430px;
        object-fit: cover;
        border-radius: 16px;
        margin-bottom: 20px;
    }

    .mp-service-title{
        font-size: 38px;
        line-height: 1.25;
        font-weight: 800;
        color: #172b4d;
        margin-bottom: 18px;
    }

    .mp-service-meta{
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 18px;
    }

    .mp-meta-pill{
        background: #f3f8fc;
        border: 1px solid #e1eaf3;
        color: #4f627b;
        border-radius: 999px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 700;
    }

    .mp-service-desc-title{
        font-size: 28px;
        font-weight: 800;
        color: #172b4d;
        margin-top: 26px;
        margin-bottom: 16px;
    }

    .mp-service-desc{
        color: #56697f;
        font-size: 16px;
        line-height: 1.9;
    }

    .mp-profile-card{
        padding: 20px;
        margin-bottom: 20px;
    }

    .mp-profile-top{
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 16px;
    }

    .mp-profile-avatar{
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #eef4fa;
    }

    .mp-profile-name{
        font-size: 20px;
        font-weight: 800;
        color: #172b4d;
        margin-bottom: 4px;
    }

    .mp-profile-sub{
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
    }

    .mp-rating{
        color: #f59e0b;
        font-weight: 800;
        font-size: 15px;
        margin-top: 4px;
    }

    .mp-service-side-card{
        padding: 22px;
        position: sticky;
        top: 15px;
    }

    .mp-side-price{
        font-size: 40px;
        font-weight: 800;
        color: #16a34a;
        line-height: 1.1;
        margin-bottom: 8px;
    }

    .mp-side-short{
        color: #64748b;
        font-size: 15px;
        line-height: 1.8;
        margin-bottom: 18px;
    }

    .mp-side-list{
        margin: 0 0 18px;
        padding: 0;
        list-style: none;
    }

    .mp-side-list li{
        padding: 10px 0;
        border-bottom: 1px dashed #e4ebf2;
        color: #55697f;
        font-weight: 700;
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .mp-side-list li:last-child{
        border-bottom: 0;
    }

    .mp-order-btn{
        width: 100%;
        background: #22ab59;
        color: #fff;
        border: 0;
        border-radius: 12px;
        padding: 14px 18px;
        font-weight: 800;
        font-size: 16px;
        box-shadow: 0 12px 24px rgba(34,171,89,.18);
    }

    .mp-order-btn:hover{
        background: #1b8f4b;
        color: #fff;
    }

    .mp-chat-card{
        margin-top: 22px;
    }

    .mp-chat-header{
        background: linear-gradient(135deg, #eef4ff 0%, #f8fbff 100%);
        padding: 16px 20px;
        border-bottom: 1px solid #e8eef5;
    }

    .mp-chat-header h5{
        margin: 0;
        font-size: 22px;
        font-weight: 800;
        color: #172b4d;
    }

    .mp-chat-body{
        max-height: 350px;
        overflow-y: auto;
        padding: 18px;
        background: #fbfdff;
    }

    .mp-chat-bubble{
        max-width: 82%;
        margin-bottom: 12px;
        padding: 14px 16px;
        border-radius: 16px;
        word-break: break-word;
        box-shadow: 0 8px 16px rgba(15,23,42,.04);
    }

    .mp-chat-me{
        margin-left: auto;
        background: #eaf8ef;
        border-bottom-right-radius: 6px;
    }

    .mp-chat-other{
        margin-right: auto;
        background: #fff;
        border: 1px solid #e7edf4;
        border-bottom-left-radius: 6px;
    }

    .mp-chat-role{
        font-size: 12px;
        font-weight: 800;
        color: #66758b;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .mp-chat-footer{
        padding: 18px;
        border-top: 1px solid #e8eef5;
        background: #fff;
    }

    .mp-chat-footer textarea{
        border-radius: 12px;
        border: 1px solid #d8e3ee;
        min-height: 100px;
        box-shadow: none !important;
    }

    .mp-send-btn{
        background: #1d4ed8;
        color: #fff;
        border: 0;
        border-radius: 10px;
        padding: 11px 18px;
        font-weight: 800;
    }

    .mp-send-btn:hover{
        background: #173fb2;
        color: #fff;
    }

    @media (max-width: 991px){
        .mp-service-title{
            font-size: 30px;
        }

        .mp-service-side-card{
            position: static;
        }
    }
</style>
<style>
    .mp-profile-card{
        background: #fff;
        border: 1px solid #e6edf5;
        border-radius: 20px;
        box-shadow: 0 12px 28px rgba(15,23,42,.06);
        padding: 20px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .mp-profile-top{
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .mp-profile-avatar{
        width: 78px;
        height: 78px;
        min-width: 78px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #eef4fa;
        background: #fff;
    }

    .mp-profile-info{
        flex: 1;
        min-width: 0;
    }

    .mp-profile-name{
        font-size: 20px;
        font-weight: 800;
        color: #172b4d;
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .mp-profile-sub{
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .mp-rating{
        color: #f59e0b;
        font-weight: 800;
        font-size: 15px;
        line-height: 1.4;
    }

    .mp-profile-extra{
        margin-top: 14px;
        color: #64748b;
        line-height: 1.8;
        font-size: 14px;
        word-break: break-word;
    }

    .mp-service-side-card{
        background: #fff;
        border: 1px solid #e6edf5;
        border-radius: 20px;
        box-shadow: 0 12px 28px rgba(15,23,42,.06);
        padding: 22px;
        position: sticky;
        top: 15px;
    }

    @media (max-width: 991px){
        .mp-service-side-card{
            position: static;
        }
    }
</style>
@endsection

@section('user-content')
<div class="mp-service-page mt-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="mp-service-main-card">
                <div class="mp-service-main-body">
                    <img src="{{ wu_service_image($service->image) }}" class="mp-service-image" alt="{{ $service->title }}">

                    <div class="mp-service-title">{{ $service->title }}</div>

                    <div class="mp-service-meta">
                        <span class="mp-meta-pill">Category: {{ $service->category ?: 'General' }}</span>
                        @if(($service->type ?? 'service') == 'digital_product')
                            <span class="mp-meta-pill">⚡ Instant Delivery</span>
                        @else
                            <span class="mp-meta-pill">Delivery: {{ $service->delivery_days }} day(s)</span>
                            <span class="mp-meta-pill">Revisions: {{ $service->revision_limit }}</span>
                        @endif
                        <button type="button" class="mp-meta-pill" style="cursor:pointer; border:none;" onclick="shareMarketplaceService()">
                            Share
                        </button>
                    </div>

                    <div class="mp-service-desc-title">Service Description</div>
                    <div class="mp-service-desc">
                        {!! nl2br(e($service->description)) !!}
                    </div>
                    <div class="wu-public-desc-title">
    Customer Reviews ({{ $totalReviews }}) ⭐ {{ $avgRating }}
</div>

@if($reviews->count() > 0)

@foreach($reviews as $review)
<div style="border-bottom:1px solid #e5e7eb; padding:15px 0;">

    <div style="display:flex; gap:10px; align-items:center;">
        <img src="{{ !empty($review->buyer_image) ? url($review->buyer_image) : asset('frontend/img/user.png') }}"
             style="width:40px; height:40px; border-radius:50%;">
        
        <strong>{{ $review->buyer_name ?? 'User' }}</strong>
    </div>

    <div style="color:#f59e0b;">
        ⭐ {{ $review->rating }}/5
    </div>

    <p style="color:#64748b;">
        {{ $review->comment }}
    </p>

</div>
@endforeach

@else

<div class="alert alert-info">
    No reviews yet
</div>

@endif
                </div>
            </div>

            @if(!$service->is_own)
                <div class="mp-chat-card">
                    <div class="mp-chat-header">
                        <h5>Chat with seller before order</h5>
                    </div>

                    <div class="mp-chat-body">
                        @forelse($inquiries as $msg)
                            <div class="mp-chat-bubble {{ $msg->sender_id == auth()->id() ? 'mp-chat-me' : 'mp-chat-other' }}">
                                <div class="mp-chat-role">{{ $msg->sender_id == auth()->id() ? 'You' : 'Seller' }}</div>
                                <div>{{ $msg->message }}</div>
                            </div>
                        @empty
                            <div class="alert alert-light mb-0">No pre-order messages yet.</div>
                        @endforelse
                    </div>

                    <div class="mp-chat-footer">
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('user.marketplace.inquiry.send', [$service->id, $service->user_id]) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea name="message" class="form-control" placeholder="Ask anything about delivery, scope, revisions, or requirements before you order..." required></textarea>
                            </div>
                            <button type="submit" class="btn mp-send-btn">Send Message</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
    <div class="mp-profile-card">
        <div class="mp-profile-top">
    <a href="{{ route('seller.profile', $service->user_id) }}">
        <img src="{{ $sellerProfileImage }}"
             alt="{{ $service->seller_name }}"
             class="mp-profile-avatar"
             onerror="this.onerror=null;this.src='{{ asset('frontend/img/user.png') }}';">
    </a>

    <div class="mp-profile-info">
        <div class="mp-profile-name">
            <a href="{{ route('seller.profile', $service->user_id) }}" style="color:inherit; text-decoration:none;">
                {{ $service->seller_name ?: 'Seller' }}
            </a>
        </div>

        <div class="mp-profile-sub">
            {{ $service->seller_experience_level ?: 'Marketplace Seller' }}
        </div>

        <div class="mp-rating">
            ★ {{ $avgRating }} / 5 ({{ $totalReviews }} review{{ $totalReviews == 1 ? '' : 's' }})
        </div>
    </div>
</div>

@if(!empty($service->seller_skills))
    <div class="mp-profile-extra">
        <strong>Skills:</strong> {{ $service->seller_skills }}
    </div>
@endif

@if(!empty($service->seller_bio))
    <div class="mp-profile-extra" style="margin-top: 10px;">
        <strong>About Seller:</strong><br>
        {{ $service->seller_bio }}
    </div>
@endif

<div class="mp-profile-extra" style="margin-top: 10px;">
    <strong>Member since:</strong> {{ \Carbon\Carbon::parse($service->seller_join_date)->format('M Y') }}
</div>
    </div>

    <div class="mp-service-side-card">
        <div class="mp-side-price">${{ number_format($service->price, 2) }}</div>
        <div class="mp-side-short">{{ $service->short_description }}</div>

        <ul class="mp-side-list">
            <li><span>Price</span> <span>${{ number_format($service->price, 2) }}</span></li>
            @if(($service->type ?? 'service') == 'digital_product')
                <li><span>Delivery</span> <span>Instant download</span></li>
            @else
                <li><span>Delivery Time</span> <span>{{ $service->delivery_days }} day(s)</span></li>
                <li><span>Revisions</span> <span>{{ $service->revision_limit }}</span></li>
            @endif
            <li><span>Category</span> <span>{{ $service->category ?: 'General' }}</span></li>
        </ul>

        @if($service->is_own)
            <div class="alert alert-info mb-0">This is your own service. You cannot order it.</div>
        @else
            <form action="{{ route('user.marketplace.order', $service->id) }}" method="POST">
    @csrf
    @if(($service->type ?? 'service') != 'digital_product')
        <div class="mb-3">
            <label class="form-label fw-bold">Requirements</label>
            <textarea name="requirements" class="form-control" rows="5" style="border-radius:12px;" placeholder="Write the requirements you want the seller to follow."></textarea>
        </div>
    @endif

    <button type="submit" class="mp-order-btn">{{ ($service->type ?? 'service') == 'digital_product' ? 'Buy Now' : 'Order Now' }}</button>
</form>
        @endif
    </div>
</div>
    </div>
</div>
@endsection

@section('js')
<script>
    function shareMarketplaceService() {
        const link = "{{ route('marketplace.service.show', $service->slug) }}@if(Auth::check())?ref={{ Auth::user()->code }}@endif";

        if (navigator.share) {
            navigator.share({
                title: "{{ $service->title }}",
                url: link
            }).catch(() => {});
            return;
        }

        if (navigator.clipboard) {
            navigator.clipboard.writeText(link).then(() => {
                toastr.success('Service link copied to clipboard!');
            });
        }
    }
</script>
@endsection