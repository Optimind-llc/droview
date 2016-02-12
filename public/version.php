<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<h1>Version of bundle.js</h1>
<?php

$json = file_get_contents('v.json');
$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
$arr = json_decode($json,true);

echo '<p>front:   ' . $arr['front'] . '</p>';
echo '<p>admin:   ' . $arr['admin'] . '</p>';

?>

</body>
</html>

