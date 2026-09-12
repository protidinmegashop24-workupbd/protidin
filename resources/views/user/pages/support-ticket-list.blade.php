@extends('user.layouts.master')

@section('css')
    <!-- কাস্টম CSS -->
    <style>
        .card-header {
            background-color: #343a40;
            color: #fff;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .status-badge {
            padding: 0.5em 0.75em;
            border-radius: 0.25rem;
            font-weight: bold;
            color: #fff;
        }
        .status-closed {
            background-color: #dc3545;
        }
        .status-pending {
            background-color: #ffc107;
            color: #212529;
        }
        .status-answered {
            background-color: #28a745;
        }
        .btn-action {
            margin: 0 2px;
        }
    </style>
@endsection

@section('user-content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="card-title">সাপোর্ট টিকিটসমূহ</div>
            </div>
            <div class="card-body">
                @if($datas->isEmpty())
                    <p class="text-center">কোনো সাপোর্ট টিকিট পাওয়া যায়নি।</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-nowrap">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" width="25%">বিষয়</th>
                                    <th scope="col" width="15%">স্ট্যাটাস</th>
                                    <th scope="col" width="15%">প্রায়োরিটি</th>
                                    <!--<th scope="col">শেষ উত্তর</th>-->
                                    <th scope="col" width="20%">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $key => $data)
                                    <tr>
                                        <td>{{ $data->subject }}</td>
                                        <td>
                                            @if($data->status == 2)
                                                <span class="status-badge status-closed">বন্ধ</span>
                                            @elseif($data->status == 0 && $data->answered == 0)
                                                <span class="status-badge status-pending">অপেক্ষমাণ</span>
                                            @elseif($data->status == 0 && $data->answered == 1)
                                                <span class="status-badge status-answered">উত্তরপ্রাপ্ত</span>
                                            @endif
                                        </td>
                                        <td>{{ ucfirst($data->priority) }}</td>
                                        <!--<td></td>-->
                                        <td>
                                            @if($data->status != 2)
                                                <a href="{{ route('user.support-ticket-close', $data->id) }}" class="btn btn-danger btn-sm btn-action">
                                                    <i class="fa fa-times"></i> বন্ধ করুন
                                                </a>
                                            @endif
                                            <a href="{{ route('user.support-ticket-show', $data->id) }}" class="btn btn-primary btn-sm btn-action">
                                                <i class="fa fa-eye"></i> বিস্তারিত
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- পেজিনেশন যদি থাকে -->
                        {{-- $datas->links() --}}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- কাস্টম JS যদি থাকে -->
@endsection
