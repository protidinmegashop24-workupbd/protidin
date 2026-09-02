@extends('frontend.layouts.master')

@section('css')
<style>
    .wu-public-show-page{
        background: #f8fbff;
        padding: 70px 0;
    }

    .wu-public-show-card,
    .wu-public-side-card,
    .wu-public-profile-card{
        background: #fff;
        border: 1px solid #e6edf5;
        border-radius: 20px;
        box-shadow: 0 12px 28px rgba(15,23,42,.06);
        overflow: hidden;
    }

    .wu-public-show-body{
        padding: 24px;
    }

    .wu-public-image{
        width: 100%;
        max-height: 430px;
        object-fit: cover;
        border-radius: 16px;
        margin-bottom: 20px;
    }

    .wu-public-title{
        font-size: 40px;
        line-height: 1.22;
        font-weight: 800;
        color: #172b4d;
        margin-bottom: 16px;
    }

    .wu-public-meta{
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 18px;
    }

    .wu-public-pill{
        background: #f3f8fc;
        border: 1px solid #e1eaf3;
        color: #52627a;
        border-radius: 999px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 700;
    }

    .wu-public-desc-title{
        font-size: 28px;
        font-weight: 800;
        color: #172b4d;
        margin: 24px 0 14px;
    }

    .wu-public-desc{
        color: #5b6d83;
        font-size: 16px;
        line-height: 1.9;
    }

    .wu-public-profile-card{
        padding: 20px;
        margin-bottom: 20px;
    }

    .wu-profile-top{
        display: flex;
        gap: 14px;
        align-items: center;
    }

    .wu-profile-avatar{
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #eef4fa;
    }

    .wu-profile-name{
        font-size: 20px;
        font-weight: 800;
        color: #172b4d;
        margin-bottom: 4px;
    }

    .wu-profile-sub{
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
    }

    .wu-profile-link{
        color: inherit;
        text-decoration: none !important;
    }

    .wu-profile-link:hover{
        color: #22ab59;
    }

    .wu-public-side-card{
        padding: 24px;
        position: sticky;
        top: 20px;
    }

    .wu-price{
        font-size: 42px;
        line-height: 1;
        color: #16a34a;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .wu-short-desc{
        color: #64748b;
        font-size: 15px;
        line-height: 1.8;
        margin-bottom: 18px;
    }

    .wu-side-list{
        margin: 0 0 18px;
        padding: 0;
        list-style: none;
    }

    .wu-side-list li{
        padding: 10px 0;
        border-bottom: 1px dashed #e4ebf2;
        display: flex;
        justify-content: space-between;
        gap: 10px;
        color: #55697f;
        font-weight: 700;
    }

    .wu-side-list li:last-child{
        border-bottom: 0;
    }

    .wu-public-btn{
        display: inline-block;
        width: 100%;
        text-align: center;
        background: #22ab59;
        color: #fff !important;
        border-radius: 12px;
        padding: 14px 20px;
        font-weight: 800;
        text-decoration: none !important;
        box-shadow: 0 12px 24px rgba(34,171,89,.18);
    }

    .wu-public-btn:hover{
        background: #1b8e4a;
        color: #fff !important;
    }

    .wu-profile-extra{
        margin-top: 12px;
        color: #64748b;
        font-size: 14px;
        line-height: 1.8;
        word-break: break-word;
    }

    @media (max-width: 991px){
        .wu-public-title{
            font-size: 30px;
        }

        .wu-public-side-card{
            position: static;
            margin-top: 20px;
        }
    }
</style>
@endsection

@section('front-content')
<div class="wu-public-show-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="wu-public-show-card">
                    <div class="wu-public-show-body">
                        <img src="{{ wu_service_image($service->image) }}" class="wu-public-image" alt="{{ $service->title }}">

                        <div class="wu-public-title">{{ $service->title }}</div>

                        <div class="wu-public-meta">
                            <span class="wu-public-pill">Category: {{ $service->category ?: 'General' }}</span>
                            <span class="wu-public-pill">Delivery: {{ $service->delivery_days }} day(s)</span>
                            <span class="wu-public-pill">Revisions: {{ $service->revision_limit }}</span>
                        </div>

                        <div class="wu-public-desc-title">Service Description</div>
                        <div class="wu-public-desc">
                            {!! nl2br(e($service->description)) !!}
                        </div>
                        <div class="wu-public-desc-title">Customer Reviews</div>

