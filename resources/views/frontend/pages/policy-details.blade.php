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

    body,
    h1, h2, h3, h4, h5, h6,
    p, a, li, span, div {
        font-family: 'Hind Siliguri', sans-serif !important;
    }

    .wu-policy-section {
        padding: 60px 0 80px;
        background: linear-gradient(135deg, #ecfeff 0%, #f8fafc 50%, #ffffff 100%);
        min-height: 70vh;
    }

    .wu-policy-wrap {
        max-width: 950px;
        margin: 0 auto;
    }

    .wu-policy-badge {
        display: inline-block;
        background: #ccfbf1;
        color: var(--wu-primary-dark);
        font-size: 14px;
        font-weight: 700;
        padding: 8px 14px;
        border-radius: 999px;
        margin-bottom: 14px;
    }

    .wu-policy-header {
        text-align: center;
        margin-bottom: 28px;
    }

    .wu-policy-title {
        font-size: 2.4rem;
        line-height: 1.2;
        font-weight: 800;
        color: var(--wu-text);
        margin-bottom: 12px;
    }

    .wu-policy-subtitle {
        color: var(--wu-muted);
        font-size: 15px;
        line-height: 1.9;
        max-width: 760px;
        margin: 0 auto;
    }

    .wu-policy-card {
        background: var(--wu-white);
        border: 1px solid var(--wu-border);
        border-radius: 24px;
        box-shadow: var(--wu-shadow);
        overflow: hidden;
    }

    .wu-policy-card-header {
        padding: 22px 26px;
        background: #f8fafc;
        border-bottom: 1px solid var(--wu-border);
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--wu-text);
    }

    .wu-policy-card-body {
        padding: 28px 26px;
        color: var(--wu-muted);
        line-height: 1.95;
        font-size: 15px;
    }

    .wu-policy-card-body h1,
    .wu-policy-card-body h2,
    .wu-policy-card-body h3,
    .wu-policy-card-body h4,
    .wu-policy-card-body h5,
    .wu-policy-card-body h6 {
        color: var(--wu-text);
        font-weight: 800;
        margin-top: 22px;
        margin-bottom: 12px;
    }

    .wu-policy-card-body p {
        margin-bottom: 14px;
    }

    .wu-policy-card-body ul,
    .wu-policy-card-body ol {
        padding-left: 20px;
        margin-bottom: 16px;
    }

    .wu-policy-card-body a {
        color: var(--wu-primary);
        text-decoration: none;
    }

    .wu-policy-card-body a:hover {
        text-decoration: underline;
    }

    @media (max-width: 767px) {
        .wu-policy-section {
            padding: 45px 0 55px;
        }

        .wu-policy-title {
            font-size: 1.8rem;
        }

        .wu-policy-card-header,
        .wu-policy-card-body {
            padding: 20px 18px;
        }
    }
</style>
@endsection

@section('front-content')
<section class="wu-policy-section">
    <div class="container">
        <div class="wu-policy-wrap">
            <div class="wu-policy-header">
                <span class="wu-policy-badge">Policy Information</span>
                <h1 class="wu-policy-title">{{ $policy->title }}</h1>
                <p class="wu-policy-subtitle">
                    Please review this policy carefully to understand the terms, conditions, and important platform information related to Protidin Mega Earn.
                </p>
            </div>

            <div class="wu-policy-card">
                <div class="wu-policy-card-header">
                    {{ $policy->title }}
                </div>
                <div class="wu-policy-card-body">
                    {!! $policy->details !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
@endsection