<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="/js/app.js" defer></script>

    <!-- Fonts -->

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    @yield('extracss')
    
</head>
<body>
    <div id="app">

        <div>
            @yield('content')
        </div>


    </div>
</body>
</html>