@if(isset($reviews) && count($reviews) > 0)

    @foreach($reviews as $review)
        <div style="border-bottom:1px solid #e5e7eb; padding:15px 0;">
            
            <strong style="color:#172b4d;">
                {{ $review->buyer_name ?? 'User' }}
            </strong>

            <div style="color:#f59e0b; font-size:14px;">
                ⭐ {{ $review->rating }}/5
            </div>

            <p style="color:#64748b; margin-top:5px;">
                {{ $review->comment }}
            </p>

            <small style="color:#94a3b8;">
                {{ \Carbon\Carbon::parse($review->created_at)->format('d M Y') }}
            </small>

        </div>
    @endforeach

@else

    <div class="alert alert-info mt-3">
        No reviews yet. Be the first to review this service.
    </div>

@endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="wu-public-profile-card">
                    <div class="wu-profile-top">
                        <a href="{{ url('/seller-profile/' . $service->user_id) }}">
                            <img src="{{ $sellerProfileImage }}"
                                 alt="{{ $service->seller_name ?: 'Seller' }}"
                                 class="wu-profile-avatar"
                                 onerror="this.onerror=null;this.src='{{ asset('frontend/img/user.png') }}';">
                        </a>

                        <div>
                            <div class="wu-profile-name">
                                <a href="{{ url('/seller-profile/' . $service->user_id) }}" class="wu-profile-link">
                                    {{ $service->seller_name ?: 'Marketplace Seller' }}
                                </a>
                            </div>
                            <div class="wu-profile-sub">
                                {{ $service->seller_experience_level ?: 'Marketplace Seller' }}
                            </div>
                        </div>
                    </div>

                    @if(!empty($service->seller_skills))
                        <div class="wu-profile-extra">
                            <strong>Skills:</strong> {{ $service->seller_skills }}
                        </div>
                    @endif

                    @if(!empty($service->seller_bio))
                        <div class="wu-profile-extra">
                            <strong>About Seller:</strong><br>
                            {{ $service->seller_bio }}
                        </div>
                    @endif
                </div>

                <div class="wu-public-side-card">
                    <div class="wu-price">${{ number_format($service->price, 2) }}</div>
                    <div class="wu-short-desc">{{ $service->short_description }}</div>

                    <ul class="wu-side-list">
                        <li><span>Service Price</span> <span>${{ number_format($service->price, 2) }}</span></li>
                        <li><span>Delivery Time</span> <span>{{ $service->delivery_days }} day(s)</span></li>
                        <li><span>Revisions</span> <span>{{ $service->revision_limit }}</span></li>
                        <li><span>Category</span> <span>{{ $service->category ?: 'General' }}</span></li>
                    </ul>
                    <a href="{{ route('user.marketplace.service.show', $service->slug) }}" class="wu-public-btn">
    Login / Continue to Chat & Order
</a>

<!-- 🔥 NEW TRUST + ORDER BOX -->
<div class="card mt-4 shadow-sm" style="border-radius:18px; border:1px solid #e5e7eb;">
    <div class="card-body p-4">
        <h4 style="font-weight:800;">Start Your Service Order</h4>
        <p style="color:#64748b;">
            Contact the seller before placing an order or continue with a secure order process through Workup BD Marketplace.
        </p>

        <ul style="list-style:none; padding-left:0; line-height:2;">
            <li>✔ Secure payment system with protected transactions</li>
            <li>✔ Chat with seller before ordering</li>
            <li>✔ Buyer protection and platform support</li>
            <li>✔ Order reviewed before completion</li>
        </ul>

        <div class="d-flex flex-wrap gap-2 mt-3">
            @auth
                <a href="{{ url('/user/marketplace/service/' . $service->slug) }}" class="btn btn-success">
                    Order Now
                </a>
                <a href="{{ url('/user/marketplace/service/' . $service->slug) }}" class="btn btn-primary">
                    Contact Seller
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-success">
                    Login to Order
                </a>
                <a href="{{ route('login') }}" class="btn btn-primary">
                    Login to Contact Seller
                </a>
            @endauth

            <a href="{{ route('contact-us') }}" class="btn btn-outline-danger">
                Report Issue
            </a>
        </div>

        <small class="d-block mt-3" style="color:#64748b;">
            All marketplace communication and transactions should remain inside Workup BD for safety.
        </small>
    </div>
</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection