@extends('user.layouts.master')
@section('css')
<style>
    /* General Styling */
    .container-fluid {
        background-color: #f5f7fa;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
    }

    .card:hover {
        transform: scale(1.02);
    }

    .card-header {
        background-color: #007bff;
        color: white;
        padding: 20px;
        border-radius: 12px 12px 0 0;
    }

    .card-body {
        padding: 20px;
        background-color: #ffffff;
        border-radius: 12px 12px 12px 12px;
    }

    h3, h2, h5 {
        font-family: 'Arial', sans-serif;
        margin-bottom: 15px;
    }

    /* Marquee Styling */
    .alert {
        background: linear-gradient(45deg, #ffbb33, #ff4444);
        color: white;
        border-radius: 0px 0px 10px 10px;
    }

    marquee a {
        color: black;
        font-weight: bold;
        text-decoration: none;
        margin-right: 15px;
    }

    /* Form Styling */
    .form-group label {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .form-control {
        border: 1px solid #ced4da;
        border-radius: 8px;
        padding: 10px;
    }

    .bttn-primary, .btn-danger {
        width: 100%;
        font-size: 18px;
        padding: 12px;
        border-radius: 8px;
        background-color: #626ED4;
        color: white;
    }

    .bttn-primary:hover, .btn-danger:hover {
        background-color: #0056b3;
        color: white;
    }

    .text-center {
        text-align: center;
    }

    .text-danger {
        color: #dc3545 !important;
    }
</style>
@endsection

@section('user-content')
    <div class="container-fluid mt-2">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-8 col-12">
                <div class="alert alert-success bg-warning text-white border-0">
                    <marquee scrollamount="6">
                        @foreach ($headlines as $key => $headline)
                            <a href="{{ $headline->link }}" class="text-black" style="font-size:20px;">
                                <i class="fe fe-link me-2" aria-hidden="true"></i>{{ $headline->title }}
                            </a>
                        @endforeach
                    </marquee>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <!-- Earning Balance Card -->
<div class="col-md-3 col-lg-3 col-12">
    <div class="card">
        <div class="card-body text-center">
            <!-- Adding an icon for a visual touch -->
            
            <div class="icon mb-3">
                <img src="{{ URL::to(Auth::user()->image) }}" alt="{{ Auth::user()->name }}" class="img-fluid img-thumbnail rounded-circle avatar-lg" onerror="this.onerror=null;this.src='https://ps.w.org/user-avatar-reloaded/assets/icon-256x256.png?rev=2540745';">
                
            </div>
            
            
            <h3 class="card-title">Your Account Details</h3>
            <hr style="border-top: 2px solid #007bff; width: 50%; margin: 0 auto 20px;">
            
            <!-- Displaying user details in a well-styled format -->
            <p class="card-text" style="font-size: 18px; color: #343a40;">
                <strong>Name:</strong> {{ Auth::user()->name }}
            </p>
            <p class="card-text" style="font-size: 18px; color: #343a40;">
                <strong>Balance:</strong> <span class="text-success">{{ Auth::user()->earning_balance }}$</span>
            </p>
            
           <div class="progress mb-3" style="height: 20px; border-radius: 10px;">
    <div class="progress-bar bg-success" role="progressbar" style="width: {{ min(100, (Auth::user()->earning_balance / 100) * 100) }}%;" aria-valuenow="{{ Auth::user()->earning_balance }}" aria-valuemin="0" aria-valuemax="100">
        {{ Auth::user()->earning_balance }}$
    </div>
</div>



        </div>
    </div>
</div>


        <!-- Withdraw Form Card -->
        <div class="col-md-6 col-lg-6 col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h2 class="card-title">Select Account For Balance Withdraw</h2>
                    <h5 class="text-danger">You can withdraw a minimum of {{ $withdraw_fee->minimum_withdraw }} and Admin Fee {{ $withdraw_fee->fee }}%.</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.withdraw-store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="withdraw_amount" class="form-label">
                                Amount <span class="text-danger">*</span>
                                @if ($withdraw_fee->minimum_withdraw > Auth::user()->earning_balance || Auth::user()->earning_balance == 0)
                                    <span class="text-danger">Insufficient Balance (Your current balance: {{ Auth::user()->earning_balance }}$)</span>
                                @endif
                            </label>
                            <input class="form-control" type="text" name="withdraw_amount" id="withdraw_amount" value="0" placeholder="Withdraw Amount" onkeyup="changeWithdrawAmount()" onchange="changeWithdrawAmount()">
                        </div>

                        <div class="form-group">
                            <label for="amount" class="form-label">Total Withdraw</label>
                            <input type="hidden" name="withdraw_charge" id="withdraw_charge" value="0" readonly>
                            <input class="form-control" type="number" name="amount" id="amount" value="0" readonly>
                            <input type="hidden" name="admin_fee" id="admin_fee" value="{{ $withdraw_fee->fee }}">
                        </div>

                        <div class="form-group">
                            <label for="account_type" class="form-label">Account Type <span class="text-danger">*</span></label>
                            <select class="form-control" name="account_type" id="account_type" required>
                                <option value="">Select One</option>
                                @foreach ($methods as $method)
                                    <option value="{{ $method->name }}">{{ $method->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="account_no" class="form-label">Account No <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="account_no" required placeholder="Account No">
                        </div>

                        <div class="form-group">
                            
                            
                            
                            
               @if(site_info()->instanat_verify_active == 1 && Auth::user()->is_verified == 0)
                   <div class="text-center mb-2">
                        <a href="{{ route('user.account-instant-verify') }}" class="btn btn-icon text-white" type="submit" name="submitProve" style="border-radius:5px; margin-top:20px; background:#27954f; width:100%;">
                            <span class="btn-inner--icon"><i class="fas fa-check"></i></span>
                            <span class="btn-inner--text">Verify Account First</span>
                        </a>
                    </div>


                            
                            
                  @else          
                            <button 
                                @if ($withdraw_fee->minimum_withdraw > Auth::user()->earning_balance || Auth::user()->earning_balance == 0)
                                    type="button" disabled class="btn btn-danger mt-4 mb-0"
                                @else
                                    type="submit" class="btn bttn-primary mt-4 mb-0"
                                @endif>
                                Submit
                            </button>
                            @endif
                            
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    function changeWithdrawAmount() {
        var withdraw_amount = parseFloat($('#withdraw_amount').val());
        if (isNaN(withdraw_amount) || withdraw_amount == '') {
            withdraw_amount = parseFloat(0);
        }

        var admin_fee = parseFloat($('#admin_fee').val());
        var charge = parseFloat((admin_fee * withdraw_amount) / 100);
        var total_amount = withdraw_amount + charge;

        $('#withdraw_charge').val(charge > 0 ? charge : 0);
        $('#amount').val(total_amount > 0 ? total_amount : 0);
    }
</script>
@endsection
