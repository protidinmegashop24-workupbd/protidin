<style>
        
    body {
        font-family: 'Hind Siliguri', sans-serif !important;
    }

    h1, h2, h3, h4, h5, h6, p {
        font-family: 'Hind Siliguri', sans-serif !important;
    }

    p {
        font-family: "Hind Siliguri", sans-serif !important;
    }


    a {
        font-family: "Hind Siliguri", sans-serif !important;
    }

    @media only screen and (max-width: 767px) {
        .vertical-menu #sidebar-menu ul {
            padding-bottom: 100px; /* নিচে কিছু স্পেস */
        }
    }



    /* মেনুর প্যারেন্ট লিংক */
    .vertical-menu #sidebar-menu ul li a {
        color: #333 !important;
        padding: 12px 20px !important;
        display: block !important;
        transition: all 0.3s ease !important;
        font-size: 15px !important;
        background-color: transparent !important;
    }

    /* মেনুর হোভার এবং অ্যাকটিভ স্টাইল */
    .vertical-menu #sidebar-menu ul li:hover {
        background-color: #f0f0f0 !important;
    }

    .vertical-menu #sidebar-menu ul li a:hover {
        background-color: #24AC5C !important;
        color: #fff !important;
        border-radius: 5px !important;
    }

    /* প্যারেন্ট মেনু অ্যাকটিভ স্টাইল */
    .vertical-menu #sidebar-menu ul li.active > a {
        background-color: #24AC5C !important;
        color: #fff !important;
        border-radius: 5px !important;
    }

    /* প্যারেন্ট মেনুতে হোভার ইফেক্ট */
    .vertical-menu #sidebar-menu ul li.active:hover {
        background-color: #1f8a4a !important;
    }

    /* সাবমেনুর স্টাইল */
    .vertical-menu #sidebar-menu ul li .sub-menu a {
        padding: 10px 20px !important;
        color: #555 !important;
        background-color: #f9f9f9 !important;
    }

    /* সাবমেনুর হোভার স্টাইল */
    .vertical-menu #sidebar-menu ul li .sub-menu a:hover {
        background-color: #24AC5C !important;
        color: #fff !important;
    }

    /* সাবমেনু অ্যাকটিভ স্টাইল (My Applied Task ক্লিক করলে) */
    .vertical-menu #sidebar-menu ul li .sub-menu li.active a {
        background-color: #1f8a4a !important; /* সবুজ রঙ */
        color: #fff !important; /* সাদা টেক্সট */
    }

    /* প্যারেন্ট মেনুর অ্যাকটিভ স্টেট ঠিক থাকবে */
    .vertical-menu #sidebar-menu ul li.active > a,
    .vertical-menu #sidebar-menu ul li.active .has-arrow {
        background-color: #24AC5C !important; /* প্যারেন্ট মেনু ব্যাকগ্রাউন্ড */
        color: #fff !important; /* প্যারেন্ট মেনু টেক্সট */
    }

</style>
<div class="vertical-menu">
    <div class="text-center pt-2 mbl-logo">
        <a href="{{ route('user.dashboard') }}">
            <span>
                <img src="{{ URL::to(website_favicon()) }}" alt="" height="40"><span class="site-title">{{site_info()->title}}</span>
            </span>
        </a>
    </div>
    
    <span class="sidebar-close" onclick="hideSidebar()">
       <i class="ti-close"></i>
    </span>
    
    <div data-simplebar class="h-100">

        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                
                
                
                
                @if(site_info()->instanat_verify_active == 1 && Auth::user()->is_verified == 0)
                    <li>
                        <a href="{{ route('user.account-instant-verify') }}" class=" waves-effect" style="background: #24AC5C !important; border-radius: 5px !important; color: #fff !important;">
                            <i class="fas fa-question-circle" style="color: #fff !important;"></i>
                            <span>Verify Account @if(Auth::user()->kyc_status == 'unapprove')<span style="background:red;color:white;padding:5px;border-radius: 100%">1</span>@endif</span>
                        </a>
                    </li>
                @endif

                
                



 <li>
                    <a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.profile') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif class=" waves-effect">
                        <i class="ti-layout-accordion-list"></i>
                        <span>Dashboard</span>
                    </a>

<li class="@if(Route::is('user.marketplace.services')) active @endif">
    <a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.marketplace.services') }} @endif"
       @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif>
        <i class="fas fa-briefcase"></i>
        <span>Brouse Services</span>
    </a>
