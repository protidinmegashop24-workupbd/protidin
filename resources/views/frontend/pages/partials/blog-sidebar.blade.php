@if(site_info() && site_info()->ad_blog_code)
    <div class="wu-blog-widget wu-blog-ad-widget">
        {!! site_info()->ad_blog_code !!}
    </div>
@endif

<div class="wu-blog-widget wu-blog-cta-widget">
    <div class="wu-blog-cta-title">প্রতিদিন ইনকাম শুরু করুন</div>
    <p class="wu-blog-cta-text">PTC জব, মার্কেটপ্লেস আর সার্ভে করে আজই আয় শুরু করুন Protidin Mega Earn-এ।</p>
    <a href="{{ route('register') }}" class="wu-blog-cta-btn">ফ্রি অ্যাকাউন্ট খুলুন</a>
</div>

@if(isset($recentBlogs) && $recentBlogs->count() > 0)
    <div class="wu-blog-widget">
        <div class="wu-blog-widget-title">সাম্প্রতিক পোস্ট</div>
        @foreach($recentBlogs as $recent)
            <a href="{{ route('blog.details', $recent->slug) }}" class="wu-blog-widget-item">
                @if($recent->feature_image)
                    <img src="{{ URL::to($recent->feature_image) }}" class="wu-blog-widget-img" alt="{{ $recent->title }}">
                @else
                    <img src="{{ asset('uploads/site-assets/home-hero.png') }}" class="wu-blog-widget-img" alt="{{ $recent->title }}">
                @endif
                <div>
                    <div class="wu-blog-widget-item-title">{{ \Illuminate\Support\Str::limit($recent->title, 55) }}</div>
                    <div class="wu-blog-widget-item-date">{{ \Carbon\Carbon::parse($recent->news_date)->format('d M Y') }}</div>
                </div>
            </a>
        @endforeach
    </div>
@endif
