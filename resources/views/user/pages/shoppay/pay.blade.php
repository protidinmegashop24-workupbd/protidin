@extends('user.layouts.master')

@section('css')
<style>
    .sp-card {
        max-width: 520px;
        margin: 40px auto;
        border: 1px solid #e6edf5;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 10px 26px rgba(15,23,42,.05);
        padding: 32px 28px;
    }
    .sp-card h2 {
        font-size: 26px;
        font-weight: 800;
        color: #172b4d;
        margin-bottom: 18px;
        text-align: center;
    }
    .sp-card label {
        font-weight: 700;
        color: #172b4d;
        margin-bottom: 8px;
    }
    .sp-card input.form-control {
        border-radius: 12px;
        padding: 12px 16px;
        border: 1px solid #dce7f2;
        margin-bottom: 18px;
    }
    .sp-btn {
        display: block;
        width: 100%;
        background: #22ab59;
        color: #fff !important;
        border: none;
        border-radius: 12px;
        padding: 13px;
        font-weight: 800;
        font-size: 16px;
        text-decoration: none !important;
    }
    .sp-btn:hover {
        background: #1b8f4b;
    }
</style>
@endsection

@section('user-content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-8 col-12">
            <marquee behavior="scroll" direction="left" scrollamount="5">
                @foreach ($headlines as $headline)
                    <a href="{{ $headline->link }}" class="text-primary" style="font-size: 20px;">
                        <i class="fe fe-link me-2" aria-hidden="true"></i>{{ $headline->title }}
                    </a>
                @endforeach
            </marquee>
        </div>
    </div>
</div>

<div class="sp-card">
    <h2>ShopPay দিয়ে ইনস্ট্যান্ট ডিপোজিট</h2>

    <form action="{{ route('user.shoppay.pay') }}" method="POST">
        @csrf
        <label for="amount">পরিমাণ ($)</label>
        <input type="number" step="0.01" min="1" name="amount" id="amount" class="form-control" placeholder="যেমন: 10" required>
        <button type="submit" class="sp-btn">এগিয়ে যান</button>
    </form>
</div>
@endsection

@section('js')
<script>
    @if(session('error'))
        if (typeof toastr !== 'undefined') {
            toastr.error(@json(session('error')));
        }
    @endif
</script>
@endsection
