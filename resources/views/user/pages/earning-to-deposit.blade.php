@extends('user.layouts.master')

@section('content')
    <div class="container">
        <h1>Earning to Deposit</h1>
        <!-- Your other content here -->

        <form method="POST" action="{{ route('user.earning-to-deposit') }}">
            @csrf
            <div class="mb-3">
                <label for="amount" class="form-label"><b>Amount to Transfer:</b></label>
                <input name="amount" type="number" id="amount" class="form-control" placeholder="Enter amount to transfer" required>
            </div>

            <button type="submit" class="btn btn-primary">Transfer</button>
        </form>
    </div>
@endsection
