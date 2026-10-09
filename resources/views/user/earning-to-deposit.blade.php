@extends('user.layouts.master')

@section('css')
    <!-- প্রয়োজনীয় স্টাইলশীট -->
    <link href="{{ asset('frontend/user/assets/css/nucleo.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/fontawesome-all.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/argon.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/myapp.css') }}" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        /* বেসিক রেস্পন্সিভ স্টাইল */
        body {
            font-family: 'Noto Serif Bengali', serif;
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            max-width: 600px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #28a745;
            color: white;
            text-align: center;
            border-radius: 10px 10px 0 0;
            padding: 20px 0;
        }
        .card-title {
            font-weight: bold;
            font-size: 24px;
            margin: 0;
        }
        .text-muted {
            font-size: 14px;
            color: #6c757d;
            margin: 15px 0;
            text-align: center;
        }
        .form-group input {
            padding: 15px;
            border-radius: 5px;
            border: 2px solid #ced4da;
            font-size: 18px;
            color: #495057;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            border-color: #28a745;
            outline: none;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
        }
        .btn-primaryy {
            background-color: #28a745;
            color: white;
            border: none;
            width: 100%;
            padding: 12px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-primaryy:hover {
            background-color: #218838;
            Color: #fff;
        }
        .btn-primaryy i {
            margin-right: 8px;
            font-size: 20px;
        }
        .alert {
            margin-top: 20px;
            font-size: 16px;
        }
        @media (max-width: 576px) {
            .container {
                max-width: 100%;
                padding: 15px;
                margin-top: 20px;
            }
            .card-header {
                padding: 15px 0;
            }
            .card-title {
                font-size: 20px;
            }
            .btn-primaryy {
                font-size: 16px;
                padding: 10px;
            }
        }
    </style>
@endsection

@section('user-content')
    <div class="container">
        <div class="card-header">
            <div class="card-title">Earning to Deposit Transfer</div>
        </div>

        <p class="text-muted">
            <span>বি.দ্রঃ ১০০% ফ্রিতে আর্নিং থেকে ডিপজিট ব্যালেন্স এ ডলার ট্রান্সফার করতে পারবেন। ব্যালেন্স ট্রান্সফার করলে আর ক্যান্সেল করা সম্ভব না। (মিনিমাম ট্রান্সফার ১$)</span>
        </p>

        <!-- Success and Error Messages -->
        @if(session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger text-center">
                {{ session('error') }}
            </div>
        @endif

        <!-- Transfer Form -->
        <form action="{{ route('user.earning-to-deposit') }}" method="POST">
            @csrf
            <div class="form-group">
                <input type="number" name="amount" id="amount" placeholder="কত $ ট্রান্সফার করতে চাচ্ছেন এখানে লিখুন" class="form-control" min="1" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-primaryy">
                <i class="fas fa-exchange-alt"></i> ট্রান্সফার
            </button>
        </form>
    </div>
@endsection

@section('js')
    <!-- প্রয়োজনীয় জাভাস্ক্রিপ্ট -->
@endsection
