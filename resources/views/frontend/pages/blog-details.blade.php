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

    .wu-blog-details-section {
        padding: 50px 0 80px;
        background: linear-gradient(135deg, #ecfeff 0%, #f8fafc 50%, #ffffff 100%);
        min-height: 70vh;
    }

    .wu-blog-details-wrap {
        max-width: 900px;
        margin: 0 auto;
    }

    .wu-blog-details-back {
        display: inline-block;
        margin-bottom: 18px;
        color: var(--wu-primary);
        font-weight: 700;
        text-decoration: none;
        font-size: 14px;
    }

    .wu-blog-details-back:hover {
        text-decoration: underline;
    }

    .wu-blog-details-hero {
        width: 100%;
        max-height: 380px;
        object-fit: cover;
        border-radius: 20px;
        margin-bottom: 24px;
        box-shadow: var(--wu-shadow);
    }

    .wu-blog-details-date {
        color: var(--wu-muted);
        font-size: 13px;
        margin-bottom: 10px;
    }

    .wu-blog-details-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--wu-text);
        margin-bottom: 24px;
        line-height: 1.3;
    }

    .wu-blog-details-card {
        background: var(--wu-white);
        border: 1px solid var(--wu-border);
        border-radius: 24px;
        box-shadow: var(--wu-shadow);
        padding: 30px;
        color: var(--wu-muted);
        line-height: 1.95;
        font-size: 15px;
    }

    .wu-blog-details-card h1, .wu-blog-details-card h2, .wu-blog-details-card h3,
    .wu-blog-details-card h4, .wu-blog-details-card h5, .wu-blog-details-card h6 {
        color: var(--wu-text);
        font-weight: 800;
        margin-top: 22px;
        margin-bottom: 12px;
    }

    .wu-blog-details-card p { margin-bottom: 14px; }
    .wu-blog-details-card ul, .wu-blog-details-card ol { padding-left: 20px; margin-bottom: 16px; }
    .wu-blog-details-card img { max-width: 100%; height: auto; border-radius: 12px; }
    .wu-blog-details-card a { color: var(--wu-primary); }

    .wu-blog-recent-title {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--wu-text);
        margin: 40px 0 18px;
    }

    .wu-blog-recent-item {
        display: flex;
        gap: 14px;
        background: var(--wu-white);
        border: 1px solid var(--wu-border);
        border-radius: 14px;
        padding: 12px;
        margin-bottom: 12px;
        text-decoration: none;
        align-items: center;
    }

    .wu-blog-recent-item:hover {
        border-color: var(--wu-primary);
    }

    .wu-blog-recent-img {
        width: 70px;
        height: 55px;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0;
        background: #e2e8f0;
    }

    .wu-blog-recent-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--wu-text);
    }

    @media (max-width: 767px) {
        .wu-blog-details-title { font-size: 1.5rem; }
        .wu-blog-details-card { padding: 20px 18px; }
    }
</style>
@endsection

@section('front-content')
<section class="wu-blog-details-section">
    <div class="container">
        <div class="wu-blog-details-wrap">
            <a href="{{ route('blog.index') }}" class="wu-blog-details-back"><i class="fa fa-arrow-left"></i> সব ব্লগ পোস্ট দেখুন</a>

            @if($blog->feature_image)
                <img src="{{ URL::to($blog->feature_image) }}" class="wu-blog-details-hero" alt="{{ $blog->title }}">
            @endif

            <div class="wu-blog-details-date"><i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($blog->news_date)->format('d M Y') }}</div>
            <h1 class="wu-blog-details-title">{{ $blog->title }}</h1>

            <div class="wu-blog-details-card">
                {!! $blog->details !!}
            </div>

            @if($recentBlogs->count() > 0)
                <div class="wu-blog-recent-title">আরও পড়ুন</div>
                @foreach($recentBlogs as $recent)
                    <a href="{{ route('blog.details', $recent->slug) }}" class="wu-blog-recent-item">
                        @if($recent->feature_image)
                            <img src="{{ URL::to($recent->feature_image) }}" class="wu-blog-recent-img" alt="{{ $recent->title }}">
                        @else
                            <img src="{{ asset('uploads/site-assets/home-hero.png') }}" class="wu-blog-recent-img" alt="{{ $recent->title }}">
                        @endif
                        <span class="wu-blog-recent-name">{{ $recent->title }}</span>
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</section>
@endsection
