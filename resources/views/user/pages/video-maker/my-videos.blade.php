@extends('user.layouts.master')
@section('css')
    <style>
        .mv-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .mv-text {
            flex: 1;
            min-width: 200px;
            color: #334155;
            font-size: 14px;
        }
        .mv-badge-pending { background: #fef9c3; color: #854d0e; }
        .mv-badge-processing { background: #dbeafe; color: #1e40af; }
        .mv-badge-completed { background: #dcfce7; color: #166534; }
        .mv-badge-failed { background: #fee2e2; color: #991b1b; }
        .mv-badge {
            padding: 6px 14px;
            border-radius: 999px;
            font-weight: bold;
            font-size: 12px;
        }
    </style>
@endsection

@section('user-content')
<div class="container-fluid mt-2">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-12">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">{{ $title }}</h4>
                <a href="{{ route('user.video-maker.index') }}" class="btn btn-success btn-sm">+ Make New Video</a>
            </div>

            @if(session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            @forelse($videos as $video)
                <div class="mv-card">
                    <div class="mv-text">
                        <strong>{{ $video->template->name ?? 'Template' }}</strong><br>
                        {{ \Illuminate\Support\Str::limit($video->text_content, 80) }}<br>
                        <small class="text-muted">{{ $video->created_at->format('d/m/Y g:i A') }} &middot; ${{ number_format($video->cost, 4) }}</small>
                    </div>
                    <div>
                        @if($video->status == 'pending')
                            <span class="mv-badge mv-badge-pending">Pending</span>
                        @elseif($video->status == 'processing')
                            <span class="mv-badge mv-badge-processing">Processing</span>
                        @elseif($video->status == 'completed')
                            <span class="mv-badge mv-badge-completed">Ready</span>
                        @elseif($video->status == 'failed')
                            <span class="mv-badge mv-badge-failed">Failed (Refunded)</span>
                        @endif
                    </div>
                    @if($video->status == 'completed')
                        <a href="{{ route('user.video-maker.download', $video->id) }}" class="btn btn-sm btn-primary">Download</a>
                    @endif
                </div>
            @empty
                <div class="text-center text-muted py-5">You haven't made any videos yet.</div>
            @endforelse

            <div class="mt-3">{{ $videos->links() }}</div>

        </div>
    </div>
</div>
@endsection