</li>

                <li>
                    <a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.find-job') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif class=" waves-effect">
                        <i class="fas fa-search-dollar"></i>
                        <span>Find Job</span>
                    </a>
                </li>
                
                
                <li class="@if(Route::Is('user.ptcList')) active @endif">
                    <a href="{{route('user.ptcList')}}">
                        <i class="fas fa-search-dollar"></i>
                        <span>Find PTC Job</span>
                    </a>
                </li> 
                
                <li class="@if(Route::Is('surveys.index')) active @endif">
                    <a href="{{route('surveys.index')}}">
                        <i class="fas fa-question"></i>
                        <span>Start Surveys</span>
                    </a>
                </li>
                
                <li>
                    <a 
                        href="
                            @if(Auth::user()->status == 0) 
                                javascript:; 
                            @else 
                                {{ route('user.communityEarn') }} 
                            @endif" 
                        @if(Auth::user()->status == 0)
                            onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" 
                        @endif 
                        class=" waves-effect">

                        <i class="fas fa-comment-dollar"></i>
                        <span>Earn Community</span>
                    </a>
                </li>

<li>
    <a href="javascript: void(0);" class="has-arrow waves-effect">
        <i class="fas fa-briefcase"></i>
        <span>Marketplace</span>
        @php
            $marketUnread = wu_marketplace_unread_inquiries() + wu_marketplace_unread_order_messages();
        @endphp
        @if($marketUnread > 0)
            <span class="badge bg-danger rounded-pill ms-2">{{ $marketUnread }}</span>
        @endif
    </a>
    <ul class="sub-menu" aria-expanded="false">

        <li class="@if(Route::is('user.marketplace')) active @endif">
            <a href="{{ route('user.marketplace') }}">
                <span>Service Panel</span>
            </a>
        </li>

        <li class="@if(Route::is('user.marketplace.inquiries')) active @endif">
            <a href="{{ route('user.marketplace.inquiries') }}">
                <span>Service Chat</span>
                @if(wu_marketplace_unread_inquiries() > 0)
                    <span class="badge bg-danger rounded-pill ms-2">{{ wu_marketplace_unread_inquiries() }}</span>
                @endif
            </a>
        </li>

        <li class="@if(Route::is('user.marketplace.orders')) active @endif">
            <a href="{{ route('user.marketplace.orders') }}">
                <span>My Orders</span>
                @if(wu_marketplace_unread_order_messages() > 0)
                    <span class="badge bg-danger rounded-pill ms-2">{{ wu_marketplace_unread_order_messages() }}</span>
                @endif
            </a>
        </li>

        <li class="@if(Route::is('user.marketplace.sales')) active @endif">
            <a href="{{ route('user.marketplace.sales') }}">
                <span>My Sales</span>
            </a>
        </li>

        <li class="@if(Route::is('user.marketplace.create')) active @endif">
            <a href="{{ route('user.marketplace.create') }}">
                <span>Sell Service</span>
            </a>
        </li>

        <li class="@if(Route::is('user.marketplace.my_services')) active @endif">
            <a href="{{ route('user.marketplace.my_services') }}">
                <span>My Services</span>
            </a>
        </li>

    </ul>
