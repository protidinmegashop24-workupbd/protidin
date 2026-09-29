@extends('user.layouts.master')

@section('css')
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-p2rW50zp3Lha4bGk50v+0gQqK9fQ0JQwZcVhtGk5Urk1J+UG4p4TwFBl4S+8tTYPXZ9QX0Oz7cJKlVxXghQ2EA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fe;
        }

        .w-100 {
            width: 100%;
        }

        /* Card Style */
        .card {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            background-color: #fff;
            margin-bottom: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background-color: #6c5ce7;
            color: #fff;
            border-radius: 12px 12px 0 0;
            padding: 20px;
            text-align: center;
        }

        .card-header h5 {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 1px;
            margin: 0;
        }

        /* List Group Styles */
        .list-group-item {
            background-color: #f8f9fe;
            border: none;
            border-bottom: 1px solid #e9ecef;
            padding: 20px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            border-radius: 0 0 12px 12px;
        }

        .list-group-item:last-child {
            border-bottom: none;
            border-radius: 0 0 12px 12px;
        }

        .list-group-item:hover {
            background-color: #e9ecef;
            transform: translateY(-2px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        /* Status Styles and Adjustments */
        .checklist-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-left: 15px; /* Adjust this value for spacing */
        }

        .checklist-item-warning {
            border-left: 4px solid #ffc107;
        }

        .checklist-item-success {
            border-left: 4px solid #28a745;
        }

        .checklist-item-danger {
            border-left: 4px solid #dc3545;
        }

        .checklist-info {
            flex: 1;
            margin-right: 15px;
        }

        .checklist-info h5 {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .checklist-info small {
            color: #6c757d;
            display: block;
            margin-bottom: 10px;
        }

        .status {
            font-size: 16px;
            font-weight: 600;
            margin-top: 5px;
            display: flex;
            align-items: center;
        }

        .status i {
            margin-right: 5px;
        }

        .status-pending {
            color: #ffc107;
        }

        .status-approved {
            color: #28a745;
        }

        .status-rejected {
            color: #dc3545;
        }

        .checklist-amount {
            font-size: 20px;
            font-weight: 700;
            color: #1D8348;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .card-header h5 {
                font-size: 18px;
            }

            .checklist-info h5 {
                font-size: 16px;
            }

            .checklist-amount {
                font-size: 18px;
            }

            .status {
                font-size: 14px;
            }
        }
    </style>
@endsection

@section('user-content')

<section style="margin-top: 100px">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="h3 mb-0">Withdraw History</h5>
                    </div>

                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush" data-toggle="checklist">

                        @forelse ($withdraws as $key => $data)
                            <li class="list-group-item">
                                <div class="checklist-item 
                                    @if($data->approval == 0) checklist-item-warning 
                                    @elseif($data->approval == 1) checklist-item-success 
                                    @else checklist-item-danger @endif">
                                    <div class="checklist-info">
                                        <h5 class="checklist-title mb-0">{{ $data->account_type }} ({{ $data->account_no }})</h5>
                                        <small>{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y g:i A')}}</small>
                                        <!-- Status -->
                                        @if($data->approval == 0)
                                            <div class="status status-pending">
                                                <i class="fas fa-hourglass-half"></i> Pending
                                            </div>
                                        @elseif($data->approval == 1)
                                            <div class="status status-approved">
                                                <i class="fas fa-check-circle"></i> Approved
                                            </div>
                                        @else
                                            <div class="status status-rejected">
                                                <i class="fas fa-times-circle"></i> Rejected
                                            </div>
                                        @endif
                                    </div>
                                    <div class="checklist-amount">
                                        {{ number_format($data->amount - $data->charge, 2) }} $
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center">
                                <p class="mb-0">No Withdraws Found.</p>
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
        // আপনি এখানে প্রয়োজনমত জাভাস্ক্রিপ্ট কোড যোগ করতে পারেন
    </script>
@endsection
