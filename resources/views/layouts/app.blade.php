<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @if(request()->getScheme()==='http')
        <meta {{$protocol = 'http://'}}>
    @else
        <meta {{$protocol = 'https://'}}>
    @endif
    <link rel="apple-touch-icon" sizes="57x57" href="{{$protocol.$_SERVER['HTTP_HOST'].'/icons'}}/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{$protocol.$_SERVER['HTTP_HOST'].'/icons'}}/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{$protocol.$_SERVER['HTTP_HOST'].'/icons'}}/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{$protocol.$_SERVER['HTTP_HOST'].'/icons'}}/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{$protocol.$_SERVER['HTTP_HOST'].'/icons'}}/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{$protocol.$_SERVER['HTTP_HOST'].'/icons'}}/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{$protocol.$_SERVER['HTTP_HOST'].'/icons'}}/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{$protocol.$_SERVER['HTTP_HOST'].'/icons'}}/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{$protocol.$_SERVER['HTTP_HOST'].'/icons'}}/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{$protocol.$_SERVER['HTTP_HOST'].'/icons'}}/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{$protocol.$_SERVER['HTTP_HOST'].'/icons'}}/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{$protocol.$_SERVER['HTTP_HOST'].'/icons'}}/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{$protocol.$_SERVER['HTTP_HOST'].'/icons'}}/favicon-16x16.png">
    <link rel="manifest" href="{{$protocol.$_SERVER['HTTP_HOST'].'/icons'}}/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
        
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title-block')</title>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
<div>
    @guest
    @else
        @include('incs.navigation')
    @endguest
    <main>
        @yield('content')
    </main>
</div>
</body>
</html>
