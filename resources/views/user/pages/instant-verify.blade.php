@extends('user.layouts.master')

@section('css')
    <!-- Include any external CSS libraries if necessary -->
    <link href="{{ asset('frontend/user/assets/css/custom.css') }}" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        /* Card Styling */
        .custom-card {
            border: none;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.5s;
            margin-top: 50px;
        }

        .custom-card:hover {
            transform: translateY(-10px);
        }

        .custom-card-body {
            padding: 40px;
        }

        .custom-card-body h6 {
            font-size: 22px;
            color: #333;
            font-weight: 600;
            margin-bottom: 30px;
        }

        /* Button Styling */
        .btn-verify {
            background: linear-gradient(45deg, #f093fb, #f5576c);
            color: #fff !important;
            padding: 12px 25px;
            font-size: 18px;
            border-radius: 50px;
            transition: background 0.3s ease-in-out;
            border: none;
        }

        .btn-verify:hover {
            background: linear-gradient(45deg, #f5576c, #f093fb);
            color: #fff;
        }

        /* Form Styling */
        .form-group label {
            font-weight: 500;
            font-size: 16px;
            color: #555;
        }

        .form-control {
            border-radius: 30px;
            padding: 12px 20px;
            font-size: 16px;
        }

        /* Alert Styling */
        .alert-success {
            background: #28a745;
            color: #fff;
            font-size: 18px;
            font-weight: 500;
            border-radius: 30px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alert-success i {
            font-size: 24px;
            margin-right: 10px;
        }

        /* Paragraph Styling */
        .info-text {
            font-size: 16px;
            color: #333;
            background: #ffe9e9;
            border-left: 5px solid #ff6b6b;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            .custom-card-body {
                padding: 30px;
            }

            .btn-verify {
                font-size: 16px;
                padding: 10px 20px;
            }

            .custom-card-body h6 {
                font-size: 20px;
            }
        }
    </style>
@endsection

@section('user-content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="card custom-card">
                    <div class="card-body custom-card-body">
                        
                        
                        
                        
                        
                        @if(Auth::user()->is_verified)
                            <!-- যদি একাউন্ট ভেরিফাইড হয়ে থাকে -->
                            <h6 class="text-center">Congratulations! Your account has verified. Now Enjoy all the features.</h6>
                            <img src="https://static.vecteezy.com/system/resources/thumbnails/047/309/918/small_2x/verified-badge-profile-icon-png.png" alt="Verified Badge" class="img-fluid d-block mx-auto" style="width: 100px; margin-top: 10px; margin-bottom: 10px;">

                                                        
                            <div class="alert alert-success text-center" role="alert" style="color: white;">
                                <a href="{{ route('user.profile') }}" style="color: white;">
                                    <span><i class="fas fa-check-circle"></i> Go To your Account</span>
                                </a>
                            </div>

                        @else
                        
                            <h6 class="text-center">{{ site_info()->instant_verify_note }}</h6>
                        
                      
                            <p class="info-text text-center">
                                এখন থেকে আপনারা চাইলে এই ওয়েবসাইটে আর্নিং করে সেই ডলার দিয়েই একাউন্ট ভেরিফাই করতে পারবেন। একাউন্ট ভেরিফাই করার আগে অবশ্যই উপরে উল্লেখিত ডলার ডিপজিট করুন অথবা আর্নিং করুন।
                            </p>
                            <form action="{{ route('user.instant-verify-my-account') }}" method="POST">
                                @csrf
                                <input type="hidden" name="balance_type" value="deposit_balance">
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-verify" onclick="return confirm('Are you sure?')">
                                        একাউন্ট ভেরিফাই করুন
                                    </button>
                                </div>
                            </form>
                            <h6 class="text-center mt-4">অথবা</h6>
                            <p class="info-text text-center">
                                NID কার্ড অথবা জন্ম নিবন্ধন দিয়ে ফ্রীতে ভেরিফাই করতে নিচে ক্লিক করে সঠিকভাবে ফর্মটি পূরণ করুন
                            </p>
                            <div class="text-center">
                                <a href="{{ route('user.account-instant-verify-by-nid') }}" class="btn btn-verify">NID কার্ড অথবা জন্ম নিবন্ধন দিয়ে ভেরিফাই করুন</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Include any external JS libraries if necessary -->
    <script src="{{ asset('frontend/user/assets/js/my_custom.js') }}"></script>
    <script>
        @if(session('success'))
            toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true
            }
            toastr.success({{ session('success') }});
        @elseif(session('error'))
            toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true
            }
            toastr.error({{ session('error') }});
        @endif
    </script>
@endsection
