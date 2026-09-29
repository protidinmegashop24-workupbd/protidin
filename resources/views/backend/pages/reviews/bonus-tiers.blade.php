@extends('backend.layouts.master')

@section('title')
    {{ $title }}
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
                        <li class="breadcrumb-item"><a href="{{ route('admin.reviews') }}">Reviews</a></li>
                        <li class="breadcrumb-item active">Bonus Schedule</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Daily Login Bonus -- Day 1 to Day 15 Amounts ($)</h3>
                        </div>
                        <form action="{{ route('admin.reviews-bonus-tiers.update') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <p class="text-muted">
                                    A user's daily login bonus starts the day their review is approved. Day 1's amount is
                                    paid that day, day 2's the next day they log in, and so on. After the last configured
                                    day, that same amount keeps being paid every day after.
                                </p>
                                <div class="row">
                                    @foreach($tiers as $tier)
                                        <div class="form-group col-lg-3 col-md-4 col-6">
                                            <label>Day {{ $tier->day_number }}</label>
                                            <input type="number" step="0.0001" min="0" class="form-control"
                                                   name="amounts[{{ $tier->id }}]" value="{{ $tier->amount }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save Schedule</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </section>
@endsection
