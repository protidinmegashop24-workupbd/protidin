@extends('layouts.app')

@section('content')
<div class="container">
  <h2 style="margin:10px 0;">Survey Completed ✅</h2>

  <div style="padding:14px;border-radius:14px;border:1px solid rgba(0,0,0,.12);background:#fff;">
    <div style="font-weight:900;margin-bottom:8px;">Your Unique Code:</div>

    <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
      <input id="codeBox" value="{{ $code }}" readonly
        style="padding:10px;border-radius:12px;border:1px solid rgba(0,0,0,.18);min-width:280px;">
      <button type="button" id="copyBtn"
        style="padding:10px 12px;border-radius:12px;border:none;background:#2563eb;color:#fff;font-weight:900;cursor:pointer;">
        Copy
      </button>
    </div>

    <div id="copiedMsg" style="display:none;margin-top:10px;font-weight:900;color:#16a34a;">
      ✅ Copied!
    </div>

    <div style="margin-top:12px;">
      <a href="{{ route('verify.show') }}" style="font-weight:900;text-decoration:none;color:#2563eb;">
        Verify Code Now →
      </a>
    </div>
  </div>
</div>

<script>
document.getElementById('copyBtn').addEventListener('click', async () => {
  const el = document.getElementById('codeBox');
  try{
    await navigator.clipboard.writeText(el.value);
  }catch(e){
    el.select();
    document.execCommand('copy');
  }
  document.getElementById('copiedMsg').style.display = 'block';
});
</script>
@endsection
