@extends('backend.layouts.master')

@section('title','Telegram Broadcast')
@section('back-content')

<div class="container-fluid mt-3">

    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Send a Message to Channel or Group</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

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

                    <form action="{{ route('admin.telegram-broadcast.send') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Send To</label>
                            <select name="target" class="form-control" required>
                                <option value="channel" {{ old('target') == 'channel' ? 'selected' : '' }}>Channel (@WorkUpBD24)</option>
                                <option value="group" {{ old('target') == 'group' ? 'selected' : '' }}>Group (@WorkUpB)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Message</label>
                            <textarea name="message" class="form-control" rows="6" placeholder="Write your post here... emojis are fine 🎉" required>{{ old('message') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Button Text (optional)</label>
                            <input type="text" name="button_text" class="form-control" placeholder="Example: 👉 Visit Now" value="{{ old('button_text') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Button Link (optional)</label>
                            <input type="url" name="button_url" class="form-control" placeholder="https://protidinmegashop.com" value="{{ old('button_url') }}">
                            <div class="form-text">Fill in both Button Text and Button Link together, or leave both empty for a plain text post.</div>
                        </div>

                        <button type="submit" class="btn btn-success" onclick="return confirm('Send this now? It will post immediately and cannot be edited afterward.');">
                            Send Now
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Preview</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">This is roughly how it will look in Telegram:</p>
                    <div style="background:#212121; color:#fff; border-radius:10px; padding:14px; font-size:14px; white-space:pre-wrap;" id="preview-text">
                        Type your message to see a preview...
                    </div>
                    <div class="text-center mt-2">
                        <span id="preview-button" style="display:none; background:#2a2a2a; color:#5fa8ff; border-radius:8px; padding:8px 16px; display:inline-block;">
                            Button
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@section('js')
<script>
    document.querySelector('textarea[name="message"]').addEventListener('input', function () {
        document.getElementById('preview-text').textContent = this.value || 'Type your message to see a preview...';
    });

    function updatePreviewButton() {
        var text = document.querySelector('input[name="button_text"]').value;
        var btn = document.getElementById('preview-button');
        if (text) {
            btn.textContent = text;
            btn.style.display = 'inline-block';
        } else {
            btn.style.display = 'none';
        }
    }
    document.querySelector('input[name="button_text"]').addEventListener('input', updatePreviewButton);
    updatePreviewButton();
</script>
@endsection
@endsection
