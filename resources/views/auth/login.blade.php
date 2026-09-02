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
        --wu-danger: #dc2626;
        --wu-success: #16a34a;
    }

    body,
    label,
    input,
    h1, h2, h3, h4, h5, h6,
    p,
    a,
    span,
    div {
        font-family: 'Hind Siliguri', sans-serif !important;
    }

    .wu-login-section {
        padding: 70px 0;
        background: linear-gradient(135deg, #ecfeff 0%, #f8fafc 50%, #ffffff 100%);
        min-height: 100vh;
    }

    .wu-login-wrap {
        max-width: 1100px;
        margin: 0 auto;
    }

    .wu-login-left {
        padding: 20px 25px 20px 0;
    }

    .wu-login-badge {
        display: inline-block;
        background: #ccfbf1;
        color: var(--wu-primary-dark);
        font-size: 14px;
        font-weight: 700;
        padding: 8px 14px;
        border-radius: 999px;
        margin-bottom: 18px;
    }

    .wu-login-title {
        font-size: 2.7rem;
        line-height: 1.15;
        font-weight: 800;
        color: var(--wu-text);
        margin-bottom: 16px;
    }

    .wu-login-title span {
        color: var(--wu-primary);
    }

    .wu-login-text {
        color: var(--wu-muted);
        font-size: 16px;
        line-height: 1.9;
        margin-bottom: 24px;
        max-width: 520px;
    }

    .wu-login-points {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .wu-login-points li {
        margin-bottom: 12px;
        color: var(--wu-text);
        font-weight: 600;
        font-size: 15px;
    }

    .wu-login-points i {
        color: var(--wu-primary);
        margin-right: 10px;
    }

    .wu-login-info {
        background: rgba(255,255,255,0.65);
        border: 1px solid rgba(15,118,110,0.10);
        border-radius: 18px;
        padding: 18px;
        margin-top: 28px;
        max-width: 520px;
    }

    .wu-login-info h4 {
        font-size: 18px;
        font-weight: 800;
        color: var(--wu-text);
        margin-bottom: 10px;
    }

    .wu-login-info p {
        font-size: 14px;
        color: var(--wu-muted);
        line-height: 1.8;
        margin: 0;
    }

    .wu-login-card {
        background: var(--wu-white);
        border: 1px solid var(--wu-border);
        border-radius: 24px;
        box-shadow: var(--wu-shadow);
        padding: 34px 28px;
    }

    .wu-login-logo {
        text-align: center;
        margin-bottom: 16px;
    }

    .wu-login-logo img {
        width: 90px;
        height: auto;
    }

    .wu-form-title {
        text-align: center;
        font-size: 1.9rem;
        font-weight: 800;
        color: var(--wu-text);
        margin-bottom: 8px;
    }

    .wu-form-subtitle {
        text-align: center;
        color: var(--wu-muted);
        font-size: 14px;
        margin-bottom: 26px;
        line-height: 1.8;
    }

    .wu-form-group {
        margin-bottom: 16px;
        position: relative;
    }

    .wu-form-label {
        display: block;
        font-weight: 700;
        font-size: 14px;
        color: var(--wu-text);
        margin-bottom: 8px;
    }

    .wu-form-control {
        width: 100%;
        height: 50px;
        border: 1px solid var(--wu-border);
        border-radius: 12px;
        padding: 0 16px;
        font-size: 15px;
        color: var(--wu-text);
        background: #fff;
        transition: all 0.3s ease;
    }

    .wu-form-control:focus {
        outline: none;
        border-color: var(--wu-primary);
        box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.08);
    }

    .wu-password-toggle {
        position: absolute;
        right: 16px;
        top: 42px;
        cursor: pointer;
        font-size: 17px;
        color: #64748b;
    }

    .wu-submit-btn {
        width: 100%;
        height: 52px;
        border: none;
        border-radius: 12px;
        background: var(--wu-primary);
        color: #fff;
        font-weight: 700;
        font-size: 16px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .wu-submit-btn:hover {
        background: var(--wu-primary-dark);
        transform: translateY(-1px);
    }

    .wu-alert {
        padding: 12px 14px;
        border-radius: 12px;
        font-size: 14px;
        margin-bottom: 16px;
    }

    .wu-alert-danger {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
    }

    .wu-alert-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .wu-remember-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-top: 6px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .wu-remember-row label {
        margin: 0;
        font-size: 14px;
        color: var(--wu-text);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }

    .wu-remember-row a {
        color: var(--wu-primary);
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
    }

    .wu-remember-row a:hover {
        text-decoration: underline;
    }

    .wu-signup-text {
        text-align: center;
        margin-top: 22px;
        color: var(--wu-muted);
        font-size: 15px;
    }

    .wu-signup-text a {
        color: var(--wu-primary);
        font-weight: 700;
        text-decoration: none;
    }

    .wu-signup-text a:hover {
        text-decoration: underline;
    }

    .wu-note {
        margin-top: 18px;
        text-align: center;
        color: var(--wu-muted);
        font-size: 13px;
        line-height: 1.8;
    }

    #captcha_code_area {
        -webkit-user-select: none;
        -webkit-touch-callout: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        border: solid 1px #f31313;
        padding: 6px 34px;
        margin-top: 30px;
        opacity: 0.7;
        letter-spacing: 5px;
        border-radius: 10px;
    }

    #contact-form {
        position: relative;
        z-index: 999999999999999;
    }

    @media (max-width: 991px) {
        .wu-login-left {
            padding: 0 0 30px 0;
        }

        .wu-login-title {
            font-size: 2.2rem;
        }
    }

    @media (max-width: 767px) {
        .wu-login-section {
            padding: 45px 0;
        }

        .wu-login-card {
            padding: 26px 18px;
            border-radius: 18px;
        }

        .wu-login-title {
            font-size: 1.8rem;
        }

        .g-recaptcha {
            transform: scale(0.90);
            transform-origin: 0 0;
        }
    }
