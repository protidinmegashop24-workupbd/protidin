@extends('user.layouts.master')
@section('css')

@endsection
@section('user-content')
    <div class="card mt-2">
        <div class="card-header">
            <div class="card-title">Service Purchase List</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-nowrap" id="example1">
                    <thead>
                        <tr>
                            <th scope="col border-bottom-0">ID</th>
                            <th scope="col border-bottom-0">Title</th>
                            <th scope="col border-bottom-0">Amount</th>
                            <th scope="col border-bottom-0">Date</th>
                            <th scope="col border-bottom-0">Reason/Remark</th>
                            <th scope="col border-bottom-0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datas as $key => $data)
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>{{ find_service_item($data->service_item_id)->title }}</td>
                                <td>{{ find_service_item($data->service_item_id)->price }} $</td>
                                <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y g:i A')}}</td>
                                <td>{{ $data->reason }}</td>
                                <td>
                                    @if($data->status == 0)
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($data->status == 1)
                                        <span class="badge bg-success p-2">Paid</span>
                                    @else
                                        <span class="badge bg-danger p-2">Rejected</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
@endsection
