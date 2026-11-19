@php
    $drawsAtValue = $data && $data->draws_at ? $data->draws_at->format('Y-m-d\TH:i') : '';
@endphp

<div class="form-row">
    <div class="form-group col-md-6">
        <label>Title</label>
        <input type="text" name="title" class="form-control" value="{{ $data->title ?? '' }}" placeholder="e.g. Weekly Lucky Draw #1" required>
    </div>
    <div class="form-group col-md-3">
        <label>Ticket Price ($)</label>
        <input type="number" step="0.0001" min="0.0001" name="price" class="form-control" value="{{ $data->price ?? '' }}" required>
    </div>
    <div class="form-group col-md-3">
        <label>Max Tickets / User (optional)</label>
        <input type="number" min="1" name="max_tickets_per_user" class="form-control" value="{{ $data->max_tickets_per_user ?? '' }}" placeholder="Unlimited">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-4">
        <label>1st Prize %</label>
        <input type="number" step="0.01" min="0" max="100" name="prize_percent_1st" class="form-control" value="{{ $data->prize_percent_1st ?? 40 }}" required>
    </div>
    <div class="form-group col-md-4">
        <label>2nd Prize %</label>
        <input type="number" step="0.01" min="0" max="100" name="prize_percent_2nd" class="form-control" value="{{ $data->prize_percent_2nd ?? 25 }}" required>
    </div>
    <div class="form-group col-md-4">
        <label>3rd Prize %</label>
        <input type="number" step="0.01" min="0" max="100" name="prize_percent_3rd" class="form-control" value="{{ $data->prize_percent_3rd ?? 15 }}" required>
    </div>
</div>
<p class="text-muted" style="font-size:12px;margin-top:-8px;">
    Pool = ticket price &times; total tickets sold. Each percentage above is paid out of that pool to the 1st/2nd/3rd place winner. Whatever's left over (100% &minus; the three percentages) stays as platform revenue.
</p>

<div class="form-row">
    <div class="form-group col-md-6">
        <label>Draw Date &amp; Time</label>
        <input type="datetime-local" name="draws_at" class="form-control" value="{{ $drawsAtValue }}" required>
        <small class="text-muted">The cron job (or the "Draw Now" button) picks the round up once this time has passed.</small>
    </div>
    <div class="form-group col-md-6">
        <label>Image (optional)</label>
        <input type="file" name="image" class="form-control-file">
        @if($data && $data->image)
            <img src="{{ asset($data->image) }}" style="height:60px;margin-top:6px;">
        @endif
    </div>
</div>

<div class="form-group">
    <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="auto_renew{{ $data->id ?? 'new' }}" name="auto_renew" value="1" @if(!$data || $data->auto_renew) checked @endif>
        <label class="custom-control-label" for="auto_renew{{ $data->id ?? 'new' }}">Automatically create next week's round after this one is drawn</label>
    </div>
</div>
