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
        }

        body,
        label,
        input,
        select,
        h1, h2, h3, h4, h5, h6,
        p,
        a,
        span,
        div {
            font-family: 'Hind Siliguri', sans-serif !important;
        }

        .wu-register-section {
            padding: 70px 0;
            background: linear-gradient(135deg, #ecfeff 0%, #f8fafc 50%, #ffffff 100%);
            min-height: 100vh;
        }

        .wu-register-wrap {
            max-width: 1100px;
            margin: 0 auto;
        }

        .wu-register-left {
            padding: 20px 25px 20px 0;
        }

        .wu-register-badge {
            display: inline-block;
            background: #ccfbf1;
            color: var(--wu-primary-dark);
            font-size: 14px;
            font-weight: 700;
            padding: 8px 14px;
            border-radius: 999px;
            margin-bottom: 18px;
        }

        .wu-register-title {
            font-size: 2.7rem;
            line-height: 1.15;
            font-weight: 800;
            color: var(--wu-text);
            margin-bottom: 16px;
        }

        .wu-register-title span {
            color: var(--wu-primary);
        }

        .wu-register-text {
            color: var(--wu-muted);
            font-size: 16px;
            line-height: 1.9;
            margin-bottom: 24px;
            max-width: 540px;
        }

        .wu-register-points {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .wu-register-points li {
            margin-bottom: 12px;
            color: var(--wu-text);
            font-weight: 600;
            font-size: 15px;
        }

        .wu-register-points i {
            color: var(--wu-primary);
            margin-right: 10px;
        }

        .wu-register-info {
            background: rgba(255,255,255,0.65);
            border: 1px solid rgba(15,118,110,0.10);
            border-radius: 18px;
            padding: 18px;
            margin-top: 28px;
            max-width: 540px;
        }

        .wu-register-info h4 {
            font-size: 18px;
            font-weight: 800;
            color: var(--wu-text);
            margin-bottom: 10px;
        }

        .wu-register-info p {
            font-size: 14px;
            color: var(--wu-muted);
            line-height: 1.8;
            margin: 0;
        }

        .wu-register-card {
            background: var(--wu-white);
            border: 1px solid var(--wu-border);
            border-radius: 24px;
            box-shadow: var(--wu-shadow);
            padding: 34px 28px;
        }

        .wu-register-logo {
            text-align: center;
            margin-bottom: 16px;
        }

        .wu-register-logo img {
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

        .form_field {
            position: relative;
            margin-bottom: 16px;
        }

        .wu-form-label {
            display: block;
            font-weight: 700;
            font-size: 14px;
            color: var(--wu-text);
            margin-bottom: 8px;
        }

        .wu-form-control,
        .wu-form-select {
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

        .wu-form-control:focus,
        .wu-form-select:focus {
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

        .my_error {
            display: block;
            color: red;
            font-size: 13px;
            margin-top: 6px;
        }

        .wu-warning {
            display: none;
            color: #dc2626;
            background: #fef2f2;
            border: 1px solid #fecaca;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 12px;
        }

        .wu-tos {
            font-size: 14px;
            color: var(--wu-muted);
            line-height: 1.8;
        }

        .wu-tos a {
            color: var(--wu-primary);
            font-weight: 700;
            text-decoration: none;
        }

        .wu-tos a:hover {
            text-decoration: underline;
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

        .wu-login-text {
            text-align: center;
            margin-top: 22px;
            color: var(--wu-muted);
            font-size: 15px;
        }

        .wu-login-text a {
            color: var(--wu-primary);
            font-weight: 700;
            text-decoration: none;
        }

        .wu-login-text a:hover {
            text-decoration: underline;
        }

        .wu-note {
            margin-top: 18px;
            text-align: center;
            color: var(--wu-muted);
            font-size: 13px;
            line-height: 1.8;
        }

        @media (max-width: 991px) {
            .wu-register-left {
                padding: 0 0 30px 0;
            }

            .wu-register-title {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 767px) {
            .wu-register-section {
                padding: 45px 0;
            }

            .wu-register-card {
                padding: 26px 18px;
                border-radius: 18px;
            }

            .wu-register-title {
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
<section class="wu-register-section">
    <div class="container">
        <div class="row align-items-center wu-register-wrap">

            <div class="col-lg-6">
                <div class="wu-register-left">
                    <span class="wu-register-badge">Create Your Account</span>

                    <h1 class="wu-register-title">
                        Join <span>{{ site_info()->title }}</span> Today
                    </h1>

                    <p class="wu-register-text">
                        Create your account to access platform features, explore digital opportunities, participate in activities, and become part of the growing Workup BD community.
                    </p>

                    <ul class="wu-register-points">
                        <li><i class="fas fa-check-circle"></i>Get access to platform features and user tools</li>
                        <li><i class="fas fa-check-circle"></i>Explore tasks, services, and activity sections</li>
                        <li><i class="fas fa-check-circle"></i>Join with your referral code when available</li>
                        <li><i class="fas fa-check-circle"></i>Create your profile in a structured environment</li>
                    </ul>

                    <div class="wu-register-info">
                        <h4>Simple and secure registration</h4>
                        <p>
                            Please use accurate information while registering your account. This helps maintain a smooth and trusted experience across the platform.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="wu-register-card">
                    <div class="wu-register-logo">
                        <a href="{{ url('/') }}">
                            <img src="{{ URL::to(website_logo()) }}" alt="Workup BD Logo">
                        </a>
                    </div>

                    <h2 class="wu-form-title">{{ about_us()->register_form_title }}</h2>
                    <p class="wu-form-subtitle">
                        Fill in your details below to create your new account.
                    </p>

                    <form action="{{ route('user-register') }}" id="contact-form" method="post">
                        @csrf

                        <div class="form_field">
                            <label for="full_name" class="wu-form-label">Name</label>
                            <input type="text" placeholder="Enter your full name" name="name" id="full_name" value="{{ old('name') }}" class="wu-form-control" />
                            @error('name')
                                <span class="my_error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form_field">
                            <label for="email" class="wu-form-label">Email Address</label>
                            <input type="email" placeholder="Enter your email address" name="email" id="email" value="{{ old('email') }}" class="wu-form-control" />
                            @error('email')
                                <span class="my_error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
    <label for="referral">Referral By</label>
    <input
        type="text"
        class="form-control"
        id="referral"
        name="referral"
        value="{{ request()->route('code') ?? old('referral', '10001') }}"
        placeholder="Referral Code">
</div>

                        <div class="form_field">
                            <label for="password" class="wu-form-label">Password</label>
                            <input type="password" placeholder="Create your password" name="password" id="password" class="wu-form-control" style="padding-right: 46px;" />
                            @error('password')
                                <span class="my_error">{{ $message }}</span>
                            @enderror
                            <i class="far fa-eye wu-password-toggle" id="togglePassword"></i>
                        </div>

                        <div class="form_field">
                            <label for="password_confirmation" class="wu-form-label">Confirm Password</label>
                            <input type="password" placeholder="Confirm your password" name="password_confirmation" id="password_confirmation" class="wu-form-control" style="padding-right: 46px;" />
                            @error('password_confirmation')
                                <span class="my_error">{{ $message }}</span>
                            @enderror
                            <i class="far fa-eye wu-password-toggle" id="toggleConfirmPassword"></i>
                            <span class="my_error" id="error_message" style="display:none;">Passwords do not match!</span>
                        </div>

                        <div class="form_field">
                            <label for="countrycode" class="wu-form-label">Select Country</label>
                            <select name="country" id="countrycode" class="wu-form-select">
                                <option value="">Select Country</option>
                                @foreach (countrys() as $key => $country)
                                    <option value="{{ $country->id }}" {{ old('country') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country')
                                <span class="my_error">{{ $message }}</span>
                            @enderror
                        </div>

                        @if (site_info()->captcha_verify_active == 1)
                            <div class="form-group mb-3">
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}" id="recaptcha"></div>
                                @error('g-recaptcha-response')
                                    <span class="my_error">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        <div id="recaptchaWarningMessage" class="wu-warning">
                            Please complete the reCAPTCHA before registration.
                        </div>

                        <div class="form_field">
                            <label class="wu-tos">
                                <input type="checkbox" name="Tos" id="tos" onchange="valueChanged()" required>
                                I agree to {{ website_title() }}
                                <a target="_blank" href="{{ url('/terms-and-conditions') }}">Terms of Service</a>
                                and
                                <a target="_blank" href="{{ url('/privacy-policy') }}">Privacy Policy</a>.
                            </label>
                        </div>

                        <div class="form_field">
                            <button type="submit" class="wu-submit-btn" id="create_account" onclick="checkRecaptcha(event)">
                                <i class="fas fa-user-check"></i>
                                <span>Create New Account</span>
                            </button>

                            <p class="wu-login-text">
                                Already have an account?
                                <a href="{{ route('login') }}">Login now</a>
                            </p>
                        </div>

                        <p class="wu-note">
                            Please make sure your details are correct before submitting the registration form.
                        </p>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script>
    function checkRecaptcha(event) {
        var recaptchaBox = document.getElementById('recaptcha');
        if (recaptchaBox && typeof grecaptcha !== 'undefined') {
            var recaptchaResponse = grecaptcha.getResponse();
            if (!recaptchaResponse) {
                event.preventDefault();
                document.getElementById('recaptchaWarningMessage').style.display = 'block';
            } else {
                document.getElementById('recaptchaWarningMessage').style.display = 'none';
            }
        }
    }
</script>

<script>
    $(window).on('load', function(){
        $.ajax({
            type: 'GET',
            url: 'reload-captcha',
            success: function(data){
                $('.captcha span').html(data.captcha);
                $('#captcha_code').val(data.captcha);
                $('#captcha-error').html('');
                $('#entered_captcha_code').val('');
            }
        });
    });

    $('#reload').click(function(){
        $.ajax({
            type: 'GET',
            url: 'reload-captcha',
            success: function(data){
                $('.captcha span').html(data.captcha);
                $('#captcha_code').val(data.captcha);
                $('#captcha-error').html('');
                $('#create_account').attr('disabled', true);
                $('#entered_captcha_code').val('');
            }
        });
    });

    function changeCaptchaCode(){
        var captcha_code = $('#captcha_code').val();
        var code = $('#entered_captcha_code').val();

        if(captcha_code === code){
            $('#captcha-error').html('');
            $('#create_account').attr('disabled', false);
        }else{
            $('#captcha-error').html('Captcha not matched! Try again!');
            $('#create_account').attr('disabled', true);
        }
    }
</script>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    }

    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPasswordInput = document.getElementById('password_confirmation');

    if (toggleConfirmPassword && confirmPasswordInput) {
        toggleConfirmPassword.addEventListener('click', function () {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    }

    document.getElementById('password_confirmation').addEventListener('input', function () {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const errorMessage = document.getElementById('error_message');

        if (password !== confirmPassword) {
            errorMessage.style.display = 'block';
        } else {
            errorMessage.style.display = 'none';
        }
    });

    document.getElementById('contact-form').addEventListener('submit', function (e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        const errorMessage = document.getElementById('error_message');

        if (password !== confirmPassword) {
            errorMessage.style.display = 'block';
            e.preventDefault();
            alert("Passwords do not match. Please try again.");
        } else {
            errorMessage.style.display = 'none';
        }
    });
</script>
@endsection