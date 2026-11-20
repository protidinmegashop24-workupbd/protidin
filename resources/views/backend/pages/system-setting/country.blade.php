@extends('backend.layouts.master')

@section('title')
    Country
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    {{-- select2 --}}
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            color: #0a0a0a !important;
        }
    </style>
@endsection

@section('back-content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Country</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">All Country</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-7 col-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">All Country</h3>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Name</th>
                                        <th>Continent</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($countries as $key => $country)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $country->name }}</td>
                                            <td>
                                                @foreach(country_continent($country->id) as $c_key=>$country_continent)
                                                    {{ continent($country_continent->continent_id) }}@if(country_continent($country->id)->count() > ($c_key+1)),@endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <a href="javascript:;" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit_{{ $key }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="edit_{{ $key }}" tabindex="-1" role="dialog"
                                            aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{ route('admin.country.update', $country->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Update Countru <strong>{{ $country->name }}</strong></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body row">
                                                            <div class="form-group col-lg-12 col-md-12 col-12">
                                                                <label for="continent_id">Continent</label>
                                                                <select class="form-control select2" name="continent_id[]" multiple required style="width: 100%;">
                                                                    @foreach ($continents as $continent)
                                                                        <option value="{{ $continent->id }}" @foreach(country_continent($country->id) as $country_continent) @if($country_continent->continent_id == $continent->id) selected @endif @endforeach>{{ $continent->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-lg-12 col-md-12 col-12">
                                                                <label for="countryName">Name</label>
                                                                <input type="text" class="form-control" id="countryName" name="name" required value="{{ $country->name }}" placeholder="country Name">
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
                <div class="col-lg-5 col-12">
                    <form action="{{ route('admin.country.store') }}" method="POST">
                        @csrf
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">New country</h3>
                            </div>
                            <div class="card-body row">
                                <div class="form-group col-lg-12 col-md-12 col-12">
                                    <label for="continent_id">Continent</label>
                                    <select class="form-control select2" name="continent_id[]" multiple required style="width: 100%;">
                                        @foreach ($continents as $continent)
                                            <option value="{{ $continent->id }}">{{ $continent->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-12 col-md-12 col-12">
                                    <label for="countryName">Name</label>
                                    <input type="text" class="form-control" id="countryName" name="name" required placeholder="country Name">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </section>
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
