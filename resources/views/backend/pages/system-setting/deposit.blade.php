
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
                        <li class="breadcrumb-item active">All {{ $title }}</li>
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
                            <h3 class="card-title">All {{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>User</th>
                                        <th>Method</th>
                                        <th>Amount</th>
                                        <th>Phone</th>
                                        <th>Transaction ID</th>
                                        <th>Receipt</th>
                                        <th>Date Time</th>
                                        <th>Status</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datas as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ user_name($data->user_id) }}</td>
                                            
                                            <td>{{ account_name($data->account_id) }}</td>
                                            
                                            <td>{{ $data->amount }}$</td>
                                            <td>{{ $data->phone }}</td>
                                            <td>{{ $data->transaction_id }}</td>
                                            
                                            
                                            
                                           <!-- Table Data with Image -->
<td>
    <a href="javascript:;" data-toggle="modal" data-target="#imageModal">
        <img src="{{ URL::to($data->receipt) }}" width="100" alt="">
    </a>
</td>


                                                
                                                
                                                
                                                
                                               <td> {{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y g:i A')}}
                                            </td>
                                            <td>
                                                @if($data->approval == 0)
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($data->approval == 1)
                                                    <span class="badge bg-success p-2">Paid</span>
                                                @else
                                                    <span class="badge bg-danger p-2">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($data->approval == 0)
                                                    <a href="javascript:;" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit_{{ $data->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                                <a href="{{ route('admin.deposit-delete', $data->id) }}" onclick="return confirm(' You want to delete?');" class="btn btn-sm btn-danger">Delete</a>
                                            </td>
                                        </tr>
                                        
                                        
                                        
                                        
                                        
<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Receipt Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ URL::to($data->receipt) }}" class="img-fluid" alt="">
            </div>
        </div>
    </div>
</div>

                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        <div class="modal fade" id="edit_{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{ route('admin.deposit-approved', $data->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <span>Update Status</span>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group col-lg-12 col-md-12 col-12">
                                                                <label for="status">Status</label>
                                                                <select class="form-control" name="approval" id="approval">
                                                                    <option value="0" @if($data->approval == 0) selected @endif>Pending</option>
                                                                    <option value="1" @if($data->approval == 1) selected @endif>Approved</option>
                                                                    <option value="2" @if($data->approval == 2) selected @endif>Rejected</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-lg-12 col-md-12 col-12">
                                                                <label for="reason">Reason</label>
                                                                <textarea class="form-control" name="reason" id="reason" cols="30" rows="3">{{ $data->reason }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection

@section('js')
<!-- Include Bootstrap CSS and JS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>


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
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });

        function depositApproved(id) {
            if (confirm("Are you sure?")) {
                document.getElementById('deposit-approved-'+id).submit();
            }
            return false;
        }
    </script>
@endsection