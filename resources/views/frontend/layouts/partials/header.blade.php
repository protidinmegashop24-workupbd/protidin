<style>
    .pace {
        -webkit-pointer-events: none;
        pointer-events: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    .pace-inactive {
        display: none;
    }

    .pace .pace-progress {
        background: #07c10d;
        position: fixed;
        z-index: 2000;
        top: 0;
        right: 100%;
        width: 100%;
        height: 2px;
    }

    .page-wrapper {
        padding: 0 !important;
    }

    .adsArea {
        padding-top: 70px;
        text-align: center;
    }

    .advertising {
        display: block !important;
    }

    .advertising img {
        max-width: 100%;
    }

    @media (min-width: 1024px) {
        .adsArea {
            margin-left: 220px;
        }
    }

    @media (min-width: 768px) {
        .mini-sidebar .adsArea {
            margin-left: 70px;
        }
    }

    .wu-navbar {
        background: #f4fff8 !important;
        border-bottom: 1px solid #22ab59;
        padding: 12px 0;
    }

    .wu-navbar .navbar-brand img {
        width: 80px;
        height: auto;
    }

    .wu-navbar .navbar-nav {
        align-items: center;
        gap: 10px;
    }

    .wu-navbar .nav-item {
        list-style: none;
    }

    .wu-navbar .nav-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 44px;
        padding: 10px 16px !important;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        color: #111 !important;
        border: 1px solid #198754;
        background: #fff;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .wu-navbar .nav-link:hover,
    .wu-navbar .nav-link.active {
        background: #172b4d;
        color: #fff !important;
        border-color: #172b4d;
    }

    .wu-navbar .nav-link i {
        font-size: 14px;
    }

    .wu-navbar .nav-link.register-btn {
        background: #46d08b;
        color: #fff !important;
        border-color: #46d08b;
    }

    .wu-navbar .nav-link.register-btn:hover {
        background: #198754;
        border-color: #198754;
        color: #fff !important;
    }

    .wu-navbar .navbar-toggler {
        border: none;
        outline: none;
        box-shadow: none;
    }

    .wu-navbar .navbar-toggler i {
        color: #555;
        font-size: 22px;
    }

    @media (max-width: 991px) {
        .wu-navbar .navbar-collapse {
            margin-top: 14px;
        }

        .wu-navbar .navbar-nav {
            align-items: stretch;
            gap: 10px;
        }

        .wu-navbar .nav-link {
            width: 100%;
            justify-content: flex-start;
        }
    }
</style>

<nav class="navbar navbar-expand-lg wu-navbar">
    <div class="container">
        <a href="{{ url('/') }}" class="navbar-brand">
            <img src="{{ URL::to(website_logo()) }}" class="light-logo" alt="Protidin Mega Earn Logo">
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars"></i>
        </button>

        <div id="navbarCollapse" class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                        <i class="fa fa-home" aria-hidden="true"></i>
                        <span>Home</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('/blog') }}" class="nav-link {{ request()->is('blog') ? 'active' : '' }}">
                        <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                        <span>Blog</span>
                    </a>
                <li class="nav-item">
    <a href="{{ route('surveys.home') }}" class="nav-link {{ request()->is('surveys-home') ? 'active' : '' }}">
        <i class="fa fa-list-alt" aria-hidden="true"></i>
        <span>Surveys</span>
    </a>
</li>

                <li class="nav-item">
    <a href="{{ route('marketplace') }}" 
       class="nav-link {{ request()->routeIs('marketplace') || request()->routeIs('marketplace.service.show') ? 'active' : '' }}">
        
        <i class="fa fa-briefcase" aria-hidden="true"></i>
        <span>Marketplace</span>
    </a>
</li>

                <li class="nav-item">
                    <a href="{{ url('/about') }}" class="nav-link {{ request()->is('about') ? 'active' : '' }}">
                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                        <span>About</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('/contact') }}" class="nav-link {{ request()->is('contact') ? 'active' : '' }}">
                        <i class="fa fa-envelope" aria-hidden="true"></i>
                        <span>Contact</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <span>Login</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('register') }}" class="nav-link register-btn">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <span>Register</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>