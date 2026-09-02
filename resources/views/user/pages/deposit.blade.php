@extends('user.layouts.master')
@section('css')
    <!-- Adding additional fonts and making responsive adjustments -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('frontend/user/assets/css/nucleo.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/fontawesome-all.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/argon.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/myapp.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <style>
        body {
            font-family: 'noto serif bengali', sans-serif;
            background-color: #f4f4f4;
        }
        
        .gallery-img {
            display: flex;
            align-items: center;
            justify-content: center;
            height: calc(100% - 64px);
            padding: 15px;
            padding-top: 0;
        }
        
        .gallery-img img {
            width: auto;
            height: auto;
            max-height: 380px;
            border-radius: 8px;
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }
        
        .container-fluid {
            padding-right: 0px;
            padding-left: 0px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #0056b3;
            border-radius: 10px 10px 0 0;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: white;
        }

        .form-group label {
            font-weight: 500;
            color: #333;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #ccc;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        .wallet-1 {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .wallet-1:hover {
            border-color: #007bff;
            transform: scale(1.05);
        }

       

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
            font-size: 1rem;
        }

        .text-muted {
            font-size: 0.9rem;
            color: #777;
        }

        /* Custom marquee styling */
        marquee {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 10px;
            color: #333;
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 20px;
        }

        /* Media Queries */
        @media only screen and (max-width: 768px) {
            .wallet-1 {
                width: 100%;
                margin-bottom: 10px;
            }

            .form-control {
                width: 100%;
            }
        }

        @media only screen and (max-width: 480px) {
            .card-title {
                font-size: 1.2rem;
            }
        }
    </style>
@endsection

@section('user-content')
    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-8 col-12">
                <!-- Enhanced Marquee -->
                <marquee behavior="scroll" direction="left" scrollamount="5">
                    @foreach ($headlines as $key => $headline)
                        <a href="{{ $headline->link }}" class="text-primary" style="font-size: 20px;">
                            <i class="fe fe-link me-2" aria-hidden="true"></i>{{ $headline->title }}
                        </a>
                    @endforeach
                </marquee>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Select Account For Balance Deposit</h4>
                   <p class="text-muted font-weight-500" style="border-radius: 4px; border: 2px solid red; padding: 5px; text-align: center; color: #fff; background-color: #f8f9fa;">
    ভেরিফিকেশন এর জন্য অপেক্ষা না করে ইন্সট্যান্ট টাকা ডিপজিট করতে চাইলে 
    <a href="/user/instant-deposit" target="_blank" style="color: #007bff; font-weight: bold;">এখানে চাপুন</a>
</p>

                </div>
                <div class="card-body">
                    
                    
                   
                 
                 
                 
                 
                 <form action="{{ route('user.deposit-store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group d-flex justify-content-center">
        @foreach ($pay_accounts as $account)
            <div class="wallet-1 m-2" onclick="setAccount({{ $account->id }})">
                <img src="{{ URL::to($account->image) }}" width="70" height="70" alt="{{ $account->name }}">
            </div>
        @endforeach
    </div>

    <input type="hidden" name="deposit_account" id="deposit_account" value="" required>
    <div id="deposit_area" style="display: none;">
        <div class="form-group">
            <h4><span id="deposit_account_text"></span> <a href="javascript:;" class="btn btn-info btn-sm" onclick="copyDepositAccountNo()">Copy</a></h4>
            <input type="hidden" value="" id="deposit_account_no" required>
            <p id="deposit_account_guideline"></p>
        </div>
        <hr>
        <div class="form-group">
            <label for="amount">Amount ($)<span class="text-red">*</span></label>
            <input class="form-control" type="number" name="amount" value="0" min="0" step="0.0001" placeholder="Deposit Amount" required>
        </div>

        <div class="form-group">
            <label for="phone">Account No <span class="text-red">*</span></label>
            <input class="form-control" type="text" name="phone" placeholder="Account No" required>
        </div>

        <div class="form-group">
            <label for="transaction_id">Transaction ID <span class="text-red">*</span></label>
            <input class="form-control" type="text" name="transaction_id" placeholder="Transaction ID" required>
        </div>

        <div class="form-group">
            <label for="receipt">Screenshot/Receipt <span class="text-red">*</span></label>
            <input class="form-control" type="file" name="receipt" required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary mt-4 mb-0">Submit</button>
        </div>
    </div>
</form>

                 
                 
                 
                 
                 
                 
                    
                    
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function setAccount(account_id){
            $('#deposit_account').val(account_id);

            $.ajax({
                url: "{{ route('user.deposit-account-info') }}",
                type:"POST",
                data:{
                    _token: '{{csrf_token()}}',
                    account_id: account_id,
                },
                success:function(data) {
                    $('#deposit_area').show();
                    $('#deposit_account_no').val(data['account_no']);
                    $('#deposit_account_text').html('Account No: '+data['account_no']);
                    $('#deposit_account_guideline').html(data['guideline']);
                },
            });
        }
        
        function copyDepositAccountNo(){
            var deposit_account_no = $('#deposit_account_no').val();
            navigator.clipboard.writeText(deposit_account_no);
            alert('Account number copied!');
        }
    </script>
@endsection
