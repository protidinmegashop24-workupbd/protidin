@extends('user.layouts.master')
@section('css')
<style>
    .ptc-view-wrap{
        max-width: 900px;
        margin: 20px auto;
    }
    .ptc-timer-bar{
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        background: #d6ebf1;
        border-radius: 8px;
        padding: 14px 18px;
        margin-bottom: 14px;
    }
    .ptc-timer-bar .label{
        font-weight: 600;
        color: #172b4d;
    }
    .ptc-timer-number{
        font-size: 26px;
        font-weight: 800;
        color: #d9534f;
        min-width: 60px;
        text-align: center;
    }
    .ptc-timer-number.done{
        color: #15ba5a;
    }
    .ptc-frame-wrap{
        position: relative;
        border: 1px solid #d6ebf1;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }
    .ptc-frame-wrap iframe{
        width: 100%;
        height: 65vh;
        min-height: 420px;
        border: 0;
        display: block;
    }
    .ptc-frame-fallback{
        text-align: center;
        padding: 40px 15px;
        color: #6c757d;
    }
    #ptc-claim-btn{
        display: none;
    }
    .ptc-actions{
        text-align: center;
        margin-top: 16px;
    }
</style>
@endsection
@section('user-content')

<div class="ptc-view-wrap">
    <div class="ptc-timer-bar">
        <div class="label">
            {{ $job->ptc_title }}<br>
            <small class="text-muted">প্রতি ক্লিকে আয়: ${{ number_format($job->ptc_each_earn, 5) }}</small>
        </div>
        <div class="text-center">
            <div class="label">অপেক্ষা করুন</div>
            <div class="ptc-timer-number" id="ptc-timer-number">{{ (int) $job->ptc_wait_time }}</div>
            <div class="label" style="font-weight:400;font-size:12px;">সেকেন্ড</div>
        </div>
    </div>

    <div class="alert alert-warning" id="ptc-warning-box">
        <strong>এই ট্যাব বন্ধ করবেন না বা অন্য পেজে যাবেন না</strong> — টাইমার শেষ হওয়ার আগে ট্যাব ছাড়লে/বন্ধ করলে এই জব ভিউ হিসেবে গণনা হবে না এবং আয় পাবেন না।
    </div>

    <div class="ptc-frame-wrap">
        <iframe src="{{ $job->ptc_jobLink }}" id="ptc-frame" referrerpolicy="no-referrer" sandbox="allow-scripts allow-same-origin allow-popups allow-forms"></iframe>
    </div>
    <p class="text-center text-muted mt-2" style="font-size:13px;">
        সাইটটি উপরে না দেখা গেলে
        <a href="{{ $job->ptc_jobLink }}" target="_blank" rel="noopener noreferrer">এখানে ক্লিক করে নতুন ট্যাবে দেখুন</a>
        (তবুও টাইমার এই ট্যাবেই চলবে, এই ট্যাব বন্ধ করবেন না)।
    </p>

    <div class="ptc-actions">
        <button type="button" id="ptc-claim-btn" class="btn btn-success btn-lg">
            Claim ${{ number_format($job->ptc_each_earn, 5) }}
        </button>
        <div id="ptc-result" class="mt-3"></div>
    </div>
</div>

@endsection
@section('js')
<script>
    const jobId = {{ (int) $job->id }};
    const csrfToken = @json(csrf_token());

    let remaining = {{ (int) $job->ptc_wait_time }};
    const numberEl = document.getElementById('ptc-timer-number');

    function beforeUnloadHandler(e) {
        e.preventDefault();
        e.returnValue = '';
        return '';
    }
    // Close-protection is active from the moment this tab opens until the
    // timer finishes -- this is the strongest guard a web page can use
    // (browsers show their own "leave site?" prompt; nothing can force a
    // tab to stay open).
    window.addEventListener('beforeunload', beforeUnloadHandler);

    const timerInterval = setInterval(function () {
        remaining--;
        if (remaining <= 0) {
            clearInterval(timerInterval);
            window.removeEventListener('beforeunload', beforeUnloadHandler);

            numberEl.textContent = '✓';
            numberEl.classList.add('done');
            document.getElementById('ptc-warning-box').style.display = 'none';
            document.getElementById('ptc-claim-btn').style.display = 'inline-block';
        } else {
            numberEl.textContent = remaining;
        }
    }, 1000);

    document.getElementById('ptc-claim-btn').addEventListener('click', function () {
        const btn = this;
        btn.disabled = true;
        btn.textContent = 'Claiming...';

        fetch("{{ route('user.jobSeeker') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ id: jobId })
        })
        .then(res => res.text())
        .then(data => {
            document.getElementById('ptc-result').innerHTML =
                '<div class="alert alert-success">' + data + '</div>' +
                '<p class="text-muted">এখন এই ট্যাবটি বন্ধ করতে পারেন। ৩ সেকেন্ড পর জব লিস্টে ফিরে যাবেন।</p>';
            btn.style.display = 'none';
            setTimeout(function () {
                window.location.href = "{{ route('user.ptcList') }}";
            }, 3000);
        })
        .catch(() => {
            document.getElementById('ptc-result').innerHTML =
                '<div class="alert alert-danger">কিছু একটা সমস্যা হয়েছে, আবার চেষ্টা করুন।</div>';
            btn.disabled = false;
            btn.textContent = 'Claim ${{ number_format($job->ptc_each_earn, 5) }}';
        });
    });
</script>
@endsection
