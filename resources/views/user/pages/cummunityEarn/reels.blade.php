@extends('user.layouts.master')

@section('title')
    Reels
@endsection

@section('css')
<style>
    .reels-page-wrap {
        background: #000;
        margin: -1.5rem -0.75rem;
    }
    .reels-scroll {
        height: calc(100vh - 70px);
        overflow-y: scroll;
        scroll-snap-type: y mandatory;
        scrollbar-width: none;
    }
    .reels-scroll::-webkit-scrollbar { display: none; }

    .reel-item {
        position: relative;
        height: calc(100vh - 70px);
        scroll-snap-align: start;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #000;
    }
    .reel-video {
        max-height: 100%;
        max-width: 100%;
        width: 100%;
        height: 100%;
        object-fit: contain;
        background: #000;
    }
    .reel-overlay-top {
        position: absolute;
        top: 12px;
        left: 12px;
        right: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #fff;
        text-shadow: 0 1px 3px rgba(0,0,0,.6);
    }
    .reel-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        background: #444;
        display:flex;align-items:center;justify-content:center;
        font-weight:800;color:#fff;
    }
    .reel-follow-btn {
        margin-left: auto;
        background: rgba(255,255,255,.15);
        border: 1px solid #fff;
        color: #fff;
        border-radius: 16px;
        padding: 3px 12px;
        font-size: 12px;
        font-weight: 700;
    }
    .reel-follow-btn.active {
        background: #198754;
        border-color: #198754;
    }
    .reel-overlay-bottom {
        position: absolute;
        left: 12px;
        right: 70px;
        bottom: 20px;
        color: #fff;
        text-shadow: 0 1px 3px rgba(0,0,0,.6);
        font-size: 14px;
    }
    .reel-actions {
        position: absolute;
        right: 10px;
        bottom: 30px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 18px;
        color: #fff;
    }
    .reel-action-btn {
        background: none;
        border: none;
        color: #fff;
        font-size: 26px;
        text-align: center;
        text-shadow: 0 1px 3px rgba(0,0,0,.6);
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
    }
    .reel-action-btn span {
        font-size: 11px;
        font-weight: 700;
    }
    .reel-action-btn.active i { color: #ff4757; }

    .reel-mute-btn {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(0,0,0,.4);
        border: none;
        color: #fff;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        font-size: 15px;
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
        color: #fff;
        text-align: center;
        padding: 60px 20px;
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
<div class="reels-page-wrap">
    <div class="reels-scroll" id="reelsScroll">
        @forelse($reels as $reel)
            <div class="reel-item" data-post-id="{{ $reel->id }}">
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
        @empty
            <div class="reel-item">
                <div class="reel-empty">
                    <div style="font-size:40px;">🎬</div>
                    <div class="mt-2" style="font-weight:800;">এখনো কোনো Reel পোস্ট হয়নি।</div>
                    <div class="mt-1" style="opacity:.8;">নিচের + বাটনে ক্লিক করে প্রথম Reel পোস্ট করুন।</div>
                </div>
            </div>
        @endforelse

        @if($reels->hasMorePages())
            <div class="reel-item">
                <a href="{{ $reels->nextPageUrl() }}" class="btn btn-success btn-lg">আরও Reel দেখুন</a>
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
</div>
@endsection

@section('js')
<script>
    // ---- Autoplay the reel currently in view, pause the rest ----
    const reelItems = document.querySelectorAll('.reel-item[data-post-id]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            const video = entry.target.querySelector('.reel-video');
            if (!video) return;
            if (entry.isIntersecting) {
                video.play().catch(() => {});
            } else {
                video.pause();
            }
        });
    }, { threshold: 0.6 });
    reelItems.forEach((item) => observer.observe(item));

    // Tap a video to play/pause.
    document.querySelectorAll('.reel-video').forEach((video) => {
        video.addEventListener('click', () => {
            if (video.paused) { video.play().catch(() => {}); } else { video.pause(); }
        });
    });

    function toggleMute(btn) {
        const video = btn.closest('.reel-item').querySelector('.reel-video');
        video.muted = !video.muted;
        btn.innerHTML = video.muted
            ? '<i class="bi bi-volume-mute-fill"></i>'
            : '<i class="bi bi-volume-up-fill"></i>';
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
