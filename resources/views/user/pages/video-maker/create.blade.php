@extends('user.layouts.master')
@section('css')
    <style>
        .vm-form-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .vm-preview-thumb {
            width: 100%;
            max-width: 220px;
            border-radius: 12px;
            margin-bottom: 16px;
        }
        .vm-cost-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 14px;
            font-size: 15px;
            margin-top: 16px;
        }
        .vm-cost-box strong { color: #16a34a; font-size: 20px; }
    </style>
@endsection

@section('user-content')
<div class="container-fluid mt-2">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-12">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">{{ $title }}</h4>
                <a href="{{ route('user.video-maker.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="vm-form-card">
                @if($template->thumbnail_path)
                    <img src="{{ asset($template->thumbnail_path) }}" class="vm-preview-thumb" alt="{{ $template->name }}">
                @endif
                <h5>{{ $template->name }}</h5>
                <p class="text-muted">{{ $template->category }} &middot; {{ $template->duration_seconds }} seconds</p>

                <form action="{{ route('user.video-maker.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="template_id" value="{{ $template->id }}">

                    <div class="form-group">
                        <label>Your Text (Bengali, Arabic, or English)</label>
                        <textarea name="text_content" class="form-control" rows="4" maxlength="400" required placeholder="Write the text you want shown on the video...">{{ old('text_content') }}</textarea>
                        <small class="text-muted">Max 400 characters. Keep it short for best results (1-2 sentences).</small>
                    </div>

                    <div class="vm-cost-box">
                        এই ভিডিও বানাতে খরচ হবে <strong>${{ number_format($template->cost, 4) }}</strong> (আপনার earning balance থেকে কাটবে)।
                        আপনার বর্তমান ব্যালেন্স: ${{ number_format(Auth::user()->earning_balance, 4) }}
                    </div>

                    <button type="submit" class="btn btn-success btn-lg btn-block mt-4">Generate Video</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
