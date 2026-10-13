<!DOCTYPE html>
<html lang="en">
<head>
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
