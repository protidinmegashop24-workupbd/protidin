@extends('user.layouts.master')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  .survey-card{border-radius:14px;border:1px solid rgba(0,0,0,.12)}
  .survey-title{font-weight:900}
  .sv-rules{background:#eef7ff;border:1px solid #cfe3f7;border-radius:14px;padding:16px 18px;}
  .sv-rules h6{font-weight:900;margin-bottom:10px;}
  .sv-rules ol{margin-bottom:0;padding-left:20px;}
  .sv-rules li{margin-bottom:6px;font-weight:700;font-size:14.5px;}
  .sv-rules .sv-sub{font-weight:800;color:#0d6efd;margin-top:10px;margin-bottom:4px;font-size:14px;}
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

  <div class="sv-rules mb-4">
    <h6>📋 সার্ভে করার নিয়ম</h6>
    <div class="sv-sub">১) CPX Research (উপরের সবুজ বাটন)</div>
    <ol start="1">
      <li>"ফুল পেজে সার্ভে দেখুন" বাটনে চাপুন, একটা তালিকা থেকে যেকোনো একটা সার্ভে বেছে নিয়ে শেষ করুন।</li>
      <li>সম্পূর্ণ করলেই <strong>অটোমেটিক</strong> টাকা আপনার earning balance-এ যোগ হয়ে যাবে — কোনো কোড বা ভেরিফাই করার দরকার নেই।</li>
    </ol>
    <div class="sv-sub">২) নিচের কার্ডের সার্ভেগুলো (নিজস্ব প্রশ্নোত্তর)</div>
    <ol start="1">
      <li>যেকোনো একটা সার্ভে কার্ডে "Start Survey" চাপুন।</li>
      <li>একের পর এক প্রশ্নের উত্তর দিয়ে "Next" চাপুন, শেষ প্রশ্নে "Finish & Get Code" চাপুন।</li>
      <li>শেষ হলে একটা <strong>ইউনিক কোড</strong> পাবেন এবং কত টাকা আয় হয়েছে তা দেখাবে — কিন্তু এই টাকা তখনো আপনার ব্যালেন্সে যোগ হয়নি।</li>
      <li>কোডটা কপি করে <strong>"Verify Code"</strong> বাটনে চাপুন (অথবা মেনু থেকে Verify পেজে গিয়ে কোডটা বসান) — তবেই টাকা ব্যালেন্সে যোগ হবে।</li>
      <li>⚠️ দিনে সর্বোচ্চ <strong>২০টা কোড ভেরিফাই</strong> করা যাবে (উপরের ব্যাজে কতটা বাকি আছে দেখা যাচ্ছে)। এর বেশি কোড জমা হলেও, পরের ভেরিফাই কালকে করতে হবে।</li>
      <li>একই সার্ভে দিনে একবারই করা যাবে — আজকে করা সার্ভে আজ আর তালিকায় দেখাবে না, কাল আবার আসবে।</li>
    </ol>
  </div>

  @if(session('success'))
    <div class="alert alert-success" style="font-weight:900;">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger" style="font-weight:900;">{{ session('error') }}</div>
  @endif

  @php
    $cpxProvider = isset($surveyProviders) ? $surveyProviders->firstWhere('slug', 'cpx-research') : null;
    $otherProviders = isset($surveyProviders) ? $surveyProviders->reject(fn($p) => $p->slug === 'cpx-research') : collect();
  @endphp

  @if($cpxProvider && $cpxProvider->app_id && $cpxProvider->secret_key)
    <div class="mb-2" style="font-weight:900;">
      {{ $cpxProvider->name }}
      <span class="badge bg-light text-dark" style="font-weight:800;font-size:11px;">Sponsored Survey Partner</span>
    </div>
    {{-- The embedded "Script Tag" iframe widget below is cramped/broken on
         mobile (confined to a small in-page box, page content and floating
         buttons still visible around it -- not an actual full page). The
         real full-page survey wall is this direct link to CPX's own
         hosted page, so it's the prominent, primary action here instead of
         small grey text under the widget. DISPLAY ONLY either way: the
         wallet is still credited exclusively by the server-side postback
         in SurveyProviderController, never by the widget's client-side
         callbacks. --}}
    <div class="text-center mb-3">
      <a href="{{ route('survey-provider.start', $cpxProvider->slug) }}" target="_blank" class="btn btn-success w-100" style="font-weight:900;max-width:950px;">
        ফুল পেজে সার্ভে দেখুন (নতুন ট্যাবে খুলবে)
      </a>
    </div>

    <div id="cpx-fullscreen" style="max-width:950px;margin:auto;min-height:40px;" class="mb-2"></div>
  @endif

  @if($otherProviders->count())
    @foreach($otherProviders as $provider)
      <div class="mb-2" style="font-weight:900;">
        {{ $provider->name }}
        <span class="badge bg-light text-dark" style="font-weight:800;font-size:11px;">Sponsored Survey Partner</span>
      </div>
      <div class="row g-3 mb-3">
        <div class="col-12">
          <div class="p-3 survey-card h-100 bg-white">
            <div class="mt-2" style="font-size:13px;opacity:.85;">
              Reward shown per survey after you open it — varies by survey.
            </div>
            <div class="mt-3">
              <a class="btn btn-success btn-sm w-100" href="{{ route('survey-provider.start', $provider->slug) }}" target="_blank">
                Start Surveys
              </a>
            </div>
          </div>
        </div>
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

@if($cpxProvider && $cpxProvider->app_id && $cpxProvider->secret_key)
  // CPX Research "Script Tag" widget config -- structure, field names, and
  // recommended settings are copied directly from CPX Research's own
  // official documentation (cpx-research.com/main/en/doc.php). secure_hash
  // uses the same md5(user_id-secret) formula already verified end-to-end
  // via a real postback for this same account.
  var cpxCommonScript = {
    div_id: "cpx-fullscreen",
    theme_style: 1, // 1 = fullscreen / full content widget
    order_by: 2,    // sort by best money first
    limit_surveys: 12
  };

  window.config = {
    general_config: {
      app_id: {{ (int) $cpxProvider->app_id }},
      ext_user_id: "{{ Auth::id() }}",
      secure_hash: "{{ md5(Auth::id() . '-' . $cpxProvider->secret_key) }}"
    },
    style_config: {
      text_color: "#2b2b2b",
      survey_box: {
        topbar_background_color: "#198754",
        box_background_color: "white",
        rounded_borders: true,
        stars_filled: "black"
      }
    },
    script_config: [cpxCommonScript],
    debug: false,
    useIFrame: true,
    iFramePosition: 1,
    functions: {
      no_surveys_available: function () {
        var el = document.getElementById('cpx-fullscreen');
        if (el) {
          el.innerHTML = '<div class="p-3 survey-card bg-white" style="font-size:13px;opacity:.85;">এই মুহূর্তে আপনার প্রোফাইলের জন্য কোনো সার্ভে নেই। কিছুক্ষণ পর আবার চেষ্টা করুন।</div>';
        }
      }
    }
  };

  (function () {
    var cpxScript = document.createElement('script');
    cpxScript.src = 'https://cdn.cpx-research.com/assets/js/script_tag_v2.0.js';
    document.body.appendChild(cpxScript);
  })();
@endif
</script>
@endsection