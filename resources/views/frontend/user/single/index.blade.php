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
                <link rel="stylesheet" href="http://localhost:3000/static/bundle.css">
	</head>
	<body>
{{--
<script>
var $count = 0;
function countUp() {
        document.getElementById('count').innerHTML = ++$count;
        document.getElementById('btn-loop').click();
        setTimeout(countUp,1500);
}
</script>
<button class="btn btn-success" id="btn-count" onclick="countUp();">ループ開始</button>
<p>カウント数</p>
<span id="count">0</span>
--}}

<script>
var $count = 0;
function ZipSearchValue(data) {
        console.log(data)
}
</script>
		<div id="root"></div>
                <script src="http://localhost:3000/static/bundle.js"></script>
	</body>
</html>