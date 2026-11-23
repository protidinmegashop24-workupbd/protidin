@extends('backend.layouts.master')

@section('title')
    Image Templates
@endsection

@section('back-content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Image Templates</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Image Templates</li>
          </ol>
        </div>
      </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
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

        <p class="text-muted" style="font-size:13px;">
            These are the still-picture backgrounds used by the "Hadith Image" option in Video Maker (Arabic + translation + reference overlaid on top). A random ENABLED image here is picked for each hadith image, regardless of which video template tile the user clicked into. If none are enabled, it falls back to grabbing a frame from a background video template instead.
        </p>

        <div class="mb-3">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add_image_template_modal">
                <i class="fas fa-plus"></i> Add New Image
            </button>
        </div>

        <div class="row">
            @forelse($templates as $template)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset($template->background_path) }}" class="card-img-top" style="height:220px;object-fit:cover;" alt="{{ $template->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $template->name }}</h5>
                            <p class="mb-2">
                                @if($template->enabled)
                                    <span class="badge badge-success">Enabled</span>
                                @else
                                    <span class="badge badge-secondary">Disabled</span>
                                @endif
                            </p>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit_image_template_{{ $template->id }}">Edit</button>
                            <form action="{{ route('admin.image-templates.delete', $template->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this image? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="edit_image_template_{{ $template->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.image-templates.update', $template->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-white">Edit: {{ $template->name }}</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ $template->name }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Replace Image (optional)</label>
                                        <input type="file" class="form-control-file" name="background" accept=".jpg,.jpeg,.png,.webp">
                                        <img src="{{ asset($template->background_path) }}" style="height:80px;margin-top:6px;">
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="edit_enabled_{{ $template->id }}" name="enabled" value="1" @if($template->enabled) checked @endif>
                                            <label class="custom-control-label" for="edit_enabled_{{ $template->id }}">Enabled</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">No image templates yet. Add one above.</div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<div class="modal fade" id="add_image_template_modal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.image-templates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white">New Image Template</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" required placeholder="e.g. Mosque Silhouette">
                    </div>
                    <div class="form-group">
                        <label>Image (JPG/PNG/WEBP)</label>
                        <input type="file" class="form-control-file" name="background" accept=".jpg,.jpeg,.png,.webp" required>
                        <small class="text-muted">Ideally a vertical/portrait image (e.g. 1080x1920) -- it's cropped to fit that shape either way.</small>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="new_enabled" name="enabled" value="1" checked>
                            <label class="custom-control-label" for="new_enabled">Enabled</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Create</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
