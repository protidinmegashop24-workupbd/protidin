
<!DOCTYPE html>
<html lang="en">
<head>
    
    
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Online earning site" name="author">
    <meta name="keywords" content="">

    <title>{{ website_title() }}</title>
    <link rel="icon" href="{{ URL::to(website_favicon()) }}" type="image/x-icon" />
    @include('user.layouts.partials.styles')
    @yield('css')

    {{-- For Tostr Alert --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-
        alpha/css/bootstrap.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    
    
    <style>
        .mh-200{
            min-height: 200px
        }
        
        .mh-300{
            min-height: 300px
        }
        .dsk-dnone{
            display: none;
        }
        .mbl-dnone{
            display: block;
            margin-right: 10px;
        }
        .notice-alert{
            position: relative;
        }
        @media screen and (max-width: 767px) {
            .dsk-dnone{
                display: block;
            }
            .mbl-dnone{
                display: none;
            }
            .body-top-section{
                margin-top: 9px;
            }
            /* The dashboard theme's .footer is position:absolute; bottom:0,
               pinned to the bottom of its container's calculated height --
               on short pages (like /verify) that height is less than the
               footer's position, so it overlaps the page's own content
               (the code input, the submit button) instead of sitting below
               it. On mobile, let it flow normally after the content instead. */
            .footer{
                position: static !important;
                left: 0 !important;
                right: 0 !important;
                height: auto !important;
            }
            /* Ad widgets pasted into the site (Google Ad, "Inside Body Tag
               Code", etc.) sometimes render at a fixed width wider than a
               phone screen, which pushes the WHOLE page wider and makes it
               scroll sideways -- everything (buttons, boxes) then looks
               shifted/cut off depending on scroll position. Cap every
               embedded ad/media element to the screen's width, and stop the
               page itself from ever scrolling horizontally, so one
               oversized ad can't drag the rest of the layout with it. */
            img, iframe, embed, object, video {
                max-width: 100% !important;
                height: auto;
            }
            /* The sidebar logo icon only ever had a bare height="40" HTML
               attribute (no width), so the generic "height: auto" rule
               above overrode it and let the logo stretch to its full
               natural (large) size. Restore its fixed size explicitly. */
            .mbl-logo img {
                max-width: 40px !important;
                width: 40px !important;
                height: 40px !important;
            }
            body, html {
                overflow-x: hidden;
                max-width: 100vw;
            }
        }
    </style>
    
    {!! site_info()->head_tag_data !!}
</head>

<body data-sidebar="dark">
    @auth
    <script>
        // Lets any widget pasted into Website Settings -> "Inside Body Tag
        // Code" (e.g. the WhatsApp chat button) identify who is logged in,
        // without that widget needing its own auth/session logic.
        window.PROTIDIN_CURRENT_USER = {
            id: {{ Auth::user()->id }},
            name: @json(Auth::user()->name),
            email: @json(Auth::user()->email)
        };
    </script>
    @endauth
    @auth
        @include('user.layouts.partials.daily-bonus-widget')
    @endauth
    {!! site_info()->after_start_body_tag !!}
    <div id="layout-wrapper">
        @include('user.layouts.partials.header')

        @include('user.layouts.partials.sidebar')

        <div class="main-content">
            <div class="page-content">
                
                <div class="dsk-dnone">
                    <div class="mb-2 d-flex flex-wrap justify-content-center d-lg-inline-block">
                        <button class="btn btn-sm btn-info text-white mbl-btn mbl-mt" style="background: #000066; border: #000066;" type="button">
                            Earning: ${{ round(Auth::user()->earning_balance, 4) }}
                        </button>
                        <button class="btn btn-sm btn-info text-white mbl-btn mbl-mt mr-2" style="background: #008000; border: #008000;" type="button">
                            Deposit: ${{ round(Auth::user()->deposit_balance, 4) }}
                        </button>

                        <div class="dropdown d-inline-block mbl-mt">
                            <button type="button" class="btn btn-sm text-white mbl-btn dropdown-toggle" style="background: #22ab59; border: #22ab59;" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Post A Service or Product
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('user.marketplace.create') }}?type=service"><i class="fas fa-briefcase me-1"></i> Post a Service</a>
                                <a class="dropdown-item" href="{{ route('user.marketplace.create') }}?type=digital_product"><i class="fas fa-download me-1"></i> Post a Digital Product</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('user.marketplace.my_services') }}"><i class="fas fa-list me-1"></i> My Listings</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="body-top-section">
                    @include('user.layouts.partials.headline-and-ads')
                </div>
    
                
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        @if(google_head_ad())
                            <div class="col-12">
                                {!! google_head_ad()->code !!}
                            </div>
                        @endif
                        @if(site_info()->ad_one_code)
                            <div class="col-12 mt-2">
                                {!! site_info()->ad_one_code !!}
                            </div>
                        @endif
                    </div>
                </div>
                    
                <div class="container-fluid">
                    @yield('user-content')
                </div>
                @include('user.layouts.partials.footer')
            </div>
        </div>

        @include('user.layouts.partials.notification-modal')
    </div>

    @include('user.layouts.partials.scripts')

    <script>
        // The site's CSS/JS was upgraded to Bootstrap 5, which dropped the
        // old jQuery `.modal('show'/'hide')` plugin -- but dozens of
        // existing onclick handlers across this site (job work proof
        // upload, report/rate/resume job, boost job, the notification
        // bell, etc.) still call it the Bootstrap 4 way. Without this,
        // every one of those buttons silently does nothing when clicked.
        // Restoring just that one method, backed by Bootstrap 5's native
        // bootstrap.Modal, fixes all of them at once instead of rewriting
        // every call site.
        if (typeof $ !== 'undefined' && typeof bootstrap !== 'undefined' && !$.fn.modal) {
            $.fn.modal = function (action) {
                return this.each(function () {
                    var instance = bootstrap.Modal.getOrCreateInstance(this);
                    if (action === 'show') {
                        instance.show();
                    } else if (action === 'hide') {
                        instance.hide();
                    } else if (action === 'toggle') {
                        instance.toggle();
                    }
                });
            };
        }
    </script>

    @yield('js')


    {{-- For Tostr Alert --}}
    <script>
        @if(Session::has('success'))
            toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true
            }
            toastr.success("{{ session('success') }}");
        @endif
        @if(Session::has('message'))
            toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true
            }
            toastr.success("{{ session('message') }}");
        @endif

        @if(Session::has('error'))
            toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true
            }
            toastr.error("{{ session('error') }}");
        @endif

        @if(Session::has('info'))
            toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true
        }
            toastr.info("{{ session('info') }}");
        @endif

        @if(Session::has('warning'))
            toastr.options =
            {
                "closeButton" : true,
                "progressBar" : true
            }
            toastr.warning("{{ session('warning') }}");
        @endif
        
        @if(count($errors) > 0)
            @foreach($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
        
        
        function showNotificationModal(){
            $('#notification_modal').modal('show');
        }
        
        function closeNotificationModal(){
            $('#notification_modal').modal('hide');
        }
        
        function hideSidebar(){
            $("body").removeClass("sidebar-enable");
        }
        
        // $(document).click(function(event) {
        //     $("body").click(function(e){
        //         if(e.target.className !== "vertical-menu" && e.target.className == "vertical-menu-btn"){
        //             $("body").removeClass('sidebar-enable');
        //         }elseif(e.target.className === "vertical-menu-btn"){
        //             $("body").addClass('sidebar-enable');
        //         }
        //     });
        // });
    </script>
    @include('partials.site-moved-notice')
    @include('user.layouts.partials.chat-widget')
</body>
</html>
