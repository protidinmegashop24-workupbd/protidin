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

    .wu-blog-details-meta {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
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

    /* Sidebar (shared widget look with blog-index) */
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

    @media (max-width: 767px) {
        .wu-blog-details-title { font-size: 1.5rem; }
        .wu-blog-details-card { padding: 20px 18px; }
    }
</style>
@endsection

@section('front-content')
<section class="wu-blog-details-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-12">
                <a href="{{ route('blog.index') }}" class="wu-blog-details-back"><i class="fa fa-arrow-left"></i> সব ব্লগ পোস্ট দেখুন</a>

                @if($blog->feature_image)
                    <img src="{{ URL::to($blog->feature_image) }}" class="wu-blog-details-hero" alt="{{ $blog->title }}">
                @endif

                <div class="wu-blog-details-meta">
                    <span><i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($blog->news_date)->format('d M Y') }}</span>
                    <span><i class="fa fa-clock-o"></i> {{ max(1, ceil(str_word_count(strip_tags($blog->details)) / 200)) }} মিনিট পড়ার সময়</span>
                </div>
                <h1 class="wu-blog-details-title">{{ $blog->title }}</h1>

                <div class="wu-blog-details-card">
                    {!! $blog->details !!}
                </div>
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
