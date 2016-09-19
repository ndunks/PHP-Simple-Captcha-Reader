<!DOCTYPE html>
<html>
<head>
	<title>Simple Captcha Reader</title>
</head>
<body>
<?php
	require './../SimpleCaptchaReader.php';
	// get the captcha
	$url	= 'http://ems.posindonesia.co.id/seccode.php';
	$ch			= curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$img_str	= curl_exec($ch);
	curl_close($ch);
	$result	= SimpleCaptchaReader::readImage($img_str);
	echo '<img src="data:image/png;base64,' . base64_encode($img_str) . '"/><h1>' . $result . '</h1>';
?>
</body>
</html>
