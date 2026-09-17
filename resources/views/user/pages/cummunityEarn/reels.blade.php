@extends('user.layouts.master')

@section('title')
    Reels
@endsection

@section('css')
<style>
    .reel-row {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        justify-content: center;
        margin-bottom: 25px;
    }
    .reel-card {
        position: relative;
        width: 100%;
        max-width: 380px;
        aspect-ratio: 9 / 16;
        max-height: 78vh;
        border-radius: 16px;
        overflow: hidden;
        background: #000;
        flex-shrink: 0;
    }
    .reel-video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        background: #000;
    }
    .reel-overlay-top {
        position: absolute;
        top: 10px;
        left: 10px;
        right: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #fff;
        text-shadow: 0 1px 3px rgba(0,0,0,.6);
    }
    .reel-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        background: #444;
        display:flex;align-items:center;justify-content:center;
        font-weight:800;color:#fff;
        font-size: 13px;
    }
    .reel-follow-btn {
        margin-left: auto;
        background: rgba(255,255,255,.15);
        border: 1px solid #fff;
        color: #fff;
        border-radius: 16px;
        padding: 3px 12px;
        font-size: 11px;
        font-weight: 700;
    }
    .reel-follow-btn.active {
        background: #198754;
        border-color: #198754;
    }
    .reel-overlay-bottom {
        position: absolute;
        left: 10px;
        right: 60px;
        bottom: 14px;
        color: #fff;
        text-shadow: 0 1px 3px rgba(0,0,0,.6);
        font-size: 13px;
    }
    .reel-actions {
        position: absolute;
        right: 8px;
        bottom: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 14px;
        color: #fff;
    }
    .reel-action-btn {
        background: none;
        border: none;
        color: #fff;
        font-size: 22px;
        text-align: center;
        text-shadow: 0 1px 3px rgba(0,0,0,.6);
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
    }
    .reel-action-btn span {
        font-size: 10px;
        font-weight: 700;
    }
    .reel-action-btn.active i { color: #ff4757; }

    .reel-ad-progress-wrap {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: rgba(255,255,255,.25);
        z-index: 2;
    }
    .reel-ad-progress-bar {
        height: 100%;
        width: 0%;
        background: #ffc107;
        transition: width .2s linear;
    }
    .reel-ad-reward-note {
        font-weight: 800;
        font-size: 13px;
        background: rgba(0,0,0,.4);
        display: inline-block;
        padding: 3px 10px;
        border-radius: 10px;
    }

    .reel-mute-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,.4);
        border: none;
        color: #fff;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        font-size: 13px;
    }

    .reel-fab {
        position: fixed;
        right: 18px;
        bottom: 90px;
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: #198754;
        color: #fff;
        border: none;
        font-size: 26px;
        box-shadow: 0 4px 12px rgba(0,0,0,.35);
        z-index: 40;
    }

    .reel-empty {
        text-align: center;
        padding: 60px 20px;
        background: var(--feed-pure-white, #fff);
        border: 1px solid var(--feed-border-color, #eee);
        border-radius: 16px;
        width: 100%;
        max-width: 380px;
    }

    /* Reused from the Community feed's side-ad rail so ad placement stays
       consistent across the site (same source: ad_banner()). */
    .reel-side-ad {
        width: 220px;
        flex-shrink: 0;
        background: var(--feed-pure-white, #fff);
        border: 1px solid var(--feed-border-color, #eee);
        border-radius: 16px;
        box-shadow: var(--feed-card-shadow, 0 1px 4px rgba(0,0,0,.08));
        padding: 8px;
        position: sticky;
        top: 12px;
    }
    .reel-side-ad .ad-placeholder {
        width: 100%;
        min-height: 250px;
        border: 1px dashed #bbb;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        font-size: 12px;
        text-align: center;
    }
    .reel-side-ad .ads-img { width: 100%; height: 300px; object-fit: cover; border-radius: 8px; }

    @media (max-width: 700px) {
        .reel-side-ad { display: none; }
    }

    .reel-upload-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.7);
        z-index: 100;
        display: none;
        align-items: center;
        justify-content: center;
    }
    .reel-upload-modal {
        background: #fff;
        border-radius: 14px;
        width: 92%;
        max-width: 420px;
        padding: 18px;
    }
    .reel-upload-modal h5 { font-weight: 900; margin-bottom: 12px; }
    .reel-upload-preview {
        width: 100%;
        max-height: 300px;
        border-radius: 10px;
        background: #000;
        display: none;
        margin-bottom: 10px;
    }
</style>
@endsection

@section('user-content')
<div class="container-fluid">
    <h4 class="mb-3" style="font-weight:900;">🎬 Reels</h4>

    @forelse($reels as $reel)
        <div class="reel-row">
            <div class="reel-card" data-post-id="{{ $reel->id }}">
                <video class="reel-video" src="{{ asset($reel->video) }}" loop playsinline muted></video>

                <button type="button" class="reel-mute-btn" onclick="toggleMute(this)">
                    <i class="bi bi-volume-mute-fill"></i>
                </button>

                <div class="reel-overlay-top">
                    <div class="reel-avatar">
                        @if($reel->user->image)
                            <img src="{{ asset($reel->user->image) }}" class="reel-avatar" alt="{{ $reel->user->name }}">
                        @else
                            {{ strtoupper(substr($reel->user->name, 0, 1)) }}
                        @endif
                    </div>
                    <strong>{{ $reel->user->name }}</strong>
                    @if(communityFollowEnabled() && Auth::id() != $reel->userId)
                        <button type="button" class="reel-follow-btn {{ in_array($reel->userId, $followingIds) ? 'active' : '' }}" data-user-id="{{ $reel->userId }}">
                            {{ in_array($reel->userId, $followingIds) ? 'Following' : '+ Follow' }}
                        </button>
                    @endif
                </div>

                <div class="reel-overlay-bottom">
                    @if($reel->postContent && $reel->postContent !== 'Reel')
                        <div>{{ $reel->postContent }}</div>
                    @endif
                </div>

                <div class="reel-actions">
                    <button type="button" class="reel-action-btn like-btn {{ (Auth::id() == $reel->userId || $reel->has_liked) ? 'active' : '' }}" data-post-id="{{ $reel->id }}">
                        <i class="bi bi-heart-fill"></i>
                        <span class="like-count" data-count="{{ $reel->likes }}">{{ $reel->likes }}</span>
                    </button>
                    <a href="{{ route('user.viewCommunityPP', $reel->id) }}" class="reel-action-btn">
                        <i class="bi bi-chat-fill"></i>
                        <span>{{ $reel->commnets }}</span>
                    </a>
                    <button type="button" class="reel-action-btn" onclick="copyReelLink('{{ $reel->id }}')">
                        <i class="bi bi-share-fill"></i>
                        <span>Share</span>
                    </button>
                </div>
            </div>

            <div class="reel-side-ad">
                @if($communityAds->count())
                    <div id="reel-ad-{{ $reel->id }}" class="carousel slide" data-bs-ride="carousel" data-bs-interval="8000">
                        <div class="carousel-inner" role="listbox">
                            @foreach($communityAds as $adKey => $ad)
                                <div class="carousel-item @if($adKey==0) active @endif">
                                    <a href="{{ route('ad.click', $ad->id) }}" target="_blank" rel="noopener">
                                        <img class="d-block ads-img" src="{{ URL::to($ad->image) }}" alt="Ad banner">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="ad-placeholder">Ad</div>
                @endif
            </div>
        </div>

        {{-- Sponsored video ad -- injected every 4th organic reel, same
             "$inFeedAds[...% count]" cycling pattern as in-feed ads
             elsewhere on the site. Only ads still under budget. --}}
        @if($videoAds->count() && $loop->iteration % 4 == 0)
            @php $adToShow = $videoAds[($loop->iteration / 4 - 1) % $videoAds->count()]; @endphp
            <div class="reel-row">
                <div class="reel-card reel-ad-card" data-ad-id="{{ $adToShow->id }}" data-min-watch="{{ $adToShow->min_watch_seconds }}">
                    <video class="reel-video" src="{{ asset($adToShow->video_path) }}" playsinline muted></video>

                    <button type="button" class="reel-mute-btn" onclick="toggleMute(this)">
                        <i class="bi bi-volume-mute-fill"></i>
                    </button>

                    <div class="reel-ad-progress-wrap">
                        <div class="reel-ad-progress-bar" id="adProgress{{ $adToShow->id }}"></div>
                    </div>

                    <div class="reel-overlay-top">
                        <span class="badge bg-warning text-dark">Sponsored</span>
                        <strong>{{ $adToShow->title }}</strong>
                    </div>

                    <div class="reel-overlay-bottom">
                        <div class="reel-ad-reward-note" id="adRewardNote{{ $adToShow->id }}">
                            দেখলে আপনি পাবেন ${{ number_format($adToShow->reward_per_view, 4) }}
                        </div>
                        @if($adToShow->link)
                            <a href="{{ route('ad.click', $adToShow->id) }}" target="_blank" rel="noopener" class="btn btn-sm btn-warning mt-1">বিস্তারিত দেখুন</a>
                        @endif
                    </div>
                </div>

                <div class="reel-side-ad">
                    @if($communityAds->count())
                        <div id="reel-side-ad-{{ $adToShow->id }}" class="carousel slide" data-bs-ride="carousel" data-bs-interval="8000">
                            <div class="carousel-inner" role="listbox">
                                @foreach($communityAds as $adKey2 => $ad2)
                                    <div class="carousel-item @if($adKey2==0) active @endif">
                                        <a href="{{ route('ad.click', $ad2->id) }}" target="_blank" rel="noopener">
                                            <img class="d-block ads-img" src="{{ URL::to($ad2->image) }}" alt="Ad banner">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="ad-placeholder">Ad</div>
                    @endif
                </div>
            </div>
        @endif
    @empty
        <div class="reel-row">
            <div class="reel-empty">
                <div style="font-size:40px;">🎬</div>
                <div class="mt-2" style="font-weight:800;">এখনো কোনো Reel পোস্ট হয়নি।</div>
                <div class="mt-1" style="opacity:.8;">নিচের + বাটনে ক্লিক করে প্রথম Reel পোস্ট করুন।</div>
            </div>
        </div>
    @endforelse

    @if($reels->hasPages())
        <div class="d-flex justify-content-center mb-4">
            {{ $reels->links() }}
        </div>
    @endif
</div>

<button type="button" class="reel-fab" onclick="openReelUpload()">+</button>

<div class="reel-upload-modal-backdrop" id="reelUploadBackdrop">
    <div class="reel-upload-modal">
        <h5>নতুন Reel পোস্ট করুন</h5>
        <video id="reelPreviewVideo" class="reel-upload-preview" controls muted></video>
        <form id="reelUploadForm" enctype="multipart/form-data">
            @csrf
            <input type="file" name="reel_video" id="reelVideoInput" accept="video/*" class="form-control mb-2" required onchange="onReelVideoSelected(this)">
            <textarea name="caption" class="form-control mb-2" rows="2" maxlength="500" placeholder="ক্যাপশন (ঐচ্ছিক)"></textarea>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-light flex-fill" onclick="closeReelUpload()">Cancel</button>
                <button type="submit" class="btn btn-success flex-fill" id="reelSubmitBtn">Post Reel</button>
            </div>
            <div class="text-muted mt-2" style="font-size:12px;">
                সর্বোচ্চ ৬০ সেকেন্ড — বেশি লম্বা ভিডিও দিলে প্রথম ৬০ সেকেন্ড রেখে বাকিটা কেটে পোস্ট হবে।
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    // ---- Sound ----
    // Browsers only allow autoplay-with-sound after some user interaction
    // on the page; a cold page load can't guarantee that. So: try to
    // autoplay each reel WITH sound first, and only fall back to muted if
    // the browser actually blocks it. Once the user taps unmute once, that
    // preference carries to every reel that plays after it (same as
    // Facebook/Instagram Reels), instead of re-muting every new video.
    let reelsMuted = false;

    function playWithSoundFallback(video) {
        video.muted = reelsMuted;
        const p = video.play();
        if (p && p.catch) {
            p.catch(() => {
                // Autoplay-with-sound blocked by the browser -- fall back to
                // muted so the video still plays; the speaker icon lets the
                // user turn sound on with one tap.
                video.muted = true;
                video.play().catch(() => {});
            });
        }
    }

    // ---- Autoplay the reel currently in view, pause the rest ----
    // Covers both organic reels (data-post-id) and sponsored video ads
    // (data-ad-id) -- the ad ones also start/stop their watch-time timer
    // here, since "in view AND playing" is exactly what should count as
    // watching for the reward.
    const reelCards = document.querySelectorAll('.reel-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            const card = entry.target;
            const video = card.querySelector('.reel-video');
            if (!video) return;
            const adId = card.dataset.adId;
            if (entry.isIntersecting) {
                playWithSoundFallback(video);
                if (adId) startAdWatchTimer(card, video, adId);
            } else {
                video.pause();
                if (adId) stopAdWatchTimer(adId);
            }
        });
    }, { threshold: 0.6 });
    reelCards.forEach((card) => observer.observe(card));

    // ---- Sponsored video ad: server-validated watch-time reward ----
    // Client only reports how long it watched; the server independently
    // re-checks the minimum, dedup, and budget before crediting anything
    // (see reelAdView() in socialEarnController.php) -- this timer is
    // just what triggers that one request, never the source of truth.
    const adWatchState = {};

    function startAdWatchTimer(card, video, adId) {
        if (!adWatchState[adId]) {
            adWatchState[adId] = { accumulated: 0, rewarded: false, timer: null };
        }
        const state = adWatchState[adId];
        if (state.rewarded || state.timer) return;

        const minWatch = parseFloat(card.dataset.minWatch || '5');
        const progressBar = document.getElementById('adProgress' + adId);

        state.timer = setInterval(() => {
            if (video.paused) return;
            state.accumulated += 0.25;
            if (progressBar) {
                progressBar.style.width = Math.min(100, (state.accumulated / minWatch) * 100) + '%';
            }
            if (state.accumulated >= minWatch && !state.rewarded) {
                state.rewarded = true; // stop this from firing twice while the request is in flight
                clearInterval(state.timer);
                state.timer = null;
                submitAdView(adId, Math.ceil(state.accumulated));
            }
        }, 250);
    }

    function stopAdWatchTimer(adId) {
        const state = adWatchState[adId];
        if (state && state.timer) {
            clearInterval(state.timer);
            state.timer = null;
        }
    }

    function submitAdView(adId, watchedSeconds) {
        $.ajax({
            url: "{{ route('user.reelAdView') }}",
            type: "POST",
            data: { ad_id: adId, watched_seconds: watchedSeconds, _token: "{{ csrf_token() }}" },
            success: function (response) {
                const note = document.getElementById('adRewardNote' + adId);
                if (response.status) {
                    toastr.success('+$' + parseFloat(response.reward).toFixed(4) + ' আপনার earning balance এ যোগ হলো!');
                    if (note) note.textContent = '✅ Reward পেয়েছেন!';
                } else if (note) {
                    note.textContent = response.message || '';
                }
            }
        });
    }

    // Tap a video to play/pause.
    document.querySelectorAll('.reel-video').forEach((video) => {
        video.addEventListener('click', () => {
            if (video.paused) { playWithSoundFallback(video); } else { video.pause(); }
        });
    });

    function toggleMute(btn) {
        reelsMuted = !reelsMuted;
        // Apply to every reel on the page (not just this one), and keep
        // every mute-icon in sync, so the preference sticks as you scroll.
        document.querySelectorAll('.reel-video').forEach((v) => { v.muted = reelsMuted; });
        document.querySelectorAll('.reel-mute-btn').forEach((b) => {
            b.innerHTML = reelsMuted
                ? '<i class="bi bi-volume-mute-fill"></i>'
                : '<i class="bi bi-volume-up-fill"></i>';
        });
    }

    // ---- Like (reuses the site's existing like endpoint) ----
    $(document).on('click', '.like-btn', function () {
        let btn = $(this);
        if (btn.hasClass('active')) return false;
        let postId = btn.data('post-id');
        let likeCountEl = btn.find('.like-count');
        let currentCount = parseInt(likeCountEl.text());

        $.ajax({
            url: "{{ route('user.newLike') }}",
            type: "POST",
            data: { postId: postId, _token: "{{ csrf_token() }}" },
            success: function (response) {
                if (response.status === true) {
                    btn.addClass('active');
                    likeCountEl.text(currentCount + 1);
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });

    // ---- Share (reuses the site's existing share endpoint + reward rule) ----
    function grantReelShareEarn(postId) {
        $.ajax({
            url: "{{ route('user.newShare') }}",
            type: "POST",
            data: { postId: postId, _token: "{{ csrf_token() }}" }
        });
    }

    function copyReelLink(postId) {
        const link = "{{ route('publicPostLink') }}/" + postId;
        if (navigator.share) {
            navigator.share({ title: 'Watch this Reel', url: link })
                .then(() => grantReelShareEarn(postId))
                .catch(() => {});
            return;
        }
        const popup = window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(link), 'fb-share', 'width=600,height=500');
        if (!popup) { return; }
        const timer = setInterval(function () {
            if (popup.closed) {
                clearInterval(timer);
                grantReelShareEarn(postId);
            }
        }, 500);
    }

    // ---- Follow (reuses the site's existing follow endpoint) ----
    $(document).on('click', '.reel-follow-btn', function () {
        let btn = $(this);
        let userId = btn.data('user-id');
        $.ajax({
            url: '/user/community-follow/' + userId,
            type: "POST",
            data: { _token: "{{ csrf_token() }}" },
            success: function (response) {
                if (response.status) {
                    btn.toggleClass('active', response.following);
                    btn.text(response.following ? 'Following' : '+ Follow');
                }
            }
        });
    });

    // ---- Upload modal ----
    function openReelUpload() {
        document.getElementById('reelUploadBackdrop').style.display = 'flex';
    }
    function closeReelUpload() {
        document.getElementById('reelUploadBackdrop').style.display = 'none';
        document.getElementById('reelUploadForm').reset();
        const preview = document.getElementById('reelPreviewVideo');
        preview.style.display = 'none';
        preview.src = '';
    }

    const MAX_REEL_SECONDS = 60;
    function canCompressVideo() {
        return !!(window.MediaRecorder && document.createElement('canvas').captureStream);
    }

    function onReelVideoSelected(input) {
        const preview = document.getElementById('reelPreviewVideo');
        if (!input.files || !input.files[0]) return;
        const fileUrl = URL.createObjectURL(input.files[0]);
        preview.onloadedmetadata = function () {
            preview.style.display = 'block';
            if (preview.duration > MAX_REEL_SECONDS + 1) {
                if (!canCompressVideo()) {
                    toastr.error('ভিডিও সর্বোচ্চ ' + MAX_REEL_SECONDS + ' সেকেন্ড হতে পারবে। এই ব্রাউজারে অটো-ট্রিম সাপোর্ট নেই, তাই নিজে ছোট করে (Trim) আপলোড করুন।');
                    input.value = '';
                    preview.style.display = 'none';
                    return;
                }
                toastr.info('শুধু প্রথম ' + MAX_REEL_SECONDS + ' সেকেন্ড রেখে বাকিটা কেটে আপলোড হবে।');
            }
        };
        preview.src = fileUrl;
    }

    // Best-effort client-side video compression + 60s trim -- native
    // browser APIs only (canvas + MediaRecorder), same approach used
    // elsewhere on this site for photo compression. Falls back to the
    // original file untouched wherever any step isn't supported or fails.
    function compressReelVideo(file, maxWidth = 720, targetBitrate = 1200000) {
        return new Promise((resolve) => {
            if (!window.MediaRecorder) { resolve(file); return; }

            const video = document.createElement('video');
            video.muted = true;
            video.playsInline = true;
            const objectUrl = URL.createObjectURL(file);
            video.src = objectUrl;

            const cleanupAndFallback = () => {
                try { URL.revokeObjectURL(objectUrl); } catch (e) {}
                resolve(file);
            };
            video.onerror = cleanupAndFallback;

            video.onloadedmetadata = () => {
                const scale = Math.min(1, maxWidth / (video.videoWidth || maxWidth));
                const width = Math.max(2, Math.round((video.videoWidth || maxWidth) * scale));
                const height = Math.max(2, Math.round((video.videoHeight || maxWidth) * scale));

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                if (!ctx || !canvas.captureStream) { cleanupAndFallback(); return; }

                const canvasStream = canvas.captureStream(25);
                try {
                    if (typeof video.captureStream === 'function') {
                        video.captureStream().getAudioTracks().forEach((t) => canvasStream.addTrack(t));
                    } else if (typeof video.mozCaptureStream === 'function') {
                        video.mozCaptureStream().getAudioTracks().forEach((t) => canvasStream.addTrack(t));
                    }
                } catch (e) { /* proceed video-only */ }

                let recorder;
                try {
                    const mimeType = MediaRecorder.isTypeSupported('video/webm;codecs=vp8,opus')
                        ? 'video/webm;codecs=vp8,opus' : 'video/webm';
                    recorder = new MediaRecorder(canvasStream, { mimeType, videoBitsPerSecond: targetBitrate });
                } catch (e) { cleanupAndFallback(); return; }

                const chunks = [];
                recorder.ondataavailable = (e) => { if (e.data && e.data.size) chunks.push(e.data); };

                let drawing = true;
                let stopped = false;
                function stopRecording() {
                    if (stopped) return;
                    stopped = true;
                    drawing = false;
                    video.pause();
                    if (recorder.state !== 'inactive') recorder.stop();
                }
                function drawFrame() {
                    if (!drawing) return;
                    try { ctx.drawImage(video, 0, 0, width, height); } catch (e) {}
                    if (video.currentTime >= MAX_REEL_SECONDS) { stopRecording(); return; }
                    requestAnimationFrame(drawFrame);
                }

                recorder.onstop = () => {
                    try { URL.revokeObjectURL(objectUrl); } catch (e) {}
                    if (!chunks.length) { resolve(file); return; }
                    const blob = new Blob(chunks, { type: 'video/webm' });
                    if (blob.size >= file.size) { resolve(file); return; }
                    resolve(new File([blob], file.name.replace(/\.[^.]+$/, '') + '.webm', { type: 'video/webm' }));
                };

                video.onended = stopRecording;
                recorder.start();
                video.currentTime = 0;
                video.play().then(() => { drawFrame(); }).catch(() => {
                    stopped = true; drawing = false;
                    try { if (recorder.state !== 'inactive') recorder.stop(); } catch (e) {}
                    cleanupAndFallback();
                });
            };
        });
    }

    document.getElementById('reelUploadForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const form = this;
        const videoInput = document.getElementById('reelVideoInput');
        if (!videoInput.files || !videoInput.files[0]) {
            toastr.error('একটি ভিডিও দিন।');
            return;
        }

        const submitBtn = document.getElementById('reelSubmitBtn');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Compressing video...';

        const formData = new FormData(form);
        try {
            const compressed = await compressReelVideo(videoInput.files[0]);
            formData.set('reel_video', compressed);
        } catch (err) {
            console.error('Video compression failed, sending original file', err);
        }

        submitBtn.textContent = 'Posting...';

        fetch("{{ route('user.reelStore') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then((res) => {
            if (res.status === 413) {
                throw new Error('ভিডিওর সাইজ সার্ভারের আপলোড সীমার চেয়ে বড়। ছোট ফাইল দিয়ে চেষ্টা করুন।');
            }
            return res.json().catch(() => {
                throw new Error('সার্ভারে সমস্যা হয়েছে (HTTP ' + res.status + ')।');
            });
        })
        .then((data) => {
            if (data.status) {
                toastr.success(data.message);
                closeReelUpload();
                setTimeout(() => window.location.reload(), 800);
            } else if (data.errors) {
                const firstError = Object.values(data.errors)[0][0];
                toastr.error(firstError || data.message || 'Validation failed.');
            } else {
                toastr.error(data.message || 'Something went wrong.');
            }
        })
        .catch((err) => {
            toastr.error(err.message || 'Something went wrong.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    });
</script>
@endsection
