@extends('backend.layouts.master')

@section('title')
    Survey Provider Conversions - Dashboard
@endsection

@section('back-content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Survey Provider Conversions</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.survey-providers') }}">Survey Providers</a></li>
            <li class="breadcrumb-item active">Conversions</li>
          </ol>
        </div>
      </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Approved</span>
                        <span class="info-box-number">${{ number_format($totals['approved'], 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="fas fa-undo"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Reversed</span>
                        <span class="info-box-number">${{ number_format($totals['reversed'], 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-list"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Conversions</span>
                        <span class="info-box-number">{{ $totals['count'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">All Conversions</h3>
            </div>
            <div class="card-body">
                <form method="GET" class="form-row mb-3">
                    <div class="form-group col-md-3">
                        <input type="text" name="user_id" class="form-control" placeholder="User ID" value="{{ request('user_id') }}">
                    </div>
                    <div class="form-group col-md-3">
                        <input type="text" name="trans_id" class="form-control" placeholder="Transaction ID" value="{{ request('trans_id') }}">
                    </div>
                    <div class="form-group col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="approved" @if(request('status')=='approved') selected @endif>Approved</option>
                            <option value="reversed" @if(request('status')=='reversed') selected @endif>Reversed</option>
                            <option value="pending" @if(request('status')=='pending') selected @endif>Pending</option>
                            <option value="rejected" @if(request('status')=='rejected') selected @endif>Rejected</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                    </div>
                </form>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Provider</th>
                            <th>Transaction ID</th>
                            <th>Amount (USD)</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conversions as $c)
                            <tr>
                                <td>{{ $c->id }}</td>
                                <td>{{ $c->user->name ?? ('#'.$c->user_id) }}</td>
                                <td>{{ $c->provider_slug }}</td>
                                <td>{{ $c->trans_id }}</td>
                                <td>${{ number_format($c->amount_usd, 4) }}</td>
                                <td>
                                    @if($c->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($c->status == 'reversed')
                                        <span class="badge badge-danger">Reversed</span>
                                    @else
                                        <span class="badge badge-warning">{{ ucfirst($c->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $c->created_at->format('d/m/Y g:i A') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No conversions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $conversions->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
