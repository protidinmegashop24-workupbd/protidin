@extends('user.layouts.master')
@section('css')
    <link href="{{ asset('frontend/user/assets/css/nucleo.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/fontawesome-all.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/argon.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('frontend/user/assets/css/myapp.css') }}" rel="stylesheet" type="text/css">
    <style>
        .directory-card {
            background-color: #f8f9fa;
            border: 1px solid #e3e6f0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
        }
        .directory-card .card-body {
            padding: 30px;
        }
        .avatar-lg {
            width: 120px;
            height: 120px;
        }
        .font-weight-bold {
            font-weight: 700;
        }
        .text-muted {
            color: #6c757d !important;
        }
        .bg-gradient-green {
            background: linear-gradient(87deg,#2dce89 0,#2dcecc 100%)!important;
        }
        .bg-gradient-info {
            background: linear-gradient(87deg,#11cdef 0,#1171ef 100%)!important;
        }
        .icon-shape {
            display: inline-flex;
            text-align: center;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
        }
        .icon {
            width: 3rem;
            height: 3rem;
        }
        .icon-shape i, .icon-shape svg {
            font-size: 1.25rem;
        }
    </style>
@endsection

@section('user-content')
<div class="page-title-box">
    <div class="row justify-content-center">
        <div class="col-xl-4 col-md-6 p-0">
            <div class="card directory-card text-center">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <div class="flex-shrink-0 mb-3">
                            <img src="{{ URL::to(Auth::user()->image) }}"
                                 alt="{{ Auth::user()->name }}"
                                 class="img-fluid img-thumbnail rounded-circle avatar-lg shadow-lg"
                                 onerror="this.onerror=null;this.src='{{ asset('frontend/img/user.png') }}';">
                        </div>

                        <div class="flex-grow-1">
                            <h5 class="text-primary font-size-20 font-weight-bold mb-1">{{ Auth::user()->name }}</h5>
                            <p class="text-muted font-size-14 mb-1">Since {{ \Carbon\Carbon::parse(Auth::user()->created_at)->format('d M Y')}}</p>
                            <p class="text-muted mb-1">User ID: <span class="text-dark font-weight-bold">{{ Auth::user()->code }}</span></p>
                            <p class="text-muted mb-1">Email: <span class="text-dark">{{ Auth::user()->email }}</span></p>
                            <p class="text-muted mb-3">Phone: <span class="text-dark">{{ Auth::user()->phone }}</span></p>

                            @if(site_info()->instanat_verify_active == 1)
                                @if(Auth::user()->is_verified)
                                    <p class="mb-1"><span class="badge bg-success p-2">Account Status: Verified</span></p>
                                @else
                                    <p class="mb-1"><span class="badge bg-danger p-2">Account Status: Unverified</span></p>
                                @endif
                            @endif

                            @if(site_info()->email_verification_active == 1)
                                @if(Auth::user()->hasVerifiedEmail())
                                    <p class="mb-1"><span class="badge bg-success p-2">Email Status: Verified</span></p>
                                @else
                                    <p class="mb-1"><span class="badge bg-warning p-2">Email Status: Unverified</span></p>
                                    <p class="mb-1">
                                        <a href="{{ route('verification.notice') }}" class="text-primary">Click here to verify your email</a>
                                    </p>
                                @endif
                            @endif

                            @if(Auth::user()->status == 0)
                                <p class="mb-0">
                                    <span class="text-danger font-weight-bold">Reason For Block: {{ Auth::user()->reason }}</span>
                                </p>
                            @endif
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            <a href="{{ route('user.manage-profile') }}" class="btn btn-info btn-sm text-white me-2">
                                <i class="fas fa-user-cog"></i> Manage Account
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="text-primary font-weight-bold mb-3">Seller Information</h5>
                    <p class="mb-2"><strong>Experience Level:</strong> {{ Auth::user()->seller_experience_level ?: 'Not added yet' }}</p>
                    <p class="mb-2"><strong>Skills:</strong> {{ Auth::user()->seller_skills ?: 'Not added yet' }}</p>
                    <p class="mb-0"><strong>Bio:</strong><br>{{ Auth::user()->seller_bio ?: 'No seller bio added yet.' }}</p>
                </div>
            </div>

            <div class="card bg-gradient-green">
                <div class="card-body cust_task_working">
                    <div class="row mb-2">
                        <div class="col">
                            <h5 class="h3 mb-0 text-white">WORK RETED</h5>
                            <h5 class="h3 mb-1 text-white">{{ total_attend_work(Auth::user()->id) }}</h5>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                                <i class="fas fa-tasks"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-sm">
                        <span class="text-white mr-2 font-weight-500"><i class="fa fa-percentage"></i> {{work_satisfication(Auth::user()->id)}}%</span>
                        <span class="text-nowrap text-light font-weight-500">Satisfaction</span>
                    </p>
                </div>
            </div>

            <div class="card bg-gradient-info">
                <div class="card-body cust_task_working">
                    <div class="row mb-2">
                        <div class="col">
                            <h5 class="h3 mb-0 text-white">JOB</h5>
                            <h5 class="h3 mb-1 text-white">{{ user_total_job(Auth::user()->id) }}</h5>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-white text-dark rounded-circle shadow">
                                <i class="fas fa-briefcase"></i>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-sm">
                        <span class="text-white mr-2 font-weight-500"><i class="fa fa-percentage"></i> {{job_satisfication(Auth::user()->id)}}%</span>
                        <span class="text-nowrap text-light font-weight-500">Satisfied</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-8 order-xl-1 p-0">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body cust_task_working">
                            <div class="row mb-2">
                                <div class="col-6">
                                    <h5 class="h3 mb-0">WORK RETED</h5>
                                    <h5 class="h3 mb-1">{{ total_attend_work(Auth::user()->id) }}</h5>
                                </div>
                                <div class="col-6 text-primary h2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="h3 mb-0">Overview Working</h5>
                        </div>
                        <div class="card-body cust_task_working">
                            <div class="row mb-2">
                                <div class="col-6">Task Attend</div>
                                <div class="col-6 text-primary h2">{{ total_attend_work(Auth::user()->id) }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">Satisfied <br> <h6>Approved in task</h6></div>
                                <div class="col-6 text-success h2">{{ user_complete_job_approve(Auth::user()->id) }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">Not Satisfied<br><h6>Rejected in task prove</h6></div>
                                <div class="col-6 text-danger h2">{{ user_complete_job_reject(Auth::user()->id) }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">Pending <br><h6>In review for rating</h6></div>
                                <div class="col-6 text-info h2">{{ user_complete_job_pending(Auth::user()->id) }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">Payment Received</div>
                                <div class="col-6 h2" style="color:#fec30f">{{ Auth::user()->earning_balance }} $</div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="h3 mb-0">Overview Job</h5>
                        </div>
                        <div style="color:#27954f">
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-6">Jobs Posted</div>
                                    <div class="col-6">{{ user_total_job(Auth::user()->id) }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6">Total Deposit</div>
                                    <div class="col-6">{{ Auth::user()->deposit_balance }} $</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6">Paid</div>
                                    <div class="col-6">$0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</div>
@endsection

@section('js')
@endsection