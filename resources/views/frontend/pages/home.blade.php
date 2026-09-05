@extends('frontend.layouts.master')

@section('css')
    <meta name="monetag" content="590ea0442b3f6eaf6b40437e8a6ee8d5">
    <meta name="7072e592928aec912817a3ff684e03124aa0853c" content="7072e592928aec912817a3ff684e03124aa0853c" />
    <script src="https://5gvci.com/act/files/tag.min.js?z=11563748" data-cfasync="false" async></script>
    <meta name='admaven-placement' content=BqHkGrdsG>
    <meta name="google-adsense-account" content="ca-pub-6314276342535503">
    <meta name="subject" content="Modern micro job and freelance services marketplace">
    <meta name="title" content="Home - {{ $website->title ?? 'Protidin Mega Earn' }}">
    <meta name="description" content="{{ $website->meta_tag ?? 'Protidin Mega Earn is a modern micro job and freelance services marketplace where users can explore online tasks, service-based opportunities, survey activities, and referral rewards in one organized platform. Businesses can also connect with active individuals for small projects and digital support.' }}">
    <meta name="keywords" content="{{ $website->meta_keyword ?? 'micro jobs, freelance services, protidin mega earn, online tasks, referral rewards, survey tasks,learning online earning' }}">
    <meta name="author" content="Protidin Mega Earn">
    <meta name="copyright" content="Protidin Mega Earn">
    <script>(function(s){s.dataset.zone='11563878',s.src='https://nap5k.com/tag.min.js'})([document.documentElement, document.body].filter(Boolean).pop().appendChild(document.createElement('script')))</script>
    <link rel="canonical" href="{{ url('/') }}" />
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KC7GJWPT');</script>
<!-- End Google Tag Manager -->
    <style>
        :root{
            --primary:#0f766e;
            --primary-dark:#115e59;
            --secondary:#f59e0b;
            --dark:#0f172a;
            --muted:#64748b;
            --light:#f8fafc;
            --white:#ffffff;
            --border:#e2e8f0;
        }

        body, h1, h2, h3, h4, h5, h6, p, span, a, li {
            font-family: 'Hind Siliguri', sans-serif !important;
        }

        .wu-section{
            padding: 72px 0;
        }

        .wu-hero{
            background: linear-gradient(135deg, #ecfeff 0%, #f8fafc 45%, #ffffff 100%);
            padding: 70px 0 60px;
            position: relative;
            overflow: hidden;
        }

        .wu-badge{
            display:inline-block;
            background:#ccfbf1;
            color:var(--primary-dark);
            font-weight:700;
            font-size:14px;
            padding:8px 14px;
            border-radius:999px;
            margin-bottom:18px;
        }

        .wu-title{
            font-size: 3.2rem;
            line-height: 1.12;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 18px;
        }

        .wu-title span{
            color: var(--primary);
        }

        .wu-subtitle{
            font-size: 1.06rem;
            line-height: 1.9;
            color: var(--muted);
            max-width: 620px;
            margin-bottom: 24px;
        }

        .wu-hero-list{
            padding:0;
            margin:0 0 28px 0;
            list-style:none;
        }

        .wu-hero-list li{
            margin-bottom:10px;
            color:var(--dark);
            font-size:16px;
            font-weight:600;
        }

        .wu-hero-list i{
            color:var(--primary);
            margin-right:10px;
        }

        .wu-btn{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            padding:14px 24px;
            border-radius:12px;
            text-decoration:none !important;
            font-weight:700;
            transition:.3s ease;
        }

        .wu-btn-primary{
            background:var(--primary);
            color:#fff !important;
        }

        .wu-btn-primary:hover{
            background:var(--primary-dark);
            transform:translateY(-2px);
        }

        .wu-btn-outline{
            border:1px solid var(--primary);
            color:var(--primary) !important;
            background:#fff;
        }

        .wu-btn-outline:hover{
            background:#f0fdfa;
            transform:translateY(-2px);
        }

        .wu-hero-image-wrap{
            position:relative;
            text-align:center;
        }

        .wu-hero-card{
            background:#fff;
            border:1px solid var(--border);
            border-radius:28px;
            padding:18px;
            box-shadow:0 20px 45px rgba(15, 23, 42, 0.08);
        }

        .wu-hero-card img{
            width:100%;
            border-radius:20px;
            object-fit:cover;
        }

        .wu-mini-box{
            position:absolute;
            background:#fff;
            border-radius:18px;
            box-shadow:0 14px 30px rgba(15,23,42,.12);
            padding:12px 16px;
            font-size:14px;
            font-weight:700;
            color:var(--dark);
            border:1px solid var(--border);
        }

        .wu-mini-box.top{
            top:20px;
            left:-10px;
        }

        .wu-mini-box.bottom{
            bottom:15px;
            right:-10px;
        }

        .wu-stats{
            background:var(--dark);
            padding:30px 0;
        }

        .wu-stat-card{
            background:#fff;
            border-radius:20px;
            padding:26px 18px;
            text-align:center;
            height:100%;
            box-shadow:0 10px 25px rgba(0,0,0,.08);
        }

        .wu-stat-card img{
            max-width:54px;
            margin-bottom:12px;
        }

        .wu-stat-card h3{
            font-size:2rem;
            font-weight:800;
            margin-bottom:6px;
            color:var(--dark);
        }

        .wu-stat-card p{
            margin:0;
            color:var(--muted);
            font-weight:600;
        }

        .wu-section-title{
            font-size:2.2rem;
            font-weight:800;
            color:var(--dark);
            margin-bottom:14px;
        }

        .wu-section-text{
            color:var(--muted);
            line-height:1.9;
            max-width:720px;
            margin:0 auto 18px;
        }

        .wu-feature-card{
            background:#fff;
            border:1px solid var(--border);
            border-radius:22px;
            padding:28px 22px;
            height:100%;
            transition:.3s ease;
            box-shadow:0 10px 30px rgba(15,23,42,.05);
        }

        .wu-feature-card:hover,
        .wu-job-card:hover,
        .wu-service-card:hover{
            transform:translateY(-5px);
            box-shadow:0 20px 40px rgba(15,23,42,.09);
        }

        .wu-feature-icon{
            width:58px;
            height:58px;
            border-radius:16px;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#ecfeff;
            color:var(--primary);
            font-size:24px;
            margin-bottom:16px;
        }

        .wu-feature-card h4{
            font-size:1.2rem;
            font-weight:800;
            color:var(--dark);
            margin-bottom:10px;
        }

        .wu-feature-card p{
            color:var(--muted);
            line-height:1.8;
            margin:0;
        }

        .wu-job-section{
            background:#f8fafc;
        }

        .wu-job-card{
            background:#fff;
            border:1px solid var(--border);
            border-radius:20px;
            padding:20px;
            margin-bottom:18px;
            transition:.3s ease;
        }

        .wu-job-time{
            font-size:13px;
            color:var(--muted);
            text-align:right;
            margin-bottom:8px;
        }

        .wu-job-card h5{
            color:var(--dark);
            font-weight:800;
            margin-bottom:10px;
            font-size:1.15rem;
        }

        .wu-job-meta{
            font-size:14px;
            color:var(--muted);
            margin-bottom:10px;
        }

        .wu-job-price{
            color:var(--primary);
            font-weight:800;
            font-size:1.2rem;
            text-align:right;
        }

        .progress{
            background:#e2e8f0;
            height:8px;
            border-radius:999px;
        }

        .progress-bar{
            background:var(--secondary) !important;
            border-radius:999px;
        }

        .wu-service-card{
            background:#fff;
            border:1px solid var(--border);
            border-radius:22px;
            padding:22px;
            height:100%;
            transition:.3s ease;
            box-shadow:0 10px 30px rgba(15,23,42,.05);
        }

        .wu-service-card img{
            max-height:64px;
            margin-bottom:16px;
        }

        .wu-service-card h4{
            font-size:1.15rem;
            font-weight:800;
            color:var(--dark);
            margin-bottom:10px;
        }

        .wu-service-card p{
            color:var(--muted);
            line-height:1.8;
            margin:0;
        }

        .wu-alt-section{
            background:#ffffff;
        }

        .wu-soft-section{
            background:#f8fafc;
        }

        .wu-cta{
            padding: 0 0 80px;
        }

        .wu-cta-box{
            background: linear-gradient(135deg, #0f766e 0%, #134e4a 100%);
            border-radius:28px;
            padding:50px 30px;
            text-align:center;
            color:#fff;
            overflow:hidden;
            position:relative;
        }

        .wu-cta-box h2{
            font-size:2.1rem;
            font-weight:800;
            margin-bottom:14px;
            color:#fff;
        }

        .wu-cta-box p{
            max-width:720px;
            margin:0 auto 24px;
            line-height:1.9;
            color:#e6fffb;
        }

        .wu-cta-box .wu-btn-primary{
            background:#fff;
            color:var(--primary) !important;
        }

        .wu-cta-box .wu-btn-primary:hover{
            background:#ecfeff;
        }

        @media(max-width:991px){
            .wu-title{
                font-size:2.45rem;
            }

            .wu-hero-image-wrap{
                margin-top:30px;
            }

            .wu-mini-box{
                position:static;
                display:inline-block;
                margin:10px 6px 0;
            }
        }

        @media(max-width:767px){
            .wu-section{
                padding:55px 0;
            }

            .wu-hero{
                padding:50px 0 40px;
            }

            .wu-title{
                font-size:2rem;
            }

            .wu-section-title{
                font-size:1.7rem;
            }

            .wu-btn{
                width:100%;
                margin-bottom:12px;
            }

            .wu-btn-group{
                display:block !important;
            }
}
        }
    </style>
    <style>
.home-marketplace-promo{
    padding: 85px 0;
    background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
    overflow: hidden;
}
.hmp-badge{
    display: inline-block;
    background: #e8f7ee;
    color: #15803d;
    font-size: 13px;
    font-weight: 700;
    padding: 8px 14px;
    border-radius: 999px;
    margin-bottom: 18px;
}
.hmp-title{
    font-size: 40px;
    font-weight: 800;
    line-height: 1.2;
    color: #0f172a;
    margin-bottom: 16px;
}
.hmp-text{
    font-size: 16px;
    line-height: 1.9;
    color: #64748b;
    margin-bottom: 22px;
    max-width: 580px;
}
.hmp-points{
    list-style: none;
    padding: 0;
    margin: 0 0 28px;
}
.hmp-points li{
    font-size: 16px;
    color: #1e293b;
    margin-bottom: 10px;
}
.hmp-btns{
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.hmp-btn-primary{
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 26px;
    border-radius: 12px;
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: #fff !important;
    font-weight: 700;
    text-decoration: none !important;
    box-shadow: 0 14px 28px rgba(22, 163, 74, 0.22);
    transition: all .25s ease;
}
.hmp-btn-primary:hover{
    transform: translateY(-2px);
    box-shadow: 0 18px 32px rgba(22, 163, 74, 0.28);
}
.hmp-image-wrap{
    text-align: center;
}
.hmp-image{
    max-width: 100%;
    height: auto;
    border-radius: 24px;
    box-shadow: 0 20px 42px rgba(15,23,42,.10);
}
@media (max-width: 991px){
    .hmp-title{
        font-size: 30px;
    }
    .home-marketplace-promo{
        padding: 70px 0;
    }
}
@media (max-width: 575px){
    .hmp-title{
        font-size: 26px;
    }
}
</style>
@endsection

@section('front-content')

<section class="wu-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="wu-badge">Micro Jobs • Freelance Services • Digital Opportunities</span>
                <h1 class="wu-title">
                    Build Skills and Explore <span>Real Online Opportunities</span>
                </h1>
                
                <script>
(function(bwirid){
var d = document,
    s = d.createElement('script'),
    l = d.scripts[d.scripts.length - 1];
s.settings = bwirid || {};
s.src = "\/\/conventionalresponse.com\/brXJVzsrd.GjlQ0\/YNWbcf\/eeyme9Zu\/ZTU\/lnkaPnTccuzbM_TSYK5vN\/zfMztdNFzYMBx\/NzjbkG3pNHwx";
s.async = true;
s.referrerPolicy = 'no-referrer-when-downgrade';
l.parentNode.insertBefore(s, l);
})({})
</script>

                <p class="wu-subtitle">
                    Protidin Mega Earn is a modern micro job and freelance services marketplace where users can explore online tasks, service-based opportunities, survey activities, and referral rewards in one organized platform. Businesses can also connect with active individuals for small projects and digital support.
                </p>

                <ul class="wu-hero-list">
                    <li><i class="fas fa-check-circle"></i>Explore beginner-friendly micro tasks and service opportunities</li>
                    <li><i class="fas fa-check-circle"></i>Take part in survey and quiz activities</li>
                    <li><i class="fas fa-check-circle"></i>Grow with referral rewards and platform engagement</li>
                </ul>

                <div class="d-flex flex-wrap wu-btn-group" style="gap:12px;">
                    <a href="{{ route('register') }}" class="wu-btn wu-btn-primary">
                        <i class="fas fa-user-plus"></i> Create Account
                    </a>
                    <a href="{{ route('login') }}" class="wu-btn wu-btn-outline">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a href="{{ url('/blog') }}" class="wu-btn wu-btn-outline">
                        <i class="fas fa-blog"></i> Visit Learning Area
                    </a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="wu-hero-image-wrap">
                    <div class="wu-hero-card">
                        <img src="https://workupbd.com/blog/wp-content/uploads/2026/04/workupbd-main-home.png" alt="Protidin Mega Earn Marketplace" loading="lazy">
                    </div>
                    <div class="wu-mini-box top">Task-Based Digital Platform</div>
                    <div class="wu-mini-box bottom">Structured Work • Active Community</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="wu-stats">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="wu-stat-card">
                    <img src="{{ URL::to($aboutus->total_job_icon) }}" alt="Total Jobs" loading="lazy">
                    <h3>
                        @if($aboutus->total_job_status == 1)
                            @if($aboutus->total_job_manual_show == 1)
                                {{ $aboutus->total_job }}
                            @else
                                {{ total_job() }}
                            @endif
                        @endif
                    </h3>
                    <p>{{ strip_tags($aboutus->total_job_title) }}</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="wu-stat-card">
                    <img src="{{ URL::to($aboutus->total_user_icon) }}" alt="Total Users" loading="lazy">
                    <h3>
                        @if($aboutus->total_user_status == 1)
                            @if($aboutus->total_user_manual_show == 1)
                                {{ $aboutus->total_user }}
                            @else
                                {{ total_user() }}
                            @endif
                        @endif
                    </h3>
                    <p>{{ strip_tags($aboutus->total_user_title) }}</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="wu-stat-card">
                    <img src="{{ URL::to($aboutus->totle_work_done_icon) }}" alt="Completed Work" loading="lazy">
                    <h3>
                        @if($aboutus->totle_work_done_status == 1)
                            @if($aboutus->totle_work_done_manual_show == 1)
                                {{ $aboutus->totle_work_done }}
                            @else
                                {{ totle_work_done() }}
                            @endif
                        @endif
                    </h3>
                    <p>{{ strip_tags($aboutus->totle_work_done_title) }}</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="wu-stat-card">
                    <img src="{{ URL::to($aboutus->total_withdraw_icon) }}" alt="Total Withdraw" loading="lazy">
                    <h3>
                        @if($aboutus->total_withdraw_status == 1)
                            @if($aboutus->paid_tast_manual_show == 1)
                                {{ $aboutus->total_withdraw }}
                            @else
                                {{ total_withdraw() }}
                            @endif
                        @endif
                    </h3>
                    <p>{{ strip_tags($aboutus->total_withdraw_title) }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="wu-section wu-alt-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="wu-section-title">Why People Choose Protidin Mega Earn</h2>
            <p class="wu-section-text">
                Protidin Mega Earn is designed for users who want a clean and practical way to participate in digital tasks, discover service opportunities, and stay active in a structured online work environment.
            </p>

        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="wu-feature-card">
                    <div class="wu-feature-icon"><i class="fas fa-briefcase"></i></div>
                    <h4>Micro Job Opportunities</h4>
                    <p>Browse task-based opportunities designed for users who want to stay active and take part in practical digital work.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="wu-feature-card">
                    <div class="wu-feature-icon"><i class="fas fa-users"></i></div>
                    <h4>Freelance Services</h4>
                    <p>Businesses and individuals can connect with capable workers for small projects, creative support, and online services.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="wu-feature-card">
                    <div class="wu-feature-icon"><i class="fas fa-wallet"></i></div>
                    <h4>Simple User Flow</h4>
                    <p>From account creation to task participation, the platform is built to feel clear, organized, and easy to use.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="wu-feature-card">
                    <div class="wu-feature-icon"><i class="fas fa-poll-h"></i></div>
                    <h4>Survey & Quiz Tasks</h4>
                    <p>Take part in survey and quiz activities that add variety to the platform and create more interactive user participation.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="wu-feature-card">
                    <div class="wu-feature-icon"><i class="fas fa-user-friends"></i></div>
                    <h4>Referral Rewards</h4>
                    <p>Invite others to join Protidin Mega Earn and expand your activity through a straightforward referral-based reward system.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="wu-feature-card">
                    <div class="wu-feature-icon"><i class="fas fa-shield-alt"></i></div>
                    <h4>Structured Platform Experience</h4>
                    <p>Our goal is to keep the platform organized, transparent, and user-focused for both workers and task publishers.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="home-marketplace-promo">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="hmp-content">
                    <span class="hmp-badge">Protidin Mega Earn Marketplace</span>
                    <h2 class="hmp-title">Buy and Sell Digital Services With Confidence</h2>
                    <p class="hmp-text">
                        Explore Protidin Mega Earn Marketplace to discover digital services, connect with active sellers,
                        chat before ordering, and manage service-based work through a structured platform experience.
                    </p>

                    <ul class="hmp-points">
                        <li>✔ Browse live digital services</li>
                        <li>✔ Chat with sellers before order</li>
                        <li>✔ Safe order flow inside the platform</li>
                        <li>✔ Suitable for both buyers and sellers</li>
                    </ul>

                    <div class="hmp-btns">
                        <a href="{{ route('marketplace') }}" class="hmp-btn-primary">
                            Explore Marketplace
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 text-center">
                <div class="hmp-image-wrap">
                    <img src="https://workupbd.com/blog/wp-content/uploads/2026/04/Marketplace-homepage-imege.png" alt="Protidin Mega Earn Marketplace" class="hmp-image">
                </div>
            </div>

        </div>
    </div>
</section>

<section class="wu-section wu-job-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="wu-section-title">Latest Job Opportunities</h2>
            <p class="wu-section-text">
                Explore recent task listings published on the platform. Registered users can access more features and participate in available opportunities.
            </p>
        </div>

        <div class="row">
            <div class="col-lg-10 mx-auto">
                @foreach ($jobs as $job)
                    <a href="{{ route('login') }}" style="text-decoration:none;">
                        <div class="wu-job-card">
                            <div class="wu-job-time">{{ time_elapsed_string($job->created_at) }}</div>

                            <h5>{{ $job->title }}</h5>

                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="wu-job-meta">
                                        Posted by <strong>{{ user_name($job->user_id) }}</strong>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="wu-job-meta text-center">
                                        {{ complete_work_this_job($job->id) }} of {{ $job->worker_need }} completed
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{ this_job_complet_rate($job->id) }}%;" aria-valuenow="{{ this_job_complet_rate($job->id) }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="wu-job-price">
                                        ${{ number_format($job->each_worker_earn + 0.50, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="wu-section wu-soft-section">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6 order-lg-1 order-2">
                <span class="wu-badge">Survey & Quiz Tasks</span>
                <h2 class="wu-section-title" style="text-align:left; margin-bottom:16px;">
                    Participate in Survey and Quiz Activities
                </h2>
                <p class="wu-section-text" style="text-align:left; margin:0 0 18px 0;">
                    Protidin Mega Earn includes survey and quiz-based task sections to give users more ways to stay engaged on the platform. These activities are structured, simple to access, and designed to make participation more interactive.
                </p>
                <p class="wu-section-text" style="text-align:left; margin:0 0 24px 0;">
                    Users can explore available survey activities, answer questions, and take part in knowledge-based tasks as part of their broader platform experience.
                </p>

                <ul class="wu-hero-list" style="margin-bottom:24px;">
                    <li><i class="fas fa-check-circle"></i>Join selected survey activities</li>
                    <li><i class="fas fa-check-circle"></i>Participate in quiz-based tasks</li>
                    <li><i class="fas fa-check-circle"></i>Enjoy more structured platform engagement</li>
                </ul>

                <a href="{{ url('/surveys') }}" class="wu-btn wu-btn-primary">
                    <i class="fas fa-poll"></i> Explore Surveys
                </a>
            </div>

            <div class="col-lg-6 order-lg-2 order-1">
                <div class="wu-hero-image-wrap">
                    <div class="wu-hero-card">
                        <img src="https://workupbd.com/blog/wp-content/uploads/2026/04/survey-task-page-e1776704812838.png" alt="Survey and Quiz Tasks" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="wu-section wu-alt-section">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <div class="wu-hero-image-wrap">
                    <div class="wu-hero-card">
                        <img src="https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=1200&q=80" alt="Referral Program" loading="lazy">
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <span class="wu-badge">Referral Program</span>
                <h2 class="wu-section-title" style="text-align:left; margin-bottom:16px;">
                    Grow the Community Through Referrals
                </h2>
                <p class="wu-section-text" style="text-align:left; margin:0 0 18px 0;">
                    Protidin Mega Earn also includes a referral system that allows users to invite others and grow the platform community in a natural and rewarding way.
                </p>
                <p class="wu-section-text" style="text-align:left; margin:0 0 24px 0;">
                    By sharing a personal referral link, users can bring new members to the platform and take part in an additional engagement channel built around community growth.
                </p>

                <ul class="wu-hero-list" style="margin-bottom:24px;">
                    <li><i class="fas fa-check-circle"></i>Share your personal referral link</li>
                    <li><i class="fas fa-check-circle"></i>Invite new users to join the platform</li>
                    <li><i class="fas fa-check-circle"></i>Expand activity through referral rewards</li>
                </ul>

                <a href="{{ route('register') }}" class="wu-btn wu-btn-primary">
                    <i class="fas fa-user-friends"></i> Start Referring
                </a>
            </div>
        </div>
    </div>
</section>

<section class="wu-section wu-soft-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="wu-section-title">Our Premium Services</h2>
            <p class="wu-section-text">
                In addition to micro jobs and platform activities, Protidin Mega Earn offers service-based support for individuals, creators, and businesses that need dependable online assistance.
            </p>
        </div>

        <div class="row g-4">
            @foreach($services as $service)
                <div class="col-lg-4 col-md-6">
                    <div class="wu-service-card">
                        <img src="{{ URL::to($service->image) }}" alt="{{ $service->name }}" loading="lazy">
                        <h4>{{ $service->name }}</h4>
                        <p>{!! $service->details !!}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="wu-cta">
    <div class="container">
        <div class="wu-cta-box">
            <h2>Ready to Explore Protidin Mega Earn?</h2>
            <p>
                Join the platform to discover micro jobs, browse service opportunities, take part in surveys, and become part of a growing online work community.
            </p>
            <a href="{{ route('register') }}" class="wu-btn wu-btn-primary">
                <i class="fas fa-arrow-right"></i> Get Started Now
            </a>
        </div>
    </div>
</section>

@endsection

@section('js')
@endsection