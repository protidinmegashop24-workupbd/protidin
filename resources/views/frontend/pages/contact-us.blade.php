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

    .wu-contact-hero {
        padding: 70px 0 45px;
        background: linear-gradient(135deg, #ecfeff 0%, #f8fafc 50%, #ffffff 100%);
        text-align: center;
    }

    .wu-contact-badge {
        display: inline-block;
        background: #ccfbf1;
        color: var(--wu-primary-dark);
        font-size: 14px;
        font-weight: 700;
        padding: 8px 14px;
        border-radius: 999px;
        margin-bottom: 16px;
    }

    .wu-contact-title {
        font-size: 3rem;
        line-height: 1.15;
        font-weight: 800;
        color: var(--wu-text);
        margin-bottom: 14px;
    }

    .wu-contact-text {
        max-width: 760px;
        margin: 0 auto;
        color: var(--wu-muted);
        font-size: 16px;
        line-height: 1.9;
    }

    .wu-contact-main {
        padding: 70px 0 80px;
        background: #ffffff;
    }

    .wu-contact-card {
        background: #ffffff;
        border: 1px solid var(--wu-border);
        border-radius: 26px;
        box-shadow: var(--wu-shadow);
        overflow: hidden;
    }

    .wu-contact-left {
        padding: 38px 32px;
        background: #f8fafc;
        height: 100%;
    }

    .wu-contact-left h3 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--wu-text);
        margin-bottom: 14px;
    }

    .wu-contact-left p {
        color: var(--wu-muted);
        line-height: 1.9;
        margin-bottom: 28px;
        font-size: 15px;
    }

    .wu-contact-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .wu-contact-list li {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 20px;
    }

    .wu-contact-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #ecfeff;
        color: var(--wu-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .wu-contact-list h5 {
        font-size: 17px;
        font-weight: 800;
        color: var(--wu-text);
        margin-bottom: 6px;
    }

    .wu-contact-list p,
    .wu-contact-list a {
        margin: 0;
        color: var(--wu-muted);
        line-height: 1.8;
        text-decoration: none;
    }

    .wu-contact-list a:hover {
        color: var(--wu-primary);
        text-decoration: underline;
    }

    .wu-contact-right {
        padding: 38px 32px;
        text-align: center;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #0f766e 0%, #134e4a 100%);
        color: #ffffff;
    }

    .wu-contact-support h3 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 14px;
        color: #ffffff;
    }

    .wu-contact-support p {
        color: #e6fffb;
        line-height: 1.9;
        margin-bottom: 24px;
        font-size: 15px;
    }

    .wu-contact-email {
        display: inline-block;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.15);
        padding: 14px 22px;
        border-radius: 14px;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 16px;
        text-decoration: none;
        margin-bottom: 18px;
        word-break: break-word;
    }

    .wu-contact-email:hover {
        background: rgba(255,255,255,0.18);
        text-decoration: none;
    }

    .wu-contact-btn {
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

    .wu-contact-btn:hover {
        background: #ecfeff;
        text-decoration: none;
        transform: translateY(-2px);
    }

    @media (max-width: 991px) {
        .wu-contact-title {
            font-size: 2.3rem;
        }

        .wu-contact-left,
        .wu-contact-right {
            padding: 28px 20px;
        }
    }

    @media (max-width: 767px) {
        .wu-contact-hero {
            padding: 50px 0 35px;
        }

        .wu-contact-title {
            font-size: 1.9rem;
        }

        .wu-contact-main {
            padding: 55px 0;
        }

        .wu-contact-left h3,
        .wu-contact-support h3 {
            font-size: 1.6rem;
        }
    }
</style>
@endsection

@section('front-content')

<section class="wu-contact-hero">
    <div class="container">
        <span class="wu-contact-badge">Contact Protidin Mega Earn</span>
        <h1 class="wu-contact-title">We’re Here to Help</h1>
        <p class="wu-contact-text">
            If you have questions about Protidin Mega Earn, need assistance, or want to learn more about our platform, you can contact us anytime through the available support channels below.
        </p>
    </div>
</section>

<section class="wu-contact-main">
    <div class="container">
        <div class="wu-contact-card">
            <div class="row g-0">

                <div class="col-lg-6">
                    <div class="wu-contact-left">
                        <h3>Get in Touch</h3>
                        <p>
                            Protidin Mega Earn aims to provide a simple and accessible platform experience. If you need help with your account, platform access, support, or public information, feel free to reach out.
                        </p>

                        <ul class="wu-contact-list">
                            <li>
                                <div class="wu-contact-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <h5>Email Support</h5>
                                    <a href="mailto:{{ website_info()->email }}">{{ website_info()->email }}</a>
                                </div>
                            </li>

                            <li>
                                <div class="wu-contact-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h5>Support Availability</h5>
                                    <p>We aim to respond to support requests as quickly as possible.</p>
                                </div>
                            </li>

                            <li>
                                <div class="wu-contact-icon">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <div>
                                    <h5>Platform Access</h5>
                                    <p>Users can explore Protidin Mega Earn through our public pages and account features.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="wu-contact-right">
                        <div class="wu-contact-support">
                            <h3>Mail Support</h3>
                            <p>
                                For general questions, account-related concerns, or platform support, please use our official support email.
                            </p>

                            <a href="mailto:{{ website_info()->email }}" class="wu-contact-email">
                                {{ website_info()->email }}
                            </a>

                            <br>

                            <a href="mailto:{{ website_info()->email }}" class="wu-contact-btn">
                                Contact Support
                            </a>
                            
                            <div class="card mt-4 shadow-sm" style="border-radius:18px; border:1px solid #e5e7eb;">
    <div class="card-body p-4">
        <h3 style="font-weight:800;">Contact Support</h3>
        <p style="color:#64748b;">Send us your question, report, or support request. Our team will review your message.</p>

        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('contact_message.send') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label>Your Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
            </div>

            <div class="form-group mb-3">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>

            <div class="form-group mb-3">
                <label>Subject</label>
                <input type="text" name="subject" class="form-control" placeholder="Message subject" required>
            </div>

            <div class="form-group mb-3">
                <label>Message</label>
                <textarea name="message" class="form-control" rows="5" placeholder="Write your message..." required></textarea>
            </div>

            <button type="submit" class="btn btn-success" style="border-radius:10px; font-weight:700;">
                Send Message
            </button>
        </form>
    </div>
</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection

@section('js')
@endsection