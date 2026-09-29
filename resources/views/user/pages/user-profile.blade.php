@extends('user.layouts.master')

@section('css')
<style>
    .seller-profile-wrap{
        padding: 25px 10px;
    }

    .seller-profile-card,
    .seller-services-card,
    .seller-stat-card{
        background: #fff;
        border: 1px solid #e6edf5;
        border-radius: 20px;
        box-shadow: 0 10px 26px rgba(15,23,42,.06);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .seller-profile-card .card-body,
    .seller-services-card .card-body,
    .seller-stat-card .card-body{
        padding: 24px;
    }

    .seller-cover{
        background: linear-gradient(135deg, #eef7ff 0%, #f8fbff 45%, #ffffff 100%);
        padding: 30px 24px;
        border-bottom: 1px solid #e9eef5;
        text-align: center;
    }

    .seller-avatar{
        width: 110px;
        height: 110px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 10px 20px rgba(15,23,42,.08);
        margin-bottom: 14px;
    }

    .seller-name{
        font-size: 34px;
        font-weight: 800;
        color: #172b4d;
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .seller-role{
        color: #64748b;
        font-size: 16px;
        font-weight: 700;
    }

    .seller-meta{
        margin-top: 18px;
        color: #52627a;
        font-size: 15px;
        line-height: 1.9;
    }

    .seller-bio-box{
        background: #f9fcff;
        border: 1px solid #e8eef5;
        border-radius: 14px;
        padding: 16px;
        color: #5b6d83;
        line-height: 1.8;
    }

    .seller-section-title{
        font-size: 22px;
        font-weight: 800;
        color: #172b4d;
        margin-bottom: 16px;
    }

    .skill-badge{
        display: inline-block;
        background: #eef4ff;
        color: #1d4ed8;
        border: 1px solid #dce8ff;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        margin: 0 8px 8px 0;
    }

    .seller-stat-value{
        font-size: 28px;
        font-weight: 800;
        color: #172b4d;
        line-height: 1.2;
    }

    .seller-stat-label{
        color: #64748b;
        font-size: 14px;
        font-weight: 700;
    }

    .service-card{
        border: 1px solid #e6edf5;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(15,23,42,.05);
        background: #fff;
        height: 100%;
        transition: .25s ease;
    }

    .service-card:hover{
        transform: translateY(-4px);
        box-shadow: 0 14px 28px rgba(15,23,42,.09);
    }

    .service-card img{
        width: 100%;
        height: 190px;
        object-fit: cover;
    }

    .service-card-body{
        padding: 18px;
    }

    .service-title{
        font-size: 20px;
        font-weight: 800;
        color: #172b4d;
        line-height: 1.35;
        margin-bottom: 10px;
    }

    .service-price{
        color: #16a34a;
        font-size: 26px;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .service-text{
        color: #64748b;
        line-height: 1.7;
        font-size: 14px;
        min-height: 70px;
    }

    .service-btn{
        display: inline-block;
        background: #4f46e5;
        color: #fff !important;
        padding: 11px 18px;
        border-radius: 10px;
        text-decoration: none !important;
        font-weight: 700;
    }

    .service-btn:hover{
        background: #4338ca;
        color: #fff !important;
    }
</style>
@endsection

@section('user-content')
<div class="seller-profile-wrap">
    <div class="row">
        <div class="col-lg-4">
            <div class="seller-profile-card">
                <div class="seller-cover">
                    <img src="{{ URL::to($user->image) }}"
                         alt="{{ $user->name }}"
                         class="seller-avatar"
                         onerror="this.onerror=null;this.src='{{ asset('frontend/img/user.png') }}';">

                    <div class="seller-name">{{ $user->name }}</div>
                    <div class="seller-role">{{ $user->seller_experience_level ?: 'Marketplace Seller' }}</div>
                </div>

                <div class="card-body">
                    <div class="seller-meta">
                        <div><strong>Joined:</strong> {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</div>
                        <div><strong>User ID:</strong> {{ $user->code }}</div>
                    </div>

                    <hr>

                    <div class="seller-section-title">About Seller</div>
                    <div class="seller-bio-box">
                        {{ $user->seller_bio ?: 'No seller bio added yet.' }}
                    </div>

                    <div class="seller-section-title mt-4">Skills</div>
                    <div>
                        @if(!empty($user->seller_skills))
                            @foreach(explode(',', $user->seller_skills) as $skill)
                                <span class="skill-badge">{{ trim($skill) }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">No skills added yet.</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="seller-stat-card">
                <div class="card-body">
                    <div class="seller-section-title">Quick Stats</div>

                    <div class="mb-3">
                        <div class="seller-stat-value">{{ count($services) }}</div>
                        <div class="seller-stat-label">Active Services</div>
                    </div>

                    <div class="mb-3">
                        <div class="seller-stat-value">{{ user_total_job($user->id) }}</div>
                        <div class="seller-stat-label">Jobs Posted</div>
                    </div>

                    <div>
                        <div class="seller-stat-value">{{ total_attend_work($user->id) }}</div>
                        <div class="seller-stat-label">Work Participated</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="seller-services-card">
                <div class="card-body">
                    <div class="seller-section-title">Active Services</div>

                    <div class="row">
                        @forelse($services as $service)
                            <div class="col-md-6 mb-4">
                                <div class="service-card">
                                    <img src="{{ wu_service_image($service->image) }}" alt="{{ $service->title }}">

                                    <div class="service-card-body">
                                        <div class="service-title">{{ $service->title }}</div>

                                        <div class="service-text">
                                            {{ \Illuminate\Support\Str::limit($service->short_description ?: $service->description, 110) }}
                                        </div>

                                        <div class="service-price">${{ number_format($service->price, 2) }}</div>

                                        <a href="{{ route('marketplace.service.show', $service->slug) }}" class="service-btn">
                                            View Service
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info mb-0">No active services found.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection