@extends('user.layouts.master')

@section('user-content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-12">
            <div class="text-center" style="margin-top: 60px; padding: 36px 24px; border-radius: 20px; background: #fff; border: 1px solid #e6edf5; box-shadow: 0 10px 26px rgba(15,23,42,.05);">
                <i class="fas fa-times-circle" style="font-size: 56px; color: #dc3545;"></i>
                <h3 style="margin-top: 16px; font-weight: 800; color: #172b4d;">{{ $message ?? 'পেমেন্ট সম্পন্ন হয়নি।' }}</h3>
                <a href="{{ route('user.shoppay.show') }}" class="btn btn-success mt-3">আবার চেষ্টা করুন</a>
            </div>
        </div>
    </div>
</div>
@endsection
