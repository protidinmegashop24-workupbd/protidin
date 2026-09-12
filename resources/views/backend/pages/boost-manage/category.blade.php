@extends('backend.layouts.master')

@section('title')
    Boost Category
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
                    <h1 class="m-0 text-dark">Category Wise Service</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">All Category</li>
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
                            <h3 class="card-title">All Category</h3>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Category Name</th>
                                        <th>Service List</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $services = smm_get_services();
                                        $groupedServices = [];
                                        foreach ($services as $service) {
                                            $category = $service['category'];
                                            $groupedServices[$category][] = $service;
                                        }
                                    @endphp
                                    @foreach ($groupedServices as $category => $services)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{$category}}</td>
                                        <td>
                                            <ol>
                                                @foreach ($services as $service)
                                                <li>{{'id'.$service['service'].'-Rate'.$service['rate'].'-Min'.$service['min'].'-Max'.$service['max'].'-'.$service['name']}}</li>
                                                @endforeach
                                            </ol>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{--
    Old Script
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-7 col-7">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">All Category</h3>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Icon</th>
                                        <th>Name</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categorys as $key => $category)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <img src="{{ URL::to($category->image) }}" alt="Banner Image" style="height: 60px; width: 80px;">
                                            </td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                <a href="javascript:;" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit_{{ $key }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="edit_{{ $key }}" tabindex="-1" role="dialog"
                                            aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{ route('admin.boost-category.update', $category->id) }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Update Category <strong>{{ $category->name }}</strong></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="categoryName">Name</label>
                                                                <input type="text" class="form-control" id="categoryName" name="name" required value="{{ $category->name }}" placeholder="Category Name">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="exampleInputFile">Icon</label>
                                                                <div class="input-group">
                                                                    <div class="custom-file">
                                                                        <input type="file" class="custom-file-input upload" id="image" name="image" type="file" accept="image/*" onchange="readURL(this);">
                                                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                                    </div>
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text" id="">Upload</span>
                                                                    </div>
                                                                </div>
                                                                <img src="{{ URL::to($category->image) }}" class="mt-2" alt="Banner Image" style="height: 60px; width: 80px;">
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
                <div class="col-lg-5 col-5">
                    <form action="{{ route('admin.boost-category.store') }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                        @csrf
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">New Category</h3>
                            </div>
                            <div class="card-body">

                                <div class="form-group">
                                    <label for="CategoryName">Name</label>
                                    <input type="text" class="form-control" id="CategoryName" name="name" required placeholder="Category Name">
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Icon</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input upload" id="image" name="image" type="file" accept="image/*" onchange="readURL(this);">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="">Upload</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    --}}
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

    </script>
@endsection