</li>

                {{-- Community Handle  Start --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-comment-dots"></i>
                        <span>Community Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">                       
    
                        <li class="@if(Route::Is('user.postFeedDashboard')) active @endif">
                            <a href="{{route('user.postFeedDashboard')}}">
                                <!--<i class="fas fa-plus-circle"></i>-->
                                <span>Social Panel</span>
                            </a>
                        </li>
                         
                        <li class="@if(Route::Is('user.myPostFeedList')) active @endif">
                            <a href="{{route('user.myPostFeedList')}}">
                                <!--<i class="fas fa-plus-circle"></i>-->
                                <span>Post History</span>
                            </a>
                        </li>
                        <li class="@if(Route::Is('user.communityEarnRate')) active @endif">
                            <a href="{{route('user.communityEarnRate')}}">
                                <!--<i class="fas fa-plus-circle"></i>-->
                                <span>Earn Rate</span>
                            </a>
                        </li>

                    </ul>
                </li>
                {{-- Community Handle End --}}
                
                    {{-- PTC Handle Start  --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-eye"></i>
                        <span>Job Post Management</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        
                        <li class="@if(Route::Is('user.job-create')) active @endif">
                            <a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.job-create') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif class=" waves-effect">
                                <!--<i class="fas fa-plus-circle"></i>-->
                                <span>Post New Job</span>
                            </a>
                        </li>
                         
                        <li class="@if(Route::Is('user.ptcAdd')) active @endif">
                            <a href="{{route('user.ptcAdd')}}">
                                <!--<i class="fas fa-plus-circle"></i>-->
                                <span>Post PTC Job</span>
                            </a>
                        </li> 
                        
                        <li class="@if(Route::Is('user.job')) active @endif">
                            <a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.job') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif class="waves-effect">
                                <!--<i class="ti-bag"></i>-->
                                <span>My Job</span>
                            </a>
                        </li>
                        
                        <li class="@if(Route::Is('user.myRunning')) active @endif">
                            <a href="{{route('user.myRunning')}}">
                                Posted Running Job
                            </a>
                        </li> 
                        <li class="@if(Route::Is('user.myPostedJobHistory')) active @endif">
                            <a href="{{route('user.myPostedJobHistory')}}">
                                Posted History
                            </a>
                        </li>
 
                    </ul>
                </li>
                    {{-- PTC Handle End  --}}
                
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-eye"></i>
                        <span>My Worked Task</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li class="@if(Route::Is('user.worked-job')) active @endif">
                            <a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.worked-job') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif>
                                <!--<i class="ti-list"></i>-->
                                <span>My Applied Task<span>
                            </a>
                        </li>
                            <!--<li><a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.complete-worked-job') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif>Accepted Workers</a></li>-->

                        <li class="@if(Route::Is('user.ptcEarned')) active @endif">
                            <a href="{{route('user.ptcEarned')}}">
                                PTC Earn History
                            </a>
                        </li>
                    </ul>
                </li>
                
                
                
                
                
                
                
                
                
                
                @if(site_info()->smm_service_active == 1)
                 <li>
                    <a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.boost') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif class=" waves-effect">
                        <i class="fa fa-server" aria-hidden="true"></i>
                        <span>Smm Service</span>
                    </a>
                </li>
                @endif
                
                
                


                
                

    

                
                
                


                <li>
                    <a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.message-list') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif class="waves-effect @if(user_message_seen(Auth::user()->id) > 0) text-warning @endif">
                        <i class="mdi mdi-bell @if(user_message_seen(Auth::user()->id) > 0) text-warning @endif"></i>
                        <span>Notification</span>
                    </a>
                </li>
                
                
                
                
                  
                @if(site_info()->balance_transfer_active == 1)
                 <li>
                    <a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.earning-to-deposit') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif class=" waves-effect">
                       <i class="mdi mdi-transfer font-size-17 align-middle me-1"></i>Balance Transfer
                    </a>
                </li>
                
                 @endif
                
                
                

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-credit-card"></i>
                        <span>Deposit</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.deposit') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif>Manual Deposit</a>
                        </li>                        
                        
                        
                
                
                        @php
                            $bkashSettings = \App\Models\BkashSetting::first(); // bKash settings থেকে স্ট্যাটাস যাচাই
                        @endphp
                        @if($bkashSettings && $bkashSettings->status == 1)
                            <li>
                                @php
                                    $bkashSettings = \App\Models\BkashSetting::first(); // bKash settings থেকে স্ট্যাটাস যাচাই
                                @endphp

                                <a href="
                                    @if($bkashSettings && $bkashSettings->status == 0) 
                                        javascript:;
                                    @else 
                                        {{ url('/user/instant-deposit') }} 
                                    @endif" 
                                    @if($bkashSettings && $bkashSettings->status == 0) 
                                        onclick="return alert('bKash payment is currently disabled! Please try again later or contact support.')" 
                                    @endif>
                                    Instant Deposit
                                </a>
                            </li>
                        @endif




                        
                        
                        
                        
                    </ul>
                </li>



                <li>
                    <a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.new-withdraw-request') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif class=" waves-effect">
                        <i class="fas fa-download"></i>
                        <span>Withdraw</span>
                    </a>
                </li>

                @if(spin_setting()->status == 1)
                    <li>
                        <a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.spin') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif class=" waves-effect">
                            <i class="fa fa-trophy"></i>
                            <span>Play Spin Earn</span>
                        </a>
                    </li>
                @endif

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-align-center"></i>
                        <span>Transaction</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.withdraw') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif>Withdraw List</a></li>
                        <li><a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.deposit-list') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif>Deposit List</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fas fa-ad"></i>
                        <span>Advertisement</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.advertisement') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif>New Advertisement</a></li>
                        <li><a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.advertisement-list') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif>Advertisement List</a></li>
                    </ul>
                </li>
                
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="fa fa-medkit"></i>
                        <span>Support Ticket</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.support-ticket-create') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif>Create Ticket</a></li>
                        <li><a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.support-ticket') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif>Ticket History</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>