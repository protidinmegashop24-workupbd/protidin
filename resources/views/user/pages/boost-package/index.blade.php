@extends('user.layouts.master')

@section('css')
    <style>
        /* Page Background and Card Styling */
        body {
            background-color: #f0f2f5;
            color: black !important;
        }
table tbody td {
    color: #000000 !important; /* Text color set to black */
}

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead th {
            background-color: #007bff;
            color: black;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
            padding: 15px;
        }

        table tbody td {
            background-color: white;
            text-align: center;
            vertical-align: middle;
            padding: 10px;
        }

        table tbody tr:nth-child(odd) {
            background-color: #f8f9fa;
        }

        /* Badge Customization */
        .badge {
            font-size: 14px;
            padding: 6px 12px;
            border-radius: 50px;
        }

        .badge.bg-success {
            background-color: #28a745;
            color: white;
        }

        .badge.bg-warning {
            background-color: #ffc107;
            color: white;
        }

        .badge.bg-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge.bg-primary {
            background-color: #007bff;
            color: white;
        }

        .badge.bg-secondary {
            background-color: #6c757d;
            color: white;
        }

        /* Button Design */
        .order-service-btn {
            background-color: #28a745;
            color: white;
            font-weight: bold;
            border-radius: 30px;
            padding: 10px 20px;
            margin-top: 10px;
            font-size: 16px;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.4);
            transition: all 0.3s ease;
        }

        .order-service-btn:hover {
            background-color: #218838;
            color: white;
            transform: translateY(-2px);
        }

        /* Table Responsive Design */
        @media (max-width: 768px) {
            table thead th, table tbody td {
                font-size: 12px;
                padding: 8px;
            }

            .order-service-btn {
                font-size: 14px;
                padding: 8px 16px;
            }
        }

        /* Marquee and Alert Styling */
        .alert {
            background-color: #ffc107;
            color: #343a40;
            font-weight: bold;
        }

        .alert a {
            color: #343a40;
            text-decoration: none;
        }

        .alert a:hover {
            text-decoration: underline;
        }
    </style>
@endsection

@section('user-content')
    <div class="container-fluid mt-3">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12 col-12">
                <div class="alert alert-success bg-warning text-dark border-0">
                    <marquee scrollamount="6">
                        @foreach ($headlines as $headline)
                            <a href="{{ $headline->link }}" class="text-dark" style="font-size:20px;">
                                <i class="fe fe-link me-2" aria-hidden="true"></i>{{ $headline->title }}
                            </a>
                        @endforeach
                    </marquee>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="card-title h4">Service History</div>
            <!-- Order New Service Button -->
            <a href="/user/boost-create" class="order-service-btn">Order New Service</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-nowrap" id="example1">
                    <thead>
                        <tr>
                            <th>OR ID</th>
                            <th>SRV ID</th>
                            <th>Date Time</th>
                            <th>Charge</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Reason</th>
                            <th>Name</th>
                            <th>Link</th>
                            <th>Category</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datas as $data)
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>{{ $data->service_id }}</td>
                                <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y g:i A')}}</td>
                                <td>{{ $data->order_charge }}$</td>
                                <td>{{ $data->order_qty }}</td>
                                <td>
                                    @if ($data->status == '1')
                                        <span class="badge bg-success">Process</span>
                                    @elseif ($data->status == '2')
                                        <span class="badge bg-warning">Inprocess</span>
                                    @elseif ($data->status == '3')
                                        <span class="badge bg-danger">Reject</span>
                                    @elseif ($data->status == '4')
                                        <span class="badge bg-primary">Complete</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>@if($data->status == '3'){{$data->reason}}@endif</td>
                                <td>{{ $data->name }}</td>
                                <td><a href="{{ $data->link }}" target="_blank">{{ $data->link }}</a></td>
                                <td>{{ $data->category }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $datas->links() }}
            </div>
        </div>
        
        {{--
        <!--Old Script Start -->
                <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-nowrap" id="example1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date Time</th>
                            <th>Link</th>
                            <th>Charge</th>
                            <th>Quantity</th>
                            <th>Service</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datas as $data)
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y g:i A')}}</td>
                                <td><a href="{{ $data->link }}" target="_blank">{{ $data->link }}</a></td>
                                <td>{{ $data->cost }}$</td>
                                <td>{{ $data->work_need }}</td>
                                <td>{{ sub_boost_category($data->sub_category) }}</td>
                                <td>{{ $data->reason }}</td>
                                <td>
                                    @if ($data->status == 1)
                                        <span class="badge bg-success">Process</span>
                                    @elseif ($data->status == 2)
                                        <span class="badge bg-warning">Inprocess</span>
                                    @elseif ($data->status == 3)
                                        <span class="badge bg-danger">Reject</span>
                                    @elseif ($data->status == 4)
                                        <span class="badge bg-primary">Complete</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($data->status == 0)
                                        <a href="{{ route('user.boost-edit', $data->id) }}" class="btn btn-sm btn-success">Edit</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $datas->links() }}
            </div>
        </div>
        <!--Old Script End -->
        --}}
    </div>
@endsection

@section('js')
    <script>
        function updateJobWorker(id){
            $('#worker_need_'+id).modal('show');
        }

        function updateJobWorkerModalClose(id){
            $('#worker_need_'+id).modal('hide');
        }
    </script>
@endsection
