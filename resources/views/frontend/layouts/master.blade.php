<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}" />

        <title>@yield('title', app_name())</title>
        <!-- Styles -->
        <link rel="stylesheet" type="text/css" href="/build/css/bootstrap-social.css">
        <link rel="stylesheet" type="text/css" href="/build/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/droview/css/main.css">
        <link rel="stylesheet" type="text/css" href="/droview/css/responsive.css">
        <link rel="stylesheet" type="text/css" href="/droview/css/droview.css">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>
    </head>
    <body id="app-layout">
        @yield('content')

        <!-- JavaScripts -->
        <script src="/vender/js/jquery.min.js"></script>
        <script src="/vender/js/bootstrap.min.js"></script>
        <script src="/droview/js/jquery.backstretch.min.js"></script>
        <script src="/droview/js/jquery.countdown.js"></script>
        <script src="/droview/js/jquery.subscribe.js"></script>
        <script src="/droview/js/main.js"></script>
    </body>
</html>