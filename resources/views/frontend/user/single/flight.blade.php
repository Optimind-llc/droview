<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{!! csrf_token() !!}" />
    <title>@yield('title', app_name())</title>
    <meta name="description" content="@yield('meta_description', 'Default Description')">
    <style type="text/css">

    </style>
  </head>
  <body>
    <video src="/img/sample.mp4"  width="990" muted autoplay loop></video>
    <div>
    </div>
  </body>
</html>

