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
        background: linear-gradient(135deg, #eef3ff 0%, #f8fbff 45%, #ffffff 100%);
        padding: 34px 34px 26px;
        border-bottom: 1px solid #e8eef5;
    }

    .mp-form-badge{
        display: inline-block;
        background: #edf4ff;
        color: #1d4ed8;
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

    .mp-image-preview{
        width: 100%;
        max-width: 220px;
        height: 180px;
        object-fit: cover;
        border-radius: 14px;
        border: 1px solid #e4edf5;
        box-shadow: 0 10px 20px rgba(15,23,42,.06);
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
        background: #1d4ed8;
        color: #fff;
        border: 0;
        border-radius: 12px;
        padding: 13px 24px;
        font-weight: 800;
        box-shadow: 0 10px 24px rgba(29, 78, 216, .18);
    }

    .mp-submit-btn:hover{
        background: #1741b8;
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
            <span class="mp-form-badge">Update Listing</span>
            <div class="mp-form-title">Edit your service professionally</div>
            <p class="mp-form-subtitle">
                Improve your service details, update your image, and make your offer stronger before it goes back for review.
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

            <form action="{{ route('user.marketplace.update', $service->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-lg-8">
                        <div class="mb-4">
                            <span class="mp-form-badge">{{ $service->type == 'digital_product' ? 'Digital Product' : 'Service' }}</span>
                        </div>

                        <div class="mb-4">
                            <label class="mp-label">{{ $service->type == 'digital_product' ? 'Product Title' : 'Service Title' }}</label>
                            <input type="text" name="title" class="form-control mp-input" value="{{ old('title', $service->title) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="mp-label">Category</label>
                                <select name="category" class="form-control mp-input" required>
    <option value="">Select Category</option>
    @foreach($categories as $cat)
        <option value="{{ $cat->name }}" {{ old('category', $service->category) == $cat->name ? 'selected' : '' }}>
            {{ $cat->name }}
        </option>
    @endforeach
</select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="mp-label">Price ($)</label>
                                <input type="number" step="0.01" name="price" class="form-control mp-input" value="{{ old('price', $service->price) }}" required>
                            </div>
                        </div>

                        @if($service->type == 'digital_product')
                            <div class="mb-4">
                                <label class="mp-label">Replace Product File</label>
                                <input type="file" name="product_file" class="form-control mp-file">
                                <div class="mp-help">Leave empty to keep the currently uploaded file. Uploading a new file replaces it.</div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="mp-label">Delivery Days</label>
                                    <input type="number" name="delivery_days" class="form-control mp-input" value="{{ old('delivery_days', $service->delivery_days) }}" required>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="mp-label">Revision Limit</label>
                                    <input type="number" name="revision_limit" class="form-control mp-input" value="{{ old('revision_limit', $service->revision_limit) }}">
                                </div>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="mp-label">Short Description</label>
                            <textarea name="short_description" class="form-control mp-textarea" rows="3">{{ old('short_description', $service->short_description) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="mp-label">Full Description</label>
                            <textarea name="description" class="form-control mp-textarea" rows="8" required>{{ old('description', $service->description) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="mp-label">Change Thumbnail / Cover Image</label>
                            <input type="file" name="image" class="form-control mp-file">
                            <div class="mp-help">Uploading a new image will replace the current preview image.</div>
                        </div>

                        <button type="submit" class="btn mp-submit-btn">Update Listing</button>
                    </div>

                    <div class="col-lg-4">
                        <div class="mb-4">
                            <label class="mp-label">Current Image</label>
                            <img src="{{ wu_service_image($service->image) }}" alt="{{ $service->title }}" class="mp-image-preview">
                        </div>

                        <div class="mp-form-box mb-4">
                            <h5>Important note</h5>
                            <p>
                                When a service is updated, it can be sent again for review so the marketplace keeps listings clear and organized.
                            </p>
                        </div>

                        <div class="mp-form-box">
                            <h5>Improve buyer confidence</h5>
                            <p>
                                A cleaner title, better image, and more helpful description can improve how buyers understand and trust your service.
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection