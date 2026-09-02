@extends('frontend.layouts.master')

@section('css')
<style>
    .otp-page {
        padding: 70px 0;
        background: linear-gradient(135deg, #f8fbff 0%, #eef7ff 100%);
        min-height: 100vh;
    }
    .otp-wrap {
        max-width: 1100px;
        margin: 0 auto;
    }
    .otp-left {
        padding: 20px 30px 20px 0;
    }
    .otp-badge {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 999px;
        background: #dcfce7;
        color: #166534;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 18px;
    }
    .otp-title {
        font-size: 2.8rem;
        line-height: 1.15;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 16px;
    }
    .otp-text {
        color: #64748b;
        font-size: 16px;
        line-height: 1.9;
        max-width: 520px;
    }
    .otp-card {
        background: #fff;
        border: 1px solid #dbe7ef;
        border-radius: 22px;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
        padding: 34px 28px;
    }
    .otp-card-title {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
        text-align: center;
    }
    .otp-card-sub {
        text-align: center;
        color: #64748b;
        font-size: 14px;
        margin-bottom: 24px;
        line-height: 1.8;
    }
    .otp-label {
        display: block;
        font-weight: 700;
        font-size: 14px;
        color: #0f172a;
        margin-bottom: 8px;
    }
    .otp-input {
        width: 100%;
        height: 52px;
        border: 1px solid #dbe7ef;
        border-radius: 12px;
        padding: 0 16px;
        font-size: 15px;
        color: #0f172a;
        margin-bottom: 16px;
    }
    .otp-input:focus {
        outline: none;
        border-color: #16a34a;
        box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.08);
    }
    .otp-btn {
        width: 100%;
        height: 52px;
        border: none;
        border-radius: 12px;
        background: #16a34a;
        color: #fff;
        font-size: 16px;
        font-weight: 800;
    }
    .otp-btn:hover {
        background: #15803d;
    }
    @media (max-width: 991px) {
        .otp-left {
            padding: 0 0 25px 0;
        }
        .otp-title {
            font-size: 2.1rem;
        }
    }
</style>
@endsection

@section('front-content')
<section class="otp-page">
    <div class="container">
        <div class="row align-items-center otp-wrap">
            <div class="col-lg-6">
                <div class="otp-left">
                    <span class="otp-badge">Email Verification</span>
                    <h1 class="otp-title">Verify your account with OTP</h1>
                    <p class="otp-text">
                        We sent a 6-digit verification code to your email address. Enter the OTP below to activate your Workup BD account and continue using the platform securely.
                    </p>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="otp-card">
                    <h2 class="otp-card-title">Verify OTP</h2>
                    <p class="otp-card-sub">
                        Enter the one-time password sent to your email address.
                    </p>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('otp.verify.submit') }}" method="POST">
                        @csrf

                        <label class="otp-label">Email Address</label>
                        <input type="email" name="email" class="otp-input" value="{{ $email ?? request()->email }}" readonly>

                        <label class="otp-label">OTP Code</label>
                        <input type="text" name="otp" class="otp-input" placeholder="Enter 6-digit OTP" required>

                        <button type="submit" class="otp-btn">Verify My Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection