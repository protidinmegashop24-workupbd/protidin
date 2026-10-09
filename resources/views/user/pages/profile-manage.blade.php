@extends('user.layouts.master')

@section('css')
<style>
    .page-title-box {
        background: linear-gradient(to right, #6a11cb, #2575fc);
        padding: 20px;
        border-radius: 10px;
        color: #fff;
        text-align: center;
        margin-bottom: 30px;
    }

    .directory-card {
        background-color: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .avatar-lg {
        width: 100px;
        height: 100px;
    }

    .flex-grow-1 h5 {
        font-size: 24px;
        color: #343a40;
        margin-bottom: 5px;
    }

    .flex-grow-1 p {
        margin: 0;
        font-size: 14px;
        color: #6c757d;
    }

    .form-group label {
        font-weight: 700;
        color: #495057;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 10px;
        padding: 10px;
    }

    .btnn-primary {
        background: #22ab59;
        color: #fff;
        border: none;
        padding: 11px 22px;
        border-radius: 50px;
        font-size: 16px;
        font-weight: 700;
        transition: background 0.3s;
    }

    .btnn-primary:hover {
        background: #1b8f4b;
    }

    .img-thumbnail {
        border: 2px solid #dee2e6;
    }

    .section-title {
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 15px;
        color: #172b4d;
    }
</style>
@endsection

@section('user-content')
<div class="page-title-box">
    <h2>Manage Profile</h2>
    <p>Update your account information and seller details from one place.</p>
</div>

<div class="row justify-content-center">
    <div class="col-xl-4 col-md-6 col-12">
        <div class="card directory-card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <img src="{{ URL::to(Auth::user()->image) }}"
                             alt="{{ Auth::user()->name }}"
                             class="img-fluid img-thumbnail rounded-circle avatar-lg"
                             onerror="this.onerror=null;this.src='{{ asset('frontend/img/user.png') }}';">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="text-primary font-size-18 mb-1">{{ Auth::user()->name }}</h5>
                        <p class="font-size-12 mb-2">Since {{ \Carbon\Carbon::parse(Auth::user()->created_at)->format('d M Y') }}</p>
                        <p class="mb-0">User ID: {{ Auth::user()->code }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-xl-8 col-md-10 col-12">
        <div class="card directory-card">
            <div class="card-body">
                @if(session('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
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

                <form action="{{ route('user.profile.update', Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="section-title">Basic Information</div>

                    <div class="form-group mb-3">
                        <label>Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="name" value="{{ Auth::user()->name }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input class="form-control" type="email" readonly value="{{ Auth::user()->email }}">
                    </div>

                    <div class="form-group mb-3">
                        <label>Phone</label>
                        <input class="form-control" type="text" value="{{ Auth::user()->phone }}" name="phone" placeholder="Phone">
                    </div>

                    <div class="form-group mb-3">
                        <label>Country</label>
                        <input class="form-control" type="text" value="{{ country(Auth::user()->country) }}" readonly>
                    </div>

                    <div class="form-group mb-4">
                        <label>Profile Image</label>
                        <input class="form-control" type="file" name="image">
                    </div>

                    <div class="section-title">Seller Information</div>

                    <div class="form-group mb-3">
                        <label>Experience Level</label>
                        <select name="seller_experience_level" class="form-control">
                            <option value="">Select Experience Level</option>
                            <option value="Beginner" {{ Auth::user()->seller_experience_level == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="Intermediate" {{ Auth::user()->seller_experience_level == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="Expert" {{ Auth::user()->seller_experience_level == 'Expert' ? 'selected' : '' }}>Expert</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Skills</label>
                        <input class="form-control" type="text" name="seller_skills" value="{{ Auth::user()->seller_skills }}" placeholder="Example: WordPress, SEO, Content Writing">
                    </div>

                    <div class="form-group mb-4">
                        <label>Seller Bio / About</label>
                        <textarea class="form-control" name="seller_bio" rows="5" placeholder="Write a short professional introduction about your work experience, services, and strengths...">{{ Auth::user()->seller_bio }}</textarea>
                    </div>

                    <div class="section-title">Security</div>

                    <div class="form-group mb-4">
                        <label>New Password</label>
                        <input class="form-control" type="password" name="password" placeholder="Enter new password">
                    </div>

                    <button type="submit" class="btnn btnn-primary mt-2 mb-0">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection