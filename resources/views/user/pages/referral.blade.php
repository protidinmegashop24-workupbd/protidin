@extends('user.layouts.master')

@section('css')
<style>
    .rf-wrap { padding: 20px 0; }
    .rf-hero {
        background: linear-gradient(135deg, #ecfeff 0%, #f8fafc 50%, #ffffff 100%);
        border: 1px solid #dbe7ef;
        border-radius: 22px;
        box-shadow: 0 14px 30px rgba(15,23,42,.06);
        padding: 28px;
        margin-bottom: 24px;
    }
    .rf-title {
        font-size: 32px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }
    .rf-sub {
        color: #64748b;
        font-size: 15px;
        line-height: 1.8;
        margin-bottom: 18px;
    }
    .rf-link-box {
        background: #fff;
        border: 1px solid #dbe7ef;
        border-radius: 14px;
        padding: 16px;
        word-break: break-all;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 14px;
    }
    .rf-btn {
        display: inline-block;
        padding: 10px 16px;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none !important;
        border: 0;
        margin: 0 8px 8px 0;
    }
    .rf-btn-copy { background: #0f766e; color: #fff !important; }
    .rf-btn-wa { background: #16a34a; color: #fff !important; }
    .rf-btn-fb { background: #1877f2; color: #fff !important; }
    .rf-btn-tg { background: #0ea5e9; color: #fff !important; }
    .rf-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 10px 26px rgba(15,23,42,.05);
        padding: 22px;
        height: 100%;
    }
    .rf-card-title {
        color: #64748b;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .rf-card-value {
        color: #0f172a;
        font-size: 30px;
        font-weight: 800;
        line-height: 1.2;
    }
    .rf-progress-wrap {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 10px 26px rgba(15,23,42,.05);
        padding: 22px;
        margin-top: 24px;
    }
    .rf-progress-bar-bg {
        width: 100%;
        height: 14px;
        border-radius: 999px;
        background: #e5edf5;
        overflow: hidden;
    }
    .rf-progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #0f766e 0%, #14b8a6 100%);
        border-radius: 999px;
    }
    .rf-section-title {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 16px;
    }
    .rf-log-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 10px 26px rgba(15,23,42,.05);
        padding: 22px;
        margin-top: 24px;
    }
    .rf-log-item {
        padding: 12px 0;
        border-bottom: 1px dashed #e2e8f0;
    }
    .rf-log-item:last-child {
        border-bottom: 0;
    }
</style>
@endsection

@section('user-content')
<div class="rf-wrap">
    <div class="rf-hero">
        <div class="rf-title">{{ $title }}</div>
        <div class="rf-sub">
            {{ referral_notice() }} Share your referral link and earn from deposits, earnings, activation bonuses, marketplace bonuses, and milestone rewards.
        </div>

        <input type="hidden" value="{{ $referralLink }}" id="refer_link">

        <div class="rf-link-box">{{ $referralLink }}</div>

        <button type="button" class="rf-btn rf-btn-copy" onclick="copyReferLink()">Copy Link</button>

        <a class="rf-btn rf-btn-wa" target="_blank"
           href="https://wa.me/?text={{ urlencode('Join Workup BD with my referral link: ' . $referralLink) }}">
            WhatsApp
        </a>

        <a class="rf-btn rf-btn-fb" target="_blank"
           href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($referralLink) }}">
            Facebook
        </a>

        <a class="rf-btn rf-btn-tg" target="_blank"
           href="https://t.me/share/url?url={{ urlencode($referralLink) }}&text={{ urlencode('Join Workup BD through my referral link') }}">
            Telegram
        </a>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="rf-card">
                <div class="rf-card-title">Total Referrals</div>
                <div class="rf-card-value">{{ $totalReferrals }}</div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-4">
            <div class="rf-card">
                <div class="rf-card-title">Active Referrals</div>
                <div class="rf-card-value">{{ $activeReferrals }}</div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-4">
            <div class="rf-card">
                <div class="rf-card-title">Deposit Commission</div>
                <div class="rf-card-value">${{ number_format($depositCommission, 2) }}</div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-4">
            <div class="rf-card">
                <div class="rf-card-title">Earning Commission</div>
                <div class="rf-card-value">${{ number_format($earningCommission, 2) }}</div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4 mb-4">
            <div class="rf-card">
                <div class="rf-card-title">Activation + Marketplace Bonus</div>
                <div class="rf-card-value">${{ number_format($activationBonus, 2) }}</div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4 mb-4">
            <div class="rf-card">
                <div class="rf-card-title">Milestone Bonus</div>
                <div class="rf-card-value">${{ number_format($milestoneBonus, 2) }}</div>
            </div>
        </div>

        <div class="col-md-12 col-xl-4 mb-4">
            <div class="rf-card">
                <div class="rf-card-title">Total Referral Income</div>
                <div class="rf-card-value">${{ number_format($totalReferralIncome, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="rf-progress-wrap">
        <div class="rf-section-title">Milestone Progress</div>

        @if($nextMilestoneTarget)
            <p class="mb-2">
                Next target: <strong>{{ $nextMilestoneTarget }} active referrals</strong>
                — Reward: <strong>${{ number_format($nextMilestoneReward, 2) }}</strong>
            </p>
            <div class="rf-progress-bar-bg">
                <div class="rf-progress-bar-fill" style="width: {{ $progressPercent }}%;"></div>
            </div>
            <p class="mt-2 mb-0">{{ $progressPercent }}% completed</p>
        @else
            <p class="mb-0">You reached all current milestone targets.</p>
        @endif
    </div>

    <div class="rf-log-card">
        <div class="rf-section-title">Recent Referral Rewards</div>

        @forelse($recentRewards as $log)
            <div class="rf-log-item">
                <strong>${{ number_format($log->amount, 2) }}</strong> —
                {{ ucwords(str_replace('_', ' ', $log->type)) }}
                <br>
                <small>{{ $log->note }}</small>
            </div>
        @empty
            <p class="mb-0">No referral reward history yet.</p>
        @endforelse
    </div>
</div>
@endsection

@section('js')
<script>
    function copyReferLink() {
        var refer_link = document.getElementById('refer_link').value;
        navigator.clipboard.writeText(refer_link);
        alert('Referral link copied successfully!');
    }
</script>
@endsection