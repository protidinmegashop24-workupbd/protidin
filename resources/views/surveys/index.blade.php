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
      Verify left today: {{ $leftToday ?? 20 }} (Used: {{ $usedToday ?? 0 }}/20)
    </span>
  </div>

  @if(session('success'))
    <div class="alert alert-success" style="font-weight:900;">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger" style="font-weight:900;">{{ session('error') }}</div>
  @endif

  @if(isset($surveyProviders) && $surveyProviders->count())
    @foreach($surveyProviders as $provider)
      <div class="mb-2" style="font-weight:900;">
        {{ $provider->name }}
        <span class="badge bg-light text-dark" style="font-weight:800;font-size:11px;">Sponsored Survey Partner</span>
      </div>
      <div class="row g-3 mb-3 provider-survey-list"
           data-slug="{{ $provider->slug }}"
           data-start-url="{{ route('survey-provider.start', $provider->slug) }}"
           data-list-url="{{ route('survey-provider.list', $provider->slug) }}">
        <div class="col-12 text-muted" style="font-weight:800;font-size:13px;">Loading available surveys…</div>
      </div>
    @endforeach
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

  // Load each provider's individually-priced surveys (e.g. CPX Research's
  // "Get Surveys" API) so the reward + time per survey is visible before
  // the user clicks, instead of one generic "Start Surveys" button.
  function isSafeProviderLink(url) {
    return typeof url === 'string' && /^https:\/\//i.test(url);
  }

  function renderProviderFallback(container, startUrl, message) {
    container.innerHTML = '';
    var col = document.createElement('div');
    col.className = 'col-12';

    var card = document.createElement('div');
    card.className = 'p-3 survey-card h-100 bg-white';

    var note = document.createElement('div');
    note.style.fontSize = '13px';
    note.style.opacity = '.85';
    note.textContent = message;
    card.appendChild(note);

    var btnWrap = document.createElement('div');
    btnWrap.className = 'mt-3';
    var a = document.createElement('a');
    a.className = 'btn btn-success btn-sm w-100';
    a.href = startUrl;
    a.target = '_blank';
    a.rel = 'noopener';
    a.textContent = 'Browse Surveys';
    btnWrap.appendChild(a);
    card.appendChild(btnWrap);

    col.appendChild(card);
    container.appendChild(col);
  }

  function renderProviderSurveys(container, data, startUrl) {
    if (!data || data.status !== 'success' || !data.surveys || !data.surveys.length) {
      renderProviderFallback(container, startUrl, 'এই মুহূর্তে আপনার প্রোফাইলের জন্য নির্দিষ্ট সার্ভে পাওয়া যায়নি — নিচের বাটনে ক্লিক করে দেখুন।');
      return;
    }

    container.innerHTML = '';
    data.surveys.forEach(function (s) {
      var payout = parseFloat(s.payout_publisher_usd || s.payout || 0).toFixed(2);
      var loi = s.loi ? (s.loi + ' min') : '—';
      var link = isSafeProviderLink(s.href_new) ? s.href_new
               : (isSafeProviderLink(s.href) ? s.href : startUrl);

      var col = document.createElement('div');
      col.className = 'col-md-6 col-lg-4';

      var card = document.createElement('div');
      card.className = 'p-3 survey-card h-100 bg-white';

      var title = document.createElement('div');
      title.className = 'survey-title mb-1';
      title.textContent = 'CPX Survey' + (String(s.top) === '1' ? ' ⭐' : '');
      card.appendChild(title);

      var meta = document.createElement('div');
      meta.className = 'text-muted';
      meta.style.fontWeight = '800';
      meta.style.fontSize = '13px';
      meta.textContent = 'Reward: $' + payout + ' | Time: ~' + loi;
      card.appendChild(meta);

      if (s.type === 'need_qualification') {
        var qnote = document.createElement('div');
        qnote.className = 'mt-1';
        qnote.style.fontSize = '12px';
        qnote.style.opacity = '.75';
        qnote.textContent = 'শুরুতে কিছু প্রোফাইল প্রশ্ন থাকতে পারে';
        card.appendChild(qnote);
      }

      var btnWrap = document.createElement('div');
      btnWrap.className = 'mt-3';
      var a = document.createElement('a');
      a.className = 'btn btn-success btn-sm w-100';
      a.href = link;
      a.target = '_blank';
      a.rel = 'noopener';
      a.textContent = 'Start Survey (New Tab)';
      btnWrap.appendChild(a);
      card.appendChild(btnWrap);

      col.appendChild(card);
      container.appendChild(col);
    });
  }

  document.querySelectorAll('.provider-survey-list').forEach(function (container) {
    var startUrl = container.getAttribute('data-start-url');
    var listUrl = container.getAttribute('data-list-url');

    fetch(listUrl, { headers: { 'Accept': 'application/json' } })
      .then(function (r) { return r.json(); })
      .then(function (data) { renderProviderSurveys(container, data, startUrl); })
      .catch(function () {
        renderProviderFallback(container, startUrl, 'সার্ভে লিস্ট লোড করা যায়নি — নিচের বাটনে ক্লিক করে দেখুন।');
      });
  });
</script>
@endsection