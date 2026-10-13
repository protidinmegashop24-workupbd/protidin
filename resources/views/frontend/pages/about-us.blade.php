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
        --wu-dark: #0f172a;
    }

    body,
    h1, h2, h3, h4, h5, h6,
    p, a, li, span, div {
        font-family: 'Hind Siliguri', sans-serif !important;
    }

    .wu-about-hero {
        padding: 70px 0 45px;
        background: linear-gradient(135deg, #ecfeff 0%, #f8fafc 50%, #ffffff 100%);
        text-align: center;
    }

    .wu-about-badge {
        display: inline-block;
        background: #ccfbf1;
        color: var(--wu-primary-dark);
        font-size: 14px;
        font-weight: 700;
        padding: 8px 14px;
        border-radius: 999px;
        margin-bottom: 16px;
    }

    .wu-about-title {
        font-size: 3rem;
        line-height: 1.15;
        font-weight: 800;
        color: var(--wu-text);
        margin-bottom: 14px;
    }

    .wu-about-text {
        max-width: 760px;
        margin: 0 auto;
        color: var(--wu-muted);
        font-size: 16px;
        line-height: 1.9;
    }

    .wu-about-main {
        padding: 70px 0;
        background: #ffffff;
    }

    .wu-about-card {
        background: #ffffff;
        border: 1px solid var(--wu-border);
        border-radius: 24px;
        box-shadow: var(--wu-shadow);
        padding: 34px 28px;
        height: 100%;
    }

    .wu-about-card h3 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--wu-text);
        margin-bottom: 18px;
    }

    .wu-about-card p {
        color: var(--wu-muted);
        line-height: 1.95;
        font-size: 15px;
        margin-bottom: 0;
    }

    .wu-about-card .global_btn,
    .wu-about-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-top: 22px;
        background: var(--wu-primary);
        color: #fff !important;
        border-radius: 12px;
        padding: 13px 24px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .wu-about-card .global_btn:hover,
    .wu-about-btn:hover {
        background: var(--wu-primary-dark);
        transform: translateY(-2px);
        text-decoration: none;
    }

    .wu-feature-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .wu-feature-box {
        background: #f8fafc;
        border: 1px solid var(--wu-border);
        border-radius: 20px;
        padding: 24px 18px;
        text-align: center;
        transition: all 0.3s ease;
        height: 100%;
    }

    .wu-feature-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 35px rgba(15, 23, 42, 0.08);
        background: #ffffff;
    }

    .wu-feature-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 16px;
        border-radius: 18px;
        background: #ecfeff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--wu-primary);
        font-size: 28px;
    }

    .wu-feature-box h4 {
        font-size: 18px;
        font-weight: 800;
        color: var(--wu-text);
        margin-bottom: 10px;
    }

    .wu-feature-box p {
        color: var(--wu-muted);
        font-size: 14px;
        line-height: 1.8;
        margin: 0;
    }

    .wu-counter-section {
        padding: 70px 0;
        background: var(--wu-dark);
    }

    .wu-counter-card {
        background: #ffffff;
        border-radius: 22px;
        padding: 28px 18px;
        text-align: center;
        height: 100%;
        box-shadow: 0 14px 35px rgba(0,0,0,0.08);
    }

    .wu-counter-card img {
        max-width: 56px;
        margin-bottom: 14px;
    }

    .wu-counter-card h2 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--wu-text);
        margin-bottom: 8px;
    }

    .wu-counter-card p {
        color: var(--wu-muted);
        margin: 0;
        font-weight: 600;
        line-height: 1.7;
    }

    .wu-about-cta {
        padding: 0 0 80px;
        background: #ffffff;
    }

    .wu-about-cta-box {
        background: linear-gradient(135deg, #0f766e 0%, #134e4a 100%);
        border-radius: 28px;
        padding: 45px 28px;
        text-align: center;
        color: #ffffff;
    }

    .wu-about-cta-box h3 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 14px;
        color: #ffffff;
    }

    .wu-about-cta-box p {
        max-width: 760px;
        margin: 0 auto 24px;
        color: #e6fffb;
        line-height: 1.9;
    }

    .wu-about-cta-box a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        color: var(--wu-primary) !important;
        border-radius: 12px;
        padding: 13px 24px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .wu-about-cta-box a:hover {
        background: #ecfeff;
        text-decoration: none;
        transform: translateY(-2px);
    }

    @media (max-width: 991px) {
        .wu-about-title {
            font-size: 2.3rem;
        }

        .wu-feature-grid {
            margin-top: 30px;
        }
    }

    @media (max-width: 767px) {
        .wu-about-hero {
            padding: 50px 0 35px;
        }

        .wu-about-title {
            font-size: 1.9rem;
        }

        .wu-about-main,
        .wu-counter-section {
            padding: 55px 0;
        }

        .wu-feature-grid {
            grid-template-columns: 1fr;
        }

        .wu-about-card {
            padding: 24px 18px;
            border-radius: 18px;
        }

        .wu-about-cta {
            padding-bottom: 55px;
        }

        .wu-about-cta-box {
            border-radius: 20px;
            padding: 34px 18px;
        }

        .wu-about-cta-box h3 {
            font-size: 1.6rem;
        }
    }
