<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{!! csrf_token() !!}" />
        <meta name="domain" content="{{$domain}}" />
        <title>@yield('title', app_name())</title>
        <meta name="description" content="@yield('meta_description', 'Default Description')">
        <meta name="author" content="@yield('author', 'Anthony Rappa')">
        @if ($env === 'local')
            <link rel="stylesheet" href="http://localhost:3000/static/bundle.css">
        @elseif ($env === 'production')
            <link rel="stylesheet" href="/dist-front/bundle.css">
        @endif
    </head>
    <body>
        <div id="root"></div>
        @if ($env === 'local')
            <script src="http://localhost:3000/static/bundle.js"></script>
        @elseif ($env === 'production')
            <script src="/dist-front/bundle.js"></script>
        @endif
    </body>
</html>