</style>

@php
    $website = DB::table('websites')->latest()->first();
    $p_categorys = DB::table('categories')->latest()->get();
@endphp
@endsection

@section('front-content')

<section class="wu-login-section">
    <div class="container">
        <div class="row align-items-center wu-login-wrap">

            <div class="col-lg-6">
                <div class="wu-login-left">
                    <span class="wu-login-badge">Welcome Back</span>

                    <h1 class="wu-login-title">
                        Login to <span>{{ site_info()->title }}</span>
                    </h1>

                    <p class="wu-login-text">
                        Sign in to access your account, explore available tasks, manage your activity, and continue using the features of the Workup BD platform in a simple and organized way.
                    </p>

                    <ul class="wu-login-points">
                        <li><i class="fas fa-check-circle"></i>Access your account dashboard</li>
                        <li><i class="fas fa-check-circle"></i>Continue tasks and platform activities</li>
                        <li><i class="fas fa-check-circle"></i>Manage your account securely</li>
                        <li><i class="fas fa-check-circle"></i>Stay connected with Workup BD features</li>
                    </ul>

                    <div class="wu-login-info">
                        <h4>Secure account access</h4>
                        <p>
                            Use your registered email address or phone and password to access your account safely and continue your activity on the platform.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="wu-login-card">
                    <div class="wu-login-logo">
                        <a href="{{ url('/') }}">
                            <img src="{{ URL::to(website_logo()) }}" alt="Workup BD Logo">
                        </a>
                    </div>

                    <h2 class="wu-form-title">{{ about_us()->login_form_title }}</h2>
                    <p class="wu-form-subtitle">
                        Enter your login details below to access your Workup BD account.
                    </p>

                    <form action="{{ route('login') }}" id="contact-form" method="post">
                        @csrf

                        @if(session()->get('login_erro') != '')
                            <div class="wu-alert wu-alert-danger">
                                {{ session()->get('login_erro') }}
                            </div>
                        @endif

                        @if(session()->get('mail_sent') != '')
                            <div class="wu-alert wu-alert-success">
                                {{ session()->get('mail_sent') }}
                            </div>
                        @endif

                        <div class="wu-form-group">
                            <label for="login_email" class="wu-form-label">Email Address / Phone</label>
                            <input type="text" placeholder="Enter your email or phone" name="phone" id="login_email" class="wu-form-control" />
                            <span class="my_error" style="display: none; color: red;">Please Enter Valid Email</span>
                            <div class="error"></div>
                        </div>

                        <div class="wu-form-group">
                            <label for="login_password" class="wu-form-label">Password</label>
                            <input type="password" placeholder="Enter your password" name="password" id="login_password" class="wu-form-control" style="padding-right: 46px;" />
                            <span class="my_error" style="display: none; color: red;">Please Enter 3 digit</span>
                            <i class="far fa-eye wu-password-toggle" id="togglePassword"></i>
                            <div class="error"></div>
                        </div>

                        @if ($errors->has('g-recaptcha-response'))
                            <span class="text-danger d-block mb-2">{{ $errors->first('g-recaptcha-response') }}</span>
                        @endif

                        @if(site_info()->captcha_verify_active == 1)
                            <div class="form-group mb-3">
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                            </div>
                        @endif

                        <div class="form_field" style="display:none;">
                            <div class="captcha">
                                <span id="captcha_code_area"></span>
                                <input type="hidden" id="captcha_code">
                                <button type="button" class="btn btn-sm btn-danger reload" id="reload">
                                    &#x21bb;
                                </button>
                            </div>
                            <input type="text" placeholder="Enter Code" id="entered_captcha_code" onkeyup="changeCaptchaCode()" />
                            <small class="text-danger" id="captcha-error"></small>
                        </div>

                        <div class="wu-remember-row">
                            <label>
                                <input type="checkbox" checked="checked" name="remember"> Remember me
                            </label>

                            <a href="{{ route('user-foreget-password') }}">
                                Forgot password?
                            </a>
                        </div>

                        <button type="submit" name="login_do" id="login_do" class="wu-submit-btn">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login Your Account</span>
                        </button>

                        <p class="wu-signup-text">
                            If you don't have an account?
                            <a href="{{ route('register') }}">Create an account</a>
                        </p>

                        <p class="wu-note">
                            Please use your correct login information to access your account securely.
                        </p>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script>
    $(window).on('load', function() {
        $.ajax({
            type: 'GET',
            url: 'reload-captcha',
            success: function(data) {
                $('.captcha span').html(data.captcha);
                $('#captcha_code').val(data.captcha);
                $('#captcha-error').html('');
                $('#entered_captcha_code').val('');
            }
        });
    });

    $('#reload').click(function() {
        $.ajax({
            type: 'GET',
            url: 'reload-captcha',
            success: function(data) {
                $('.captcha span').html(data.captcha);
                $('#captcha_code').val(data.captcha);
                $('#captcha-error').html('');
                $('#login_do').attr('disabled', true);
                $('#entered_captcha_code').val('');
            }
        });
    });

    function changeCaptchaCode() {
        var captcha_code = $('#captcha_code').val();
        var code = $('#entered_captcha_code').val();

        if (captcha_code === code) {
            $('#captcha-error').html('');
            $('#login_do').attr('disabled', false);
        } else {
            $('#captcha-error').html('Captcha not matched! Try again!');
            $('#login_do').attr('disabled', true);
        }
    }
</script>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#login_password');

    if (togglePassword && password) {
        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    }
</script>

@endsection