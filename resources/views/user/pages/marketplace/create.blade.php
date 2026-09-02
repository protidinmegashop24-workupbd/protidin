@extends('user.layouts.master')

@section('css')
<style>
    .mp-form-wrap{
        max-width: 1100px;
        margin: 0 auto;
    }

    .mp-form-card{
        border: 1px solid #e6edf5;
        border-radius: 22px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
        overflow: hidden;
        background: #fff;
    }

    .mp-form-hero{
        background: linear-gradient(135deg, #eefaf2 0%, #f8fbff 45%, #ffffff 100%);
        padding: 34px 34px 26px;
        border-bottom: 1px solid #e8eef5;
    }

    .mp-form-badge{
        display: inline-block;
        background: #e8fff1;
        color: #15803d;
        font-weight: 800;
        font-size: 12px;
        padding: 8px 13px;
        border-radius: 999px;
        margin-bottom: 14px;
    }

    .mp-form-title{
        font-size: 34px;
        line-height: 1.2;
        font-weight: 800;
        color: #172b4d;
        margin-bottom: 10px;
    }

    .mp-form-subtitle{
        color: #5f6f86;
        font-size: 16px;
        line-height: 1.8;
        max-width: 760px;
        margin-bottom: 0;
    }

    .mp-form-body{
        padding: 30px;
    }

    .mp-label{
        font-size: 14px;
        font-weight: 800;
        color: #172b4d;
        margin-bottom: 8px;
        display: block;
    }

    .mp-input,
    .mp-textarea,
    .mp-file{
        border: 1px solid #d8e3ee;
        border-radius: 12px;
        min-height: 48px;
        padding: 12px 14px;
        box-shadow: none !important;
    }

    .mp-textarea{
        min-height: 130px;
        resize: vertical;
    }

    .mp-help{
        color: #6b7280;
        font-size: 12px;
        margin-top: 6px;
    }

    .mp-form-box{
        background: #f9fcff;
        border: 1px solid #e8eef5;
        border-radius: 16px;
        padding: 20px;
        height: 100%;
    }

    .mp-form-box h5{
        font-weight: 800;
        color: #172b4d;
        margin-bottom: 10px;
    }

    .mp-form-box p{
        color: #607086;
        line-height: 1.8;
        margin-bottom: 0;
    }

    .mp-submit-btn{
        background: #22ab59;
        color: #fff;
        border: 0;
        border-radius: 12px;
        padding: 13px 24px;
        font-weight: 800;
        box-shadow: 0 10px 24px rgba(34, 171, 89, .18);
    }

    .mp-submit-btn:hover{
        background: #1b8e4a;
        color: #fff;
    }

    @media (max-width: 767px){
        .mp-form-hero{
            padding: 22px 18px;
        }

        .mp-form-body{
            padding: 18px;
        }

        .mp-form-title{
            font-size: 28px;
        }
    }
</style>
@endsection

@section('user-content')
<div class="mp-form-wrap mt-4">
    <div class="mp-form-card">

        <div class="mp-form-hero">
            <span class="mp-form-badge">Sell on Workup BD</span>
            <div class="mp-form-title">Create a new service listing</div>
            <p class="mp-form-subtitle">
                Add a clear title, delivery terms, pricing, and a professional description so buyers can understand your offer quickly and decide with confidence.
            </p>
        </div>

        <div class="mp-form-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.marketplace.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-lg-8">
                        <div class="mb-4">
                            <label class="mp-label">Service Title</label>
                            <input type="text" name="title" class="form-control mp-input" value="{{ old('title') }}" placeholder="Example: I will customize your WordPress website professionally" required>
                            <div class="mp-help">Use a clear title that tells buyers exactly what you offer.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="mp-label">Category</label>
                                <select name="category" class="form-control mp-input" required>
    <option value="">Select Category</option>
    @foreach($categories as $cat)
        <option value="{{ $cat->name }}" {{ old('category') == $cat->name ? 'selected' : '' }}>
            {{ $cat->name }}
        </option>
    @endforeach
</select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="mp-label">Price ($)</label>
                                <input type="number" step="0.01" name="price" class="form-control mp-input" value="{{ old('price') }}" placeholder="10.00" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="mp-label">Delivery Days</label>
                                <input type="number" name="delivery_days" class="form-control mp-input" value="{{ old('delivery_days') }}" placeholder="3" required>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="mp-label">Revision Limit</label>
                                <input type="number" name="revision_limit" class="form-control mp-input" value="{{ old('revision_limit', 0) }}" placeholder="0">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="mp-label">Short Description</label>
                            <textarea name="short_description" class="form-control mp-textarea" rows="3" placeholder="Write a short summary of your service">{{ old('short_description') }}</textarea>
                            <div class="mp-help">This short text appears in service cards and helps attract buyers.</div>
                        </div>

                        <div class="mb-4">
                            <label class="mp-label">Full Description</label>
                            <textarea name="description" class="form-control mp-textarea" rows="8" placeholder="Describe your service in detail, what the buyer will get, what you need from the buyer, and how your process works." required>{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="mp-label">Service Image</label>
                            <input type="file" name="image" class="form-control mp-file">
                            <div class="mp-help">Use a clean and high-quality image to make your service look more professional.</div>
                        </div>

                        <button type="submit" class="btn mp-submit-btn">Publish Service for Review</button>
                    </div>

                    <div class="col-lg-4">
                        <div class="mp-form-box mb-4">
                            <h5>What makes a good service?</h5>
                            <p>
                                A strong service title, fair price, realistic delivery time, and detailed description help buyers trust your listing more quickly.
                            </p>
                        </div>

                        <div class="mp-form-box mb-4">
                            <h5>Before you submit</h5>
                            <p>
                                Make sure your service image is relevant, the description is easy to understand, and the scope of work is clearly explained.
                            </p>
                        </div>

                        <div class="mp-form-box">
                            <h5>Approval process</h5>
                            <p>
                                New or edited services are sent for admin review before they appear publicly in the marketplace.
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection