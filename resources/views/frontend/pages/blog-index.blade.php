@extends('frontend.layouts.master')

@section('css')
<style>
    :root {
        --wu-primary: #0f766e;
        --wu-primary-dark: #115e59;
        --wu-light: #f8fafc;
        --wu-border: #dbe4ea;
        --wu-text: #0f172a;
        --wu-muted: #64748b;
        --wu-white: #ffffff;
        --wu-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
    }

    body, h1, h2, h3, h4, h5, h6, p, a, li, span, div {
        font-family: 'Hind Siliguri', sans-serif !important;
    }

    .wu-blog-section {
        padding: 60px 0 80px;
        background: linear-gradient(135deg, #ecfeff 0%, #f8fafc 50%, #ffffff 100%);
        min-height: 70vh;
    }

    .wu-blog-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .wu-blog-badge {
        display: inline-block;
        background: #ccfbf1;
        color: var(--wu-primary-dark);
        font-size: 14px;
        font-weight: 700;
        padding: 8px 14px;
        border-radius: 999px;
        margin-bottom: 14px;
    }

    .wu-blog-title {
        font-size: 2.3rem;
        font-weight: 800;
        color: var(--wu-text);
        margin-bottom: 12px;
    }

    .wu-blog-subtitle {
        color: var(--wu-muted);
        font-size: 15px;
        max-width: 700px;
        margin: 0 auto;
    }

    .wu-blog-card {
        background: var(--wu-white);
        border: 1px solid var(--wu-border);
        border-radius: 18px;
        box-shadow: var(--wu-shadow);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: transform .2s ease;
    }

    .wu-blog-card:hover {
        transform: translateY(-4px);
    }

    .wu-blog-card-img {
        width: 100%;
        height: 190px;
        object-fit: cover;
        background: #e2e8f0;
    }

    .wu-blog-card-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .wu-blog-card-date {
        font-size: 12px;
        color: var(--wu-muted);
        margin-bottom: 8px;
    }

    .wu-blog-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--wu-text);
        margin-bottom: 10px;
        flex: 1;
    }

    .wu-blog-card-excerpt {
        font-size: 14px;
        color: var(--wu-muted);
        line-height: 1.7;
        margin-bottom: 14px;
    }

    .wu-blog-read-more {
        color: var(--wu-primary);
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
    }

    .wu-blog-read-more:hover {
        text-decoration: underline;
        color: var(--wu-primary-dark);
    }

    .wu-blog-empty {
        text-align: center;
        padding: 60px 20px;
        color: var(--wu-muted);
    }

    /* Sidebar */
    .wu-blog-sidebar {
        position: sticky;
        top: 20px;
    }

    .wu-blog-widget {
        background: var(--wu-white);
        border: 1px solid var(--wu-border);
        border-radius: 16px;
        box-shadow: var(--wu-shadow);
        padding: 18px;
        margin-bottom: 22px;
    }

    .wu-blog-ad-widget {
        text-align: center;
        overflow: hidden;
        padding: 12px;
    }

    .wu-blog-widget-title {
        font-size: 1rem;
        font-weight: 800;
        color: var(--wu-text);
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--wu-border);
    }

    .wu-blog-widget-item {
        display: flex;
        gap: 12px;
        text-decoration: none;
        margin-bottom: 14px;
        align-items: center;
    }

    .wu-blog-widget-item:last-child {
        margin-bottom: 0;
    }

    .wu-blog-widget-img {
        width: 60px;
        height: 48px;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0;
        background: #e2e8f0;
    }

    .wu-blog-widget-item-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--wu-text);
        line-height: 1.4;
    }

    .wu-blog-widget-item-date {
        font-size: 11px;
        color: var(--wu-muted);
        margin-top: 4px;
    }

    .wu-blog-cta-widget {
        background: linear-gradient(135deg, var(--wu-primary) 0%, var(--wu-primary-dark) 100%);
        color: #fff;
    }

    .wu-blog-cta-title {
        font-size: 1.05rem;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .wu-blog-cta-text {
        font-size: 13px;
        opacity: .9;
        line-height: 1.7;
        margin-bottom: 14px;
    }

    .wu-blog-cta-btn {
        display: inline-block;
        background: #fff;
        color: var(--wu-primary-dark);
        font-weight: 700;
        font-size: 13px;
        padding: 10px 18px;
        border-radius: 999px;
        text-decoration: none;
    }

    .wu-blog-cta-btn:hover {
        opacity: .9;
        color: var(--wu-primary-dark);
    }

    @media (max-width: 991px) {
        .wu-blog-sidebar {
            position: static;
            margin-top: 30px;
        }
    }
</style>
@endsection

@section('front-content')
<section class="wu-blog-section">
    <div class="container">
        <div class="wu-blog-header">
            <span class="wu-blog-badge">Protidin Mega Earn Blog</span>
            <h1 class="wu-blog-title">আমাদের ব্লগ</h1>
            <p class="wu-blog-subtitle">আয়, মার্কেটপ্লেস, PTC এবং প্ল্যাটফর্ম সংক্রান্ত সব আপডেট ও গাইড এখানে পাবেন।</p>
        </div>

        <div class="row">
            <div class="col-lg-8 col-12">
                @if($blogs->count() > 0)
                    <div class="row">
                        @foreach($blogs as $blog)
                            <div class="col-md-6 col-12 mb-4">
                                <a href="{{ route('blog.details', $blog->slug) }}" class="text-decoration-none">
                                    <div class="wu-blog-card">
                                        @if($blog->feature_image)
                                            <img src="{{ URL::to($blog->feature_image) }}" class="wu-blog-card-img" alt="{{ $blog->title }}">
                                        @else
                                            <img src="{{ asset('uploads/site-assets/home-hero.png') }}" class="wu-blog-card-img" alt="{{ $blog->title }}">
                                        @endif
                                        <div class="wu-blog-card-body">
                                            <div class="wu-blog-card-date">
                                                <i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($blog->news_date)->format('d M Y') }}
                                            </div>
                                            <div class="wu-blog-card-title">{{ $blog->title }}</div>
                                            <div class="wu-blog-card-excerpt">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($blog->details), 100) }}
                                            </div>
                                            <span class="wu-blog-read-more">আরও পড়ুন &rarr;</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $blogs->links() }}
                    </div>
                @else
                    <div class="wu-blog-empty">
                        <h4>এখনো কোনো ব্লগ পোস্ট নেই</h4>
                        <p>শীঘ্রই নতুন পোস্ট আসছে।</p>
                    </div>
                @endif
            </div>

            <div class="col-lg-4 col-12">
                <div class="wu-blog-sidebar">
                    @include('frontend.pages.partials.blog-sidebar')
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
