<!DOCTYPE html>
<html>
<head>
	<title>Simple Captcha Reader</title>
</head>
<body>
<?php
	require './../SimpleCaptchaReader.php';
	$file	= 'seccode.png';
	$result	= SimpleCaptchaReader::readImageFromFile($file);
	echo '<img src="' . $file . '"/><h1>' . $result . '</h1>';
?>
</body>
</html>
