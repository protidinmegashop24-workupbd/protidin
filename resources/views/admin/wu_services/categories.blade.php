@extends('backend.layouts.master')

@section('title','Marketplace Categories')
@section('back-content')

<div class="container-fluid mt-3">

    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Add Category</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.wu-marketplace-categories-store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Category Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter category name" required>
                        </div>

                        <button type="submit" class="btn btn-success">Add Category</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">All Categories</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th width="220">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $cat)
                                    <tr>
                                        <td>{{ $cat->id }}</td>
                                        <td>{{ $cat->name }}</td>
                                        <td>{{ $cat->slug }}</td>
                                        <td>
                                            <form action="{{ route('admin.wu-marketplace-categories-update', $cat->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                <div class="input-group">
                                                    <input type="text" name="name" value="{{ $cat->name }}" class="form-control form-control-sm" required>
                                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                                </div>
                                            </form>

                                            <a href="{{ route('admin.wu-marketplace-categories-delete', $cat->id) }}"
                                               class="btn btn-danger btn-sm mt-1"
                                               onclick="return confirm('Delete this category?')">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No categories found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection