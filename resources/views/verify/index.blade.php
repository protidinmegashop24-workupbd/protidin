@extends('user.layouts.master')

@section('user-content')
<div class="container mt-4">

  <div class="card shadow-sm" style="border-radius:16px;">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center"
         style="border-radius:16px 16px 0 0;">
      <div style="font-weight:900;">Verify Code</div>

      @php
        $used = $usedToday ?? (session('usedToday') ?? 0);
        $left = $leftToday ?? (session('leftToday') ?? 10);
      @endphp

      <div style="font-weight:900;">
        Verify left today: {{ $left }} <span style="opacity:.85;">(Used: {{ $used }}/10)</span>
      </div>
    </div>

    <div class="card-body">

      @if(session('success'))
        <div class="alert alert-success" style="font-weight:900;">
          {{ session('success') }}
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger" style="font-weight:900;">
          {{ session('error') }}
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger" style="font-weight:900;">
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('verify.verify') }}">
        @csrf
        <label style="font-weight:900;">Enter Code</label>
        <input type="text" name="code" class="form-control mt-2"
               placeholder="BD-SV-YYYYMMDD-XXXXXXXX" required>

        <button type="submit" class="btn btn-success mt-3" style="font-weight:900;">
          Verify
        </button>

        <a href="{{ route('surveys.index') }}" class="btn btn-dark mt-3" style="font-weight:900;">
          Back to Surveys
        </a>
      </form>

      {{-- ✅ Verified result box --}}
      @if(session('verified_code'))
        <hr>
        <div class="p-3" style="border:1px solid rgba(22,163,74,.35);background:rgba(22,163,74,.08);border-radius:14px;">
          <div style="font-weight:900;margin-bottom:6px;">✅ Verification Result</div>

          <div style="font-weight:900;">
            Code: <span style="letter-spacing:.6px;">{{ session('verified_code') }}</span>
          </div>

          <div style="font-weight:900;margin-top:6px;">
            Correct: {{ session('correctCount') }}/{{ session('totalQuestions') }}
          </div>

          <div style="font-weight:900;margin-top:6px;">
            Earned Added: ${{ session('earnedUsd') }}
          </div>

          <div class="mt-2" style="opacity:.85;font-weight:800;">
            ✅ Earned amount আপনার main balance এ যোগ হয়েছে।
          </div>
        </div>
      @endif

    </div>
  </div>

</div>
@endsection