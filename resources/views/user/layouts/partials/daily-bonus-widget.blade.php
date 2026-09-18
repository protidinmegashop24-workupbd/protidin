<style>
    .daily-bonus-float {
        position: fixed;
        bottom: 90px;
        left: 20px;
        background: linear-gradient(135deg,#f59e0b,#f97316);
        color: #fff;
        padding: 12px 18px;
        border-radius: 999px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 8px 22px rgba(0,0,0,.25);
        z-index: 9998;
        animation: dailyBonusPulse 1.8s infinite;
        display: none;
    }
    @keyframes dailyBonusPulse {
        0% { box-shadow: 0 0 0 0 rgba(249,115,22,.5); }
        70% { box-shadow: 0 0 0 12px rgba(249,115,22,0); }
        100% { box-shadow: 0 0 0 0 rgba(249,115,22,0); }
    }
    .daily-bonus-modal-overlay {
        position: fixed; inset: 0;
        background: rgba(15,23,42,.6);
        z-index: 99998;
        display: none;
        align-items: center; justify-content: center;
        padding: 16px;
    }
    .daily-bonus-modal-box {
        background: #fff;
        border-radius: 16px;
        max-width: 480px;
        width: 100%;
        padding: 24px;
        position: relative;
        font-family: Arial, Helvetica, sans-serif;
    }
    .daily-bonus-close {
        position: absolute; top: 10px; right: 14px;
        background: none; border: none; font-size: 22px; cursor: pointer; color: #94a3b8;
    }
    .daily-bonus-days-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
        margin: 16px 0;
    }
    .daily-bonus-day-box {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 4px;
        text-align: center;
        font-size: 13px;
        font-weight: 700;
        color: #94a3b8;
    }
    .daily-bonus-day-box.claimed { background: #dcfce7; border-color: #16a34a; color: #166534; }
    .daily-bonus-day-box.claimable { background: #fef9c3; border-color: #f59e0b; color: #92400e; cursor: pointer; }
</style>

<div id="daily-bonus-float" class="daily-bonus-float">🎁 ডেইলি বোনাস নিন</div>

<div id="daily-bonus-modal" class="daily-bonus-modal-overlay">
    <div class="daily-bonus-modal-box">
        <button type="button" class="daily-bonus-close" onclick="closeDailyBonusModal()">&times;</button>
        <h4 style="font-weight:800; margin-bottom:14px;">ডেইলি লগইন বোনাস</h4>
        <div id="daily-bonus-content">লোড হচ্ছে...</div>
    </div>
</div>

<script>
(function () {
    var statusUrl = "{{ route('user.daily-bonus.status') }}";
    var claimUrl = "{{ route('user.daily-bonus.claim') }}";
    var reviewUrl = "{{ route('user.review') }}";
    var csrfToken = "{{ csrf_token() }}";

    function fetchDailyBonusStatus() {
        fetch(statusUrl, { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (status) {
                window.__dailyBonusStatus = status;
                var floatBtn = document.getElementById('daily-bonus-float');
                if (!floatBtn) return;
                if (status.eligible && status.claimed_today) {
                    floatBtn.style.display = 'none';
                } else if (status.eligible) {
                    floatBtn.textContent = '🎁 ডেইলি বোনাস নিন';
                    floatBtn.style.display = 'block';
                } else {
                    floatBtn.textContent = '⭐ রিভিউ দিয়ে বোনাস আনলক করুন';
                    floatBtn.style.display = 'block';
                }
            })
            .catch(function () {});
    }

    window.openDailyBonusModal = function () {
        var overlay = document.getElementById('daily-bonus-modal');
        var content = document.getElementById('daily-bonus-content');
        overlay.style.display = 'flex';

        var status = window.__dailyBonusStatus;
        if (!status) {
            content.innerHTML = 'লোড হচ্ছে...';
            return;
        }

        if (!status.eligible) {
            content.innerHTML =
                '<p>প্রথমে একটা রিভিউ (স্টার রেটিং + কমেন্ট) দিন। অ্যাডমিন অনুমোদন করলেই ডেইলি বোনাস শুরু হয়ে যাবে।</p>' +
                '<a href="' + reviewUrl + '" class="btn btn-success btn-sm">রিভিউ দিন</a>';
            return;
        }

        var maxDay = status.max_day || 15;
        var html = '<div class="daily-bonus-days-grid">';
        for (var d = 1; d <= maxDay; d++) {
            var cls = 'daily-bonus-day-box';
            var clickable = false;
            if (d <= status.current_streak_day) {
                cls += ' claimed';
            } else if (d === status.next_day_number && !status.claimed_today) {
                cls += ' claimable';
                clickable = true;
            }
            html += '<div class="' + cls + '"' + (clickable ? ' onclick="claimDailyBonus()"' : '') + '>Day ' + d + (d <= status.current_streak_day ? ' &#10003;' : '') + '</div>';
        }
        html += '</div>';

        if (status.claimed_today) {
            html += '<p style="color:#166534; font-weight:700;">আজকের বোনাস ইতিমধ্যে নেওয়া হয়ে গেছে। আগামীকাল আবার আসুন!</p>';
        } else if (status.next_amount) {
            html += '<p>আজকের বোনাস: <strong>$' + Number(status.next_amount).toFixed(4) + '</strong> -- উপরের হলুদ বক্সে ক্লিক করুন।</p>';
        }

        html += '<p style="color:#94a3b8; font-size:12px; margin-top:10px;">একদিন মিস করলে আবার Day 1 থেকে শুরু হবে -- তাই প্রতিদিন লগইন করে বোনাস নিতে ভুলবেন না।</p>';

        content.innerHTML = html;
    };

    window.closeDailyBonusModal = function () {
        document.getElementById('daily-bonus-modal').style.display = 'none';
    };

    window.claimDailyBonus = function () {
        fetch(claimUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(function (r) { return r.json(); })
        .then(function (result) {
            if (result.success) {
                var msg = 'Day ' + result.day_number + ' বোনাস যোগ হয়েছে: $' + Number(result.amount).toFixed(4);
                if (typeof toastr !== 'undefined') {
                    toastr.success(msg);
                } else {
                    alert(msg);
                }
                fetchDailyBonusStatus();
                setTimeout(function () { window.openDailyBonusModal(); }, 300);
            } else {
                var errMsg = result.message || 'কিছু একটা ভুল হয়েছে।';
                if (typeof toastr !== 'undefined') {
                    toastr.error(errMsg);
                } else {
                    alert(errMsg);
                }
            }
        });
    };

    document.addEventListener('DOMContentLoaded', function () {
        var floatBtn = document.getElementById('daily-bonus-float');
        if (floatBtn) {
            floatBtn.addEventListener('click', function () {
                window.openDailyBonusModal();
            });
        }
        fetchDailyBonusStatus();
    });
})();
</script>
