@extends('user.layouts.master')
@section('css')
    <style>
        .vm-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            height: 100%;
        }
        .vm-thumb {
            width: 100%;
            height: 260px;
            object-fit: cover;
            background: #0f172a;
        }
        .vm-thumb-placeholder {
            width: 100%;
            height: 260px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f172a;
            color: #fff;
            font-size: 32px;
        }
        .vm-body { padding: 14px; }
        .vm-title { font-weight: bold; font-size: 15px; margin-bottom: 4px; }
        .vm-cost { color: #16a34a; font-weight: bold; }
        .vm-btn {
            display: block;
            text-align: center;
            background: #16a34a;
            color: #fff;
            border-radius: 8px;
            padding: 10px;
            font-weight: bold;
            margin-top: 10px;
            text-decoration: none;
        }
        .vm-btn:hover { background: #15803d; color: #fff; }
        .vm-category-title {
            font-size: 20px;
            font-weight: bold;
            margin: 24px 0 14px;
            color: #1e293b;
        }
    </style>
@endsection

@section('user-content')
<div class="container-fluid mt-2">
    <div class="row justify-content-center">
        <div class="col-lg-11 col-12">

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <h4 class="mb-0">🎬 {{ $title }}</h4>
                <a href="{{ route('user.video-maker.my-videos') }}" class="btn btn-outline-primary btn-sm">My Videos</a>
            </div>

            <div class="alert alert-info" style="font-size:14px;">
                একটা টেমপ্লেট বাছাই করো, নিজের টেক্সট লিখো (বাংলা/আরবি/ইংরেজি যেকোনো ভাষায়), আর কিছুক্ষণের মধ্যেই একটা রিয়েল ভিডিও বানিয়ে দেবে — ডাউনলোড করে সোশ্যাল মিডিয়ায় পোস্ট করতে পারবে। প্রতিটা ভিডিওর একটা ছোট খরচ আছে (নিচে দেখানো আছে), আপনার earning balance থেকে কাটবে।
            </div>

            @forelse($templates as $category => $categoryTemplates)
                <div class="vm-category-title">{{ $category }}</div>
                <div class="row">
                    @foreach($categoryTemplates as $template)
                        <div class="col-md-3 col-6 mb-4">
                            <div class="vm-card">
                                @if($template->thumbnail_path)
                                    <img src="{{ asset($template->thumbnail_path) }}" class="vm-thumb" alt="{{ $template->name }}">
                                @else
                                    <div class="vm-thumb-placeholder"><i class="fas fa-video"></i></div>
                                @endif
                                <div class="vm-body">
                                    <div class="vm-title">{{ $template->name }}</div>
                                    <div class="vm-cost">${{ number_format($template->cost, 4) }}</div>
                                    <a href="{{ route('user.video-maker.create', $template->id) }}" class="vm-btn">Use This</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="text-center text-muted py-5">এখন কোনো ভিডিও টেমপ্লেট নেই। পরে আবার চেক করুন।</div>
            @endforelse

        </div>
    </div>
</div>
@endsection
