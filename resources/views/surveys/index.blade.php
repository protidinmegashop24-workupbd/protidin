@extends('user.layouts.master')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  .survey-card{border-radius:14px;border:1px solid rgba(0,0,0,.12)}
  .survey-title{font-weight:900}
</style>
@endsection

@section('user-content')
<div class="container mt-4">

  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="mb-0" style="font-weight:900;">Surveys</h4>

    <span class="badge rounded-pill bg-primary">
      Verify left today: {{ $leftToday ?? 10 }} (Used: {{ $usedToday ?? 0 }}/10)
    </span>
  </div>

  @if(session('success'))
    <div class="alert alert-success" style="font-weight:900;">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger" style="font-weight:900;">{{ session('error') }}</div>
  @endif

  @if(isset($surveys) && $surveys->count())
    <div class="row g-3" id="svList">
      @foreach($surveys as $s)
        <div class="col-md-6 col-lg-4" id="svCard{{ $s->id }}">
          <div class="p-3 survey-card h-100 bg-white">
            <div class="survey-title mb-1">{{ $s->title }}</div>
            <div class="text-muted" style="font-weight:800;font-size:13px;">
              Reward (Total): ${{ number_format((float)$s->reward, 4) }} | Topic: {{ $s->topic ?? 'general' }}
            </div>

            <div class="mt-2" style="font-size:13px;opacity:.85;">
              Questions/day: {{ (int)($s->questions_per_attempt ?? 10) }}
            </div>

            <div class="mt-3">
              <a class="btn btn-success btn-sm w-100 svOpen"
                 href="{{ route('surveys.show', $s->id) }}"
                 target="_blank"
                 data-id="{{ $s->id }}">
                Start Survey (New Tab)
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-3">
      {{ $surveys->links() }}
    </div>
  @else
    <div class="p-3 bg-white" style="border:1px solid rgba(0,0,0,.12);border-radius:12px;font-weight:900;">
      আজকের জন্য কোন নতুন Survey নাই।
    </div>
  @endif
</div>
@endsection

@section('js')
<script>
  // ✅ instant vanish without reload
  document.querySelectorAll('.svOpen').forEach(btn=>{
    btn.addEventListener('click', function(){
      const id = this.getAttribute('data-id');
      const card = document.getElementById('svCard'+id);
      if(card) card.remove();
    });
  });
</script>
@endsection