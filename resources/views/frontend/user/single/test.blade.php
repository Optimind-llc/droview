<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{!! csrf_token() !!}" />
    <title>@yield('title', app_name())</title>
    <meta name="description" content="@yield('meta_description', 'Default Description')">
  </head>
  <body>
    <div id="aaa">
      <p>テストを実行中です</p>
      <p>テストが完了したら自動的にこのウィンドウは閉じられます</p>
    </div>
    <script>
        setTimeout( function() {
          var token = JSON.parse(localStorage.getItem('redux'));
          localStorage.setItem('testConnectionResult', JSON.stringify(token.jwtToken.testToken));
        }, 3000);
    </script>
  </body>
</html>


