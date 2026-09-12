
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
        }
    </style>
    
    {!! site_info()->head_tag_data !!}
</head>

<body data-sidebar="dark">
    {!! site_info()->after_start_body_tag !!}
    <div id="layout-wrapper">
        @include('user.layouts.partials.header')

        @include('user.layouts.partials.sidebar')

        <div class="main-content">
            <div class="page-content">
                
                <div class="dsk-dnone">
                    <div class="mb-2 d-flex justify-content-center d-lg-inline-block">
                        <button class="btn btn-sm btn-info text-white mbl-btn mbl-mt" style="background: #000066; border: #000066;" type="button">
                            Earning: ${{ round(Auth::user()->earning_balance, 4) }}
                        </button>
                        <button class="btn btn-sm btn-info text-white mbl-btn mbl-mt mr-2" style="background: #008000; border: #008000;" type="button">
                            Deposit: ${{ round(Auth::user()->deposit_balance, 4) }}
                        </button>
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
</body>
</html>
