@extends('backend.layouts.master')

@section('title')
    SMM Panel Providers
@endsection

@section('back-content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">SMM Panel Providers</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">SMM Panel Providers</li>
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

        <div class="alert alert-info">
            <strong>Note:</strong> Paste the API URL and API Key from each provider's own panel (usually under an
            "API" tab inside your account on their site) below, then turn it Enabled. Orders placed under that
            category are then sent to that provider automatically -- nothing else needs to change. Leave a
            provider Disabled (or its API Key blank) to keep its category hidden from users until you're ready.
        </div>

        @if($providers->isEmpty())
            <div class="alert alert-warning">
                No providers found yet. Run <code>/system-add-smm-panel/&lt;token&gt;</code> once to set this up.
            </div>
        @endif

        @foreach($providers as $provider)
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">{{ $provider->name }}
                        @if($provider->enabled)
                            <span class="badge badge-success">Enabled</span>
                        @else
                            <span class="badge badge-secondary">Disabled</span>
                        @endif
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.smm-providers.update', $provider->id) }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <label>API URL</label>
                                <input type="text" name="api_url" class="form-control" value="{{ $provider->api_url }}" placeholder="e.g. https://{{ $provider->slug }}.com/api/v2 -- copy the exact URL from this provider's own API page">
                            </div>
                            <div class="form-group col-md-4">
                                <label>API Key</label>
                                <input type="text" name="api_key" class="form-control" value="{{ $provider->api_key }}" placeholder="From this provider's API page">
                            </div>
                            <div class="form-group col-md-1">
                                <label>Status</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="enabled_{{ $provider->id }}" name="enabled" value="1" @if($provider->enabled) checked @endif>
                                    <label class="custom-control-label" for="enabled_{{ $provider->id }}">Enabled</label>
                                </div>
                            </div>
                            <div class="form-group col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-success btn-block"><i class="fas fa-save"></i> Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
