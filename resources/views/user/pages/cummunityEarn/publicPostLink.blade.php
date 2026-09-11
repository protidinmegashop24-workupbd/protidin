<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title ?? 'Post Detail' }}</title>
    <meta name="description" content="{{ Str::limit(strip_tags($post->postContent), 160) }}">
    <meta name="keywords" content="community, post, comments, feed">
    
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->title ?? 'Community Post' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($post->postContent), 160) }}">
    <meta property="og:image" content="{{ asset($post->image ?? 'default-image.jpg') }}">
    
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $post->title ?? 'Community Post' }}">
    <meta property="twitter:description" content="{{ Str::limit(strip_tags($post->postContent), 160) }}">
    <meta property="twitter:image" content="{{ asset($post->image ?? 'default-image.jpg') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color:#f5f7fa; font-family: Arial, sans-serif; }
        .post-card { background-color: #fff; border-radius:10px; padding:20px; margin-bottom:20px; box-shadow:0 2px 6px rgba(0,0,0,0.05); }
        .post-header { display:flex; align-items:center; margin-bottom:15px; }
        .profile-icon { width:40px; height:40px; border-radius:50%; overflow:hidden; display:flex; align-items:center; justify-content:center; background:#ddd; font-weight:bold; color:#555; }
        .post-body img { max-width:100%; margin-top:10px; border-radius:8px; max-height: 500px}
        .post-body video { max-width:100%; width:100%; margin-top:10px; border-radius:8px; max-height: 500px; background:#000; }
        .post-actions button { margin-right:10px; }
        .comment-section { margin-top:20px; }
        .comment-wrapper, .reply-wrapper { display:flex; align-items:flex-start; margin-bottom:10px; }
        .comment-bubble { padding:8px 12px; border-radius:12px; background:#f0f2f5; }
        .reply-wrapper { margin-left:50px; }
        .verified-badge { color:#0d6efd; }
        .owner-tag { font-size:0.7rem; background:#6c757d; color:#fff; padding:2px 6px; border-radius:4px; margin-left:5px; }
        .comment-meta { font-size:0.7rem; color:#6c757d; margin-top:2px; }
        .comment-input-form { display:flex; align-items:center; margin-top:15px; }
        .comment-input-form textarea { flex:1; border-radius:20px; border:1px solid #ccc; padding:6px 12px; resize:none; }
        .comment-input-form button { margin-left:5px; }
        .url-preview-card {
            border: 1px solid #e1e8ed;
            border-radius: 12px;
            overflow: hidden;
            background-color: #fff;
            transition: background-color 0.2s ease-in-out;
            max-width: 550px;
            margin: 10px 0;
        }
        
        .url-preview-card:hover {
            background-color: #f5f8fa;
            border-color: #ccd6dd;
            text-decoration: none !important;
        }
        
        .preview-link {
            text-decoration: none !important;
            display: block;
            color: inherit;
        }
        
        .preview-image-wrapper {
            width: 100%;
            max-height: 280px;
            overflow: hidden;
            border-bottom: 1px solid #e1e8ed;
        }
        
        .preview-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .url-preview-content {
            padding: 12px 15px;
        }
        
        .preview-title {
            margin: 0 0 5px 0;
            font-size: 16px;
            font-weight: 600;
            color: #1c1e21;
        }
        
        .preview-desc {
            font-size: 14px;
            color: #65676b;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .preview-url {
            font-size: 12px;
            color: #8a8d91;
            letter-spacing: 0.5px;
        }

        .product-post-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
            margin-top: 10px;
        }
        .product-post-card img {
            width: 100%;
            max-height: 320px;
            object-fit: cover;
            display: block;
        }
        .product-post-body { padding: 10px 12px; }
        .buy-now-btn {
            display: block;
            text-align: center;
            margin: 10px 12px 12px;
            padding: 10px;
            border-radius: 8px;
            background: #f97316;
            color: #fff !important;
            font-weight: 700;
            text-decoration: none;
        }
        .buy-now-btn:hover { background: #ea580c; }

        .post-body-with-ad {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }
        .post-body-with-ad .post-main-content { flex: 1; min-width: 0; }
        .post-side-ad {
            width: 160px;
            flex-shrink: 0;
        }
        .post-side-ad .ad-placeholder {
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
        @media (max-width: 600px) {
            .post-body-with-ad { flex-direction: column; }
            .post-side-ad { width: 100%; }
            .post-side-ad .ad-placeholder { min-height: 100px; }
        }
    </style>
</head>
<body>
<div class="container py-4">

    <!-- Post Card -->
    <div class="post-card" id="post-{{$post->id}}">
        <!-- Post Header -->
        <div class="post-header">
            <div class="profile-icon me-2">
                @if($post->user?->image)
                    <img src="{{ asset($post->user->image) }}" class="w-100 h-100" style="object-fit:cover;">
                @else
                    {{ strtoupper(substr($post->user?->name ?? 'A',0,1)) }}
                @endif
            </div>
            <div>
                <h6 class="mb-0 fw-bold">{{ $post->user?->name ?? 'Anonymous' }}
                    @if($post->user?->kyc_status === 'approve')
                        <i class="bi bi-patch-check-fill verified-badge"></i>
                    @endif
                </h6>
                <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
            </div>
        </div>

        <!-- Post Body -->
        @php $isProductPost = ($post->postType ?? 'article') === 'product'; @endphp
        <div class="post-body mt-2 post-body-with-ad">
            <div class="post-main-content">
                {!! linkify($post->postContent) !!}
                <div class="text-left">
                    @if($post->video)
                        <video src="{{ asset($post->video) }}" controls preload="metadata"></video>
                    @elseif($post->image)
                        <img src="{{ asset($post->image) }}" alt="Post Image" loading="lazy">
                    @endif

                    @if($post->fetchUrl)
                        @php $hasOwnMedia = $post->video || $post->image; @endphp
                        @if($isProductPost)
                            <div class="product-post-card">
                                @if($post->fetchImg && !$hasOwnMedia)
                                    <img src="{{ $post->fetchImg }}" alt="{{ $post->fetchTitle }}">
                                @endif
                                <div class="product-post-body">
                                    @if($post->fetchTitle)<strong>{{ $post->fetchTitle }}</strong>@endif
                                    @if($post->fetchDescription)<p style="margin:4px 0 0;">{{ Str::limit($post->fetchDescription, 120) }}</p>@endif
                                </div>
                                <a href="{{ $post->fetchUrl }}" target="_blank" rel="noopener nofollow ugc" class="buy-now-btn">🛒 Buy Now</a>
                            </div>
                        @else
                            <div class="url-preview-card">
                                <a href="{{ $post->fetchUrl }}" target="_blank" rel="noopener nofollow ugc" class="preview-link">
                                    @if($post->fetchImg && !$hasOwnMedia)
                                        <div class="preview-image-wrapper">
                                            <img src="{{ $post->fetchImg }}" alt="{{ $post->fetchTitle }}" class="preview-img">
                                        </div>
                                    @endif

                                    <div class="url-preview-content">
                                        @if($post->fetchTitle)
                                            <h5 class="preview-title text-truncate">{{ $post->fetchTitle }}</h5>
                                        @endif

                                        @if($post->fetchDescription)
                                            <p class="preview-desc text-muted">{{ Str::limit($post->fetchDescription, 120) }}</p>
                                        @endif

                                        <span class="preview-url text-uppercase text-muted">
                                            <i class="fas fa-link me-1"></i> {{ parse_url($post->fetchUrl, PHP_URL_HOST) }}
                                        </span>
                                    </div>
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <div class="post-side-ad">
                @if(isset($communitySideAd) && $communitySideAd)
                    {!! $communitySideAd->code !!}
                @else
                    <div class="ad-placeholder">Ad</div>
                @endif
            </div>
        </div>

        <!-- Stats -->
        <div class="d-flex mt-3">
            <div class="me-3"><i class="bi bi-hand-thumbs-up-fill text-success"></i> {{$post->likes}} Likes</div>
            <div>{{$post->commnets}} Comments</div>
        </div>

        <!-- Actions -->
        <div class="post-actions mt-3" onclick="window.location.href='{{route('login')}}'">
            <button class="btn btn-sm btn-outline-success">Like</button>
            <button class="btn btn-sm btn-outline-primary">Comment</button>
        </div>

        <!-- Comments Section -->
        <div class="comment-section">
            @foreach($comments->whereNull('parentId') as $comment)
                <!-- Parent Comment -->
                <div class="comment-wrapper">
                    <div class="profile-icon me-2" style="width:36px;height:36px;">
                        @if($comment->user?->image)
                            <img src="{{ asset($comment->user->image) }}" class="w-100 h-100" style="object-fit:cover;">
                        @else
                            {{ strtoupper(substr($comment->user?->name ?? 'A',0,1)) }}
                        @endif
                    </div>
                    <div>
                        <div class="comment-bubble">
                            <div class="fw-semibold small">{{ $comment->user?->name ?? 'Anonymous' }}
                                @if($comment->user?->kyc_status === 'approve')
                                    <i class="bi bi-patch-check-fill verified-badge"></i>
                                @endif
                                @if($comment->userId === $post->userId)
                                    <span class="owner-tag">Post Author</span>
                                @endif
                            </div>
                            <div class="small mt-1">{{ $comment->comment }}</div>
                        </div>
                        <div class="comment-meta">
                            <span>{{ $comment->created_at->diffForHumans() }}</span>
                        </div>

                        <!-- Replies -->
                        @foreach($comment->replies as $reply)
                            <div class="reply-wrapper">
                                <div class="profile-icon me-2" style="width:30px;height:30px;">
                                    @if($reply->user?->image)
                                        <img src="{{ asset($reply->user->image) }}" class="w-100 h-100" style="object-fit:cover;">
                                    @else
                                        {{ strtoupper(substr($reply->user?->name ?? 'A',0,1)) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="comment-bubble bg-white border">
                                        <div class="fw-semibold small text-success">{{ $reply->user?->name ?? 'Anonymous' }}
                                            @if($reply->user?->kyc_status === 'approve')
                                                <i class="bi bi-patch-check-fill verified-badge"></i>
                                            @endif
                                            @if($reply->userId === $post->userId)
                                                <span class="owner-tag">Post Author</span>
                                            @endif
                                        </div>
                                        <div class="small mt-1">{{ $reply->comment }}</div>
                                    </div>
                                    <div class="comment-meta small mt-1">{{ $reply->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Login Notice -->
            @guest
                <div class="alert alert-info mt-3">
                    To comment and earn, <a href="{{ route('login') }}">log in first</a>.
                </div>
            @endguest

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
