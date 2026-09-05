@extends('frontend.layouts.master')

@section('css')
<style>

      .wu-market-hero{
    padding: 90px 0;
    background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
}
.wu-market-hero-content h1{
    font-size: 42px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 16px;
}
.wu-market-hero-content p{
    font-size: 16px;
    line-height: 1.9;
    color: #64748b;
    margin-bottom: 22px;
}
.wu-market-badge{
    display: inline-block;
    background: #e8f7ee;
    color: #15803d;
    font-size: 13px;
    font-weight: 700;
    padding: 8px 14px;
    border-radius: 999px;
    margin-bottom: 18px;
}
.wu-market-points{
    list-style: none;
    padding: 0;
    margin: 0 0 25px;
}
.wu-market-points li{
    margin-bottom: 10px;
    font-size: 16px;
    color: #1e293b;
}
.wu-market-btns{
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.wu-market-btn-primary,
.wu-market-btn-secondary{
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 24px;
    border-radius: 12px;
    text-decoration: none !important;
    font-weight: 700;
}
.wu-market-btn-primary{
    background: #16a34a;
    color: #fff !important;
}
.wu-market-btn-secondary{
    background: #eff6ff;
    color: #1d4ed8 !important;
    border: 1px solid #bfdbfe;
}
.wu-market-hero-img img{
    max-width: 100%;
    height: auto;
    border-radius: 22px;
    box-shadow: 0 16px 35px rgba(15,23,42,.10);
}
@media (max-width: 991px){
    .wu-market-hero-content h1{
        font-size: 32px;
    }
  }
    .wu-marketplace-page {
        background: #f8fbff;
    }

    .wu-marketplace-hero {
        padding: 80px 0 60px;
        background: linear-gradient(135deg, #eef9f3 0%, #f8fbff 45%, #ffffff 100%);
        border-bottom: 1px solid #e8eef5;
    }

    .wu-hero-badge {
        display: inline-block;
        background: #e8fff1;
        color: #15803d;
        font-weight: 700;
        font-size: 13px;
        padding: 8px 14px;
        border-radius: 999px;
        margin-bottom: 18px;
    }

    .wu-marketplace-hero h1 {
        font-size: 42px;
        line-height: 1.2;
        font-weight: 800;
        color: #172b4d;
        margin-bottom: 18px;
    }

    .wu-marketplace-hero p {
        font-size: 17px;
        line-height: 1.8;
        color: #52627a;
        max-width: 760px;
        margin: 0 auto 25px;
    }

    .wu-hero-btn {
        display: inline-block;
        padding: 12px 22px;
        border-radius: 10px;
        font-weight: 700;
        text-decoration: none !important;
        margin: 6px;
        transition: .25s ease;
    }

    .wu-hero-btn-primary {
        background: #22ab59;
        color: #fff !important;
    }

    .wu-hero-btn-primary:hover {
        background: #1a8c48;
        transform: translateY(-2px);
    }

    .wu-hero-btn-secondary {
        background: #172b4d;
        color: #fff !important;
    }

    .wu-hero-btn-secondary:hover {
        background: #10203b;
        transform: translateY(-2px);
    }

    .wu-section {
        padding: 70px 0;
    }

    .wu-section-title {
        text-align: center;
        margin-bottom: 18px;
    }

    .wu-section-title h2 {
        font-size: 34px;
        font-weight: 800;
        color: #172b4d;
        margin-bottom: 12px;
    }

    .wu-section-title p {
        color: #5f6f86;
        max-width: 760px;
        margin: 0 auto;
        line-height: 1.8;
        font-size: 16px;
    }

    .wu-marketplace-card {
        background: #fff;
        border: 1px solid #e5edf5;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(17, 24, 39, 0.06);
        height: 100%;
        transition: .25s ease;
    }

    .wu-marketplace-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 36px rgba(17, 24, 39, 0.10);
    }

    .wu-marketplace-card img {
        width: 100%;
        height: 230px;
        object-fit: cover;
        display: block;
    }

    .wu-marketplace-card-body {
        padding: 20px;
    }

    .wu-marketplace-card h4 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 10px;
        line-height: 1.35;
    }

    .wu-marketplace-card h4 a {
        color: #172b4d;
        text-decoration: none;
    }

    .wu-marketplace-card h4 a:hover {
        color: #22ab59;
    }

    .wu-service-desc {
        color: #5f6f86;
        font-size: 15px;
        line-height: 1.7;
        min-height: 78px;
    }

    .wu-service-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 14px 0 18px;
        gap: 10px;
        flex-wrap: wrap;
    }

    .wu-price {
        color: #16a34a;
        font-weight: 800;
        font-size: 22px;
    }

    .wu-delivery {
        color: #52627a;
        font-size: 14px;
        font-weight: 600;
    }

    .wu-card-btn {
        display: inline-block;
        background: #22ab59;
        color: #fff !important;
        padding: 11px 18px;
        border-radius: 10px;
        text-decoration: none !important;
        font-weight: 700;
        transition: .25s ease;
    }

    .wu-card-btn:hover {
        background: #1a8c48;
        transform: translateY(-2px);
    }

    .wu-feature-box,
    .wu-step-box,
    .wu-category-box,
    .wu-info-box,
    .wu-faq-box {
        background: #fff;
        border: 1px solid #e5edf5;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(17, 24, 39, 0.05);
        height: 100%;
        padding: 24px;
    }

    .wu-feature-box h4,
    .wu-step-box h4,
    .wu-category-box h4,
    .wu-info-box h4,
    .wu-faq-box h4 {
        font-size: 21px;
        font-weight: 700;
        color: #172b4d;
        margin-bottom: 10px;
    }

    .wu-feature-box p,
    .wu-step-box p,
    .wu-category-box p,
    .wu-info-box p,
    .wu-faq-box p {
        color: #5f6f86;
        line-height: 1.8;
        margin-bottom: 0;
    }

    .wu-step-number {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #eaf7ff;
        color: #0f62fe;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 20px;
        margin-bottom: 16px;
    }

    .wu-category-pill {
        display: inline-block;
        background: #f3f8fc;
        color: #172b4d;
        padding: 10px 16px;
        border-radius: 999px;
        font-weight: 700;
        margin: 6px;
        border: 1px solid #dde8f2;
    }

    .wu-cta-box {
        background: linear-gradient(135deg, #172b4d 0%, #1f3b6b 100%);
        border-radius: 24px;
        padding: 50px 30px;
        text-align: center;
        color: #fff;
        box-shadow: 0 16px 40px rgba(23, 43, 77, 0.18);
    }

    .wu-cta-box h2 {
        font-size: 34px;
        font-weight: 800;
        margin-bottom: 14px;
    }

    .wu-cta-box p {
        color: #dce8ff;
        font-size: 16px;
        max-width: 760px;
        margin: 0 auto 24px;
        line-height: 1.8;
    }

    .wu-soft-bg {
        background: #f5fbf7;
    }

    @media (max-width: 767px) {
        .wu-marketplace-hero h1 {
            font-size: 30px;
        }

        .wu-section-title h2 {
            font-size: 28px;
        }

        .wu-marketplace-card h4 {
            font-size: 21px;
        }
    }
</style>
<style>
.mp-hero{
    padding: 95px 0;
    background: linear-gradient(135deg, #eff8ff 0%, #f8fffb 45%, #ffffff 100%);
    overflow: hidden;
}
.mp-badge{
    display: inline-block;
    background: #e8f7ee;
    color: #15803d;
    font-size: 13px;
    font-weight: 700;
    padding: 8px 14px;
    border-radius: 999px;
    margin-bottom: 18px;
}
.mp-hero-content h1{
    font-size: 44px;
    font-weight: 800;
    line-height: 1.18;
    color: #0f172a;
    margin-bottom: 16px;
}
.mp-hero-content p{
    font-size: 16px;
    line-height: 1.95;
    color: #64748b;
    margin-bottom: 22px;
    max-width: 590px;
}
.mp-hero-points{
    list-style: none;
    padding: 0;
    margin: 0 0 28px;
}
.mp-hero-points li{
    font-size: 16px;
    color: #1e293b;
    margin-bottom: 10px;
}
.mp-hero-btns{
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.mp-btn-primary{
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 28px;
    border-radius: 12px;
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: #fff !important;
    font-weight: 700;
    text-decoration: none !important;
    box-shadow: 0 14px 28px rgba(22, 163, 74, 0.22);
    transition: all .25s ease;
}
.mp-btn-primary:hover{
    transform: translateY(-2px);
    box-shadow: 0 18px 32px rgba(22, 163, 74, 0.28);
}
.mp-btn-secondary{
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 28px;
    border-radius: 12px;
    background: #eff6ff;
    color: #1d4ed8 !important;
    font-weight: 700;
    text-decoration: none !important;
    border: 1px solid #bfdbfe;
    transition: all .25s ease;
}
.mp-btn-secondary:hover{
    background: #dbeafe;
}
.mp-hero-img img{
    max-width: 100%;
    height: auto;
    border-radius: 24px;
    box-shadow: 0 20px 42px rgba(15,23,42,.10);
}
@media (max-width: 991px){
    .mp-hero{
        padding: 75px 0;
    }
    .mp-hero-content h1{
        font-size: 30px;
    }
    .mp-hero-img{
        margin-top: 28px;
    }
}
@media (max-width: 575px){
    .mp-hero-content h1{
        font-size: 26px;
    }
}
.wu-search-bar{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    justify-content:center;
    margin-bottom:22px;
}
.wu-search-bar input, .wu-search-bar select{
    border:1px solid #dde8f2;
    border-radius:10px;
    padding:10px 14px;
}
.wu-search-bar input[type="text"]{ flex:1 1 240px; max-width:320px; }
.wu-search-bar input[type="number"]{ width:120px; }
</style>
@endsection

@section('front-content')
<div class="wu-marketplace-page">

    <section class="mp-hero">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-6">
                <div class="mp-hero-content">
                    <span class="mp-badge">Protidin Mega Earn Marketplace</span>
                    <h1>Buy and Sell Digital Services in a Professional Marketplace</h1>
                    <p>
                        Explore a smart digital service marketplace where buyers can discover skilled sellers,
                        chat before placing orders, and complete projects through a structured order system.
                        Protidin Mega Earn Marketplace helps users start, grow, and manage service-based online work with confidence.
                    </p>

                    <ul class="mp-hero-points">
                        <li>✔ Discover verified digital services</li>
                        <li>✔ Communicate with sellers before ordering</li>
                        <li>✔ Secure payment with protected transactions</li>
                        <li>✔ Structured workflow for safe service delivery </li>
                        <li>✔ Buyer protection with platform support </li>
                        <li>✔ Built for both buyers and sellers </li>
                    </ul>

                    <div class="mp-hero-btns">
                        <a href="https://workupbd.com/blog/" class="mp-btn-primary">
                            Learning Marketplace
                        </a>

                        <a href="{{ route('user.marketplace.services') }}" class="mp-btn-secondary">
                            Browse Services
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 text-center">
                <div class="mp-hero-img">
                    <img src="https://workupbd.com/blog/wp-content/uploads/2026/04/Marketplace-homepage-imege.png" alt="Marketplace Hero">
                </div>
            </div>

        </div>
    </div>
</section>

    <section class="wu-section">
        <div class="container">
            <div class="wu-section-title">
                <h2>Available Services</h2>
                <p>
                    Browse live service listings, review delivery terms, and open a service page to learn more before starting a conversation or placing an order.
                </p>
            </div>
<form method="GET" action="{{ url()->current() }}" class="wu-search-bar">
    <input type="text" name="q" placeholder="Search services..." value="{{ request('q') }}">
    <input type="number" name="min_price" placeholder="Min $" step="0.01" min="0" value="{{ request('min_price') }}">
    <input type="number" name="max_price" placeholder="Max $" step="0.01" min="0" value="{{ request('max_price') }}">
    <select name="sort">
        <option value="">Newest</option>
        <option value="price_asc" @if(request('sort') == 'price_asc') selected @endif>Price: Low to High</option>
        <option value="price_desc" @if(request('sort') == 'price_desc') selected @endif>Price: High to Low</option>
    </select>
    <button type="submit" class="wu-card-btn">Search</button>
</form>
<div class="text-center mb-4">
    <a href="{{ route('marketplace') }}" class="wu-category-pill {{ !isset($category) ? 'active' : '' }}">All</a>

    @foreach($categories as $cat)
        <a href="{{ route('marketplace.category', $cat->slug) }}" class="wu-category-pill {{ (isset($category) && $category->id == $cat->id) ? 'active' : '' }}">
            {{ $cat->name }}
        </a>
    @endforeach
</div>
            <div class="row">
                @forelse($services as $service)
                    <div class="col-md-4 mb-4">
                        <div class="wu-marketplace-card">
                            <img src="{{ wu_service_image($service->image) }}" alt="{{ $service->title }}">

                            <div class="wu-marketplace-card-body">
                                <h4>
                                    <a href="{{ route('marketplace.service.show', $service->slug) }}">{{ $service->title }}</a>
                                </h4>

                                <div class="wu-service-desc">
                                    {{ \Illuminate\Support\Str::limit($service->short_description ?: $service->description, 110) }}
                                </div>

                                <div class="wu-service-meta">
                                    <div class="wu-price">${{ number_format($service->price, 2) }}</div>
                                    @if(($service->type ?? 'service') == 'digital_product')
                                        <div class="wu-delivery">⚡ Instant Delivery</div>
                                    @else
                                        <div class="wu-delivery">Delivery: {{ $service->delivery_days }} day(s)</div>
                                    @endif
                                </div>

                                <a href="{{ route('marketplace.service.show', $service->slug) }}" class="wu-card-btn">View Service</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">No services are available right now.</div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $services->links() }}
            </div>
        </div>
    </section>

    <section class="wu-section wu-soft-bg">
        <div class="container">
            <div class="wu-section-title">
                <h2>How the marketplace works</h2>
                <p>
                    The platform is built to make service discovery and delivery more structured for both buyers and sellers.
                </p>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="wu-step-box">
                        <div class="wu-step-number">1</div>
                        <h4>Browse and compare services</h4>
                        <p>
                            Visitors can review service listings, pricing, and delivery details before deciding which offer matches their needs.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="wu-step-box">
                        <div class="wu-step-number">2</div>
                        <h4>Chat before ordering</h4>
                        <p>
                            Logged-in users can ask questions, discuss requirements, and confirm whether the seller can handle the task properly before payment.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="wu-step-box">
                        <div class="wu-step-number">3</div>
                        <h4>Place order and continue inside the system</h4>
                        <p>
                            After ordering, the work process, delivery updates, and communication continue inside the marketplace for better tracking and clarity.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="wu-section">
        <div class="container">
            <div class="wu-section-title">
                <h2>Popular service categories</h2>
                <p>
                    These categories help users understand the kind of digital work that can be offered through the platform.
                </p>
            </div>
            <div class="text-center mb-4">
    <a href="{{ route('marketplace') }}" class="wu-category-pill {{ !isset($category) ? 'active' : '' }}">All</a>

    @foreach($categories as $cat)
        <a href="{{ route('marketplace.category', $cat->slug) }}" class="wu-category-pill {{ (isset($category) && $category->id == $cat->id) ? 'active' : '' }}">
            {{ $cat->name }}
        </a>
    @endforeach
           </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="wu-feature-box">
                        <h4>WordPress and website support</h4>
                        <p>
                            Users can explore help related to website customization, updates, design adjustments, and general platform improvement tasks.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="wu-feature-box">
                        <h4>Creative and visual work</h4>
                        <p>
                            Service providers may offer design-related support for branding, layouts, banners, graphics, and visual presentation improvements.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="wu-feature-box">
                        <h4>Online assistance and digital tasks</h4>
                        <p>
                            Buyers can also discover service offers that help with structured online work, digital support activities, and project-based tasks.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="wu-section wu-soft-bg">
        <div class="container">
            <div class="wu-section-title">
                <h2>Why users may prefer this marketplace</h2>
                <p>
                    Protidin Mega Earn Marketplace focuses on clarity, controlled communication, and a process that is easier to understand for both newer and experienced users.
                </p>
            </div>

            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="wu-info-box">
                        <h4>Structured ordering</h4>
                        <p>Requirements, delivery, and updates stay connected inside the same marketplace flow.</p>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="wu-info-box">
                        <h4>Pre-order discussion</h4>
                        <p>Buyers can ask service-related questions first instead of ordering blindly.</p>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="wu-info-box">
                        <h4>Seller opportunity</h4>
                        <p>Existing users can create service listings without needing a separate seller account.</p>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="wu-info-box">
                        <h4>Internal workflow</h4>
                        <p>Communication and work updates remain inside the platform for better control.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="wu-section">
        <div class="container">
            <div class="wu-section-title">
                <h2>Safe and platform-based communication</h2>
                <p>
                    The marketplace is designed to keep conversations focused on service work. Before an order is placed, users are encouraged to communicate only inside the platform to maintain a cleaner and safer workflow.
                </p>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="wu-faq-box">
                        <h4>Discuss service details before ordering</h4>
                        <p>
                            Buyers can ask about delivery time, work scope, revisions, and project requirements before they commit to a purchase.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="wu-faq-box">
                        <h4>Order only after the service is clear</h4>
                        <p>
                            Once the buyer understands the service properly, the order can be placed through the internal buyer panel and managed step by step.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="wu-faq-box">
                        <h4>Seller delivery stays organized</h4>
                        <p>
                            After an order starts, sellers can continue the work process through delivery and message features designed for service completion.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="wu-faq-box">
                        <h4>Suitable for growing digital work activity</h4>
                        <p>
                            This marketplace can help new users understand service-based online work while giving experienced users a place to present what they offer.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="wu-section">
        <div class="container">
            <div class="wu-cta-box">
                <h2>Ready to offer a service or hire someone for a task?</h2>
                <p>
                    Create an account to explore buyer tools, seller tools, service chat, and internal order management through the Protidin Mega Earn Marketplace.
                </p>
                <a href="{{ route('register') }}" class="wu-hero-btn wu-hero-btn-primary">Start With an Account</a>
                <a href="{{ route('user.marketplace.services') }}" class="mp-btn-secondary">Login to Continue</a>
            </div>
        </div>
    </section>

</div>
@endsection