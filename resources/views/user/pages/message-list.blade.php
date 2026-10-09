@extends('user.layouts.master')

@section('css')
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-p2rW50zp3Lha4bGk50v+0gQqK9fQ0JQwZcVhtGk5Urk1J+UG4p4TwFBl4S+8tTYPXZ9QX0Oz7cJKlVxXghQ2EA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom Styles -->
    <style>
        /* General Styles */
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }

        /* Container Styling */
        .notifications-section {
            padding: 60px 15px;
        }

        /* Header Styling */
        .notifications-header {
            background-color: #ffffff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }

        .notifications-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: #2dce89;
            text-transform: uppercase;
            margin: 0;
        }

        /* Notification Card Styling */
        .notification-card {
            border: none;
            border-left: 5px solid #2dce89;
            transition: transform 0.2s, box-shadow 0.2s;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }

        .notification-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .notification-icon {
            font-size: 24px;
            color: #2dce89;
        }

        .notification-content {
            padding: 15px 20px;
            display: flex;
            flex-direction: column;
        }

        .notification-title {
            font-size: 18px;
            font-weight: 600;
            color: #343a40;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notification-time {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .notification-time i {
            margin-right: 5px;
            color: #6c757d;
        }

        .notification-message {
            font-size: 16px;
            color: #495057;
            margin: 0;
        }

        /* No Notifications Message */
        .no-notifications {
            text-align: center;
            padding: 50px 0;
            color: #6c757d;
        }

        .no-notifications i {
            font-size: 50px;
            margin-bottom: 20px;
            color: #adb5bd;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .notifications-header {
                padding: 15px 20px;
            }

            .notifications-header h2 {
                font-size: 20px;
            }

            .notification-title {
               
                font-size: 16px;
            }

            .notification-message {
                font-size: 14px;
            }

            .notification-time {
                font-size: 12px;
            }
        }

        /* Additional Enhancements */
        .notification-card .card-body {
            display: flex;
            align-items: flex-start;
        }

        .notification-card .notification-icon-container {
            flex-shrink: 0;
            margin-right: 15px;
        }

        .notification-card .notification-icon-container i {
            font-size: 28px;
        }

        .notification-card .notification-content .notification-title {
            font-size: 18px;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 5px;
        }

        .notification-card .notification-content .notification-time {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 8px;
        }

        .notification-card .notification-content .notification-message {
            font-size: 16px;
            color: #495057;
            margin: 0;
        }
    </style>
@endsection

@section('user-content')

<section class="notifications-section">
    <div class="container">
        <!-- Notifications Header -->
        <div class="notifications-header">
            <h2>সর্বশেষ নোটিফিকেশন</h2>
        </div>
        <!-- Notifications List -->
        <div class="row">
            @if(count($datas) > 0)
                @foreach ($datas as $data)
                    <div class="col-12 mb-4">
                        <div class="card notification-card">
                            <div class="card-body d-flex align-items-start">
                                <!-- Notification Icon -->
                                <div class="notification-icon-container me-3">
                                    <i class="fas fa-bell notification-icon"></i>
                                </div>
                                <!-- Notification Content -->
                                <div class="flex-grow-1">
                                    <div class="notification-content">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="notification-title" title="{{ $data->message_title }}">{{ $data->message_title }}</span>
                                            <small class="notification-time">
                                                <i class="fas fa-clock"></i>
                                                {{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y g:i A') }}
                                            </small>
                                        </div>
                                        <p class="notification-message mb-0">{{ $data->message }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- No Notifications Message -->
                <div class="col-12">
                    <div class="no-notifications">
                        <i class="fas fa-inbox"></i>
                        <h4>বর্তমানে কোন নোটিফিকেশন নেই।</h4>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

@endsection

@section('js')
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
                    $('#deposit_area').fadeIn();
                    $('#deposit_account_text').html('অ্যাকাউন্ট নম্বর: ' + data['account_no']);
                    $('#deposit_account_guideline').html(data['guideline']);
                },
                error: function(xhr, status, error) {
                    console.error("একটি ত্রুটি ঘটেছে: " + error);
                    alert("অ্যাকাউন্ট তথ্য লোড করতে ব্যর্থ হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।");
                }
            });
        }
    </script>
@endsection
