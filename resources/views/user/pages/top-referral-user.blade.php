@extends('user.layouts.master')
@section('css')
<style>
    .table th {
        background-color: rgba(250, 250, 250, 1); /* White faded background */
        color: #000066; /* Black font color */
        font-size: 12px;
    }
/* Remove vertical borders for table cells and adjust horizontal border color */
    .table-no-vertical-border th,
    .table-no-vertical-border td {
        border-left: none;  /* Remove left border */
        border-right: none; /* Remove right border */
        border-color: rgba(0, 0, 0, 0.1); /* Adjust the border color (0.1 for a very faint line) */
    }
    .table td {
        font-size: 14px;
        color: #000;
    }
</style>
@endsection
@section('user-content')
    
    <div class="card mt-2">
        <div class="card-header">
            <div class="card-title text-center" style="font-weight: 500; color: green; font-size: 22px;">Top 10 Refer</div>
        </div>
        <div class="card-body">
            <div class="notice-box mb-2">
                <marquee bgcolor="#000080" style="color:white;padding: 5px;border-radius: 5px;margin-top: 5px" behavior="scroll">
                    @foreach ($headlines as $key=>$headline)
                        <a href="{{ $headline->link }}" class="text-white" style="font-size:15px;">
                            <i class="fe fe-link me-2 white-text" aria-hidden="true"></i>{{ $headline->title }}
                        </a>
                        @if (!$loop->last)
                            <span class="mx-2">|</span>
                        @endif
                    @endforeach
                </marquee>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered text-nowrap table-no-vertical-border" id="example1">
                    <thead>
                        <tr>
                            <th scope="col border-bottom-0">RANK</th>
                            <th scope="col border-bottom-0">NAME</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rank = 1; // Initialize the rank variable
                        @endphp
                        @foreach ($top_users as $key => $data)
                            <tr>
                                <td>{{ $rank++ }}</td> <!-- Increment rank with each row -->
                                <td>{{ $data->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!--<div class="container-fluid mt-2">-->
    <!--    <div class="row justify-content-center">-->
    <!--        <div class="col-lg-8 col-md-8 col-12">-->
    <!--            <div class="alert alert-success bg-warning text-white border-0">-->
    <!--                <marquee scrollamount="6">-->
    <!--                    @foreach ($headlines as $key=>$headline)-->
    <!--                        <a href="{{ $headline->link }}" class="text-black" style="font-size:20px;"><i class="fe fe-link me-2 white-text" aria-hidden="true"></i>{{ $headline->title }}</a>-->
    <!--                    @endforeach-->
    <!--                </marquee>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <!--<div class="card mt-2">-->
    <!--    <div class="card-header">-->
    <!--        <div class="card-title">Top 10 Refer Users</div>-->
    <!--    </div>-->
    <!--    <div class="card-body">-->
    <!--        <div class="table-responsive">-->
    <!--            <table class="table table-bordered text-nowrap" id="example1">-->
    <!--                <thead>-->
    <!--                    <tr>-->
    <!--                        <th scope="col border-bottom-0">ID</th>-->
    <!--                        <th scope="col border-bottom-0">Name</th>-->
    <!--                        <th scope="col border-bottom-0">Amount</th>-->
    <!--                    </tr>-->
    <!--                </thead>-->
    <!--                <tbody>-->
    <!--                    @foreach ($top_users as $key => $data)-->
    <!--                        <tr>-->
    <!--                            <td>{{ $data->code }}</td>-->
    <!--                            <td>{{ $data->name }}</td>-->
    <!--                            <td>{{ $data->refer }}</td>-->
    <!--                        </tr>-->
    <!--                    @endforeach-->
    <!--                </tbody>-->
    <!--            </table>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
@endsection
@section('js')
<script>
    // Function to refresh the page after one month (30 days)
    function refreshPageAfterOneMonth() {
        var lastRefreshTimestamp = localStorage.getItem('lastRefreshTimestamp');
        var oneMonthInMilliseconds = 30 * 24 * 60 * 60 * 1000; // 30 days * 24 hours * 60 minutes * 60 seconds * 1000 milliseconds
        var currentTimestamp = new Date().getTime();

        if (!lastRefreshTimestamp || currentTimestamp - lastRefreshTimestamp >= oneMonthInMilliseconds) {
            // If it's been more than one month or the timestamp doesn't exist, refresh the page
            location.reload();
            localStorage.setItem('lastRefreshTimestamp', currentTimestamp);
        }
    }

    // Call the function when the page loads
    window.addEventListener('load', refreshPageAfterOneMonth);
</script>
@endsection
