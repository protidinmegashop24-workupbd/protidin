@extends('backend.layouts.master')

@section('title')
    Boost Service
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    {{-- select2 --}}
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('back-content')
<?php
        // echo "<pre>";
        // print_r(smm_get_services());
        // echo "</pre>";
?>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Service</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">All Service</li>
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
                            <h3 class="card-title">All Service</h3>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">Id</th>
                                        <th>Name</th>
                                        <!--<th>Icon</th>-->
                                        <th>Category</th>
                                        <th>Cost</th>
                                        <th>Min</th>
                                        <th>Max</th>
                                        <!--<th width="10%">Action</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $apiServices = smm_get_services(); @endphp
                                    @foreach ($apiServices as $services) 
                                    <tr>
                                        <td>{{ $services['service'] }}</td>
                                        <td>{{ $services['name'] }}</td>
                                        <td>{{ $services['category'] }}</td>
                                        <td>{{ $services['rate'] }}</td>
                                        <td>{{ $services['min'] }}</td>
                                        <td>{{ $services['max'] }}</td>
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
    <!--Old Script-->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-7 col-7">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">All Service</h3>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Title</th>
                                        <th>Icon</th>
                                        <th>Category</th>
                                        <th>Cost</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sub_categorys as $key => $s_category)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <img src="{{ URL::to($s_category->image) }}" alt="Icon" style="height: 60px; width: 80px;">
                                            </td>
                                            <td>{{ $s_category->name }}</td>
                                            <td>{{ main_boost_category($s_category->category_id) }}</td>
                                            <td>${{ $s_category->cost }} /1000 Work</td>
                                            <td>
                                                <a href="javascript:;" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit_{{ $key }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="edit_{{ $key }}" tabindex="-1" role="dialog"
                                            aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{ route('admin.boost-sub-category.update', $s_category->id) }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Update Category <strong>{{ $s_category->name }}</strong></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="CategoryName">Main Category</label>
                                                                <select class="form-control select2" name="category_id" required style="width: 100%;">
                                                                    <option value="">Select One</option>
                                                                    @foreach ($categorys as $cat)
                                                                        <option value="{{ $cat->id }}" @if($s_category->category_id == $cat->id) selected @endif>{{ $cat->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="categoryName">Title</label>
                                                                <input type="text" class="form-control" id="categoryName" name="name" required value="{{ $s_category->name }}" placeholder="Title">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="cost">Charge Per 1000 Work</label>
                                                                <input type="text" class="form-control" id="cost" name="cost" required value="{{ $s_category->cost }}">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="notice">Notice</label>
                                                                <textarea class="form-control" cols="10" rows="3" name="notice" placeholder="Notice.">{{ $s_category->notice }}</textarea>
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
                                                                <img src="{{ URL::to($s_category->image) }}" class="mt-2" alt="Icon" style="height: 60px; width: 80px;">
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
                    <form action="{{ route('admin.boost-sub-category.store') }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                        @csrf
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">New Service</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="CategoryName">Category</label>
                                    <select class="form-control select2" name="category_id" required style="width: 100%;">
                                        <option selected="selected" value="">Select One</option>
                                        @foreach ($categorys as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="CategoryName">Title</label>
                                    <input type="text" class="form-control" id="CategoryName" name="name" required placeholder="Title">
                                </div>

                                <div class="form-group">
                                    <label for="cost">Charge Per 1000 Work</label>
                                    <input type="text" class="form-control" id="cost" name="cost" required value="0">
                                </div>

                                <div class="form-group">
                                    <label for="notice">Notice</label>
                                    <textarea class="form-control" cols="10" rows="3" name="notice" placeholder="Notice."></textarea>
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
    <!-- Select2 -->
    <script src="{{ asset('backend/plugins/select2/js/select2.full.min.js') }}"></script>

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

        $(function () {
            $('.select2').select2();
        });

    </script>
@endsection
