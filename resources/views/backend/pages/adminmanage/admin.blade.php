@extends('backend.layouts.master')

@section('title')
    Admin Accounts
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .new-user {
            float: right;
        }
        svg {
            width: 25px;
        }
    </style>
@endsection

@section('back-content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Admin Accounts</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Admin Accounts</li>
          </ol>
        </div>
      </div>
    </div>
</div>


<!-- Modal for adding new account -->
<div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog" aria-labelledby="addAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAccountModalLabel">Create New Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.account.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role_id" required>
                            <option value="">Select Role</option>
                            <option value="1">Admin</option>
                            <option value="2">Sub Admin</option>
                           
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">All Admin Accounts</h3>
            </div>
            <div class="card-body overflow-x">
                <input type="text" class="form-control mb-2" id="admin-filter" placeholder="ID/Email/Password/Role"/>
                <table id="admin-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                             <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $admin)
                            <tr>
                                <td>{{ $admin->id }}</td>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>
                                    @if($admin->role_id == 1)
                                        <span class="badge bg-primary">Main Admin</span>
                                    @elseif($admin->role_id == 2)
                                        <span class="badge bg-secondary">Sub Admin</span>
                                    @elseif($admin->role_id == 3)
                                        <span class="badge bg-warning">User</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="javascript:;" class="btn btn-info btn-sm" data-toggle="modal" data-target="#editAdminModal{{ $admin->id }}">
                                        Edit
                                    </a>
                                    @if ($admin->role_id != 1)
                                        <a href="javascript:;" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteAdminModal{{ $admin->id }}">
                                            Delete
                                        </a>
                                    @endif
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editAdminModal{{ $admin->id }}" tabindex="-1" role="dialog" aria-labelledby="editAdminModalLabel{{ $admin->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editAdminModalLabel{{ $admin->id }}">Edit Admin Account</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.account.update', $admin->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{ $admin->name }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" value="{{ $admin->email }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter New Password (Optional)">
                                                </div>
                                                <div class="form-group">
                                                    <label for="role">Role</label>
                                                    <select class="form-control" id="role" name="role_id" required>
                                                        <option value="1" {{ $admin->role_id == 1 ? 'selected' : '' }}>Admin</option>
                                                        <option value="2" {{ $admin->role_id == 2 ? 'selected' : '' }}>Sub Admin</option>
                                                        <option value="3" {{ $admin->role_id == 3 ? 'selected' : '' }}>User</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Update Account</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteAdminModal{{ $admin->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteAdminModalLabel{{ $admin->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteAdminModalLabel{{ $admin->id }}">Delete Admin</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.account.delete', $admin->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                Are you sure you want to delete this admin?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </tbody>
                </table>
                
<div class="mb-3 text-center">
    <button class="btn btn-primary new-user btn-block" data-toggle="modal" data-target="#addAccountModal">Add New Admin Account</button>
</div>

            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {
            $("#admin-table").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#admin-table_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
