@extends('backend.layouts.master')

@section('title')
    Kyc Requested
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    {{-- select2 --}}
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .new-user{
            float: right;
        }
        svg {
            width: 25px;
        }
    </style>
@endsection

@section('back-content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Requested Users</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">KYC Requested List</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
  <section class="content">
    <div class="container-fluid">
        <div class="card card-success">
            <div class="card-header">
              <div class="row">
                  <div class="col-10"><h3 class="card-title">KYC Requested Users</h3></div>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body overflow-x">
                <input type="text" class="form-control mb-2" id="filter" placeholder="Name/Phone/Email"/>
              <table id="user-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="6%">ID</th>
                        <th width="7%">Photo</th>
                        <th>KYC Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Birth Day <br>YYYY-MM-DD</th>
                        <th>Type</th>
                        <th>Card No</th>
                        <th>Front</th>
                        <th>Back</th>
                        <th>View Full</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key=>$user)
                        <tr>
                            <td>{{ $user->code }}</td>
                            <td>
                                <img style="width:50px;" src="{{custom_path($user->kyc_userimg)}}">
                            </td>
                            <td>{{ $user->kyc_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->kyc_address }}</td>
                            <td>{{ $user->kyc_birthday }}</td>
                            <td>
                                @if($user->kyc_card_type == 'nid')
                                    <span class="badge bg-info">NID</span>
                                @else
                                    <span class="badge bg-primary">Birth</span>
                                @endif
                            </td>
                            <td>{{ $user->kyc_nid_number }}</td>
                            <td>
                                <img style="width:50px;" src="{{custom_path($user->kyc_frontpart)}}">
                            </td>
                            <td>
                                <img style="width:50px;" src="{{custom_path($user->kyc_backpart)}}">
                            </td>                       
                            
                            <td>
                                <button class="btn btn-info" data-toggle="modal" data-target="#viewUser{{ $user->id }}">View Details</button>
                            </td>
                        </tr>

                        <div class="modal fade" id="viewUser{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="statusModalLabel">User Full Details</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.kyc-verify-check-update') }}">
                                        @csrf
                                        <input type="hidden" name="submitType" value="firstAction">
                                        <input type="hidden" name="id" value="{{$user->id}}">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="">My Image</label>
                                                <img src="{{custom_path($user->kyc_userimg)}}" style="width: 100%" alt="">
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="form-label">KYC Name : {{$user->kyc_name}}</label>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="form-label">KYC Date Of Birth : {{$user->kyc_birthday}}</label>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="form-label">KYC Card Number : {{$user->kyc_nid_number}}</label>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="" class="form-label">KYC Address : {{$user->kyc_address}}</label>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="form-label">KYC Address : {{$user->kyc_nid_number}}</label>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Front Side</label>
                                                <img src="{{custom_path($user->kyc_frontpart)}}" style="width: 100%" alt="">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Back Side</label>
                                                <img src="{{custom_path($user->kyc_backpart)}}" style="width: 100%" alt="">
                                            </div>
                                            <div class="form-group" style="display: flex; justify-content:space-between;align-item:center;">
                                                <label for="kyc_notice" class="form-label">Send him Notice :</label>
                                                <textarea style="width:100%;" name="kyc_notice" id="kyc_notice" rows="5"> {{$user->kyc_notice}}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="accountStatus">Select Account Status</label>
                                                <select class="form-control" id="accountStatus" name="kyc_status">
                                                    <option value="pending" {{ $user->kyc_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="approve" {{ !$user->kyc_status == 'approve' ? 'selected' : '' }}>Approve</option>
                                                    <option value="unapprove" {{ !$user->kyc_status == 'unapprove' ? 'selected' : '' }}>UnApprove</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                    @endforeach
                </tbody>
              </table>

              
              <div class="mt-4 text-center">{{ $users->onEachSide(1)->links() }}</div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
    @include('backend.pages.usermanage.partials.usercreatemodal')
  </section>
  <!-- /.content -->
@endsection

@section('js')








    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    {{-- <script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script> --}}
    <script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('backend/plugins/select2/js/select2.full.min.js') }}"></script>

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

        // this function for image show when select image to upload database------------
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e){
                    $('#photo')
                        .attr('src', e.target.result)
                        .width(80)
                        .height(80);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(function() {
          $("#filter").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#user-table > tbody > tr").filter(function() {      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
          });
        });

    </script>
        <script src="{{ asset('frontend/user/assets/js/my_custom.js') }}"></script>
        <script>
            @if(session('success'))
                toastr.options =
                {
                    "closeButton" : true,
                    "progressBar" : true
                }
                toastr.success({{ session('success') }});
            @elseif(session('error'))
                toastr.options =
                {
                    "closeButton" : true,
                    "progressBar" : true
                }
                toastr.error({{ session('error') }});
            @endif
        </script>
@endsection
