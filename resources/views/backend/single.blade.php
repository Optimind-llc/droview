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
    <link rel="stylesheet" href="http://localhost:3001/static/bundle.css">
  </head>
    <div id="root"></div>
    <script src="http://localhost:3001/static/bundle.js"></script>
</html>