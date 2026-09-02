<style>
    .wu-footer {
        background: linear-gradient(180deg, #0f172a 0%, #132238 100%);
        color: #e2e8f0;
        padding: 70px 0 0;
        font-family: 'Hind Siliguri', sans-serif !important;
    }

    .wu-footer h4 { color: #fff; }

    .wu-footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .wu-footer-links li {
        margin-bottom: 12px;
    }

    .wu-footer-links a {
        color: #cbd5e1;
        text-decoration: none;
        transition: 0.3s;
    }

    .wu-footer-links a:hover {
        color: #46d08b;
        transform: translateX(4px);
    }

    .wu-footer-links i {
        margin-right: 6px;
        color: #46d08b;
    }

    .wu-footer-bottom {
        background: #132238;
        border-top: 1px solid rgba(255,255,255,0.08);
        margin-top: 10px;
        padding: 18px 0;
        text-align: center;
    }
</style>

<footer class="wu-footer">
    <div class="container">
        <div class="row">

            <!-- Brand -->
            <div class="col-lg-4 col-md-6">
                <a href="{{ url('/') }}">
                    <img src="{{ URL::to(website_logo()) }}" width="90">
                </a>

                <h4>{{ website_title() }}</h4>

                <p>
                    Workup BD is a modern micro job and freelance services platform where users can explore digital tasks, surveys, services, and referral-based opportunities.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h4>Quick Links</h4>
                <ul class="wu-footer-links">

                    <li><a href="{{ url('/') }}"><i class="fa fa-angle-right"></i> Home</a></li>

                    <li><a href="{{ url('/blog') }}"><i class="fa fa-angle-right"></i> Blog</a></li>

                    <li><a href="{{ url('/surveys') }}"><i class="fa fa-angle-right"></i> Surveys</a></li>

                    <li><a href="{{ url('/user/marketplace/services') }}"><i class="fa fa-angle-right"></i> Marketplace</a></li>
                    <li>
                        <a href="{{ route('login') }}">
                            <i class="fa fa-angle-right"></i> Login
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('register') }}">
                            <i class="fa fa-angle-right"></i> Register
                        </a>
                    </li>

                </ul>
            </div>

            <!-- Company -->
            <div class="col-lg-3 col-md-6">
                <h4>Company</h4>
                <ul class="wu-footer-links">

                    <li><a href="{{ url('/about') }}"><i class="fa fa-angle-right"></i> About Us</a></li>

                    <li><a href="{{ url('/contact') }}"><i class="fa fa-angle-right"></i> Contact</a></li>

                    <li><a href="https://workupbd.com/policy-details/faq"><i class="fa fa-angle-right"></i> FAQ</a></li>

                </ul>
            </div>

            <!-- Legal -->
            <div class="col-lg-3 col-md-6">
                <h4>Legal</h4>
                <ul class="wu-footer-links">

                    <li>
                        <a href="https://workupbd.com/policy-details/privacy-policy">
                            <i class="fa fa-angle-right"></i> Privacy Policy
                        </a>
                    </li>

                    <li>
                        <a href="https://workupbd.com/policy-details/terms-condition">
                            <i class="fa fa-angle-right"></i> Terms & Conditions
                        </a>
                    </li>

                    <li>
                        <a href="https://workupbd.com/policy-details/refund-policy">
                            <i class="fa fa-angle-right"></i> Refund Policy
                        </a>
                    </li>

                    <li>
                        <a href="https://workupbd.com/policy-details/marketplace-policy">
                            <i class="fa fa-angle-right"></i> Marketplace Policy
                        </a>
                    </li>
                    
                    <li>
                        <a href="https://workupbd.com/policy-details/buyer-protection">
                            <i class="fa fa-angle-right"></i> Buyer Protection
                        </a>
                    </li>
                    
                    <li>
                        <a href="https://workupbd.com/policy-details/seller-policy">
                            <i class="fa fa-angle-right"></i> Seller Policy
                        </a>
                    </li>

                </ul>
            </div>

        </div>
    </div>

    <div class="wu-footer-bottom">
        <p>
            © {{ date('Y') }} {{ website_title() }}. All rights reserved.
        </p>
    </div>
</footer>