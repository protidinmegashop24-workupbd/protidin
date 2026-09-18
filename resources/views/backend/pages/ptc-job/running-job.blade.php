@extends('backend.layouts.master')

@section('title')
    {{ Route::is('admin.ptcRunningAdmin') ? 'Running Job List' : '' }}
    {{ Route::is('admin.ptcExpiredAdmin') ? 'Expired Job List' : '' }}
    {{ Route::is('admin.ptcAdminPending') ? 'Strong Pending By Admin Job List' : '' }}
    {{ Route::is('admin.ptcDeleteRequest') ? 'Request To Delete Job List' : '' }}
    {{ Route::is('admin.ptcRejectList') ? 'Rejected Job List' : '' }}
    {{ Route::is('admin.ptcDeleteList') ? 'Deleted Job List' : '' }}
    {{ Route::is('admin.ptcJobHistoryAdmin') ? 'Total History' : '' }}


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
          <h1 class="m-0 text-dark">
            {{ Route::is('admin.ptcRunningAdmin') ? 'Running Job List' : '' }}
            {{ Route::is('admin.ptcExpiredAdmin') ? 'Expired Job List' : '' }}
            {{ Route::is('admin.ptcAdminPending') ? 'Strong Pending By Admin Job List' : '' }}
            {{ Route::is('admin.ptcDeleteRequest') ? 'Request To Delete Job List' : '' }}
            {{ Route::is('admin.ptcRejectList') ? 'Rejected Job List' : '' }}
            {{ Route::is('admin.ptcDeleteList') ? 'Deleted Job List' : '' }}
            {{ Route::is('admin.ptcJobHistoryAdmin') ? 'Total History' : '' }}
          </h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">
                {{ Route::is('admin.ptcRunningAdmin') ? 'Running Job List' : '' }}
                {{ Route::is('admin.ptcExpiredAdmin') ? 'Expired Job List' : '' }}
                {{ Route::is('admin.ptcAdminPending') ? 'Strong Pending By Admin Job List' : '' }}
                {{ Route::is('admin.ptcDeleteRequest') ? 'Request To Delete Job List' : '' }}
                {{ Route::is('admin.ptcRejectList') ? 'Rejected Job List' : '' }}
                {{ Route::is('admin.ptcDeleteList') ? 'Deleted Job List' : '' }}
                {{ Route::is('admin.ptcJobHistoryAdmin') ? 'Total History' : '' }}
            </li>
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
                  <div class="col-10"><h3 class="card-title">
                    {{ Route::is('admin.ptcRunningAdmin') ? 'Running Job List' : '' }}
                    {{ Route::is('admin.ptcExpiredAdmin') ? 'Expired Job List' : '' }}
                    {{ Route::is('admin.ptcAdminPending') ? 'Strong Pending By Admin Job List' : '' }}
                    {{ Route::is('admin.ptcDeleteRequest') ? 'Request To Delete Job List' : '' }}
                    {{ Route::is('admin.ptcRejectList') ? 'Rejected Job List' : '' }}
                    {{ Route::is('admin.ptcDeleteList') ? 'Deleted Job List' : '' }}
                    {{ Route::is('admin.ptcJobHistoryAdmin') ? 'Total History' : '' }}
                </h3></div>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body overflow-x">
                <input type="text" class="form-control mb-2" id="filter" placeholder="Name/Phone/Email"/>
              <table id="user-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="6%">ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Job <br>Title</th>
                        <th>Ads</th>
                        <th>Worker <br> Need</th>
                        <th>Total <br> Clicked</th>
                        <th>Per <br> Price</th>
                        <th>Wait <br> Time</th>
                        <th>Expire <br> Date</th>                        
                        {!! Route::is('admin.ptcJobHistoryAdmin') ? '<td>status</td>' : '' !!}
                        <th>View Full</th>
                        {!! Route::is('admin.ptcRejectList') ? '<td>Notice</td>' : '' !!}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jobs as $job)
                        <tr>
                            <td>{{ $job->id }}</td>
                            <td>{{ $job->user->name }}</td>
                            <td>{{ $job->user->email }}</td>
                            <td>{{ $job->ptc_title }}</td>
                            <td> <a href="{{ $job->ptc_jobLink }}" target="_blank">Click</a></td>
                            <td>{{ $job->ptc_worker_needed }}</td>
                            <td>{{ $job->ptc_clicked }}</td>
                            <td>{{ $job->ptc_each_earn }}</td>
                            <td>{{ $job->ptc_wait_time }}</td>
                            <td>{{ \Carbon\Carbon::parse($job->ptc_expire_day)->format('d-M-y') }}</td>
                            {!! Route::is('admin.ptcJobHistoryAdmin') ? "<td> $job->ptc_status</td>" : "" !!}
                            <td>
                                <button class="btn btn-info" data-toggle="modal" data-target="#viewUser{{ $job->id }}">View Details</button>
                            </td>
                            {!! Route::is('admin.ptcRejectList') ? "<td> $job->ptc_reject_notice</td>" : "" !!}
                        </tr>

                        <div class="modal fade" id="viewUser{{ $job->id }}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="statusModalLabel">Job Manage Handler</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.ptcRunningAdminStore') }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$job->id}}">
                                        <div class="modal-body">
                                            <div class="form-group" style="display: flex; justify-content:space-between;align-item:center;">
                                                <label for="ptc_reject_notice" class="form-label">Job Reject Notice :</label>
                                                <textarea style="width:100%;" name="ptc_reject_notice" id="ptc_reject_notice" rows="5">{{$job->ptc_reject_notice}}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="accountStatus">Change Job Status</label>
                                                <select class="form-control" id="accountStatus" name="ptc_status">                                                    
                                                    <option value="running">Approve / Run</option>
                                                    <option value="pending">User Pending</option>    
                                                    <option value="review">User Review</option>
                                                    <option value="reject">Reject</option>
                                                    <option value="adminPending">Strong Pending</option>
                                                    <option value="req_delete">Request To Delete</option>
                                                    <option value="deleted">Delete</option>       
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

              
              <div class="mt-4 text-center">{{ $jobs->onEachSide(1)->links() }}</div>
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
@endsection
