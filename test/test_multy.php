<!DOCTYPE html>
<html>
<head>
	<title>Simple Captcha Reader</title>
</head>
<body>
<?php
	set_time_limit(0);
	require './../SimpleCaptchaReader.php';
	// get the captcha
	$url	= 'http://ems.posindonesia.co.id/seccode.php';
	$gagal	= 0;
	$total	= 100; // try 100
	for($i = 1; $i <= $total; $i++)
	{
		$ch			= curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$img_str	= curl_exec($ch);
		curl_close($ch);
		$result	= SimpleCaptchaReader::readImage($img_str);
		
		echo '<img src="data:image/png;base64,' . base64_encode($img_str) . '"/>' . $result . '<br/>';

		if(strpos($result, '_') !== false)
		{
			$gagal++;
			file_put_contents("FAIL_$result.png", $img_str);
		}
	}
	$berhasil	= $total - $gagal;
	echo "<h1>Success $berhasil%, FAIL $gagal%</h1>";
?>
</body>
</html>
