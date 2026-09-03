<style>
    .lastoption {
        margin-bottom: 30px;
    }
</style>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" target="_blank" class="brand-link">
      <img src="{{ !empty($website->favicon) ? URL::to($website->favicon) : asset('frontend/assets/img/logo.png') }}" alt="{{ $website->title ?? 'Admin' }}" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">{{ $website->title ?? 'Admin' }}</span>
      
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ URL::to(Auth::user()->image) }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="{{ route('admin.profile') }}" class="d-block">{{ Auth::user()->name }} <i class="fa fa-circle text-success"></i></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            {{-- Dashboard --}}
            <li class="nav-item has-treeview">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p> Dashboard </p>
                </a>
            </li>

            {{-- <li class="nav-header">User & Area Manage</li> --}}
            <li class="nav-item has-treeview {{ Route::is('admin.kyc-user-unapprove') || Route::is('admin.kyc-user-list') || Route::is('admin.kyc-verify-check') || Route::is('admin.role') || Route::is('admin.user') || Route::is('admin.duplicate-users') || Route::is('admin.user.edit') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-users-cog"></i>
                    <p> User Manage <i class="right fas fa-angle-left"></i> </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.user') }}" class="nav-link {{ Route::is('admin.user') || Route::is('admin.user.edit') ? 'active' : '' }}">
                        <i class="fas fa-user nav-icon"></i>
                        <p>User</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.duplicate-users') }}" class="nav-link {{ Route::is('admin.duplicate-users') ? 'active' : '' }}">
                        <i class="fas fa-user nav-icon"></i>
                        <p>Duplicate Users</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.kyc-verify-check') }}" class="nav-link {{ Route::is('admin.kyc-verify-check') ? 'active' : '' }}">
                        <i class="fas fa-user nav-icon"></i>
                        <p>KYC Requested</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.kyc-user-list') }}" class="nav-link {{ Route::is('admin.kyc-user-list') ? 'active' : '' }}">
                        <i class="fas fa-user nav-icon"></i>
                        <p>KYC User List</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.kyc-user-unapprove') }}" class="nav-link {{ Route::is('admin.kyc-user-unapprove') ? 'active' : '' }}">
                        <i class="fas fa-user nav-icon"></i>
                        <p>KYC Not Approved</p>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- <li class="nav-header">User & Area Manage</li> --}}


            @if (Auth::user()->role_id == 1)            
                <li class="nav-item">
                    <a href="/super-admin/accounts" class="nav-link {{ Route::is('admin.accounts') ? 'active' : '' }}">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                        <p>&nbsp;Admin Account Manage</p>
                    </a>
                </li>
                <li class="nav-item has-treeview {{ Route::is('admin.about_us') || Route::is('admin.contact_info') || Route::is('admin.header-info') || Route::is('admin.counter-info') || Route::is('admin.contact_msg') || Route::is('admin.google-ad') || Route::is('admin.policy') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link"> <i class="fa fa-building" aria-hidden="true"></i> 
                        <p> Company <i class="right fas fa-angle-left"></i> </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.about_us') }}" class="nav-link {{ Route::is('admin.about_us') ? 'active' : '' }}">
                                <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                <p>About Us</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.contact_info') }}" class="nav-link {{ Route::is('admin.contact_info') ? 'active' : '' }}">
                                <i class="fa fa-arrow-right" aria-hidden="true"></i>
                            <p>Contact Information</p>
                            </a>
                        </li>                    
                        <li class="nav-item">
                            <a href="{{ route('admin.policy') }}" class="nav-link {{ Route::is('admin.policy') ? 'active' : '' }}">
                                <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                <p>Policy</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.header-info') }}" class="nav-link {{ Route::is('admin.header-info') ? 'active' : '' }}">
                                <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                <p>Header Info</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.counter-info') }}" class="nav-link {{ Route::is('admin.counter-info') ? 'active' : '' }}">
                                <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                <p>Counter Info</p>
                            </a>
                        </li>                    
                        <li class="nav-item">
                            <a href="{{ route('admin.refer-info') }}" class="nav-link {{ Route::is('admin.counter-info') ? 'active' : '' }}">
                                <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                <p>Reffer info</p>
                            </a>
                        </li>
                    </ul>
                </li>            
                <li class="nav-item">
                    <a href="{{ route('admin.service') }}" class="nav-link {{ Route::is('admin.service') ? 'active' : '' }}">
                    <i class="fa fa-server" aria-hidden="true"></i>
                    <p>Service</p>
                    </a>
                </li>            
            @endif
            <li class="nav-item has-treeview {{ Route::is('admin.advertisement') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-image"></i>
                    <p>
                        Advertisement Manage
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                
                <ul class="nav nav-treeview">
                    @if (Auth::user()->role_id == 1)
                        <li class="nav-item">
                            <a href="{{ route('admin.ads-rate') }}" class="nav-link {{ Route::is('admin.ads-rate') ? 'active' : '' }}">
                                <i class="fas fa-arrow-right nav-icon"></i>
                                <p>Ads Rate</p>
                            </a>
                        </li>
                        
                    @endif
              
                    <li class="nav-item">
                        <a href="{{ route('admin.pending-advertisement') }}" class="nav-link {{ Route::is('admin.pending-advertisement') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Pending Ads List</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.advertisement') }}" class="nav-link {{ Route::is('admin.advertisement') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Ads List</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item has-treeview {{ Route::is('admin.job') || Route::is('admin.job-work') || Route::is('admin.delete-requested-job') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-list"></i>
                    <p>
                        Job Manage
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.pending-job') }}" class="nav-link {{ Route::is('admin.job') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Pending Job List</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.job') }}" class="nav-link {{ Route::is('admin.job') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Approval Job List</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.delete-requested-job') }}" class="nav-link {{ Route::is('admin.delete-requested-job') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Job Delete Request</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.job-work') }}" class="nav-link {{ Route::is('admin.job-work') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Job Work List</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.reject-request-job-work') }}" class="nav-link {{ Route::is('admin.reject-request-job-work') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Work Reject Request</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.rejected-job-work') }}" class="nav-link {{ Route::is('admin.rejected-job-work') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Rejected Work List</p>
                        </a>
                    </li>
                </ul>
            </li>
                {{-- PTC Job Start --}}
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-list"></i>
                    <p>
                        PTC Link Manage
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">

                    <li class="nav-item">
                        <a href="{{route('admin.ptcRunningAdmin')}}" class="nav-link {{ Route::is('admin.ptcRunningAdmin') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>PTC Running Job List</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.ptcExpiredAdmin')}}" class="nav-link {{ Route::is('admin.ptcExpiredAdmin') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>PTC Expired Job List</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.ptcAdminPending')}}" class="nav-link {{ Route::is('admin.ptcAdminPending') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>PTC Pause List By Admin </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.ptcDeleteRequest')}}" class="nav-link {{ Route::is('admin.ptcDeleteRequest') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>PTC Delete Request </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.ptcRejectList')}}" class="nav-link {{ Route::is('admin.ptcRejectList') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>PTC Reject Job</p>
                        </a>
                    </li>                    
                    <li class="nav-item">
                        <a href="{{route('admin.ptcDeleteList')}}" class="nav-link {{ Route::is('admin.ptcDeleteList') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>PTC Deleted List </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.ptcJobHistoryAdmin')}}" class="nav-link {{ Route::is('admin.ptcJobHistoryAdmin') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>PTC Total Job History</p>
                        </a>
                    </li>

                </ul>
            </li>
                {{-- PTC Job Menu End --}}
            <li class="nav-item has-treeview {{ Route::is('admin.deposit-account') || Route::is('admin.deposit-list') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-dollar-sign"></i>
                    <p>
                        Deposit Manage
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    
                
                @if (Auth::user()->role_id == 1)
                    <li class="nav-item">
                        <a href="/super-admin/bkash-settings" class="nav-link {{ Route::is('admin.bkash-settings') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Instant Deposit</p>
                        </a>
                    </li>
                @endif    
                    
                    
                 @if (Auth::user()->role_id == 1)
                    <li class="nav-item">
                        <a href="{{ route('admin.deposit-account') }}" class="nav-link {{ Route::is('admin.deposit-account') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Deposit Account</p>
                        </a>
                    </li>
                @endif

                    
                    
                    

                    <li class="nav-item">
                        <a href="{{ route('admin.deposit-list') }}" class="nav-link {{ Route::is('admin.deposit-list') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Deposit List</p>
                        </a>
                    </li>

                </ul>
            </li>
            <li class="nav-item has-treeview {{ Route::is('admin.withdraw-method') || Route::is('admin.withdraw-request') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-dollar-sign"></i>
                    <p>
                        Withdraw Manage
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                
                
                <ul class="nav nav-treeview">
                    
                  @if (Auth::user()->role_id == 1)
                    <li class="nav-item">
                        <a href="{{ route('admin.withdraw-method') }}" class="nav-link {{ Route::is('admin.withdraw-method') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Withdraw Method</p>
                        </a>
                    </li>
                    @endif
                    
                    
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.pending-withdraw-request') }}" class="nav-link {{ Route::is('admin.pending-withdraw-request') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Pending Withdraw</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.withdraw-request') }}" class="nav-link {{ Route::is('admin.withdraw-request') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Withdraw List</p>
                        </a>
                    </li>

                </ul>
            </li>
            <li class="nav-item has-treeview {{ Route::is('admin.headline') || Route::is('admin.accept-task-headline') || Route::is('admin.complete-task-headline') || Route::is('admin.deposit-headline') || Route::is('admin.withdraw-headline') || Route::is('admin.boost-package-headline') || Route::is('admin.top-deposit-user-headline') || Route::is('admin.top-earning-user-headline') || Route::is('admin.top-referral-user-headline') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-dollar-sign"></i>
                    <p>
                        Headline Manage
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">

                    <li class="nav-item">
                        <a href="{{ route('admin.headline') }}" class="nav-link {{ Route::is('admin.headline') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Main Headline</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.accept-task-headline') }}" class="nav-link {{ Route::is('admin.accept-task-headline') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Accept Task Headline</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.complete-task-headline') }}" class="nav-link {{ Route::is('admin.complete-task-headline') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Complete Task Headline</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.deposit-headline') }}" class="nav-link {{ Route::is('admin.deposit-headline') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Deposit Headline</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.withdraw-headline') }}" class="nav-link {{ Route::is('admin.withdraw-headline') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Withdraw Headline</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.boost-package-headline') }}" class="nav-link {{ Route::is('admin.boost-package-headline') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Boost Service</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.top-deposit-user-headline') }}" class="nav-link {{ Route::is('admin.top-deposit-user-headline') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Top Deposit User</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.top-earning-user-headline') }}" class="nav-link {{ Route::is('admin.top-earning-user-headline') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Top Earning User</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.top-referral-user-headline') }}" class="nav-link {{ Route::is('admin.top-referral-user-headline') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Top Referral User</p>
                        </a>
                    </li>

                </ul>
            </li>            
            <li class="nav-item has-treeview {{ Route::is('admin.support-ticket') || Route::is('admin.pending-support-ticket') || Route::is('admin.closed-support-ticket') || Route::is('admin.answered-support-ticket') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-dollar-sign"></i>
                    <p>
                        Support Ticket
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.pending-support-ticket') }}" class="nav-link {{ Route::is('admin.pending-support-ticket') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Pending Ticket</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.closed-support-ticket') }}" class="nav-link {{ Route::is('admin.closed-support-ticket') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Closed Ticket</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.answered-support-ticket') }}" class="nav-link {{ Route::is('admin.answered-support-ticket') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Answered Ticket</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.support-ticket') }}" class="nav-link {{ Route::is('admin.support-ticket') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>All Ticket</p>
                        </a>
                    </li>
                </ul>
            </li>          

            <li class="nav-item has-treeview {{ Route::is('admin.job-category') || Route::is('admin.spin-setting') || Route::is('admin.mail-configure') || Route::is('admin.job-sub-category') || Route::is('admin.continent') || Route::is('admin.zone') || Route::is('admin.country') || Route::is('admin.default-setup') || Route::is('admin.user-message') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-tools"></i>
                    <p> System Setting <i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.job-category') }}" class="nav-link {{ Route::is('admin.job-category') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Job Category</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.job-sub-category') }}" class="nav-link {{ Route::is('admin.job-sub-category') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Job Sub Category</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.continent') }}" class="nav-link {{ Route::is('admin.continent') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Continent</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.country') }}" class="nav-link {{ Route::is('admin.country') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Country</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.zone') }}" class="nav-link {{ Route::is('admin.zone') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Location Zone</p>
                        </a>
                    </li> 
                    
                    @if (Auth::user()->role_id == 1)
                        <li class="nav-item">
                            <a href="{{ route('admin.default-setup') }}" class="nav-link {{ Route::is('admin.default-setup') ? 'active' : '' }}">
                                <i class="fas fa-arrow-right nav-icon"></i>
                                <p>Default Setup</p>
                            </a>
                        </li>                   
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.spin-setting') }}" class="nav-link {{ Route::is('admin.spin-setting') ? 'active' : '' }}">
                                <i class="fas fa-arrow-right nav-icon"></i>
                                <p>Sping Setting</p>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('admin.user-message') }}" class="nav-link {{ Route::is('admin.user-message') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>User Message</p>
                        </a>
                    </li>
                </ul>
            </li>
            
            @if (Auth::user()->role_id == 1)
                <li class="nav-item has-treeview {{ Route::is('admin.boost-charge') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-dollar-sign"></i>
                        <p>
                            Boost Manage
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.boost-charge') }}" class="nav-link {{ Route::is('admin.boost-charge') ? 'active' : '' }}">
                                <i class="fas fa-arrow-right nav-icon"></i>
                                <p>Charge</p>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            <li class="nav-item has-treeview {{ Route::is('admin.boost-category') || Route::is('admin.boost-sub-category') || Route::is('admin.boost-package') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link"> <i class="nav-icon fas fa-dollar-sign"></i>
                    <p> Smm Service <i class="right fas fa-angle-left"></i> </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.boost-category') }}" class="nav-link {{ Route::is('admin.boost-category') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Category</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.boost-sub-category') }}" class="nav-link {{ Route::is('admin.boost-sub-category') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>Service</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.boost-package') }}" class="nav-link {{ Route::is('admin.boost-package') ? 'active' : '' }}">
                            <i class="fas fa-arrow-right nav-icon"></i>
                            <p>All Request</p>
                        </a>
                    </li>

                </ul>
            </li>
            <li class="nav-item has-treeview">
              <a class="nav-link" href="/super-admin/surveys">
                <i class="nav-icon fas fa-question"></i>
                <p>Survey Manage</p>
                </a>
            </li>
            <li>
    <a href="{{ route('admin.wu-marketplace-services') }}">
        <i class="fa fa-briefcase"></i>
        <span>Marketplace Services</span>
    </a>
</li>
<li>
    <a href="{{ route('admin.wu-marketplace-categories') }}">
        <i class="fa fa-tags"></i>
        <span>Marketplace Categories</span>
    </a>
</li>

            @if (Auth::user()->role_id == 1)
                <li class="nav-item">
                    <a href="{{ route('admin.website') }}" class="nav-link {{ Route::is('admin.website') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Setting</p>
                    </a>
                </li>
            @endif
            @if (Auth::user()->role_id == 1)
                <li class="nav-item">
                    <div class=""> 
                        <a href="{{ route('admin.communityRateSetup') }}" class="nav-link {{ Route::is('admin.communityRateSetup') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Community Rate</p>
                        </a>
                    </div>
                </li>
                <li class="nav-item">
                    <div class="lastoption"> 
                        <a href="{{ route('admin.cummunityPostList') }}" class="nav-link {{ Route::is('admin.cummunityPostList') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Community Post Manage</p>
                        </a>
                    </div>
                </li>
            @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
