@extends('user.layouts.master')
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
    .feed-container { width: 100%; margin: 0 auto; padding: 25px 15px; }
    .profile-header-card {
        background: var(--feed-pure-white);
        border: 1px solid var(--feed-border-color);
        border-radius: 16px;
        box-shadow: var(--feed-card-shadow);
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    .profile-avatar {
        width: 72px; height: 72px; border-radius: 50%;
        background: var(--feed-brand-green-soft);
        color: var(--feed-brand-green);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; font-weight: 700; flex-shrink: 0; overflow: hidden;
    }
    .profile-stats { display: flex; gap: 20px; margin-top: 8px; flex-wrap: wrap; }
    .profile-stat strong { display: block; font-size: 1.1rem; color: var(--feed-text-main); }
    .profile-stat span { font-size: 0.8rem; color: var(--feed-text-muted); }
    .follow-btn {
        padding: 8px 18px;
        border-radius: 20px;
        border: 1.5px solid var(--feed-brand-green);
        background: #fff;
        color: var(--feed-brand-green);
        font-weight: 700;
        cursor: pointer;
    }
    .follow-btn.active { background: var(--feed-brand-green-soft); }
    .profile-post-card {
        background: var(--feed-pure-white);
        border: 1px solid var(--feed-border-color);
        border-radius: 16px;
        box-shadow: var(--feed-card-shadow);
        padding: 16px 18px;
        margin-bottom: 16px;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .profile-post-card:hover { border-color: var(--feed-brand-green); }
    .profile-post-type-badge {
        font-size: 0.7rem; font-weight: 700; padding: 2px 10px; border-radius: 10px;
        background: var(--feed-brand-green-soft); color: var(--feed-brand-green);
    }
    .profile-post-content { margin-top: 8px; color: var(--feed-text-main); white-space: pre-line; }
</style>
@endsection

@section('user-content')
<div class="row" style="background-color:#d6ebf1;">
    <div class="feed-container">

        <div class="profile-header-card">
            <div class="profile-avatar">
                @if($profileUser->image)
                    <img src="{{ asset($profileUser->image) }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    {{ strtoupper(substr($profileUser->name, 0, 1)) }}
                @endif
            </div>
            <div style="flex:1; min-width:200px;">
                <h4 class="mb-0 fw-bold">
                    {{ $profileUser->name }}
                    @if($profileUser->kyc_status == 'approve')<i class="bi bi-patch-check-fill" style="color:#0ea5e9;"></i>@endif
                </h4>
                <div class="profile-stats">
                    <div class="profile-stat"><strong>{{ $postCount }}</strong><span>Posts</span></div>
                    <div class="profile-stat"><strong>{{ $followersCount }}</strong><span>Followers</span></div>
                    <div class="profile-stat"><strong>{{ $followingCount }}</strong><span>Following</span></div>
                </div>
            </div>
            @if(communityFollowEnabled() && Auth::id() != $profileUser->id)
                <button type="button" class="follow-btn {{ $isFollowing ? 'active' : '' }}" data-user-id="{{ $profileUser->id }}">
                    {{ $isFollowing ? 'Following' : '+ Follow' }}
                </button>
            @endif
        </div>

        @forelse($posts as $post)
            <a href="{{ route('user.viewCommunityPP', $post->id) }}" class="profile-post-card">
                <span class="profile-post-type-badge">{{ ($post->postType ?? 'article') === 'product' ? '🛍️ Product' : '❓ Q&A / Article' }}</span>
                <div class="profile-post-content">{{ \Illuminate\Support\Str::limit(strip_tags($post->postContent), 200) }}</div>
                @if($post->image)
                    <img src="{{ asset($post->image) }}" style="max-width:100%; max-height:200px; border-radius:8px; margin-top:8px;">
                @endif
            </a>
        @empty
            <div class="profile-post-card">এখনো কোনো পোস্ট নেই।</div>
        @endforelse

        @if($posts->hasPages())
            <nav class="my-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item {{ $posts->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $posts->previousPageUrl() ?? '#' }}">Previous</a>
                    </li>
                    <li class="page-item {{ !$posts->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $posts->nextPageUrl() ?? '#' }}">Next</a>
                    </li>
                </ul>
            </nav>
        @endif

    </div>
</div>
@endsection

@section('js')
<script>
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
</script>
@endsection
