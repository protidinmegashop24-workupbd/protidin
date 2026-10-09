@extends('backend.layouts.master')

@section('title')
    SMTP Configure
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    {{-- select2 --}}
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@endsection

@section('back-content')
  <section class="content mt-2">
    <div class="container-fluid">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">SMTP Configure</h3>
            </div>
            
            <div class="card-body">
                <form class="row" action="{{ route('admin.mail-configure.update',$website->id) }}" method="POST" enctype="multipart/form-data" >
                    @csrf
                    <div class="form-group col-lg-6 col-md-6 col-12">
                        <label for="mail_transport">Mail Transport</label>
                        <input type="text" class="form-control" id="mail_transport" name="mail_transport" value="{{ $website->mail_transport }}" required placeholder="Mail Transport">
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-12">
                        <label for="mail_host">Mail Host</label>
                        <input name="mail_host" type="text" class="form-control" id="mail_host" placeholder="Mail Host" required value="{{$website->mail_host}}">
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-12">
                        <label for="mail_port">Mail Port</label>
                        <input name="mail_port" type="text" class="form-control" id="mail_port" placeholder="Mail Port" required  value="{{$website->mail_port}}">
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-12">
                        <label for="mail_encryption">Mail Encryption</label>
                        <input name="mail_encryption" type="text" class="form-control" id="mail_encryption" placeholder="Mail Encryption" required value="{{$website->mail_encryption}}">
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="mail_user_name">Mail Username</label>
                        <input name="mail_user_name" type="text" class="form-control" id="mail_user_name" placeholder="Mail Username" required value="{{$website->mail_user_name}}" >
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="pail_password">Mail Password</label>
                        <input name="pail_password" type="text" class="form-control" id="pail_password" placeholder="Mail Password" required value="{{$website->pail_password}}" >
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="mail_from">Mail From</label>
                        <input name="mail_from" type="text" class="form-control" id="mail_from" placeholder="Mail From" required value="{{$website->mail_from}}" >
                    </div>

                    <div class="form-group col-12">
                        <button type="submit" class="btn btn-success m-t-15 waves-effect">UPDATE</button>
                    </div>
                </form>
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
    <!-- Select2 -->
    <script src="{{ asset('backend/plugins/select2/js/select2.full.min.js') }}"></script>

    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <script>
        $(function () {
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
