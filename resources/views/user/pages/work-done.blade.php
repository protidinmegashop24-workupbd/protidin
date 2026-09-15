@extends('user.layouts.master')
@section('css')
    <link href="{{ asset('frontend/user/assets/css/custom.css') }}" rel="stylesheet" />
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Poppins', sans-serif;
        }

        .card {
            border: none;
            border-radius: 15px;
            background: linear-gradient(145deg, #ffffff, #e6e6e6);
            box-shadow: 20px 20px 60px #d9d9d9, -20px -20px 60px #ffffff;
        }

        .card-body {
            padding: 40px;
        }

        h4 {
            font-size: 2rem;
            color: #28a745;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 30px;
            padding: 12px 30px;
            font-size: 1.2rem;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-primary:focus {
            outline: none;
            box-shadow: 0px 5px 15px rgba(0, 123, 255, 0.4);
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        .text-center {
            text-align: center;
        }

        .col-lg-6 {
            margin-top: 50px;
        }

        .success-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 20px;
        }

        /* Add subtle animations */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        /* Add responsiveness */
        @media (max-width: 767px) {
            h4 {
                font-size: 1.5rem;
            }

            .btn-primary {
                font-size: 1rem;
                padding: 10px 20px;
            }

            .card-body {
                padding: 20px;
            }

            .col-lg-6 {
                margin-top: 30px;
            }
        }
    </style>
@endsection
@section('user-content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body text-center">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h4 class="text-success">Successfully Complete Your Job</h4>
                    <a href="{{ route('user.dashboard') }}" class="btn btn-primary btn-lg mt-4 mb-0">Back To Job List</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('frontend/user/assets/js/my_custom.js') }}"></script>
    <script>
        // Optional: You can add a little fade-in animation for the page load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.card').classList.add('fade-in');
        });
    </script>
    <style>
        .fade-in {
            opacity: 0;
            animation: fadeIn ease 2s;
            animation-fill-mode: forwards;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }
    </style>
@endsection
