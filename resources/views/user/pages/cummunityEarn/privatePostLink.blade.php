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
        #copy-alert {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--feed-brand-green);
            color: white;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: 600;
            z-index: 9999;
            display: none;
            box-shadow: 0 4px 12px rgba(0, 128, 0, 0.3);
            animation: slideUp 0.3s ease-out;
            font-size: 0.85rem;
        }
        .feed-container {
            width: 100%;
            /* max-width: 850px;  */
            margin: 0 auto;
            padding: 25px 15px;
        }
        .post-card {
            background: var(--feed-pure-white);
            border: 1px solid var(--feed-border-color);
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: var(--feed-card-shadow);
            overflow: hidden;
        }

        .profile-icon {
            width: 44px;
            height: 44px;
            background-color: var(--feed-brand-green-soft);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--feed-brand-green);
            flex-shrink: 0;
            font-weight: 700;
        }

        /* --- Post Creator Form --- */
        .create-post-container {
            padding: 20px;
            border-bottom: 1px solid var(--feed-border-color);
        }

        #initial-state {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .static-placeholder {
            background-color: #f8fafc;
            border: 1px solid var(--feed-border-color);
            border-radius: 12px;
            padding: 10px 18px;
            flex-grow: 1;
            color: var(--feed-text-muted);
            font-size: 0.95rem;
            transition: background 0.2s;
        }

        #editor-state {
            display: none;
            flex-direction: column;
            gap: 12px;
        }

        .post-textarea {
            width: 100%;
            border: none;
            outline: none;
            font-size: 1.1rem;
            resize: none;
            min-height: 100px;
            padding: 5px 0;
            overflow: hidden;
            line-height: 1.4;
            background: transparent;
        }
        /* Image Upload Section */
        .image-upload-trigger {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border: 1px solid var(--feed-border-color);
            border-radius: 12px;
            cursor: pointer;
            color: var(--feed-text-muted);
            font-weight: 600;
            transition: all 0.2s;
            margin-top: 10px;
        }
        .image-upload-trigger:hover {
            background-color: #f8fafc;
            border-color: var(--feed-brand-green);
            color: var(--feed-brand-green);
        }

        #image-preview-container {
            position: relative;
            display: none;
            margin-top: 15px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--feed-border-color);
        }

        #image-preview-container img {
            width: 100%;
            height: auto;
            max-height: 450px;
            object-fit: cover;
            display: block;
        }

        .remove-image-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.6);
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .editor-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid var(--feed-border-color);
        }

        .word-counter { font-size: 0.8rem; color: var(--feed-text-muted); font-weight: 500; }

        .btn-brand {
            background-color: var(--feed-brand-green);
            color: white;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            padding: 6px 20px;
            font-size: 0.9rem;
        }

        /* --- Post Content --- */
        .post-header { padding: 15px 20px 8px; }
        .verified-badge { color: #0ea5e9; font-size: 0.85rem; margin-left: 2px; }
        
        .post-body { 
            padding: 5px 20px 15px; 
            font-size: 1.05rem; 
            white-space: pre-line; 
            color: #334155;
            font-family: 'Noto Serif Bengali', serif;
            line-height: 1.5;
        }

        .post-stats {
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: var(--feed-text-muted);
            border-top: 1px solid #f8fafc;
        }

        .post-actions {
            display: flex;
            border-top: 1px solid var(--feed-border-color);
            background-color: #fdfdfe;
        }

        .action-btn {
            flex: 1;
            background: none;
            border: none;
            padding: 12px;
            color: var(--feed-text-muted);
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .action-btn:hover { background-color: #f8fafc; }
        .action-btn.active { 
            color: var(--feed-text-main); 
            font-weight: bold;
            scale: 1.2;
        }

        /* --- Comments Section --- */
        .comment-section { 
            padding: 15px 20px; 
            background-color: var(--feed-pure-white);
            border-top: 1px solid var(--feed-border-color);
        }
        
        .comment-wrapper { 
            display: flex; 
            margin-bottom: 8px;
            gap: 12px; 
            align-items: flex-start;
        }
        
        .comment-bubble-container { 
            flex-grow: 1; 
            max-width: 100%;
        }

        .comment-bubble {
            background-color: #f1f5f9;
            padding: 10px 14px;
            border-radius: 16px;
            border-top-left-radius: 4px;
            position: relative;
            font-family: 'Hind Siliguri', sans-serif;
            display: inline-block;
            min-width: 150px;
        }

        .is-owner .comment-bubble {
            background-color: var(--feed-brand-green-soft);
            border: 1px solid #e0f2e9;
        }

        .comment-author-name {
            display: block;
            font-weight: 700;
            font-size: 0.9rem;
            color: #1a1d23;
            margin-bottom: 4px;
            line-height: 1.2;
        }

        .comment-text-content {
            display: block;
            font-size: 0.95rem;
            color: #334155;
            line-height: 1.5;
            white-space: initial;
            word-break: break-word;
        }

        .owner-tag {
            font-size: 0.65rem;
            background-color: var(--feed-brand-green);
            color: white;
            padding: 1px 6px;
            border-radius: 4px;
            margin-left: 6px;
            text-transform: uppercase;
            font-weight: 800;
            vertical-align: middle;
            display: inline-block;
        }

        .comment-meta {
            font-size: 0.75rem;
            color: var(--feed-text-muted);
            margin-top: 5px;
            margin-left: 4px;
            display: flex;
            gap: 12px;
            font-weight: 600;
        }
        .comment-meta span { cursor: pointer; }
        .comment-meta span:hover { color: var(--feed-brand-green); }

        .reply-wrapper {
            margin-left: 48px;
            margin-top: 10px;
            position: relative;
        }
        .reply-wrapper::before {
            content: '';
            position: absolute;
            left: -20px;
            top: 0;
            bottom: 20px;
            width: 2px;
            background-color: #e2e8f0;
            border-radius: 2px;
        }

        /* --- Comment Form --- */
        .comment-input-form {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-top: 6px;
            padding-top: 10px;
            border-top: 1px solid var(--feed-border-color);
            width: 100%;
        }

        .mini-input-container {
            position: relative;
            flex-grow: 1;
        }

        .mini-input {
            background-color: #f1f5f9;
            border: 1px solid transparent;
            border-radius: 18px;
            padding: 8px 45px 8px 15px;
            font-size: 0.9rem;
            width: 100%;
            outline: none;
            transition: all 0.2s;
            resize: none;
            overflow: hidden;
            min-height: 38px;
            line-height: 1.4;
            display: block;
            font-family: 'Hind Siliguri', sans-serif;
        }
        .post-image-full {
            /* width: 100%;  */
            max-width: 100%;
            display: block;
            border-top: 1px solid var(--feed-border-color);
            border-bottom: 1px solid var(--feed-border-color);
            max-height: 350px;
        }
        .post-video-full {
            width: 100%;
            max-height: 450px;
            display: block;
            background: #000;
            border-top: 1px solid var(--feed-border-color);
            border-bottom: 1px solid var(--feed-border-color);
        }
        .mini-input:focus { background-color: #ffffff; border-color: var(--feed-brand-green); }

        .comment-send-btn {
            position: absolute;
            right: 12px;
            bottom: 8px;
            background: none;
            border: none;
            color: var(--feed-brand-green);
            font-size: 1.1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            opacity: 0.8;
            transition: transform 0.1s;
        }
        .comment-send-btn:active { transform: scale(0.9); }

        /* --- Pagination --- */
        .pagination .page-link {
            color: var(--feed-text-main);
            border: 1px solid var(--feed-border-color);
            margin: 0 4px;
            border-radius: 8px !important;
            font-weight: 600;
            padding: 8px 16px;
            font-size: 0.9rem;
        }
        .pagination .active .page-link {
            background-color: var(--feed-brand-green) !important;
            border-color: var(--feed-brand-green) !important;
            color: white !important;
        }

        @media (max-width: 576px) {
            .feed-container { padding: 5px 0; }
            .post-card { border-radius: 0; border-left: none; border-right: none; margin-bottom: 8px; }
            .reply-wrapper { margin-left: 30px; }
            .reply-wrapper::before { left: -15px; }
            .comment-section { padding: 10px 15px; }
            .post-header { padding: 12px 15px 5px; }
            .post-body { padding: 5px 15px 12px; }
            .comment-bubble { max-width: 100%; }
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
        .product-price-row { display:flex; align-items:center; gap:8px; margin-top:4px; flex-wrap:wrap; }
        .product-price { font-size:1.1rem; font-weight:800; color:#0f766e; }
        .product-discount {
            font-size: 0.75rem;
            font-weight: 700;
            color: #b91c1c;
            background: #fee2e2;
            padding: 2px 8px;
            border-radius: 10px;
        }
        .product-feature-list { margin:6px 0 0; padding-left:18px; font-size:0.85rem; color:#475569; }
        .product-feature-list li { margin-bottom:2px; }
        .affiliate-disclosure {
            font-size: 0.7rem;
            color: #94a3b8;
            text-align: center;
            padding: 0 12px 10px;
        }

        /* The ad sits BESIDE the post card, in its own separate box --
           not inside the post card itself -- so it's visually obvious
           it's not part of what the user posted (Quora-style side rail). */
        .post-row {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }
        .post-row .post-card { flex: 1; min-width: 0; margin-bottom: 0; }
        .post-side-ad {
            width: 160px;
            flex-shrink: 0;
            background: var(--feed-pure-white);
            border: 1px solid var(--feed-border-color);
            border-radius: 16px;
            box-shadow: var(--feed-card-shadow);
            padding: 8px;
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
            .post-row { flex-direction: column; }
            .post-side-ad { width: 100%; }
            .post-side-ad .ad-placeholder { min-height: 100px; }
        }
        .post-topic-badge {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--feed-brand-green);
            background: var(--feed-brand-green-soft);
            padding: 2px 8px;
            border-radius: 10px;
            margin-top: 2px;
        }
        .follow-btn {
            flex-shrink: 0;
            padding: 6px 14px;
            border-radius: 20px;
            border: 1.5px solid var(--feed-brand-green);
            background: #fff;
            color: var(--feed-brand-green);
            font-weight: 700;
            font-size: 0.8rem;
            cursor: pointer;
        }
        .follow-btn.active {
            background: var(--feed-brand-green-soft);
            color: var(--feed-brand-green);
        }
    </style>
@endsection

@section('user-content')
<div id="copy-alert">Link copied to clipboard!</div>
<div class="row" style="background-color:#d6ebf1;">
    <div class="feed-container"> 
        <div id="postFeed">
            <div class="post-row">
            <div class="post-card" id="post-{{$post->id}}">
                <div class="post-header d-flex align-items-center">
                    <div class="profile-icon me-3">
                        @if($post->user->image)
                            <img class="rounded-circle" src="{{ asset($post->user->image) }}" alt="{{ Auth::user()->name }}" style="width:100%; height:100%; object-fit:cover;">
                        @else
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold" style="font-size: 0.95rem;">
                            {{$post->user->name}} @if($post->user->kyc_status == 'approve')<i class="bi bi-patch-check-fill verified-badge"></i> @endif
                            {{-- <span class="owner-tag">Owner</span> --}}
                        </h6>
                        <small class="text-muted" style="font-size: 0.7rem;">
                            {{ $post->created_at->diffForHumans() }}
                        </small>
                        @if(communityTopicsEnabled() && $post->topics->count())
                            <div>
                                @foreach($post->topics as $topic)
                                    <span class="post-topic-badge">{{ $topic->icon }} {{ $topic->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @if(communityFollowEnabled() && Auth::id() != $post->userId)
                        <button type="button" class="follow-btn {{ $isFollowingAuthor ? 'active' : '' }}" data-user-id="{{ $post->userId }}">
                            {{ $isFollowingAuthor ? 'Following' : '+ Follow' }}
                        </button>
                    @endif
                </div>

                @php $isProductPost = ($post->postType ?? 'article') === 'product'; @endphp
                <div class="post-body">
                    <div class="post-main-content">
                        {!! linkify($post->postContent) !!}
                        @if($post->video)
                            <video src="{{asset($post->video)}}" class="post-video-full" controls preload="metadata"></video>
                        @elseif($post->image)
                            <img src="{{asset($post->image)}}" class="post-image-full" alt="Post content" loading="lazy" >
                        @endif

                        @if($post->fetchUrl)
                            @php $hasOwnMedia = $post->video || $post->image; @endphp
                            @if($isProductPost)
                                <div class="product-post-card">
                                    @if($post->fetchImg && !$hasOwnMedia)
                                        <img src="{{$post->fetchImg}}" alt="{{$post->fetchTitle}}">
                                    @endif
                                    <div class="product-post-body">
                                        @if($post->fetchTitle)<strong>{{$post->fetchTitle}}</strong>@endif
                                        @if(communityProductFieldsEnabled() && ($post->productPrice || $post->discountText))
                                            <div class="product-price-row">
                                                @if($post->productPrice)<span class="product-price">{{ $post->productPrice }}</span>@endif
                                                @if($post->discountText)<span class="product-discount">{{ $post->discountText }}</span>@endif
                                            </div>
                                        @endif
                                        @if($post->fetchDescription)<p style="margin:4px 0 0;">{{ Str::limit($post->fetchDescription, 120) }}</p>@endif
                                        @if(communityProductFieldsEnabled() && $post->productFeatures)
                                            <ul class="product-feature-list">
                                                @foreach(explode("\n", $post->productFeatures) as $feature)
                                                    @if(trim($feature) !== '')<li>{{ trim($feature) }}</li>@endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                    <a href="{{ communityLinkClickTrackingEnabled() ? route('community.go', $post->id) : $post->fetchUrl }}" target="_blank" rel="noopener nofollow ugc" class="buy-now-btn">🛒 Buy Now</a>
                                    <div class="affiliate-disclosure">Affiliate/Sponsored Link — এই লিঙ্কে কেনাকাটা করলে পোস্টদাতা কমিশন পেতে পারেন</div>
                                </div>
                            @else
                                <div class="url-preview-viewpart">
                                    <a style="display:block;line-height: 1;text-align: center;" href="{{$post->fetchUrl}}" target="_blank" rel="noopener nofollow ugc">
                                        @if($post->fetchImg && !$hasOwnMedia)<img src="{{$post->fetchImg}}" alt="{{$post->fetchTitle}}">@endif
                                        <div class="url-preview-content">
                                            @if($post->fetchTitle)<strong>{{$post->fetchTitle}}</strong>@endif
                                            @if($post->fetchDescription)<p style="margin:0;">{{$post->fetchDescription}}</p>@endif
                                            <small>{{$post->fetchUrl}}</small>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="post-stats">
                    <div class="stat-item fw-semibold">
                        <i class="bi bi-hand-thumbs-up-fill text-success"></i> {{$post->likes}} Likes
                    </div>
                    <div class="stat-item fw-medium">
                        <span>{{$post->commnets}} Comments</span>
                        <span class="mx-1">·</span>
                        <span class="share-count" data-count="{{ $post->shares }}">{{$post->shares}} Shares</span>
                    </div>
                </div>

                <div class="post-actions">
                    <button class="action-btn like-btn {{ (Auth::id() == $post->userId || $post->has_liked) ? 'active' : '' }}" data-post-id="{{ $post->id }}">
                            <i class="bi bi-hand-thumbs-up"></i> Like
                    </button>
                    <button class="action-btn {{ (Auth::id() == $post->userId || $post->has_commented) ? 'active' : '' }}" onclick="focusComment('post-{{$post->id}}')">
                        <i class="bi bi-chat-text"></i>Comment
                    </button>
                    {{-- <a href="{{route('user.viewCommunityPP', $post->id)}}" class="action-btn"><i class="bi bi-chat-text"></i>Comment</a> --}}
                    <button class="action-btn" onclick="copyPostLink('{{$post->id}}')">Share</button>
                    @if(communityBookmarkEnabled())
                        <button type="button" class="action-btn save-btn {{ $isPostSaved ? 'active' : '' }}" data-post-id="{{ $post->id }}">
                            <i class="bi bi-bookmark{{ $isPostSaved ? '-fill' : '' }}"></i> <span class="save-btn-label">{{ $isPostSaved ? 'Saved' : 'Save' }}</span>
                        </button>
                    @endif
                </div>

                <div class="comment-section">
                    <!-- Existing Comment Loop -->
                        @foreach($comments->whereNull('parentId') as $comment)
                            <div class="comment-wrapper">
                                <div class="profile-icon" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    @if($comment->user->image)
                                        <img class="rounded-circle" src="{{ asset($comment->user->image) }}" alt="{{$comment->user?->name ?? 'Annonymous'}}" style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        {{ strtoupper(substr($comment->user->name ?? 'Annonymous', 0, 1)) }}
                                    @endif
                                    
                                </div>

                                <div class="comment-bubble-container">

                                    <div class="comment-bubble">
                                        <div class="comment-author-name flex-grow-1">
                                            {{$comment->user?->name ?? 'Annonymous'}}
                                            {{-- if verified  --}}
                                            <span class="mb-0 fw-bold" style="font-size: 0.95rem;">
                                                @if($comment->user?->kyc_status == 'approve')<i class="bi bi-patch-check-fill verified-badge"></i> @endif
                                            </span>   
                                            @if($comment->userId == $post->userId)
                                                <span class="owner-tag">Post Author</span>
                                            @endif
                                        </div>
                                        
                                        <div class="comment-text-content">{{$comment->comment ?? 'No data'}}</div>
                                    </div>
                                    <div class="comment-meta">
                                        {{-- <span>Like</span> --}}
                                        @if($post->userId == Auth::user()->id ?? 0)
                                            <span onclick="simulateReplyAction('{{$comment->user?->name ?? 'Annonymous'}}', 'post-{{$post->id}}','{{ $comment->id}}')">Reply</span>
                                        @endif
                                        <span>{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    
                                    @foreach($comments->where('parentId', $comment->id) as $reply)
                                        <!-- Reply Demo -->
                                        <div class="reply-wrapper">
                                            <div class="comment-wrapper is-owner">
                                                <div class="profile-icon" style="width: 26px; height: 26px; font-size: 0.65rem;">
                                                    @if($reply->user->image)
                                                        <img class="rounded-circle" src="{{ asset($reply->user->image) }}" alt="{{$reply->user?->name ?? 'Annonymous'}}" style="width:100%; height:100%; object-fit:cover;">
                                                    @else
                                                        {{ strtoupper(substr($reply->user->name ?? 'Annonymous', 0, 1)) }}
                                                    @endif
                                                </div>
                                                <div class="comment-bubble-container">
                                                    <div class="comment-bubble">
                                                        <div class="comment-author-name flex-grow-1" style="color: var(--feed-brand-green);">

                                                            {{$reply->user?->name ?? 'Annonymous'}}
                                                            {{-- if verified  --}}
                                                            <span class="mb-0 fw-bold" style="font-size: 0.95rem;">
                                                                @if($reply->user?->kyc_status == 'approve')<i class="bi bi-patch-check-fill verified-badge"></i> @endif
                                                            </span>   
                                                            @if($reply->userId == $post->userId)
                                                                <span class="owner-tag">Post Author</span>
                                                            @endif

                                                            {{-- Admin User <span class="owner-tag">Owner</span> --}}
                                                        </div>
                                                        <div class="comment-text-content">{{$reply->comment ?? 'No data'}}</div>
                                                    </div>
                                                    <div class="comment-meta">
                                                        {{-- <span>Like</span> --}}
                                                        <span>{{ $reply->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                    <!-- Comment Submission Form -->
                    <form action="{{route('user.newComment', $post->id)}}" method="POST" class="comment-input-form">  
                        @csrf                          
                        <input type="hidden" name="postId" value="{{$post->id}}">
                        <input type="hidden" name="parentId" id="parentId-{{ $post->id }}">
                        <div class="profile-icon" style="width: 32px; height: 32px; font-size: 0.75rem;">
                            @if(Auth::user()->image)
                                <img class="rounded-circle" src="{{ asset(Auth::user()->image) }}" alt="{{ Auth::user()->name }}" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="mini-input-container">
                            <div class="reply-indicator d-none" style="cursor:pointer">
                                Replying to <strong class="reply-user"></strong>
                                <span onclick="cancelReply(this)">×</span>
                            </div>
                            <textarea name="comment_content" class="mini-input" placeholder="Write a comment..." oninput="autoExpandComment(this)" required></textarea>
                            <button type="submit" class="comment-send-btn" title="Send Comment">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </form>
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

        </div>
    </div>

    <!-- Hidden input for copy functionality -->
    <input type="text" id="copy-helper" style="position: absolute; left: -9999px;">
</div>

@endsection
@section('js')
<script>
    function autoExpandComment(el) {
        el.style.height = 'auto';
        el.style.height = (el.scrollHeight) + 'px';
        if (el.scrollHeight > 150) {
            el.style.overflowY = 'auto';
            el.style.height = '150px';
        } else {
            el.style.overflowY = 'hidden';
        }
    }
    function focusComment(postId) {
        const post = document.getElementById(postId);
        const input = post.querySelector('.mini-input');
        if (input) input.focus();
    }
    function cancelReply(el) {
        const form = el.closest('.comment-section');
        const textarea = form.querySelector('.mini-input');
        const parentInput = form.querySelector('input[name="parentId"]');

        parentInput.value = '';
        textarea.value = '';
        textarea.focus();

        form.querySelector('.reply-indicator').classList.add('d-none');
    }
    function showReplyIndicator(post, name) {
        const indicator = post.querySelector('.reply-indicator');
        indicator.querySelector('.reply-user').innerText = name;
        indicator.classList.remove('d-none');
    }
    function simulateReplyAction(name, postId, commentId) {
        const post = document.getElementById(postId);
        const textarea = post.querySelector('.mini-input');
        const parentInput = post.querySelector('#parentId-' + postId.replace('post-', ''));

        parentInput.value = commentId;

        // textarea.value = '@' + name.replace(/\s/g, '') + ' ';
        textarea.focus();

        showReplyIndicator(post, name);
    }
    // function simulateReplyAction(name, postId) {
    //     const post = document.getElementById(postId);
    //     const target = post.querySelector('.mini-input');
    //     if (target) {
    //         target.value = "@" + name.replace(/\s/g, '') + " ";
    //         target.focus();
    //         autoExpandComment(target);
    //     }
    // }
    function grantShareEarn(postId) {
        const postCard = document.getElementById('post-' + postId);
        $.ajax({
            url: "{{Route('user.newShare')}}",
            type: "POST",
            data: {
                postId: postId,
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
                if (postCard) {
                    const shareCountEl = postCard.querySelector('.share-count');
                    if (shareCountEl) {
                        const newCount = parseInt(shareCountEl.dataset.count || '0') + 1;
                        shareCountEl.dataset.count = newCount;
                        shareCountEl.textContent = newCount + ' Shares';
                    }
                }
            }
        });
    }

    function copyLinkFallback(link) {
        const helper = document.getElementById('copy-helper');
        helper.value = link;
        helper.select();
        document.execCommand('copy');
        const alertBox = document.getElementById('copy-alert');
        alertBox.style.display = 'block';
        setTimeout(() => { alertBox.style.display = 'none'; }, 2000);
    }

    // Earning is only granted once the user actually goes through a share
    // action (the OS share sheet resolving, or the Facebook share popup
    // being opened and closed) -- clicking the button alone no longer pays
    // out, since that was earnable with zero actual sharing.
    function copyPostLink(postId) {
        const link = "{{route('publicPostLink')}}/" + postId;

        if (navigator.share) {
            navigator.share({ title: 'Share Post', url: link })
                .then(() => grantShareEarn(postId))
                .catch(() => console.log('Share cancelled or failed'));
            return;
        }

        const fbShareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(link);
        const popup = window.open(fbShareUrl, 'fb-share', 'width=600,height=500');

        if (!popup) {
            copyLinkFallback(link);
            grantShareEarn(postId);
            return;
        }

        const timer = setInterval(function () {
            if (popup.closed) {
                clearInterval(timer);
                grantShareEarn(postId);
            }
        }, 500);
    }
    
    $(document).on('click', '.like-btn', function () {

        let btn = $(this);

        // stop if already liked
        if (btn.hasClass('active')) {
            return false;
        }

        let postId = btn.data('post-id');
        let likeCountEl = btn.closest('.post-card').find('.like-count');

        let currentCount = parseInt(likeCountEl.text());

        $.ajax({
            url: "{{Route('user.newLike')}}",
            type: "POST",
            data: {
                postId: postId,
                // _token: $('meta[name="csrf-token"]').attr('content')
                // 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {

                if (response.status === true) {

                    btn.addClass('active');

                    // increment only once
                    likeCountEl.text(currentCount + 1);

                    toastr.success(response.message);

                } else {
                    toastr.error(response.message);
                }

            },
            error: function () {
                toastr.error('Something went wrong. Try again.');
            }
        });
    });

    $(document).on('click', '.follow-btn', function () {
        let btn = $(this);
        let userId = btn.data('user-id');

        $.ajax({
            url: '/user/community-follow/' + userId,
            type: "POST",
            data: { _token: "{{ csrf_token() }}" },
            success: function (response) {
                if (response.status === true) {
                    if (response.following) {
                        btn.addClass('active').text('Following');
                    } else {
                        btn.removeClass('active').text('+ Follow');
                    }
                } else {
                    toastr.error(response.message);
                }
            },
            error: function () {
                toastr.error('Something went wrong. Try again.');
            }
        });
    });

    $(document).on('click', '.save-btn', function () {
        let btn = $(this);
        let postId = btn.data('post-id');

        $.ajax({
            url: '/user/community-save/' + postId,
            type: "POST",
            data: { _token: "{{ csrf_token() }}" },
            success: function (response) {
                if (response.status === true) {
                    let icon = btn.find('i');
                    let label = btn.find('.save-btn-label');
                    if (response.saved) {
                        btn.addClass('active');
                        icon.removeClass('bi-bookmark').addClass('bi-bookmark-fill');
                        label.text('Saved');
                    } else {
                        btn.removeClass('active');
                        icon.removeClass('bi-bookmark-fill').addClass('bi-bookmark');
                        label.text('Save');
                    }
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function () {
                toastr.error('Something went wrong. Try again.');
            }
        });
    });
</script>
@endsection