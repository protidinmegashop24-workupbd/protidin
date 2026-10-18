@extends('frontend.layouts.master')

@section('css')
    <style>
        .fj-heading {
            margin-bottom: 30px;
        }

        .fj-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 18px;
            margin-bottom: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: box-shadow 0.2s ease;
        }

        .fj-card:hover {
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        }

        .fj-title {
            font-size: 16px;
            font-weight: 700;
            color: #111;
            margin-bottom: 8px;
        }

        .fj-category {
            font-size: 12px;
            color: #22ab59;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .fj-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            font-size: 13px;
            color: #555;
        }

        .fj-price {
            font-size: 18px;
            font-weight: 700;
            color: #172b4d;
        }

        .fj-btn {
            display: block;
            text-align: center;
            background: #46d08b;
            color: #fff !important;
            border-radius: 8px;
            padding: 10px;
            font-weight: 600;
            text-decoration: none;
        }

        .fj-btn:hover {
            background: #198754;
            color: #fff !important;
        }

        .fj-guest-banner {
            background: #f4fff8;
            border: 1px solid #22ab59;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
        }

        .fj-guest-banner a {
            display: inline-block;
            margin: 6px;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
        }

        .fj-guest-banner .btn-register {
            background: #46d08b;
            color: #fff !important;
        }

        .fj-guest-banner .btn-login {
            background: #fff;
            color: #172b4d !important;
            border: 1px solid #172b4d;
        }
    </style>
@endsection

@section('front-content')
<div class="blog-area default-padding bottom-less bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="site-heading text-center fj-heading">
                    <h2>Find Job</h2>
                    <p>যেসব কাজ এখন করা যাচ্ছে তার লিস্ট -- কাজ করে আয় শুরু করতে লগইন/রেজিস্ট্রেশন লাগবে।</p>
                </div>
            </div>
        </div>

        @guest
            <div class="fj-guest-banner">
                <strong>কাজ করে আয় করতে অ্যাকাউন্ট লাগবে।</strong>
                <div>
                    <a href="{{ route('register') }}" class="btn-register">Register</a>
                    <a href="{{ route('login') }}" class="btn-login">Login</a>
                </div>
            </div>
        @endguest

        <div class="row">
            @forelse ($jobs as $job)
                @php
                    $category = $categorys->firstWhere('id', $job->category_id);
                    $spotsLeft = max(0, (int) $job->worker_need - (int) $job->worker_confirmed);
                @endphp
                <div class="col-md-4 col-sm-6">
                    <div class="fj-card">
                        <div>
                            <div class="fj-title">{{ $job->title }}</div>
                            @if($category)
                                <div class="fj-category">{{ $category->name }}</div>
                            @endif
                            <div class="fj-meta">
                                <span>{{ $spotsLeft }} স্পট বাকি</span>
                                <span>মোট {{ $job->worker_need }} জন</span>
                            </div>
                        </div>
                        <div>
                            <div class="fj-price">${{ number_format((float) $job->each_worker_earn, 4, '.', '') }} <small style="font-size:12px; font-weight:400;">/ কাজ</small></div>
                            @auth
                                <a href="{{ route('job-details', $job->code) }}" class="fj-btn">Apply Now</a>
                            @else
                                <a href="{{ route('login') }}" class="fj-btn">Login to Apply</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-md-12 text-center">
                    <p>এই মুহূর্তে কোনো কাজ খালি নেই। পরে আবার দেখো।</p>
                </div>
            @endforelse
        </div>

        {{ $jobs->links() }}
    </div>
</div>
@endsection

@section('js')
@endsection
