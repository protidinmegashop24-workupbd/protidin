@extends('user.layouts.master')

@section('css')
<!-- Custom CSS for Ads History Page -->
<style>
    /* Container Styling */
    .ads-history-section {
        margin-top: 140px;
        padding-bottom: 50px;
    }

    /* Card Styling */
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .card-header {
        background-color: #4A90E2;
        color: #fff;
        text-align: center;
        padding: 20px;
    }

    /* List Group Styling */
    .list-group-item {
        border: none;
        padding: 20px;
        margin-bottom: 10px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        transition: background-color 0.3s, box-shadow 0.3s;
    }

    .list-group-item:hover {
        background-color: #f9f9f9;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    /* Ad Details */
    .ad-details {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: nowrap;
    }

    .ad-info {
        display: flex;
        flex-direction: column;
    }

    .ad-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
        color: #333;
    }

    .ad-date {
        font-size: 14px;
        color: #777;
        margin-bottom: 10px;
    }

    .ad-cost {
        font-size: 16px;
        color: #1D8348;
        font-weight: bold;
    }

    /* Status Badge */
    .status-badge {
        display: flex;
        align-items: center;
        font-size: 14px;
        padding: 5px 10px;
        border-radius: 20px;
        color: #fff;
        margin-left: auto;
    }

    .status-approved {
        background-color: #28a745;
    }

    .status-rejected {
        background-color: #dc3545;
    }

    .status-pending {
        background-color: #ffc107;
        color: #212529;
    }

    /* Status Icons */
    .status-badge i {
        margin-right: 5px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .ad-details {
            flex-wrap: wrap;
        }

        .status-section {
            display: flex;
            justify-content: flex-end;
            width: 20%;
        }
    }

    /* Optional: Add smooth transitions for status changes */
    .status-badge {
        transition: background-color 0.3s, color 0.3s;
    }

    /* Custom styling for each ad card shape */
    .list-group-item:nth-child(odd) {
        background: #f5f5f5;
    }

    .list-group-item:nth-child(even) {
        background: #ffffff;
    }

</style>
<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-p2rW50zp3Lha4bGk50v+0gQqK9fQ0JQwZcVhtGk5Urk1J+UG4p4TwFBl4S+8tTYPXZ9QX0Oz7cJKlVxXghQ2EA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('user-content')

<section class="ads-history-section">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Ads History</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse ($datas as $key => $data)
                                <li class="list-group-item">
                                    <div class="ad-details">
                                        <div class="ad-info">
                                            <h5 class="ad-title">{{ $data->title }}</h5>
                                            <span class="ad-date">{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y g:i A') }}</span>
                                            <span class="ad-cost">{{ number_format($data->cost, 2) }} $</span>
                                            @if ($data->approval == 2 && !empty($data->reason))
                                                <span class="ad-reason text-danger"><i class="fas fa-exclamation-circle"></i> Reason: {{ $data->reason }}</span>
                                            @endif
                                        </div>
                                        <div class="status-section">
                                            @if ($data->approval == 1)
                                                <span class="status-badge status-approved">
                                                    <i class="fas fa-check-circle"></i> Approved
                                                </span>
                                            @elseif ($data->approval == 2)
                                                <span class="status-badge status-rejected">
                                                    <i class="fas fa-times-circle"></i> Rejected
                                                </span>
                                            @else
                                                <span class="status-badge status-pending">
                                                    <i class="fas fa-hourglass-half"></i> Pending
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center">
                                    <p class="mb-0">No Ads Found.</p>
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
<!-- Optional JavaScript (if needed) -->
<script>
    function setAccount(account_id) {
        $('#deposit_account').val(account_id);

        $.ajax({
            url: "{{ route('user.deposit-account-info') }}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                account_id: account_id,
            },
            success: function(data) {
                $('#deposit_area').show();
                $('#deposit_account_text').html('Account No: ' + data['account_no']);
                $('#deposit_account_guideline').html(data['guideline']);
            },
            error: function(error) {
                console.error("Error fetching account info:", error);
            }
        });
    }
</script>
@endsection
