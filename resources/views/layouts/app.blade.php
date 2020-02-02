<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>
    @if (!empty($seo_meta))
        <meta name="description" content="{{ $seo_meta }}" />
    @endif

    @stack('header-top')
    @stack('header')
    @stack('header-bottom')

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700|Roboto+Condensed:300,400,700|Poppins:200,300,400,500,700" rel="stylesheet" />

</head>
<body>

    <div id="app">
        @yield('header')
        @yield('content')
        @yield('footer')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('footer-top')

    @stack('footer')


    @stack('footer-bottom')


</body>
</html>
