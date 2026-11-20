<!DOCTYPE html>
<html lang="en">
    <head>
        
        
        <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <title>{{ website_title() }}</title>
        <link rel="icon" href="{{ URL::to(website_favicon()) }}" type="image/x-icon" />
        @include('frontend.layouts.partials.styles')
        @yield('css')
        <!--<meta name="google-site-verification" content="wpOGVjOfBoHYkTi38yDHvOmOQVwTs8VfvcY21b8fUGw" />-->
        <meta name="google-site-verification" content="hZrJqVypdtE7M947kCzx-dnfGYdroV34YSuAK0QoTn0" />
        <meta name="google-adsense-account" content="ca-pub-6314276342535503">
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6314276342535503" crossorigin="anonymous"></script>
     <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WB28CSK');</script>
<!-- End Google Tag Manager -->
    
    
    
    
    
    
    
    
    
    
    
    
    </head>

    <body>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WB28CSK"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        @auth
        <script>
            // Same PROTIDIN_CURRENT_USER identity block as
            // user/layouts/master.blade.php, so widgets in "Inside Body
            // Tag Code" work the same way for logged-in visitors here too.
            window.PROTIDIN_CURRENT_USER = {
                id: {{ Auth::user()->id }},
                name: @json(Auth::user()->name),
                email: @json(Auth::user()->email)
            };
        </script>
        @endauth
        {!! site_info()->after_start_body_tag !!}

        @include('frontend.layouts.partials.header')

        @yield('front-content')

        @include('frontend.layouts.partials.footer')

        @include('frontend.layouts.partials.scripts')
        @yield('js')
        @include('partials.site-moved-notice')
    </body>
</html>
