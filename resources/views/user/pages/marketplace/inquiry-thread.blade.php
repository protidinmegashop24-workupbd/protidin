@extends('user.layouts.master')

@section('css')
<style>
    .mp-chat-card{
        border: 1px solid #e6edf5;
        border-radius: 20px;
        box-shadow: 0 12px 28px rgba(15,23,42,.06);
        overflow: hidden;
        background: #fff;
    }

    .mp-chat-header{
        background: linear-gradient(135deg, #eef4ff 0%, #f8fbff 100%);
        padding: 18px 22px;
        border-bottom: 1px solid #e8eef5;
    }

    .mp-chat-header h5{
        margin: 0;
        font-size: 24px;
        font-weight: 800;
        color: #172b4d;
    }

    .mp-chat-body{
        max-height: 520px;
        overflow-y: auto;
        padding: 20px;
        background: #f8fbff;
    }

    .mp-chat-bubble{
        max-width: 82%;
        margin-bottom: 14px;
        padding: 14px 16px;
        border-radius: 16px;
        word-break: break-word;
        box-shadow: 0 8px 16px rgba(15,23,42,.04);
    }

    .mp-chat-me{
        margin-left: auto;
        background: #eaf8ef;
        border-bottom-right-radius: 6px;
    }

    .mp-chat-other{
        margin-right: auto;
        background: #fff;
        border: 1px solid #e7edf4;
        border-bottom-left-radius: 6px;
    }

    .mp-chat-role{
        font-size: 12px;
        font-weight: 800;
        color: #66758b;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .mp-chat-footer{
        padding: 18px;
        border-top: 1px solid #e8eef5;
        background: #fff;
    }

    .mp-send-btn{
        background: #1d4ed8;
        color: #fff;
        border: 0;
        border-radius: 10px;
        padding: 11px 18px;
        font-weight: 800;
    }

    .mp-send-btn:hover{
        background: #173fb2;
        color: #fff;
    }
</style>
@endsection

@section('user-content')
<div class="mp-chat-card mt-4">
    <div class="mp-chat-header">
        <h5>Inquiry Chat - {{ $service->title }}</h5>
    </div>

    <div class="mp-chat-body">
        @forelse($messages as $msg)
            <div class="mp-chat-bubble {{ $msg->sender_id == auth()->id() ? 'mp-chat-me' : 'mp-chat-other' }}">
                <div class="mp-chat-role">{{ $msg->sender_id == auth()->id() ? 'You' : $otherUser->name }}</div>
                <div>{{ $msg->message }}</div>
                @if($msg->file)
                    <div class="mt-2">
                        <a href="{{ url($msg->file) }}" target="_blank">Download Attachment</a>
                    </div>
                @endif
            </div>
        @empty
            <div class="alert alert-light mb-0">No messages found.</div>
        @endforelse
    </div>

    <div class="mp-chat-footer">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('user.marketplace.inquiry.send', [$service->id, $otherUser->id]) }}" method="POST">
            @csrf
            <div class="mb-3">
                <textarea name="message" class="form-control" rows="3" placeholder="Write message..." required></textarea>
            </div>
            <button type="submit" class="btn mp-send-btn">Send Message</button>
        </form>
    </div>
</div>
@endsection