@extends('user.layouts.master')

@section('css')
    <link href="{{ asset('frontend/user/assets/css/custom.css') }}" rel="stylesheet" />
    <style>
        body {
            font-family: 'noto serif bengali', sans-serif;
            background-color: #f1f1f1;
        }

        h6 {
            font-family: 'noto serif bengali', sans-serif;
        }

        /* Custom Card and Button Styling */
        .custom-card {
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
        }

        .custom-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .custom-card-body {
            background: linear-gradient(135deg, #e9ecef, #ffffff);
            padding: 50px;
            text-align: center;
            position: relative;
        }

        .custom-card-header {
            background-color: #343a40;
            color: #fff;
            font-size: 24px;
            padding: 20px;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            text-align: center;
            position: relative;
        }

        .custom-card-header::after {
            content: '';
            width: 100%;
            height: 5px;
            background-color: #17a2b8;
            position: absolute;
            bottom: 0;
            left: 0;
        }

        .btn-link-custom {
            color: #007bff;
            text-decoration: underline;
            padding: 10px 0;
            display: inline-block;
            transition: color 0.3s ease;
        }

        .btn-link-custom:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .btn-resend {
            background-color: #17a2b8;
            color: #fff;
            border-radius: 25px;
            padding: 12px 30px;
            font-size: 18px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-resend:hover {
            background-color: #138496;
            transform: scale(1.05);
        }

        .alert-success-custom {
            background-color: #d4edda;
            color: #155724;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Icon Styling */
        .custom-card-header i {
            font-size: 40px;
            color: #17a2b8;
            margin-bottom: 10px;
        }

        /* Additional Decorations */
        .custom-card-body:before {
            content: '✔';
            position: absolute;
            font-size: 80px;
            color: rgba(23, 162, 184, 0.2);
            top: 20%;
            right: 10%;
            transform: rotate(15deg);
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            .custom-card-body {
                padding: 30px;
            }

            .custom-card-header {
                font-size: 20px;
            }

            .btn-resend {
                font-size: 16px;
                padding: 10px 25px;
            }
        }
    </style>
@endsection

@section('user-content')
    <div class="row justify-content-center pt-4">
        <div class="col-lg-8 col-md-10 col-sm-12">
            <div class="card custom-card">
                <div class="custom-card-header">
                  
                    <i class="fas fa-envelope"></i>
                    {{ __('Verify Your Email Address') }}
                </div>
                <div class="custom-card-body">
                    @if (session('resent'))
                        <div class="alert alert-success-custom" role="alert">
                            {{ __('A fresh verification link has been sent to your email address. ভেরিফিকেশন লিংকে ক্লিক করার আগে অবশ্যই  ব্রাউজারে আপনার একাউন্ট লগিন করে নিন') }}
                        </div>
                    @endif
  
                    <p> এই পেইজের এক্সেস পেতে আপনাকে আপনার মেইল ভেরিফিকেশন করতে হবে। নিচের বাটনে ক্লিক করলে আপনার মেইলে ভেরিফিকেশন লিংক চলে যাবে সেখানে ক্লিক করে একাউন্ট ভেরিফাই করে নিন </p>
                    <p class="mb-4">
                        {{ __('Before proceeding, please check your email for a verification link.') }}
                    </p>

                    <p class="mb-4">
                        {{ __('If you did not receive the email, click the button below to request another.') }}
                    </p>

                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-resend">{{ __('Resend Verification Email') }}</button>
                    </form>

                    <p class="mt-3">
                        <a href="{{ route('user.profile') }}" class="btn-link-custom">{{ __('Go back to dashboard') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('frontend/user/assets/js/my_custom.js') }}"></script>
@endsection
