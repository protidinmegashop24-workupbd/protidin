@extends('backend.layouts.master')

@section('title')
    Offer Wall Providers - Dashboard
@endsection

@section('back-content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Offer Wall Providers</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Offer Wall Providers</li>
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
            <strong>Note:</strong> Unlike CPX Research (Surveys), none of these 3 providers' widget URLs or
            postback field names are confirmed yet -- their docs sites could not be reached. For each one:
            fill in App ID / Secret Key from your publisher account, paste the exact offerwall entry link from
            their dashboard into "Widget URL Template" (replace the user id part with the literal text
            <code>{user_id}</code>), then send their Postback Settings/API docs page so the postback can be
            wired up for real crediting. Until then, postbacks are only logged (see Conversions Log, status
            "unverified") -- no wallet is credited automatically.
        </div>

        <div class="mb-3">
            <a href="{{ route('admin.offer-wall-providers.conversions') }}" class="btn btn-outline-primary btn-sm">View Conversions Log</a>
        </div>

        @if($providers->isEmpty())
            <div class="alert alert-warning">
                No providers found yet. Run <code>/system-add-offerwall-providers/&lt;token&gt;</code> once to set this up (it also seeds disabled BitLabs / Lootably / AdGate Media rows).
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
                    <form action="{{ route('admin.offer-wall-providers.update', $provider->id) }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Display Name (shown on the find-job page card)</label>
                                <input type="text" name="name" class="form-control" value="{{ $provider->name }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label>App ID / Publisher ID</label>
                                <input type="text" name="app_id" class="form-control" value="{{ $provider->app_id }}" placeholder="From your {{ $provider->name }} publisher dashboard">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Secret Key</label>
                                <input type="text" name="secret_key" class="form-control" value="{{ $provider->secret_key }}" placeholder="Postback / Security secret from the dashboard">
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
                        <div class="form-group">
                            <label>Widget URL Template (paste the offerwall entry link from their dashboard, replacing your user id with the literal text <code>{user_id}</code>)</label>
                            <input type="text" name="widget_url_template" class="form-control" value="{{ $provider->widget_url_template }}" placeholder="e.g. https://example.com/offerwall?pub_id=YOUR_ID&user_id={user_id}">
                        </div>
                    </form>

                    <hr>
                    <p class="mb-1"><strong>Postback URL to set in your {{ $provider->name }} dashboard (once you find that setting):</strong></p>
                    <code>{{ url('/postback/offer-wall/' . $provider->slug) }}</code>
                    <p class="text-muted mt-2 mb-0" style="font-size:12px;">
                        This endpoint currently only logs whatever the provider sends (see Conversions Log,
                        status "unverified") -- it does not credit any user yet. Send a screenshot of a real
                        test postback's result plus this provider's Postback Settings/docs page so real
                        crediting can be wired up without guessing field names.
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
