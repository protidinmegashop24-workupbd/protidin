@extends('backend.layouts.master')

@section('title')
    {{ $title }} - Dashboard
@endsection

@section('back-content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">{{ $title }}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.lottery') }}">Lucky Draw</a></li>
            <li class="breadcrumb-item active">Tickets</li>
          </ol>
        </div>
      </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    {{ $lottery->title }}
                    @if($lottery->drawn_at)
                        <span class="badge badge-success">Drawn: {{ \Illuminate\Support\Carbon::parse($lottery->drawn_at)->format('d M Y, h:i A') }}</span>
                    @else
                        <span class="badge badge-warning">Not drawn yet</span>
                    @endif
                </h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ticket Code</th>
                            <th>User</th>
                            <th>Bought At</th>
                            <th>Prize Rank</th>
                            <th>Prize Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $i => $t)
                            <tr class="@if($t->is_winner) table-success @endif">
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $t->code }}</td>
                                <td>{{ $t->user->name ?? ('User #' . $t->user_id) }} ({{ $t->user->email ?? '' }})</td>
                                <td>{{ $t->created_at }}</td>
                                <td>
                                    @if($t->prize_rank == 1) 🥇 1st
                                    @elseif($t->prize_rank == 2) 🥈 2nd
                                    @elseif($t->prize_rank == 3) 🥉 3rd
                                    @elseif($t->prize_rank == 4) 🎟️ Top Buyer
                                    @else -
                                    @endif
                                </td>
                                <td>@if($t->prize_amount) ${{ number_format($t->prize_amount, 4) }} @else - @endif</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">No tickets bought yet for this round.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
