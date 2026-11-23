@extends('frontend.layouts.master')

@section('css')
<style>
    .wu-auth-page{
        background: linear-gradient(135deg, #eef7ff 0%, #f8fbff 50%, #ffffff 100%);
        padding: 70px 0;
    }
    .wu-auth-card{
        max-width: 520px;
        margin: 0 auto;
        background: #fff;
        border: 1px solid #e6edf5;
        border-radius: 20px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }
    .wu-auth-header{
        padding: 28px 30px 18px;
        text-align: center;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(135deg, #eefaf2 0%, #f8fbff 100%);
    }
    .wu-auth-header h2{
        margin: 0 0 8px;
        font-size: 30px;
        font-weight: 800;
        color: #172b4d;
    }
    .wu-auth-header p{
        margin: 0;
        color: #64748b;
        font-size: 15px;
        line-height: 1.7;
    }
    .wu-auth-body{
        padding: 30px;
    }
    .wu-label{
        font-size: 14px;
        font-weight: 800;
        color: #172b4d;
        margin-bottom: 8px;
        display: block;
    }
    .wu-input{
        width: 100%;
        height: 50px;
        border: 1px solid #d8e3ee;
        border-radius: 12px;
        padding: 12px 14px;
        box-shadow: none !important;
    }
    .wu-btn{
        width: 100%;
        border: 0;
        border-radius: 12px;
        padding: 14px 18px;
        background: #22ab59;
        color: #fff;
        font-weight: 800;
        font-size: 15px;
    }
</style>
@endsection

@section('front-content')
<div class="wu-auth-page">
    <div class="container">
        <div class="wu-auth-card">
            <div class="wu-auth-header">
                <h2>Set New Password</h2>
                <p>Create a new secure password for your Protidin Mega Earn account.</p>
            </div>

            <div class="wu-auth-body">
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

                <form action="{{ route('set-new-password.post') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="wu-label">Email Address</label>
                        <input type="email" name="email" class="wu-input" value="{{ old('email', $email ?? session('password_reset_verified_email')) }}" required readonly>
                    </div>

                    <div class="mb-3">
                        <label class="wu-label">New Password</label>
                        <input type="password" name="password" class="wu-input" required>
                    </div>

                    <div class="mb-4">
                        <label class="wu-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="wu-input" required>
                    </div>

                    <button type="submit" class="wu-btn">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection