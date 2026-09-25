@extends('backend.layouts.master')

@section('title')
    {{ $title }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
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
                        <li class="breadcrumb-item"><a href="{{ route('admin.reviews') }}">Pending</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.reviews-approved') }}">Approved</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.reviews-rejected') }}">Rejected</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.reviews-bonus-tiers') }}">Bonus Schedule</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>User</th>
                                        <th>Rating</th>
                                        <th>Comment</th>
                                        <th>Date</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($datas as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->user->name ?? 'N/A' }} ({{ $data->user->email ?? '' }})</td>
                                            <td>
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fa{{ $i <= $data->rating ? 's' : 'r' }} fa-star text-warning"></i>
                                                @endfor
                                            </td>
                                            <td>{{ $data->comment }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y g:i A') }}</td>
                                            <td>
                                                @if($data->status == 'pending')
                                                    <form action="{{ route('admin.review-approve', $data->id) }}" method="GET" style="display:inline-block;">
                                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this review? It will show on the homepage.');">Approve</button>
                                                    </form>
                                                    <form action="{{ route('admin.review-reject', $data->id) }}" method="GET" style="display:inline-block;">
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this review?');">Reject</button>
                                                    </form>
                                                @elseif($data->status == 'approved')
                                                    <span class="badge bg-success p-2">Approved</span>
                                                    <form action="{{ route('admin.review-reject', $data->id) }}" method="GET" style="display:inline-block;">
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this already-approved review? It will be removed from the homepage.');">Reject</button>
                                                    </form>
                                                @else
                                                    <span class="badge bg-danger p-2">Rejected</span>
                                                    <form action="{{ route('admin.review-approve', $data->id) }}" method="GET" style="display:inline-block;">
                                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this review after all?');">Approve</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No reviews found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection

@section('js')
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "autoWidth": false,
            });
        });
    </script>
@endsection
