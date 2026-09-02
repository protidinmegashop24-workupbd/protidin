<!DOCTYPE html>
<html lang="en">
<head>
    <script>(function(s){s.dataset.zone='11563878',s.src='https://nap5k.com/tag.min.js'})([document.documentElement, document.body].filter(Boolean).pop().appendChild(document.createElement('script')))</script>
    <script src="https://5gvci.com/act/files/tag.min.js?z=11563748" data-cfasync="false" async></script>
    <meta name="monetag" content="590ea0442b3f6eaf6b40437e8a6ee8d5">
    <meta name="7072e592928aec912817a3ff684e03124aa0853c" content="7072e592928aec912817a3ff684e03124aa0853c" />
    <meta name='admaven-placement' content=BqHkGrdsG>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- CSS ফাইল যুক্ত করুন -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')  <!-- এই স্থানে অন্য ভিউয়ের কনটেন্ট যুক্ত হবে -->
        </main>
    </div>

    <!-- JavaScript ফাইল যুক্ত করুন -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
