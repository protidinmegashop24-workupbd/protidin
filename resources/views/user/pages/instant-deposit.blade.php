@extends('user.layouts.master')
@section('css')
    <link href="{{ asset('frontend/user/assets/css/nucleo.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/fontawesome-all.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/argon.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/myapp.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <style>
        * {
            font-family: 'Nunito', sans-serif;
        }

        h1 {
            font-family: 'Baloo Thambi 2', sans-serif;
            font-weight: 700;
            color: #4A3FBE;
            font-size: 48px;
            text-align: center;
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.15);
        }

        .container {
            margin-top: 50px;
        }

        .payment-box {
            background-color: #f1f5f9;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .payment-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .form-label {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .btn-primary {
            background-color: #4A3FBE;
            border: none;
            font-size: 18px;
            padding: 15px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #322E9C;
        }

        .form-control {
            padding: 15px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .wallet {
            display: flex;
            align-items: center;
            margin-top: 20px;
        }

        .bdt {
            font-weight: bold;
            color: #4A3FBE;
            margin-right: 10px;
        }

        .bdtamount {
            max-width: 150px;
            margin-right: 10px;
        }

        .alert {
            margin-top: 20px;
            font-size: 16px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .payment-box {
                padding: 20px;
            }

            .btn-primary {
                font-size: 16px;
            }

            .form-control {
                padding: 12px;
                font-size: 14px;
            }

            h1 {
                font-size: 36px;
                padding: 10px;
            }
        }

        /* Custom animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .container {
            animation: fadeIn 0.8s ease;
        }
    </style>
@endsection

@section('user-content')
    <div class="container">
        <h1><span>Auto Deposit</span></h1>

        <div class="text-center">
            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEihuMtlS_oqTDG5kjLoCKR-LflpqojDNxys4aTo8-6Vi65MynRPl2kp0f0nymq_yinfpl8mAGWsfDNTZj0NrQhKvvRPBbxsLsBsZ3RNSRrcrKEem4KK2qVnY3E5rjsZABQq6VpVXLG8TVLeqQd3FuPc9OnXlWtYg53rn6jJMwP2VFmQho6W7aW4bvXMLVnF/s320/7e5d178642a95e6ecc3dd1d2e12afd0b34bd3031.png" alt="Image Description" width="320" style="margin-bottom: 30px;">
        </div>










      

        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="payment-box">
                    
                    
                    
                    
@php
    $bkashSettings = \App\Models\BkashSetting::first();
@endphp

@if($bkashSettings && $bkashSettings->description)
    <div class="description-box">
       <p class="form-label text-muted font-weight-500" style="border-radius: 4px; border: 2px solid red; padding: 10px; color:black; text-align: center;">{{ $bkashSettings->description }}</p>
    </div>
@endif
                    
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="get" action="{{ route('bkash-create-payment') }}">
                        @csrf
                        
                        

                        <div class="mb-3">
                            <label for="amount" class="form-label"><b>Amount (in BDT):</b></label>
                            <input name="amount" type="number" id="amount" class="form-control" placeholder="1$ Deposit করতে ১০০ লিখুন" onkeyup="convertToDollar()" onchange="convertToDollar()" step="any">
                        </div>

                        <div class="wallet">
                            <span class="bdt">Balance Will Add ($)</span>
                            <input type="number" id="dollarAmount" class="bdtamount form-control" value="0" disabled>
                            <span style="color: green;">100 BDT = 1$</span>
                        </div>

                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="termsCheckbox">
                            <label class="form-check-label" for="termsCheckbox">
                                I agree with <a href="#" target="_blank">terms & conditions</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-3" id="payNowButton" onclick="validateForm(event)">Pay Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function convertToDollar() {
            var bdtAmount = document.getElementById('amount').value;
            var dollarAmount = (bdtAmount / 100).toFixed(2); // 1$ = 100 BDT কনভার্টার
            document.getElementById('dollarAmount').value = dollarAmount;
        }

        function validateForm(event) {
            var termsCheckbox = document.getElementById('termsCheckbox');
            if (!termsCheckbox.checked) {
                event.preventDefault();
                alert('You must agree with the terms & conditions.');
                return false;
            }
        }
    </script>
@endsection