@extends('user.layouts.master')
{{-- Css Start From Here For Single Page  --}}
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --feed-brand-green: #008000;
            --feed-brand-green-soft: #f0fdf4;
            --feed-pure-white: #ffffff;
            --feed-border-color: #f1f3f5;
            --feed-text-main: #1a1d23;
            --feed-text-muted: #64748b;
            --feed-card-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }.dashboard-container {
            width: 100%;
            /* max-width: 1000px;  */
            margin: 0 auto;
            /* padding: 30px 40px; */
        }

        /* --- Header Section --- */
        .dashboard-header {
            background-color: var(--feed-pure-white);
            /* padding: 25px; */
            border-radius: 20px;
            box-shadow: var(--feed-card-shadow);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }

        .user-welcome h2 {
            margin: 0;
            font-weight: 700;
            color: var(--feed-brand-green);
            font-size: 1.5rem;
        }

        .user-welcome p {
            margin: 5px 0 0;
            color: var(--feed-text-muted);
            font-size: 0.95rem;
        }

        .main-balance-card {
            background: linear-gradient(135deg, #008000 0%, #006400 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 15px;
            text-align: right;
            min-width: 200px;
        }

        .balance-label {
            font-size: 0.85rem;
            opacity: 0.9;
            display: block;
            margin-bottom: 5px;
        }

        .balance-amount {
            font-size: 1.8rem;
            font-weight: 700;
            display: block;
        }

        /* --- Stats Grid --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background-color: var(--feed-pure-white);
            border-radius: 16px;
            padding: 25px;
            box-shadow: var(--feed-card-shadow);
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid transparent;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--feed-brand-green-soft);
            box-shadow: 0 10px 25px rgba(0, 128, 0, 0.1);
        }

        .stat-icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        /* Icon Colors */
        .icon-green { background-color: var(--feed-brand-green-soft); color: var(--feed-brand-green); }
        .icon-blue { background-color: #eff6ff; color: #3b82f6; }
        .icon-purple { background-color: #f5f3ff; color: #8b5cf6; }
        .icon-orange { background-color: #fff7ed; color: #f97316; }
        .icon-pink { background-color: #fdf2f8; color: #ec4899; }

        .stat-title {
            color: var(--feed-text-muted);
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            color: var(--feed-text-main);
            font-size: 1.6rem;
            font-weight: 800;
        }

        .currency {
            color: var(--feed-brand-green);
            margin-right: 2px;
        }

        /* Section Titles */
        .section-title {
            font-weight: 700;
            color: var(--feed-text-main);
            margin-bottom: 20px;
            padding-left: 5px;
            border-left: 4px solid var(--feed-brand-green);
            line-height: 1;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }
            .main-balance-card {
                width: 100%;
                text-align: left;
                margin-top: 10px;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr); /* 2 columns on mobile */
                gap: 15px;
            }
            .stat-card { padding: 20px; }
            .stat-value { font-size: 1.3rem; }
            .stat-icon-wrapper { width: 40px; height: 40px; font-size: 1.2rem; }
        }
    </style>
@endsection 
@section('user-content')
<div class="row">
    <div class="dashboard-container">
        
        <!-- Header & Main Balance -->
        <div class="dashboard-header">
            <div class="user-welcome">
                <h2>Dashboard Overview (Last 6 Months)</h2>
                <p style="background-color: red;color: white;padding: 10px;border-radius: 15px;font-weight: bold;">
                    Note : Earned Money Can be Freeze or vanished if Post Rejected By Admin Review
                </p>
            </div>
            <div class="main-balance-card">
                <span class="balance-label">Total Earnings</span>
                <span class="balance-amount">${{$totalEarnings}}</span>
            </div>
        </div>

        <!-- Section 1: Content Analytics -->
        <h4 class="section-title">Activity Stats</h4>
        <div class="stats-grid">
            <!-- Total Posts -->
            <div class="stat-card">
                <div class="stat-icon-wrapper icon-blue">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <div class="stat-title">Total Posts</div>
                    <div class="stat-value">{{number_format($totalPosts)}}</div>
                </div>
            </div>

            <!-- Total Likes -->
            <div class="stat-card">
                <div class="stat-icon-wrapper icon-pink">
                    <i class="bi bi-heart-fill"></i>
                </div>
                <div>
                    <div class="stat-title">Total Likes</div>
                    <div class="stat-value">{{number_format($totalLikes)}}</div>
                </div>
            </div>

            <!-- Total Comments -->
            <div class="stat-card">
                <div class="stat-icon-wrapper icon-orange">
                    <i class="bi bi-chat-dots-fill"></i>
                </div>
                <div>
                    <div class="stat-title">Total Comments</div>
                    <div class="stat-value">{{number_format($totalComments)}}</div>
                </div>
            </div>
        </div>

        <!-- Section 2: Earnings Breakdown -->
        <h4 class="section-title">Earnings Breakdown</h4>
        <div class="stats-grid">
            <!-- Earn from Post -->
            <div class="stat-card">
                <div class="stat-icon-wrapper icon-green">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div>
                    <div class="stat-title">Post Earnings</div>
                    <div class="stat-value"><span class="currency">$</span>{{$postEarning}}</div>
                </div>
            </div>

            <!-- Earn from Like -->
            <div class="stat-card">
                <div class="stat-icon-wrapper icon-green">
                    <i class="bi bi-hand-thumbs-up"></i>
                </div>
                <div>
                    <div class="stat-title">Like Earnings</div>
                    <div class="stat-value"><span class="currency">$</span>{{$likeEarning}}</div>
                </div>
            </div>

            <!-- Earn from Comment -->
            <div class="stat-card">
                <div class="stat-icon-wrapper icon-green">
                    <i class="bi bi-chat-text"></i>
                </div>
                <div>
                    <div class="stat-title">Comment Earnings</div>
                    <div class="stat-value"><span class="currency">$</span>{{$commentEarning}}</div>
                </div>
            </div>

            <!-- Earn from Affiliate -->
            <div class="stat-card">
                <div class="stat-icon-wrapper icon-purple">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="stat-title">Affiliate Comm.</div>
                    <div class="stat-value"><span class="currency">$</span>{{$affiliateEarning}}</div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection