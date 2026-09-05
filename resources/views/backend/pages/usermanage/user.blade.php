@extends('backend.layouts.master')

@section('title')
    {{ $website->title }}
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
          <h1 class="m-0 text-dark">User</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            

            
            
            
            <li class="breadcrumb-item active">All Users</li>
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
                  <div class="col-10"><h3 class="card-title">All Users</h3></div>
              </div>
            </div>
            <!-- /.card-header -->
            
            
           
            
            <div class="card-body overflow-x">
                <form action="{{route('admin.user-search')}}" method="GET">
                    @csrf
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" name="search_data" placeholder="Name/Phone/Email"/>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary btn-sm">Search</button>
                        </div>
                    </div>
                </form>
              <table id="user-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th width="6%">ID</th>
                    <th width="7%">Image</th>
                    <th>Name</th>
                    <th>Email</th>
                     @if (Auth::user()->role_id == 1)<th> Password</th>@endif
                    <th>Total Job</th>
                    <th>Complete Job</th>
                    <th>Deposit</th>
                    <th>Earning</th>                  
                    <th>Short<br>Details</th>
                    <th>Status</th>
                    @if(site_info()->instanat_verify_active == 1)
                        <th>Dollar verify</th>
                    @endif
                      <th>Email verify</th>
                    @if (Auth::user()->role_id == 1) <th>More Action</th>@endif
                </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key=>$user)
                        <tr>
                            <td><span class="badge bg-info">{{ $user->code }}</span></td>
                           <td>
    <img src="{{ $user->image ? URL::to($user->image) : 'https://www.freeiconspng.com/uploads/no-image-icon-4.png' }}" alt="{{ $user->name }}" style="height: 60px; width: 60px;">
</td>

                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            
                            
                            
                           @if (Auth::user()->role_id == 1) <td>
                <!-- Password button -->
                <button type="button" class="badge bg-success" data-toggle="modal" data-target="#passwordModal{{ $user->id }}">
                    View
                </button>
            </td>@endif
                          
                          
                            <td>{{ user_total_job($user->id) }}</td>
                            <td>{{ user_complete_job($user->id) }}</td>
                            <td>{{ $user->deposit_balance }}</td>
                            <td>{{ $user->earning_balance }}</td>
                            <td>
                                <a style="cursor:pointer;" href="{{route('admin.user_full_job_view',['id'=> $user->id , 'viewType' => 'postedJob'])}}">Posted : {{$jobs->where('user_id',$user->id)->count()}}</a> 
                                <br>
                                <a style="cursor:pointer;" href="{{route('admin.user_full_job_view',['id'=> $user->id , 'viewType' => 'appliedJob'])}}">Applied : {{$JobWork->where('user_id',$user->id)->count()}}</a>
                                <br>
                                <a style="cursor:pointer;" href="{{route('admin.user_full_job_view',['id'=> $user->id , 'viewType' => 'ptcPostedJob'])}}">ptc Posted : {{$ptc_job->where('ptc_post_user_id',$user->id)->count()}}</a> 
                                <br>
                                <a style="cursor:pointer;" href="{{route('admin.user_full_job_view',['id'=> $user->id , 'viewType' => 'ptcEarnedJob'])}}">ptc Applied : {{$ptc_earn_history->where('ptc_worker_id',$user->id)->count()}}</a> 
                            </td>
                            <td>
                                <div class="input-group">
                                    @if($user->is_ban == 1)
                                        <span class="badge bg-warning">Account Ban</span>
                                    @elseif($user->is_suspended == 1)
                                        <span class="badge bg-warning">Account Suspended</span>
                                    @else
                                        @if($user->status == 0)
                                            <span class="badge bg-warning">Inactive</span>
                                        @elseif($user->status == 1)
                                            <span class="badge bg-success p-2">Active</span>
                                        @endif
                                        <div class="input-group-append">
                                            <a href="javascript:;" class="btn btn-info btn-sm" data-toggle="modal" data-target="#edit_status_{{ $user->id }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="input-group mt-2">
                                    @if($user->is_suspended == 0)
                                        <a href="javascript:;" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#user_suspended_{{ $user->id }}">
                                            Make Suspend
                                        </a>
                                    @elseif($user->is_suspended == 1)
                                        <a href="javascript:;" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#user_suspended_{{ $user->id }}">
                                            Make Unsuspend
                                        </a>
                                    @endif
                                </div>
                                <div class="input-group mt-2">
                                    @if($user->is_ban == 0)
                                        <a href="javascript:;" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#user_ban_{{ $user->id }}">
                                            Make Ban
                                        </a>
                                    @elseif($user->is_ban == 1)
                                        <a href="javascript:;" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#user_ban_{{ $user->id }}">
                                            Make Unband
                                        </a>
                                    @endif
                                </div>
                            </td>
                            
                            
                            @if(site_info()->instanat_verify_active == 1)
                                <td>
                                    <a href="javascript:;" data-toggle="modal" data-target="#statusModal_{{ $user->id }}">
                                        @if($user->is_verified)
                                            <span class="badge bg-success">Verified</span>
                                        @else
                                            <span class="badge bg-danger">Unverified</span>
                                        @endif
                                    </a>
                                    {{-- if user Kyc Verified  --}}
                                    @if($user->kyc_status == 'approve')
                                        <style>
                                            .loader {
                                                display: inline-grid;
                                                width: 3vw;
                                                aspect-ratio: 1;
                                                clip-path: polygon(100% 50%,85.36% 85.36%,50% 100%,14.64% 85.36%,0% 50%,14.64% 14.64%,50% 0%,85.36% 14.64%);
                                                background: #574951;
                                                animation: l2 6s infinite linear;
                                                margin :auto;
                                            }
                                            .loader:before,
                                            .loader:after {
                                                content:"";
                                                grid-area: 1/1;
                                                background: #83988E;
                                                clip-path: polygon(100% 50%,81.17% 89.09%,38.87% 98.75%,4.95% 71.69%,4.95% 28.31%,38.87% 1.25%,81.17% 10.91%);
                                                margin: 10%;
                                                animation: inherit;
                                                animation-duration: 10s;
                                            }
                                            .loader:after {
                                                background: #BCDEA5;
                                                clip-path: polygon(100% 50%,75% 93.3%,25% 93.3%,0% 50%,25% 6.7%,75% 6.7%);
                                                margin: 20%;
                                                animation-duration: 3s;
                                                animation-direction: reverse;
                                            }
                                            @keyframes l2 {to{rotate: 1turn}}
                                        </style>
                                        <div class="loader"></div>
                                    @endif
                                </td>
                            @endif





<!-- Email Verification Status Column -->
<td>
    <a href="javascript:;" data-toggle="modal" data-target="#emailStatusModal_{{ $user->id }}">
        @if($user->hasVerifiedEmail())
            <span class="badge bg-success">Yes</span>
        @else
            <span class="badge bg-danger">No</span>
        @endif
    </a>
</td>      
                            
                 
                 
                 
                 
                            
                            
                             @if (Auth::user()->role_id == 1)
                            <td>
                                <a href="{{ route('admin.user.delete',$user->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash-alt"></i></a>
                                <a href="javascript:;" class="btn btn-success btn-sm" data-toggle="modal" data-target="#edit_{{ $user->id }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                            @endif
                            
                            
                        </tr>
                        
                        
                        
                        
                        
                       
                       
                       
                        @if (Auth::user()->role_id == 1)
                       <!-- Modal for updating account status -->
    <div class="modal fade" id="statusModal_{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Update Account Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.user.update.status', $user->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="accountStatus">Select Account Status</label>
                            <select class="form-control" id="accountStatus" name="is_verified">
                                <option value="1" {{ $user->is_verified ? 'selected' : '' }}>Verified</option>
                                <option value="0" {{ !$user->is_verified ? 'selected' : '' }}>Unverified</option>
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

 
                        
                        
                        
                        
                        
                        
                        
                        
                        
                     
                        <!-- Modal for updating email verification status -->
<div class="modal fade" id="emailStatusModal_{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="emailStatusModalLabel_{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailStatusModalLabel_{{ $user->id }}">Update Email Verification Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.user.update.emailStatus', $user->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="emailStatus">Select Email Verification Status</label>
                        <select class="form-control" id="emailStatus" name="is_verified">
                            <option value="1" {{ $user->hasVerifiedEmail() ? 'selected' : '' }}>Verified</option>
                            <option value="0" {{ !$user->hasVerifiedEmail() ? 'selected' : '' }}>Unverified</option>
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
                        
                        
          @endif              
                        
                        
                        
                <!-- Modal for updating user information -->
<div class="modal fade" id="edit_{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="userEditModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userEditModalLabel">Edit User Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <!-- User Name -->
                    <div class="form-group col-lg-6 col-md-6 col-12">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required value="{{ $user->name }}">
                    </div>
                    <!-- User Email -->
                    <div class="form-group col-lg-6 col-md-6 col-12">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required value="{{ $user->email }}">
                    </div>
                    
                    <!-- User Deposit Balance -->
                    <div class="form-group col-lg-6 col-md-6 col-12">
                        <label for="deposit_balance">Deposit Balance</label>
                        <input type="text" class="form-control" id="deposit_balance" name="deposit_balance" required value="{{ $user->deposit_balance }}">
                    </div>
                    <!-- User Earning Balance -->
                    <div class="form-group col-lg-6 col-md-6 col-12">
                        <label for="earning_balance">Earning Balance</label>
                        <input type="text" class="form-control" id="earning_balance" name="earning_balance" required value="{{ $user->earning_balance }}">
                    </div>
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                   
                    <!-- Additional Fields for more information -->
                    <!-- You can add more fields here based on your needs -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>


                        
                        
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                        <div class="modal fade" id="edit_status_{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="{{ route('admin.user-activity.update', $user->id) }}"
                                    method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Update User Activity</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body row">
                                            <div class="form-group col-lg-12 col-md-12 col-12">
                                                <label for="status">Status</label>
                                                <select class="form-control" name="status" id="status">
                                                    <option value="0" @if($user->status == 0) selected @endif>Inactive</option>
                                                    <option value="1" @if($user->status == 1) selected @endif>Active</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-12">
                                                <label for="reason">Reason</label>
                                                <textarea class="form-control" name="reason" id="reason" cols="30" rows="3">{{ $user->reason }}</textarea>
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
                        <div class="modal fade" id="user_suspended_{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="{{ route('admin.user-suspend', $user->id) }}"
                                    method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">User Suspend</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body row">
                                            <div class="form-group col-lg-12 col-md-12 col-12">
                                                <label for="status">Suspend Status</label>
                                                <select class="form-control" name="is_suspended">
                                                    <option value="0" @if($user->is_suspended == 0) selected @endif>No</option>
                                                    <option value="1" @if($user->is_suspended == 1) selected @endif>Yes</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-12">
                                                <label for="suspend_reason">Reason</label>
                                                <textarea class="form-control" name="suspend_reason" cols="30" rows="3">{{ $user->suspend_reason }}</textarea>
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-12">
                                                <label for="suspend_release">Suspend For(Hours)</label>
                                                <input type="text" class="form-control" name="suspend_release" required value="0">
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
                        <div class="modal fade" id="user_ban_{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="{{ route('admin.user-ban', $user->id) }}"
                                    method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">User Ban</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body row">
                                            <div class="form-group col-lg-12 col-md-12 col-12">
                                                <label for="is_ban">Ban Status</label>
                                                <select class="form-control" name="is_ban">
                                                    <option value="0" @if($user->is_ban == 0) selected @endif>No</option>
                                                    <option value="1" @if($user->is_ban == 1) selected @endif>Yes</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-12">
                                                <label for="ban_reason">Reason</label>
                                                <textarea class="form-control" name="ban_reason" cols="30" rows="3">{{ $user->ban_reason }}</textarea>
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
              
              
              
              
              
              
              <!-- Password Modal -->
@foreach($users as $user)
<div class="modal fade" id="passwordModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                
                <h5 class="modal-title" id="passwordModalLabel{{ $user->id }}">User other info</h5>
               
                 
                 <p>User id: {{ $user->id }}</p>
                 
                <p> Mail Adrees: {{ $user->email }} </p>
                 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Display password here -->
                Password:  <span class="badge bg-danger">{{ $user->pass_text }}</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
              
              
              
              
              
              
              
              
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
@endsection