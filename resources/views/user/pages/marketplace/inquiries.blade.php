@extends('user.layouts.master')

@section('css')
<style>
    .mp-inquiry-card{
        border: 1px solid #e6edf5;
        border-radius: 18px;
        box-shadow: 0 10px 26px rgba(15,23,42,.06);
        overflow: hidden;
        background: #fff;
    }

    .mp-inquiry-header{
        background: linear-gradient(135deg, #eef4ff 0%, #f8fbff 100%);
        padding: 18px 22px;
        border-bottom: 1px solid #e8eef5;
    }

    .mp-inquiry-header h4{
        margin: 0;
        font-weight: 800;
        color: #172b4d;
    }

    .mp-thread-row{
        border: 1px solid #e8eef5;
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 14px;
        background: #fff;
        box-shadow: 0 8px 16px rgba(15,23,42,.04);
    }

    .mp-thread-title{
        font-size: 20px;
        font-weight: 800;
        color: #172b4d;
        margin-bottom: 6px;
    }

    .mp-thread-sub{
        color: #617288;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .mp-open-btn{
        border-radius: 10px;
        font-weight: 800;
        padding: 10px 16px;
    }
</style>
@endsection

@section('user-content')
<div class="mp-inquiry-card mt-4">
    <div class="mp-inquiry-header">
        <h4>Service Inquiries</h4>
    </div>

    <div class="card-body p-4">
        @forelse($threads as $thread)
            @php
                $unreadCount = $thread->unread_count ?? 0;
            @endphp

            <div class="mp-thread-row">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <div class="mp-thread-title">{{ $thread->service_title }}</div>
                        <div class="mp-thread-sub">Conversation with {{ $thread->other_user_name }}</div>
                    </div>

                    @if($unreadCount > 0)
                        <span class="badge bg-danger rounded-pill">{{ $unreadCount }} new</span>
                    @endif
                </div>

                <a href="{{ route('user.marketplace.inquiry.thread', [$thread->service_id, $thread->other_user_id]) }}" class="btn btn-info mp-open-btn mt-2">
                    Open Chat
                </a>
            </div>
        @empty
            <div class="alert alert-info mb-0">No inquiries found.</div>
        @endforelse
    </div>
</div>
@endsection