@extends('user.layouts.master')
@section('css')
<style>
    .ptc-view-card{
        max-width: 560px;
        margin: 30px auto;
        text-align: center;
    }
    .ptc-timer-circle{
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: 8px solid #d6ebf1;
        border-top-color: #15ba5a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
        font-weight: 700;
        margin: 20px auto;
        transition: border-top-color .3s;
    }
    .ptc-timer-circle.done{
        border-top-color: #15ba5a;
        border-color: #15ba5a;
        color: #15ba5a;
    }
    #ptc-claim-btn{
        display: none;
    }
</style>
@endsection
@section('user-content')

<div class="card ptc-view-card">
    <div class="card-body">
        <h4 class="mb-2">{{ $job->ptc_title }}</h4>
        <p class="text-muted mb-4">প্রতি ক্লিকে আয়: <strong>${{ number_format($job->ptc_each_earn, 5) }}</strong></p>

        <div id="ptc-step-visit">
            <p>নিচের বাটনে ক্লিক করলে সাইটটি নতুন ট্যাবে খুলবে এবং এখানে টাইমার শুরু হবে। টাইমার শেষ না হওয়া পর্যন্ত এই ট্যাব বন্ধ বা এখান থেকে অন্য পেজে যাবেন না, নাহলে আয় পাবেন না।</p>
            <button type="button" id="ptc-visit-btn" class="btn btn-primary btn-lg">সাইট ভিজিট করুন ও টাইমার শুরু করুন</button>
        </div>

        <div id="ptc-step-timer" style="display:none;">
            <div class="ptc-timer-circle" id="ptc-timer-circle">{{ (int) $job->ptc_wait_time }}</div>
            <p class="text-muted">অপেক্ষা করুন... এই ট্যাব বন্ধ করবেন না।</p>
            <a href="{{ $job->ptc_jobLink }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">সাইটটি আবার খুলুন</a>
        </div>

        <button type="button" id="ptc-claim-btn" class="btn btn-success btn-lg">
            Claim ${{ number_format($job->ptc_each_earn, 5) }}
        </button>

        <div id="ptc-result" class="mt-3"></div>
    </div>
</div>

@endsection
@section('js')
<script>
    const waitTime = {{ (int) $job->ptc_wait_time }};
    const jobId = {{ (int) $job->id }};
    const jobLink = @json($job->ptc_jobLink);
    const csrfToken = @json(csrf_token());

    let timerRunning = false;
    let remaining = waitTime;
    let timerInterval = null;

    function beforeUnloadHandler(e) {
        e.preventDefault();
        e.returnValue = '';
        return '';
    }

    document.getElementById('ptc-visit-btn').addEventListener('click', function () {
        window.open(jobLink, '_blank', 'noopener,noreferrer');

        document.getElementById('ptc-step-visit').style.display = 'none';
        document.getElementById('ptc-step-timer').style.display = 'block';

        timerRunning = true;
        window.addEventListener('beforeunload', beforeUnloadHandler);

        const circle = document.getElementById('ptc-timer-circle');
        timerInterval = setInterval(function () {
            remaining--;
            if (remaining <= 0) {
                clearInterval(timerInterval);
                timerRunning = false;
                window.removeEventListener('beforeunload', beforeUnloadHandler);

                circle.textContent = '✓';
                circle.classList.add('done');
                document.getElementById('ptc-step-timer').style.display = 'none';
                document.getElementById('ptc-claim-btn').style.display = 'inline-block';
            } else {
                circle.textContent = remaining;
            }
        }, 1000);
    });

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
                '<p class="text-muted">এখন এই ট্যাবটি বন্ধ করতে পারেন।</p>';
            btn.style.display = 'none';
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
