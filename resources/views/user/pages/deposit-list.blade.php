@extends('user.layouts.master')

@section('css')
    <!-- Custom CSS for Deposit History Page -->
    <link href="{{ asset('frontend/user/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/nucleo.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/fontawesome-all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/argon.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/custom-style.css') }}" rel="stylesheet" type="text/css">
    <style>
        .mb-0 { color: white; }

        /* Container Styling */
        .deposit-history-section {
            margin-top: 100px;
            padding-bottom: 50px;
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: #fff;
        }

        .card-header {
            background-color: #5e72e4;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        .card-header h5 {
            font-size: 22px;
            font-weight: 600;
            margin: 0;
        }

        /* List Group Styling */
        .list-group-item {
            border: none;
            padding: 20px;
            background-color: #f8f9fe;
            border-radius: 10px;
            margin-bottom: 15px;
            transition: background-color 0.3s;
        }

        .list-group-item:hover {
            background-color: #e9ecef;
        }

        /* Deposit Details */
        .deposit-details {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .deposit-info {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .deposit-title {
            font-size: 18px;
            font-weight: 500;
            color: #333;
            margin-bottom: 5px;
        }

        .deposit-date {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .deposit-amount {
            font-size: 20px;
            color: #1D8348;
            font-weight: bold;
            margin-top: 10px;
            text-align: left;
        }

        /* Status Badges */
        .status-section {
            display: flex;
            justify-content: flex-end;
            flex-direction: column;
            align-items: flex-end;
        }

        .status-badge {
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 20px;
            color: #fff;
        }

        .status-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .status-approved {
            background-color: #28a745;
        }

        .status-rejected {
            background-color: #dc3545;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .deposit-details {
                flex-direction: row; /* Changed from column to row */
                align-items: center; /* Vertically center items */
                justify-content: space-between; /* Space between elements */
            }

            .deposit-info {
                flex: 1; /* Allow deposit info to take available space */
                margin-right: 10px; /* Space between info and status */
            }

            .deposit-amount {
                margin-top: 0; /* Remove top margin */
                text-align: right; /* Align amount to the right */
            }

            .status-section {
                flex-direction: row; /* Align badges horizontally */
                align-items: center; /* Vertically center badges */
                justify-content: flex-end; /* Align badges to the end */
                margin-top: 0; /* Remove top margin */
            }

            .status-badge {
                font-size: 12px; /* Smaller font size for mobile */
                padding: 4px 8px; /* Adjust padding */
                margin-left: 10px; /* Space between badges if multiple */
            }
            
              
       
        
            
        }
        
        
        
    </style>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-p2rW50zp3Lha4bGk50v+0gQqK9fQ0JQwZcVhtGk5Urk1J+UG4p4TwFBl4S+8tTYPXZ9QX0Oz7cJKlVxXghQ2EA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('user-content')
<section class="deposit-history-section">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Deposit History</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse ($deposits as $key => $data)
                                <li class="list-group-item">
                                    <div class="deposit-details">
                                        <div class="deposit-info">
                                            <h5 class="deposit-title">{{ account_name($data->account_id) }} ({{ $data->phone ?? 'auto pay' }})</h5>
                                            <span class="deposit-date">{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y g:i A') }}</span>
                                        </div>
                                        <div class="status-section">
                                            <div class="deposit-amount">
                                                {{ number_format($data->amount, 2) }} $
                                            </div>
                                            @if($data->approval == 0)
                                                <span class="status-badge status-pending">
                                                    <i class="fas fa-hourglass-half"></i> Pending
                                                </span>
                                            @elseif($data->approval == 1)
                                                <span class="status-badge status-approved">
                                                    <i class="fas fa-check-circle"></i> Approved
                                                </span>
                                            @else
                                                <span class="status-badge status-rejected">
                                                    <i class="fas fa-times-circle"></i> Rejected
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center">
                                    <p class="mb-0">No Deposits Found.</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
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
                    $('#deposit_account_text').html('Account No: '+data['account_no']);
                    $('#deposit_account_guideline').html(data['guideline']);
                },
                error: function(error) {
                    console.error("Error fetching account info:", error);
                }
            });
        }
    </script>
@endsection
