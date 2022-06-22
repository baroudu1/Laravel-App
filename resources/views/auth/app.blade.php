<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="https://fst-usmba.ac.ma/framework/themes/fstf/favicon/xapple-touch-icon.png.pagespeed.ic.EDr70UkBnZ.webp">
    <link rel="icon" type="image/png" href="https://fst-usmba.ac.ma/framework/themes/fstf/favicon/xfavicon-32x32.png.pagespeed.ic.FJym0_kf5K.webp">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">


    <!-- Nucleo Icons -->
    <link rel="stylesheet" href="{{ asset('css/nucleo-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nucleo-svg.css') }}">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/soft-ui-dashboard.css?v=1.0.1') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/seach_css_2.css') }}">


    <!-- Scripts -->
    <script src="{{ asset('js/core/popper.min.js') }}" defer></script>
    <script src="{{ asset('js/core/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('js/core/jquery.min.js') }}" defer></script>
    <script src="{{ asset('js/core/fontawesome.js') }}" defer></script>
    <script src="{{ asset('js/appp.js') }}" defer></script>
    <script src="{{ asset('js/soft-ui-dashboard.js?v=1.0.1') }}" defer></script>

</head>

<body>
    <div>
        @yield('content')
    </div>
</body>

@yield('scriptt')

</html>
