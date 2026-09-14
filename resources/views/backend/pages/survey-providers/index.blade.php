@extends('backend.layouts.master')

@section('title')
    Survey Providers - Dashboard
@endsection

@section('back-content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Survey Providers</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Survey Providers</li>
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

        <div class="mb-3">
            <a href="{{ route('admin.survey-providers.conversions') }}" class="btn btn-outline-primary btn-sm">View Conversions Log</a>
        </div>

        @if($providers->isEmpty())
            <div class="alert alert-warning">
                No providers found yet. Run <code>/system-add-survey-providers/&lt;token&gt;</code> once to set this up (it also seeds a disabled "CPX Research" row).
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
                    <form action="{{ route('admin.survey-providers.update', $provider->id) }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>App ID / Publisher ID</label>
                                <input type="text" name="app_id" class="form-control" value="{{ $provider->app_id }}" placeholder="From your {{ $provider->name }} publisher dashboard">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Secret Key</label>
                                <input type="text" name="secret_key" class="form-control" value="{{ $provider->secret_key }}" placeholder="Postback / Security secret from the dashboard">
                            </div>
                            <div class="form-group col-md-2">
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
                    @if($provider->slug == 'cpx-research')
                        <hr>
                        <p class="mb-1"><strong>Postback URL to set in your CPX Research dashboard (Postback Settings tab):</strong></p>
                        <code>{{ url('/postback/survey-provider/cpx-research') }}?status={status}&trans_id={trans_id}&user_id={user_id}&amount_usd={amount_usd}&hash={hash}</code>
                        <p class="text-muted mt-2 mb-0" style="font-size:12px;">
                            The exact macro names (status/trans_id/user_id/amount_usd/hash) and the secure_hash formula should be double-checked against your own CPX Research dashboard documentation before going live -- this was set up from CPX Research's publicly documented integration pattern, not a copy of their private dashboard.
                        </p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
