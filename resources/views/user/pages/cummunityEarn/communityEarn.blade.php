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

        .in-feed-ad-card {
            background: var(--feed-pure-white);
            border: 1px solid var(--feed-border-color);
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: var(--feed-card-shadow);
            overflow: hidden;
            padding: 10px 15px;
        }

        .in-feed-ad-label {
            font-size: 0.7rem;
            color: var(--feed-text-muted);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
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

        #video-preview-container {
            position: relative;
            display: none;
            margin-top: 15px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--feed-border-color);
            background: #000;
        }

        #video-preview-container video {
            width: 100%;
            max-height: 450px;
            display: block;
        }

        .post-video-full {
            width: 100%;
            max-height: 500px;
            border-radius: 0;
            background: #000;
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
        
        .post-url-input{
            width:100%;
            margin-top:8px;
            border:1px solid #ddd;
            border-radius:8px;
            padding:8px 10px;
            font-size:14px;
        }
        .url-preview {
            transition: opacity 0.3s ease-in-out;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background: #f9f9f9;
            margin-top: 10px;
            gap: 10px;
            align-items: center;
        }
        
        .url-preview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 6px;
            flex-shrink: 0;
        }
        
        /* Loading state */
        .url-preview.loading {
            opacity: 0.6;
            pointer-events: none;
        }
        .url-preview-viewpart {
            transition: opacity 0.3s ease-in-out;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
            align-items: center;
            line-height:1;
        }
        
        .url-preview-viewpart img {
            width: 250px;
            /*height: 100px;*/
            /*object-fit: cover;*/
            border-radius: 6px;
            flex-shrink: 0;
        }
        
        /* Loading state */
        .url-preview-viewpart.loading {
            opacity: 0.6;
            pointer-events: none;
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
        .product-post-linkarea { display:block; color:inherit; text-decoration:none; }
        .product-more-details { display:block; margin-top:6px; color:#0f766e; font-weight:600; }
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
            margin-bottom: 25px;
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
        /* The site's real Advertise-section banners (.ads-img) are sized
           for the wider "Click Now" box on find-job/dashboard -- shrink
           the fixed height here so they fit this narrow side rail. */
        .post-side-ad .ads-img { height: 140px; object-fit: cover; border-radius: 8px; }
        @media (max-width: 600px) {
            .post-row { flex-direction: column; }
            .post-side-ad { width: 100%; }
            .post-side-ad .ad-placeholder { min-height: 100px; }
            .post-side-ad .ads-img { height: 180px; }
        }

        .topic-header {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--feed-pure-white);
            border: 1px solid var(--feed-border-color);
            border-radius: 16px;
            padding: 14px 18px;
            margin-bottom: 12px;
        }
        .topic-header-icon { font-size: 1.8rem; }
        .topic-chip-row {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 4px 2px 16px;
        }
        .topic-chip {
            flex-shrink: 0;
            padding: 6px 14px;
            border-radius: 20px;
            background: var(--feed-pure-white);
            border: 1px solid var(--feed-border-color);
            color: var(--feed-text-main);
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
        }
        .topic-chip.active {
            background: var(--feed-brand-green);
            border-color: var(--feed-brand-green);
            color: #fff;
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
        .report-btn {
            flex-shrink: 0;
            margin-left: 6px;
            background: none;
            border: none;
            font-size: 0.95rem;
            cursor: pointer;
            opacity: 0.6;
        }
        .report-btn:hover { opacity: 1; }
    </style>
@endsection
{{-- Css End Here For Single Page  --}}
@section('user-content')
<div id="copy-alert">Link copied to clipboard!</div>
<div class="row" style="background-color:#d6ebf1;">
    <div class="feed-container">

        <div class="post-card" style="padding:14px 18px; font-weight:700; text-align:center; background:#0f766e; color:#fff;">
            🎉 এখানে ফ্রি ফ্রি আপনার নিজের এফিলিয়েট লিংক বা আপনার নিজের সাইটের লিংক ব্যাক লিংকের জন্য পোস্ট করুন!
        </div>

        <!-- Post Creator Form -->
        <div class="post-card">
            <!-- Note: Action # is for demo. Replace with real backend URL (e.g., /posts/store) -->
            <form action="{{route('user.communityPostStore')}}" method="POST" class="create-post-container" enctype="multipart/form-data" id="main-post-form">
                @csrf
                <input type="hidden" name="post_type" id="post_type" value="article">
                <div class="post-type-toggle" style="display:flex; gap:8px; padding:10px 12px 0;">
                    <button type="button" class="post-type-btn active" data-type="article" onclick="setPostType('article', this)"
                        style="flex:1; padding:8px; border-radius:8px; border:2px solid #0f766e; background:#0f766e; color:#fff; font-weight:600; cursor:pointer;">
                        ❓ Q&amp;A / Article (ব্যাকলিংক)
                    </button>
                    <button type="button" class="post-type-btn" data-type="product" onclick="setPostType('product', this)"
                        style="flex:1; padding:8px; border-radius:8px; border:2px solid #0f766e; background:#fff; color:#0f766e; font-weight:600; cursor:pointer;">
                        🛍️ Product (Buy Now)
                    </button>
                </div>

                @if($topics->count())
                <div id="topic-select-wrap" style="padding:10px 12px 0;">
                    <select name="topic_id" id="topic_id" style="width:100%; padding:8px; border-radius:8px; border:1px solid #ddd; color:#334155;">
                        <option id="topic-default-option" value="">বিষয় বেছে নিন (Topic) — ঐচ্ছিক</option>
                        @foreach($articleTopics as $topic)
                            <option value="{{ $topic->id }}">{{ $topic->icon }} {{ $topic->name }}</option>
                        @endforeach
                    </select>
                </div>
                <script>
                    // Product's Topic list is its own set (admin-managed
                    // "applies_to" per topic) -- swapped in by setPostType()
                    // below instead of sharing Article's list.
                    var communityArticleTopics = @json($articleTopics->map(fn($t) => ['id' => $t->id, 'label' => $t->icon . ' ' . $t->name]));
                    var communityProductTopics = @json($productTopics->map(fn($t) => ['id' => $t->id, 'label' => $t->icon . ' ' . $t->name]));
                </script>
                @endif

                @if(communityProductFieldsEnabled())
                <div id="product-fields" style="display:none; padding:10px 12px 0; gap:8px; flex-wrap:wrap;">
                    <input type="text" name="product_price" placeholder="দাম (যেমন: $19.99) — ঐচ্ছিক" style="flex:1; min-width:140px; padding:8px; border-radius:8px; border:1px solid #ddd;">
                    <input type="text" name="discount_text" placeholder="ছাড়/অফার (যেমন: 20% OFF) — ঐচ্ছিক" style="flex:1; min-width:140px; padding:8px; border-radius:8px; border:1px solid #ddd;">
                    <textarea name="product_features" placeholder="প্রোডাক্ট ফিচার (প্রতি লাইনে একটা) — ঐচ্ছিক" rows="2" style="width:100%; padding:8px; border-radius:8px; border:1px solid #ddd;"></textarea>
                </div>
                @endif

                <div id="initial-state" onclick="toggleEditor(true)">
                    <div class="profile-icon">
                        @if(Auth::user()->image)
                            <img class="rounded-circle" src="{{ asset(Auth::user()->image) }}" alt="{{ Auth::user()->name }}" style="width:100%; height:100%; object-fit:cover;">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="static-placeholder">What's on your mind? Click to write...</div>
                </div>

                <div id="editor-state">
                    <div class="d-flex align-items-center mb-2">
                        <div class="profile-icon me-2">                            
                            @if(Auth::user()->image)
                                <img class="rounded-circle" src="{{ asset(Auth::user()->image) }}" alt="{{ Auth::user()->name }}" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div class="fw-bold small">{{Auth::user()->name}}</div>
                            <div class="text-muted" style="font-size: 0.65rem;"><i class="bi bi-people-fill"></i> public</div>
                        </div>
                    </div>
                    
                    <!-- Main Post Input -->
                    <textarea 
                        name="post_content" 
                        id="post-input" 
                        class="post-textarea" 
                        placeholder="Write your post here..."
                        oninput="handleInput(this)"
                        required></textarea>

                    <!-- Image Preview Area -->
                    <div id="image-preview-container">
                        <button type="button" class="remove-image-btn" onclick="removeSelectedImage()"><i class="bi bi-x-lg"></i></button>
                        <img id="image-preview" src="" alt="preview">
                    </div>

                    <!-- Video Preview Area -->
                    <div id="video-preview-container">
                        <button type="button" class="remove-image-btn" onclick="removeSelectedVideo()"><i class="bi bi-x-lg"></i></button>
                        <video id="video-preview" src="" controls></video>
                    </div>
                    <!--Fatch System: link is auto-detected from the post text itself, Facebook-style -->
                    <input type="hidden" name="fatchUrl" id="post-url">
                    <input type="hidden" name="fetchTitle" id="fetchTitle">
                    <input type="hidden" name="fetchDescription" id="fetchDescription">
                    <input type="hidden" name="fetchImg" id="fetchImg">
                    
                    <div id="url-preview" class="url-preview" style="display:none">
                        <img id="url-preview-img" src="" alt="" onerror="this.style.display='none';">
                        <div class="url-preview-content">
                            <strong id="url-preview-title"></strong>
                            <p id="url-preview-desc"></p>
                            <small id="url-preview-link"></small>
                        </div>
                    </div>
                    <!--Fatch System End Here -->
                    <!-- Visual Image/Video/Product Selector Triggers -->
                    <div class="d-flex" style="gap:10px;">
                        <div class="image-upload-trigger flex-grow-1" id="upload-trigger" onclick="document.getElementById('post_image').click()">
                            <i class="bi bi-image-fill text-success fs-5"></i>
                            <span>Add a Photo</span>
                        </div>
                    </div>

                    <!-- Hidden Real Inputs -->
                    <input type="file" name="post_image" id="post_image" accept="image/*" style="display: none;" onchange="previewImage(this)">
                    <input type="file" name="post_video" id="post_video" accept="video/*" style="display: none;" onchange="previewVideo(this)">


                    <div class="editor-footer">
                        <div class="word-counter">
                            <span id="word-count">0</span> words
                        </div>
                        <div class="editor-actions">
                            <button type="button" class="btn btn-light btn-sm me-2" onclick="toggleEditor(false)" style="border-radius: 8px; font-weight: 600;">Discard</button>
                            <button type="submit" class="btn-brand">Post Now</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        @if($activeTopicSlug && $topics->firstWhere('slug', $activeTopicSlug))
            @php $activeTopic = $topics->firstWhere('slug', $activeTopicSlug); @endphp
            <div class="topic-header">
                <span class="topic-header-icon">{{ $activeTopic->icon }}</span>
                <div>
                    <strong>{{ $activeTopic->name }}</strong>
                    <div class="text-muted" style="font-size:0.8rem;">এই বিষয়ের সব পোস্ট</div>
                </div>
            </div>
        @endif

        @if($topics->count() || communityBookmarkEnabled())
        <div class="topic-chip-row">
            @if($topics->count())
                <a href="{{ route('user.communityEarn') }}" class="topic-chip {{ !$activeTopicSlug && !$showSavedOnly ? 'active' : '' }}">সব</a>
                @foreach($topics as $topic)
                    <a href="{{ route('user.communityEarn', ['topic' => $topic->slug]) }}" class="topic-chip {{ $activeTopicSlug === $topic->slug ? 'active' : '' }}">
                        {{ $topic->icon }} {{ $topic->name }}
                    </a>
                @endforeach
            @endif
            @if(communityBookmarkEnabled())
                <a href="{{ route('user.communityEarn', ['saved' => 1]) }}" class="topic-chip {{ $showSavedOnly ? 'active' : '' }}">🔖 Saved Posts</a>
            @endif
        </div>
        @endif

        <div id="postFeed">
            <!-- Post Item 1 -->
            @foreach($posts as $post)
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
                                <a href="{{ route('user.community.profile', $post->userId) }}" style="color:inherit; text-decoration:none;">{{$post->user->name}}</a> @if($post->user->kyc_status == 'approve')<i class="bi bi-patch-check-fill verified-badge"></i> @endif
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
                            <button type="button" class="follow-btn {{ in_array($post->userId, $followingIds) ? 'active' : '' }}" data-user-id="{{ $post->userId }}">
                                {{ in_array($post->userId, $followingIds) ? 'Following' : '+ Follow' }}
                            </button>
                        @endif
                        @if(communityReportsEnabled() && Auth::id() != $post->userId)
                            <button type="button" class="report-btn" data-post-id="{{ $post->id }}" title="Report this post">🚩</button>
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
                                        <a href="{{ route('user.viewCommunityPP', $post->id) }}" class="product-post-linkarea">
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
                                                    <small class="product-more-details">বিস্তারিত ফিচার দেখতে পোস্টে ক্লিক করুন »</small>
                                                @endif
                                            </div>
                                        </a>
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
                            <i class="bi bi-hand-thumbs-up-fill text-success"></i>
                            <span class="like-count" data-count="{{ $post->likes }}">
                                {{$post->likes}}
                            </span>  Likes
                        </div>
                        <div class="stat-item fw-medium">
                            <span>{{$post->commnets}} Comments</span>
                            <span class="mx-1">·</span>
                            <span class="share-count" data-count="{{ $post->shares }}">{{$post->shares}} Shares</span>
                            @if(communityViewsEnabled())
                                <span class="mx-1">·</span>
                                <span>👁 {{ $post->views }} Views</span>
                            @endif
                        </div>
                    </div>

                    <div class="post-actions">
                        <button class="action-btn like-btn {{ (Auth::id() == $post->userId || $post->has_liked) ? 'active' : '' }}" data-post-id="{{ $post->id }}">
                            <i class="bi bi-hand-thumbs-up"></i> Like
                        </button>
                        {{-- <button class="action-btn" onclick="focusComment('post-{{$post->id}}')"><i class="bi bi-chat-text"></i>Comment</button> --}}
                        <a href="{{ route('user.viewCommunityPP', $post->id) }}" class="action-btn {{ (Auth::id() == $post->userId || $post->has_commented) ? 'active' : '' }}">
                            <i class="bi bi-chat-text"></i> Comment
                        </a>
                        <button class="action-btn" onclick="copyPostLink('{{$post->id}}')">Share</button>
                        @if(communityBookmarkEnabled())
                            <button type="button" class="action-btn save-btn {{ in_array($post->id, $savedPostIds) ? 'active' : '' }}" data-post-id="{{ $post->id }}">
                                <i class="bi bi-bookmark{{ in_array($post->id, $savedPostIds) ? '-fill' : '' }}"></i> <span class="save-btn-label">{{ in_array($post->id, $savedPostIds) ? 'Saved' : 'Save' }}</span>
                            </button>
                        @endif
                    </div>
                </div>

                <div class="post-side-ad">
                    @php $communityAds = ad_banner(); @endphp
                    @if($communityAds->count())
                        <div id="community-ad-{{ $post->id }}" class="carousel slide" data-bs-ride="carousel" data-bs-interval="8000">
                            <div class="carousel-inner" role="listbox">
                                @foreach($communityAds as $adKey => $ad)
                                    <div class="carousel-item @if($adKey==0) active @endif">
                                        <a href="{{ $ad->link }}" target="_blank" rel="noopener">
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

                @if($inFeedAds->count() && $loop->iteration % 4 == 0)
                    @php $adToShow = $inFeedAds[($loop->iteration / 4 - 1) % $inFeedAds->count()]; @endphp
                    <div class="in-feed-ad-card">
                        <div class="in-feed-ad-label">Sponsored</div>
                        {!! $adToShow->code !!}
                    </div>
                @endif
            @endforeach

        </div>

        @if ($posts->hasPages())
        <nav class="my-5 pb-5">
            <ul class="pagination justify-content-center">

                {{-- Previous --}}
                <li class="page-item {{ $posts->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $posts->previousPageUrl() ?? '#' }}">
                        Previous
                    </a>
                </li>

                {{-- Page Numbers --}}
                @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                    <li class="page-item {{ $posts->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach

                {{-- Next --}}
                <li class="page-item {{ $posts->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $posts->nextPageUrl() ?? '#' }}">
                        Next
                    </a>
                </li>

            </ul>
        </nav>
        @endif
    </div>
        
    <!-- Hidden input for copy functionality -->
    <input type="text" id="copy-helper" style="position: absolute; left: -9999px;">
</div>
@endsection
 {{-- JS Start For Single Page  --}}
@section('js')
<script>
    let controller;
    let debounceTimer;
    function isValidURL(string) {
        try {
            let url = new URL(string);
            return url.protocol === "http:" || url.protocol === "https:";
        } catch (_) {
            return false;
        }
    }
    function extractFirstUrl(text) {
        const match = text.match(/https?:\/\/[^\s]+/i);
        if (!match) return '';
        // Trim common trailing punctuation that isn't really part of the link.
        return match[0].replace(/[.,!?)\]]+$/, '');
    }

    document.getElementById('post-input').addEventListener('input', function () {
        const url = extractFirstUrl(this.value);
        const urlField = document.getElementById('post-url');
        const previewContainer = document.getElementById('url-preview');
        const previewTitle = document.getElementById('url-preview-title');

        clearTimeout(debounceTimer);

        if (!url) {
            urlField.value = '';
            clearUrlPreview(false);
            return;
        }

        if (url === urlField.value) {
            // Same link as already fetched -- nothing to do.
            return;
        }

        debounceTimer = setTimeout(() => {

            if (!isValidURL(url)) {
                return;
            }
            urlField.value = url;
            if (controller) controller.abort();
            controller = new AbortController();
            previewContainer.style.display = 'flex';
            previewContainer.style.opacity = '0.5';
            previewTitle.innerText = "Fetching preview...";
            document.getElementById('url-preview-desc').innerText = '';
            document.getElementById('url-preview-img').src = '';

            console.log("Fetching URL:", url);
    
            fetch("{{ route('user.commynityEarnFatch') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ url }),
                signal: controller.signal
            })
            .then(async res => {
                const contentType = res.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    throw new Error("Server returned non-JSON response (Likely 500/404 Error)");
                }
                return res.json();
            })
            .then(res => {
                console.log("Server Response:", res); // Debugging Log
                if (res.status) {
                    showUrlPreview(res.data);
                } else {
                    console.warn("Server status false");
                    clearUrlPreview();
                }
            })
            .catch((err) => {
                if (err.name === 'AbortError') {
                    console.log('Fetch aborted by user input');
                } else {
                    console.error('Fetch Error:', err);
                    clearUrlPreview();
                }
            })
            .finally(() => {
                previewContainer.style.opacity = '1';
            });
    
        }, 500);
    });
    
    // Quora-style: once we know the page's title, replace the raw pasted
    // URL inside the textarea with [Title](url) so the long link itself
    // stays hidden and only a clean link label shows in the post text.
    function autoLinkifyPastedUrl(url, title) {
        const textarea = document.getElementById('post-input');
        if (!url || !textarea) return;
        // Already converted (or the user typed it inside brackets themselves) -- don't touch it again.
        if (textarea.value.includes('](' + url + ')')) return;
        if (!textarea.value.includes(url)) return;

        let linkText = title;
        if (!linkText) {
            try { linkText = new URL(url).hostname.replace(/^www\./, ''); } catch (_) { linkText = url; }
        }
        // Strip characters that would break the [text](url) markdown itself.
        linkText = linkText.replace(/[\[\]\r\n]/g, '').trim() || url;

        textarea.value = textarea.value.replace(url, '[' + linkText + '](' + url + ')');
        textarea.dispatchEvent(new Event('input'));
    }

    function showUrlPreview(data) {
        document.getElementById('fetchTitle').value = data.title || '';
        document.getElementById('fetchDescription').value = data.description || '';
        document.getElementById('fetchImg').value = data.image || '';

        autoLinkifyPastedUrl(document.getElementById('post-url').value, data.title);

        const previewImgEl = document.getElementById('url-preview-img');
        if (data.image) {
            previewImgEl.src = data.image;
            previewImgEl.style.display = '';
        } else {
            // No og:image on this page -- hide the <img> instead of leaving
            // an empty src, which shows a broken-image icon in most browsers.
            previewImgEl.removeAttribute('src');
            previewImgEl.style.display = 'none';
        }
        document.getElementById('url-preview-title').innerText = data.title || 'No Title Available';
        document.getElementById('url-preview-desc').innerText = data.description || '';
        document.getElementById('url-preview-link').innerText = document.getElementById('post-url').value;

        // Shown alongside text/photo too, just like a Facebook link preview.
        document.getElementById('url-preview').style.display = 'flex';
    }
    function clearUrlPreview(clearInput = true) {
        document.getElementById('fetchTitle').value = '';
        document.getElementById('fetchDescription').value = '';
        document.getElementById('fetchImg').value = '';
        if (clearInput) {
            document.getElementById('post-url').value = '';
        }

        document.getElementById('url-preview-img').src = '';
        document.getElementById('url-preview-title').innerText = '';
        document.getElementById('url-preview').style.display = 'none';
    }
    // Live Image Preview Logic
    function previewImage(input) {
        const previewContainer = document.getElementById('image-preview-container');
        const previewImg = document.getElementById('image-preview');
        const trigger = document.getElementById('upload-trigger');

        if (input.files && input.files[0]) {
            removeSelectedVideo();
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.style.display = 'block';
                trigger.style.display = 'none';
                // A link preview (if any) stays visible alongside the photo,
                // like Facebook shows both an uploaded photo and a link card.
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeSelectedImage() {
        const input = document.getElementById('post_image');
        const previewContainer = document.getElementById('image-preview-container');
        const trigger = document.getElementById('upload-trigger');

        input.value = "";
        previewContainer.style.display = 'none';
        trigger.style.display = 'flex';
    }

    // Live Video Preview Logic
    function previewVideo(input) {
        const previewContainer = document.getElementById('video-preview-container');
        const previewVideoEl = document.getElementById('video-preview');
        const trigger = document.getElementById('video-upload-trigger');
        const urlPreviewDiv = document.getElementById('url-preview');

        if (input.files && input.files[0]) {
            removeSelectedImage();
            const fileUrl = URL.createObjectURL(input.files[0]);
            previewVideoEl.src = fileUrl;
            previewContainer.style.display = 'block';
            if (trigger) trigger.style.display = 'none';
            urlPreviewDiv.style.display = 'none';
        }
    }

    function removeSelectedVideo() {
        const input = document.getElementById('post_video');
        const previewContainer = document.getElementById('video-preview-container');
        const previewVideoEl = document.getElementById('video-preview');
        const trigger = document.getElementById('video-upload-trigger');

        input.value = "";
        previewVideoEl.src = "";
        previewContainer.style.display = 'none';
        if (trigger) trigger.style.display = 'flex';
    }

    // --- Product Attach Logic ---
    // --- Other UI Logic ---
    function toggleEditor(show) {
        const initialState = document.getElementById('initial-state');
        const editorState = document.getElementById('editor-state');
        const input = document.getElementById('post-input');
        if (show) {
            initialState.style.display = 'none';
            editorState.style.display = 'flex';
            input.focus();
        } else {
            initialState.style.display = 'flex';
            editorState.style.display = 'none';
            input.value = '';
            document.getElementById('post-url').value = ''; // Clear URL too
            clearUrlPreview();
            removeSelectedImage();
            removeSelectedVideo();
            handleInput(input);
        }
    }
    
    function handleInput(el) {
        el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
        const text = el.value.trim();
        const words = text ? text.split(/\s+/).length : 0;
        document.getElementById('word-count').innerText = words;
    }


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

    function simulateReplyAction(name, postId) {
        const post = document.getElementById(postId);
        const target = post.querySelector('.mini-input');
        if (target) {
            target.value = "@" + name.replace(/\s/g, '') + " ";
            target.focus();
            autoExpandComment(target);
        }
    }

    // // Demo submit prevention to show UI feedback in preview
    // document.querySelectorAll('form').forEach(form => {
    //     form.addEventListener('submit', function(e) {
    //         e.preventDefault();
    //         alert('Form Submitted! \nData: ' + new FormData(this).get('comment_content') || new FormData(this).get('post_content'));
    //         this.reset();
    //         // In production, remove this block and let the form submit naturally
    //     });
    // });
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
        helper.setSelectionRange(0, 99999);
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
            // Mobile: real OS share sheet. Only counts if the user picks an
            // app and the share promise resolves (not on cancel/failure).
            navigator.share({ title: 'Share Post', url: link })
                .then(() => grantShareEarn(postId))
                .catch(() => console.log('Share cancelled or failed'));
            return;
        }

        // Desktop: open Facebook's real share dialog instead of just
        // copying the link, and grant the earning once that popup closes
        // (best-effort signal -- Facebook does not expose any way to
        // confirm a share actually posted).
        const fbShareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(link);
        const popup = window.open(fbShareUrl, 'fb-share', 'width=600,height=500');

        if (!popup) {
            // Popup blocked -- fall back to copy-link so sharing still works.
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
    function setPostType(type, btn) {
        document.getElementById('post_type').value = type;
        document.querySelectorAll('.post-type-btn').forEach(function (b) {
            b.classList.remove('active');
            b.style.background = '#fff';
            b.style.color = '#0f766e';
        });
        btn.classList.add('active');
        btn.style.background = '#0f766e';
        btn.style.color = '#fff';

        let productFields = document.getElementById('product-fields');
        if (productFields) {
            productFields.style.display = (type === 'product') ? 'flex' : 'none';
        }

        // A Product post's Topic doubles as its category (which kind of
        // affiliate product this is) and admin can set an entirely
        // separate list of topics for Product vs Article/Q&A -- rebuild
        // the dropdown's options from the matching list each time the
        // type toggles, instead of sharing one shared list.
        let topicSelect = document.getElementById('topic_id');
        if (topicSelect) {
            let isProduct = (type === 'product');
            let list = isProduct
                ? (typeof communityProductTopics !== 'undefined' ? communityProductTopics : [])
                : (typeof communityArticleTopics !== 'undefined' ? communityArticleTopics : []);

            let placeholderText = isProduct
                ? 'প্রোডাক্ট ক্যাটাগরি (Topic) বেছে নিন — আবশ্যক'
                : 'বিষয় বেছে নিন (Topic) — ঐচ্ছিক';

            topicSelect.innerHTML = '';
            let placeholderOption = document.createElement('option');
            placeholderOption.id = 'topic-default-option';
            placeholderOption.value = '';
            placeholderOption.textContent = placeholderText;
            topicSelect.appendChild(placeholderOption);

            list.forEach(function (topic) {
                let opt = document.createElement('option');
                opt.value = topic.id;
                opt.textContent = topic.label;
                topicSelect.appendChild(opt);
            });

            topicSelect.required = isProduct;
        }
    }

    // Shrinks a photo in the browser before it's ever uploaded, since the
    // server-side resize only runs AFTER the full original file (often
    // several MB straight off a phone camera) has already been uploaded --
    // on this host's PHP upload limits that upload gets cut off first,
    // showing as a generic connection error. Falls back to the original
    // file untouched if anything about compression fails.
    function compressImageFile(file, maxWidth = 1600, quality = 0.8) {
        return new Promise(function (resolve) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = new Image();
                img.onload = function () {
                    let width = img.width;
                    let height = img.height;
                    if (width > maxWidth) {
                        height = Math.round(height * (maxWidth / width));
                        width = maxWidth;
                    }
                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    canvas.getContext('2d').drawImage(img, 0, 0, width, height);
                    canvas.toBlob(function (blob) {
                        if (!blob) { resolve(file); return; }
                        resolve(new File([blob], file.name.replace(/\.[^.]+$/, '.jpg'), { type: 'image/jpeg' }));
                    }, 'image/jpeg', quality);
                };
                img.onerror = function () { resolve(file); };
                img.src = e.target.result;
            };
            reader.onerror = function () { resolve(file); };
            reader.readAsDataURL(file);
        });
    }

    document.getElementById('main-post-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = this;

        // A link is mandatory -- this feature exists so users can post
        // their own affiliate/site link for a backlink, so block the
        // submit early instead of round-tripping to the server to find out.
        if (!document.getElementById('post-url').value) {
            toastr.error('পোস্টে একটি লিংক (এফিলিয়েট বা আপনার সাইটের লিংক) যোগ করুন।');
            return;
        }

        const formData = new FormData(form);

        const imageInput = document.getElementById('post_image');
        if (imageInput.files && imageInput.files[0]) {
            try {
                const compressed = await compressImageFile(imageInput.files[0]);
                formData.set('post_image', compressed);
            } catch (err) {
                console.error('Image compression failed, sending original file', err);
            }
        }

        fetch(form.action, {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(res => {
            if (res.status === 413) {
                throw new Error('ভিডিও/ছবির সাইজ সার্ভারের আপলোড সীমার চেয়ে বড়। ছোট ফাইল দিয়ে চেষ্টা করুন।');
            }
            return res.json().catch(() => {
                throw new Error('সার্ভারে সমস্যা হয়েছে (HTTP ' + res.status + ')। ফাইলের সাইজ কমিয়ে আবার চেষ্টা করুন।');
            });
        })
        .then(data => {
            if (data.status) {
                // alert(data.message);
                form.reset();
                removeSelectedImage();
                removeSelectedVideo();
                toastr.success(data.message);
                toggleEditor(false);
            } else if (data.errors) {
                // Laravel validation error response shape
                const firstError = Object.values(data.errors)[0][0];
                toastr.error(firstError || data.message || 'Validation failed.');
            } else {
                toastr.error(data.message || 'Something went wrong. Try again.');
            }
        })
        .catch(err => {
            console.error(err);
            if (err.name === 'TypeError' && /fetch/i.test(err.message)) {
                toastr.error('ছবির সাইজ সার্ভারের আপলোড সীমার চেয়ে বড় বলে কানেকশন কেটে গেছে। ছোট ফাইল দিয়ে আবার চেষ্টা করুন।');
            } else {
                toastr.error(err.message || 'Something went wrong. Try again.');
            }
        });
    });
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

    $(document).on('click', '.report-btn', function () {
        let btn = $(this);
        let postId = btn.data('post-id');
        let reason = prompt('কেন এই পোস্টটা রিপোর্ট করছেন? (ঐচ্ছিক)');
        if (reason === null) {
            return; // cancelled
        }

        $.ajax({
            url: '/user/community-report/' + postId,
            type: "POST",
            data: { _token: "{{ csrf_token() }}", reason: reason },
            success: function (response) {
                if (response.status === true) {
                    toastr.success(response.message);
                    btn.prop('disabled', true).css('opacity', 0.3);
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
 {{-- JS End For Single Page  --}}