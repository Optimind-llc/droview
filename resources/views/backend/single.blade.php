<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{!! csrf_token() !!}" />
    <meta name="domain" content="{{$domain}}" />
    <title>@yield('title', app_name())</title>
    <!-- Webpack compiled -->
    @if ($env === 'local')
        <link rel="stylesheet" href="http://localhost:3001/static/bundle.css">
    @elseif ($env === 'production')
        <link rel="stylesheet" href="/dist-back/bundle.css">
    @endif
  </head>
    <div id="root"></div>
    @if ($env === 'local')
        <script src="http://localhost:3001/static/bundle.js"></script>
    @elseif ($env === 'production')
        <script src="/dist-back/bundle.js"></script>
    @endif
</html>