</style>
@endsection

@section('front-content')

<section class="wu-about-hero">
    <div class="container">
        <span class="wu-about-badge">About Protidin Mega Earn</span>
        <h1 class="wu-about-title">A Platform Built Around Opportunity, Structure, and Growth</h1>
        <p class="wu-about-text">
            Protidin Mega Earn is designed to create a more organized digital environment where users can explore micro jobs, freelance services, surveys, referrals, and community-based online activities through a modern and accessible platform.
        </p>
    </div>
</section>

<section class="wu-about-main">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <div class="wu-about-card">
                    <h3>Who We Are</h3>
                    <p>
    Protidin Mega Earn is a modern micro job and freelance services platform designed to create a structured and accessible online environment for users and businesses. Our goal is to simplify how people explore digital opportunities and connect with useful platform features.
</p>

<p>
    The platform brings together different types of online activities, including micro tasks, service-based opportunities, survey participation, and referral features. Each section is designed to be easy to understand, so users can navigate and engage without confusion.
</p>

<p>
    We focus on building a clean, user-friendly experience where individuals can manage their accounts, explore platform tools, and interact with different features in an organized way. Protidin Mega Earn continues to improve its system to make online activity more structured and accessible.
</p>

<p>
    Our mission is to provide a simple and practical digital platform where users can explore opportunities, build engagement, and stay connected with a growing online community.
</p>
                    <a href="{{ route('contact-us') }}" class="wu-about-btn">Contact Us</a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="wu-feature-grid">
                    <div class="wu-feature-box">
                        <div class="wu-feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4>Growing Community</h4>
                        <p>We aim to build a helpful online environment where users can stay active and explore platform opportunities.</p>
                    </div>

                    <div class="wu-feature-box">
                        <div class="wu-feature-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h4>Task & Service Focus</h4>
                        <p>From micro jobs to service-based support, the platform is built to organize different types of digital work activity.</p>
                    </div>

                    <div class="wu-feature-box">
                        <div class="wu-feature-icon">
                            <i class="fas fa-poll"></i>
                        </div>
                        <h4>Interactive Features</h4>
                        <p>Survey activities, referral features, and user tools help create a more engaging and structured experience.</p>
                    </div>

                    <div class="wu-feature-box">
                        <div class="wu-feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Trusted Platform Direction</h4>
                        <p>We focus on clear navigation, useful features, and a practical platform structure for users and businesses.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="wu-counter-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="wu-counter-card">
                    <img src="{{ URL::to($aboutus->total_job_icon) }}" alt="Total jobs" loading="lazy" />
                    <h2>
                        @if($aboutus->total_job_status == 1)
                            @if($aboutus->total_job_manual_show == 1)
                                {{ $aboutus->total_job }}
                            @else
                                {{ total_job() }}
                            @endif
                        @endif
                    </h2>
                    <p>{!! $aboutus->total_job_title !!}</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="wu-counter-card">
                    <img src="{{ URL::to($aboutus->total_user_icon) }}" alt="Total users" loading="lazy" />
                    <h2>
                        @if($aboutus->total_user_status == 1)
                            @if($aboutus->total_user_manual_show == 1)
                                {{ $aboutus->total_user }}
                            @else
                                {{ total_user() }}
                            @endif
                        @endif
                    </h2>
                    <p>{!! $aboutus->total_user_title !!}</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="wu-counter-card">
                    <img src="{{ URL::to($aboutus->totle_work_done_icon) }}" alt="Completed work" loading="lazy" />
                    <h2>
                        @if($aboutus->totle_work_done_status == 1)
                            @if($aboutus->totle_work_done_manual_show == 1)
                                {{ $aboutus->totle_work_done }}
                            @else
                                {{ totle_work_done() }}
                            @endif
                        @endif
                    </h2>
                    <p>{!! $aboutus->totle_work_done_title !!}</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="wu-counter-card">
                    <img src="{{ URL::to($aboutus->total_withdraw_icon) }}" alt="Total withdraw" loading="lazy" />
                    <h2>
                        @if($aboutus->total_withdraw_status == 1)
                            @if($aboutus->paid_tast_manual_show == 1)
                                {{ $aboutus->total_withdraw }}
                            @else
                                {{ total_withdraw() }}
                            @endif
                        @endif
                    </h2>
                    <p>{!! $aboutus->total_withdraw_title !!}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="wu-about-cta">
    <div class="container">
        <div class="wu-about-cta-box">
            <h3>Want to Learn More About Protidin Mega Earn?</h3>
            <p>
                Explore our platform, discover how it works, and connect with us if you want to know more about our services, support, and public-facing features.
            </p>
            <a href="{{ route('contact-us') }}">Get in Touch</a>
        </div>
    </div>
</section>

@endsection

@section('js')
@endsection