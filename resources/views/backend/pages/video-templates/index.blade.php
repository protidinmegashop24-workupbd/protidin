@extends('backend.layouts.master')

@section('title')
    Video Templates
@endsection

@section('back-content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Video Templates</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Video Templates</li>
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

        <div class="mb-3">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add_template_modal">
                <i class="fas fa-plus"></i> Add New Template
            </button>
        </div>

        <div class="row">
            @forelse($templates as $template)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        @if($template->thumbnail_path)
                            <img src="{{ asset($template->thumbnail_path) }}" class="card-img-top" style="height:220px;object-fit:cover;" alt="{{ $template->name }}">
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-dark text-white" style="height:220px;">
                                <i class="fas fa-video fa-2x"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $template->name }}</h5>
                            <p class="mb-1"><span class="badge badge-secondary">{{ $template->category }}</span></p>
                            <p class="mb-1">Cost: ${{ number_format($template->cost, 4) }}</p>
                            <p class="mb-1">Duration: {{ $template->duration_seconds }}s</p>
                            <p class="mb-2">
                                @if($template->enabled)
                                    <span class="badge badge-success">Enabled</span>
                                @else
                                    <span class="badge badge-secondary">Disabled</span>
                                @endif
                            </p>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit_template_{{ $template->id }}">Edit</button>
                            <form action="{{ route('admin.video-templates.delete', $template->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this template? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="edit_template_{{ $template->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.video-templates.update', $template->id) }}" method="POST">
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
                                        <label>Category</label>
                                        <input type="text" class="form-control" name="category" value="{{ $template->category }}" required placeholder="e.g. Islamic, Nature, Quotes">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Duration (seconds)</label>
                                            <input type="number" class="form-control" name="duration_seconds" value="{{ $template->duration_seconds }}" min="3" max="60" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Cost ($)</label>
                                            <input type="number" step="0.0001" class="form-control" name="cost" value="{{ $template->cost }}" min="0" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Font Size</label>
                                            <input type="number" class="form-control" name="font_size" value="{{ $template->font_size }}" min="20" max="120" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Text Color</label>
                                            <input type="color" class="form-control" name="text_color" value="{{ $template->text_color }}" required>
                                        </div>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="enabled_{{ $template->id }}" name="enabled" value="1" @if($template->enabled) checked @endif>
                                        <label class="custom-control-label" for="enabled_{{ $template->id }}">Enabled (visible to users)</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">No video templates yet. Click "Add New Template" to upload one.</div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<div class="modal fade" id="add_template_modal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.video-templates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white"><i class="fas fa-plus"></i> New Video Template</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" required placeholder="e.g. Green Nature Loop">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" class="form-control" name="category" required placeholder="e.g. Islamic, Nature, Quotes">
                    </div>
                    <div class="form-group">
                        <label>Background Video (MP4, max 50MB)</label>
                        <input type="file" class="form-control" name="background" accept="video/mp4,video/quicktime,video/webm" required>
                        <small class="text-muted">This loops in the background behind the user's text.</small>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Duration (seconds)</label>
                            <input type="number" class="form-control" name="duration_seconds" value="10" min="3" max="60" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Cost ($)</label>
                            <input type="number" step="0.0001" class="form-control" name="cost" value="0.0500" min="0" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Font Size</label>
                            <input type="number" class="form-control" name="font_size" value="60" min="20" max="120" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Text Color</label>
                            <input type="color" class="form-control" name="text_color" value="#ffffff" required>
                        </div>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="enabled_new" name="enabled" value="1" checked>
                        <label class="custom-control-label" for="enabled_new">Enabled (visible to users)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Upload Template</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
