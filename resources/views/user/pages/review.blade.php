@extends('user.layouts.master')

@section('css')
<style>
    .rv-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 10px 26px rgba(15,23,42,.05);
        padding: 24px;
        margin-bottom: 24px;
    }
    .rv-title {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
    }
    .rv-star-input {
        font-size: 32px;
        color: #cbd5e1;
        cursor: pointer;
        direction: ltr;
    }
    .rv-star-input i.active { color: #f5b301; }
    .rv-status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 13px;
        margin-bottom: 14px;
    }
    .rv-status-pending { background: #fef3c7; color: #92400e; }
    .rv-status-approved { background: #dcfce7; color: #166534; }
    .rv-status-rejected { background: #fee2e2; color: #991b1b; }
    .rv-bonus-box {
        background: linear-gradient(135deg, #ecfeff 0%, #f8fafc 100%);
        border: 1px solid #dbe7ef;
        border-radius: 14px;
        padding: 18px;
    }
</style>
@endsection

@section('user-content')
<div class="rv-card">
    <div class="rv-title">{{ $title }}</div>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($review)
        <span class="rv-status-badge rv-status-{{ $review->status }}">
            @if($review->status == 'pending') আপনার রিভিউ পর্যালোচনাধীন (Pending)
            @elseif($review->status == 'approved') আপনার রিভিউ অনুমোদিত হয়েছে ✅
            @else আপনার রিভিউ প্রত্যাখ্যান করা হয়েছে
            @endif
        </span>
    @endif

    @if(!$review || $review->status != 'approved')
        <form action="{{ route('user.review.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Rating</label><br>
                <div class="rv-star-input" id="rv-star-input">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="{{ $review && $review->rating >= $i ? 'fas active' : 'far' }} fa-star" data-value="{{ $i }}"></i>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rv-rating-value" value="{{ $review->rating ?? 0 }}">
            </div>

            <div class="form-group">
                <label>Your Comment</label>
                <textarea name="comment" class="form-control" rows="4" minlength="10" maxlength="1000" required>{{ old('comment', $review->comment ?? '') }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Submit Review</button>
        </form>
    @else
        <p><strong>Rating:</strong>
            @for($i = 1; $i <= 5; $i++)
                <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star text-warning"></i>
            @endfor
        </p>
        <p><strong>Comment:</strong> {{ $review->comment }}</p>
    @endif
</div>

@if($review && $review->status == 'approved')
<div class="rv-bonus-box">
    <h4 style="font-weight:800; margin-bottom:10px;">Daily Login Bonus</h4>
    <p style="margin-bottom:6px;">প্রতিদিন সাইটে লগইন করলে ছোট একটা বোনাস পাবেন, দিন দিন বাড়তে থাকবে (১৫ দিন পর্যন্ত)।</p>
    <p style="margin-bottom:6px;">আজ পর্যন্ত ক্লেইম করা দিন: <strong>{{ $claimedDays }}</strong></p>
    @if($todayClaimed)
        <p style="color:#166534; font-weight:700;">আজকের বোনাস ইতিমধ্যে যোগ হয়ে গেছে। ✅</p>
    @endif
    @if($nextBonusAmount)
        <p style="margin-bottom:0;">পরের বোনাস (আগামীকাল লগইন করলে): <strong>${{ number_format($nextBonusAmount, 4) }}</strong></p>
    @endif
</div>
@endif
@endsection

@section('js')
<script>
    var stars = document.querySelectorAll('#rv-star-input i');
    var ratingInput = document.getElementById('rv-rating-value');
    if (stars.length) {
        stars.forEach(function (star) {
            star.addEventListener('click', function () {
                var value = parseInt(star.getAttribute('data-value'), 10);
                ratingInput.value = value;
                stars.forEach(function (s) {
                    var sValue = parseInt(s.getAttribute('data-value'), 10);
                    if (sValue <= value) {
                        s.classList.add('fas', 'active');
                        s.classList.remove('far');
                    } else {
                        s.classList.remove('fas', 'active');
                        s.classList.add('far');
                    }
                });
            });
        });
    }
</script>
@endsection
