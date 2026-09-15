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
        }


        .container-earnings {
            width: 100%;
            /* max-width: 850px;  */
            margin: 0 auto;
            padding: 30px 15px;
        }

        .page-title {
            font-weight: 700;
            color: var(--feed-brand-green);
            margin-bottom: 10px;
            text-align: center;
        }

        .page-subtitle {
            text-align: center;
            color: var(--feed-text-muted);
            margin-bottom: 40px;
            font-size: 0.95rem;
        }

        .earning-card {
            background: var(--feed-pure-white);
            border: 1px solid var(--feed-border-color);
            border-radius: 20px;
            margin-bottom: 40px;
            box-shadow: var(--feed-card-shadow);
            overflow: hidden;
        }

        .card-header-custom {
            background-color: var(--feed-brand-green-soft);
            padding: 15px 25px;
            border-bottom: 1px solid var(--feed-border-color);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-header-custom h5 {
            margin: 0;
            font-weight: 700;
            color: var(--feed-brand-green);
            font-size: 1.1rem;
        }

        /* Responsive Table Styling */
        .table-responsive-custom {
            width: 100%;
            overflow-x: auto;
        }

        .earning-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .earning-table th {
            background-color: #f8fafc;
            color: var(--feed-text-muted);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 15px 25px;
            border-bottom: 2px solid var(--feed-border-color);
            text-align: left;
        }

        .earning-table td {
            padding: 18px 25px;
            border-bottom: 1px solid var(--feed-border-color);
            vertical-align: middle;
            font-size: 0.95rem;
        }

        .earning-table tr:last-child td {
            border-bottom: none;
        }

        .type-col {
            font-weight: 600;
            color: var(--feed-text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .type-icon {
            color: var(--feed-brand-green);
            font-size: 1.1rem;
        }

        .price-standard {
            font-weight: 700;
            color: #334155;
        }

        .price-elite {
            font-weight: 800;
            color: var(--feed-brand-green);
            background-color: var(--feed-brand-green-soft);
            padding: 4px 12px;
            border-radius: 12px;
            display: inline-block;
        }

        /* Mobile Adjustments */
        @media (max-width: 576px) {
            .container-earnings { padding: 15px 10px; }
            .earning-table th, .earning-table td {
                padding: 12px 15px;
                font-size: 0.85rem;
            }
            .card-header-custom { padding: 12px 15px; }
            .price-elite { padding: 3px 8px; font-size: 0.8rem; }
        }
    </style>
@endsection 
{{-- Css End Here For Single Page  --}}
@section('user-content')
<div class="row" style="background-color:#d6ebf1;">

    <div class="container-earnings">
        
        <div class="page-title-section">
            <h2 class="page-title">Community Earning Rates</h2>
            <p class="page-subtitle">Understand how much you can earn based on your activity and membership status.</p>
        </div>

        <!-- Table 1: Post Owner Rewards -->
        <div class="earning-card">
            <div class="card-header-custom">
                <i class="bi bi-person-badge-fill fs-4 text-success"></i>
                <h5>Post Owner Rewards</h5>
            </div>
            <div class="table-responsive-custom">
                <table class="earning-table">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Earning Type</th>
                            <th style="width: 30%;">Standard Earn</th>
                            <th style="width: 30%;">Affiliate bonus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="type-col">
                                <i class="bi bi-pencil-square type-icon"></i>
                                New Post Submission
                            </td>
                            <td class="price-standard"><span class="price-elite">+{{$prices->firstWhere('bonusKey','newPost')->bonusRate}} $</span></td>
                            <td>+{{number_format(($prices->firstWhere('bonusKey','newPost')->bonusRate * $percent) / 100 , 6, '.' , '') }} $</td>
                        </tr>
                        {{-- <tr>
                            <td class="type-col">
                                <i class="bi bi-heart-fill type-icon"></i>
                                Receive a Like
                            </td>
                            <td class="price-standard">0.05 Points</td>
                            <td><span class="price-elite">0.15 Points</span></td>
                        </tr>
                        <tr>
                            <td class="type-col">
                                <i class="bi bi-chat-fill type-icon"></i>
                                Receive a Comment
                            </td>
                            <td class="price-standard">0.10 Points</td>
                            <td><span class="price-elite">0.25 Points</span></td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table 2: Post Viewer Rewards -->
        <div class="earning-card">
            <div class="card-header-custom">
                <i class="bi bi-eye-fill fs-4 text-success"></i>
                <h5>Post Viewer Rewards</h5>
            </div>
            <div class="table-responsive-custom">
                <table class="earning-table">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Earning Type</th>
                            <th style="width: 30%;">Standard Price</th>
                            <th style="width: 30%;">Elite Bonus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="type-col">
                                <i class="bi bi-hand-thumbs-up type-icon"></i>
                                Like others' Post
                            </td>
                            <td class="price-standard"><span class="price-elite">+{{$prices->firstWhere('bonusKey','postViewerLink')->bonusRate}}$</span></td>
                            <td>+{{number_format(($prices->firstWhere('bonusKey','postViewerLink')->bonusRate * $percent) / 100 , 6, '.' , '') }}$</td>
                        </tr>
                        <tr>
                            <td class="type-col">
                                <i class="bi bi-chat-dots type-icon"></i>
                                Comment on Post
                            </td>
                            <td class="price-standard"><span class="price-elite">+{{$prices->firstWhere('bonusKey','postViewerComment')->bonusRate}}$</span></td>
                            <td>+{{number_format(($prices->firstWhere('bonusKey','postViewerComment')->bonusRate * $percent) / 100 , 6, '.' , '') }}$</td>
                        </tr>
                        <tr>
                            <td class="type-col">
                                <i class="bi bi-building-fill-lock type-icon"></i>
                                Max Post One Day
                            </td>
                            <td class="price-standard" style="text-align: center" colspan="2">
                                <span class="price-elite">{{number_format($prices->firstWhere('bonusKey','maxPostPerDay')->bonusRate)}} Pcs</span>
                            </td>
                            {{-- <td><span class="price-elite">0.20 Points</span></td> --}}
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>





    </div>
</div>
@endsection
 {{-- JS Start For Single Page  --}}
@section('js')

@endsection
 {{-- JS End For Single Page  --}}