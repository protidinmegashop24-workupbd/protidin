<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>@yield('title', 'BEC - Dashboard')</title>

    {{-- Favicon (Safe) --}}
    <link rel="icon"
          href="{{ isset($website) && !empty($website->favicon) ? asset($website->favicon) : asset('favicon.ico') }}"
          type="image/x-icon" />

    <meta name="viewport" content="width=device-width, initial-scale=1">

    @include('backend.layouts.partials.styles')

    @yield('css')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    {{-- Navbar --}}
    @include('backend.layouts.partials.navbar')

    {{-- Sidebar --}}
    @include('backend.layouts.partials.sidebar')

    {{-- Content --}}
    <div class="content-wrapper">
        @yield('back-content')
    </div>

    {{-- Footer --}}
    @include('backend.layouts.partials.footer')

</div>


{{-- Scripts --}}
@include('backend.layouts.partials.scripts')

@yield('js')
</body>
</html>