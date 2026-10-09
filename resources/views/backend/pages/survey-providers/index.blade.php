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
                            <div class="form-group col-md-3">
                                <label>Display Name (shown on the Surveys page card)</label>
                                <input type="text" name="name" class="form-control" value="{{ $provider->name }}" placeholder="e.g. Bonus Survey">
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
                    </form>
                    @if($provider->slug == 'cpx-research')
                        <hr>
                        <p class="mb-1"><strong>Postback URL to set in your CPX Research dashboard (Postback Settings tab):</strong></p>
                        <code>{{ url('/postback/survey-provider/cpx-research') }}?status={status}&trans_id={trans_id}&user_id={user_id}&sub_id={subid}&sub_id_2={subid_2}&amount_local={amount_local}&amount_usd={amount_usd}&offer_id={offer_ID}&hash={secure_hash}&ip_click={ip_click}</code>
                        <p class="text-muted mt-2 mb-0" style="font-size:12px;">
                            Verified end-to-end via CPX Research's own "Test your Postback URL" tool -- the hash formula, parameter names, and widget URL all matched.
                        </p>
                        <p class="text-muted mt-2 mb-0" style="font-size:12px;">
                            Note: "Display Name" only changes the card title on YOUR site. Once a user clicks Start, they leave your site and land on CPX Research's own website (offers.cpx-research.com), which still shows CPX's own branding -- that part cannot be changed from here.
                        </p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
