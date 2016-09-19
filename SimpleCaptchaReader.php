<?php
/**
* Class to read simple captcha
* author	: klampok.child@gmail.com
* website	: playstore-api.com
*/

// Extract of cropped digit code (pixel map)
if(empty(SimpleCaptchaReader::$DIGIT))
	SimpleCaptchaReader::$DIGIT	= array(
		'00011110000011111100011000011011000000111100000011011000011000111111000001111000', //DIGIT 0
		'001000000101100000011111111111111111111100000000010000000001', //DIGIT 1
		'00100000010110000011110000011110000011011000011001110011000101111000010011000001', //DIGIT 2
		'01000000101100000011100000000110001000011000100001110111001101110111100010001100', //DIGIT 3
		'00000110000000111000000110100000110010000110001000111111111111111111110000001000', //DIGIT 4
		'11111001001111100110100010001110010000011001000001100110001110001111100000011100', //DIGIT 5
		'00111111000111111110110001001110001000011000100001110011001101100111100000001100', //DIGIT 6
		'10000000111000000111100000110010000110001000110000100110000011110000001110000000', //DIGIT 7
		'00100011000111011110110111001110001000011000100001110111001101110111100010001100', //DIGIT 8
		'00110000000111100110110011001110000100011000010001110010001101111111100011111100' //DIGIT 9
	);
class SimpleCaptchaReader
{
	static $DIGIT	= null;
	
	static function readImageFromFile($file)
	{
		return self::readImage(file_get_contents($file));
	}

	static function readImageFromURL($url)
	{
		$ch			= curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$img_str	= curl_exec($ch);
		curl_close($ch);
		return self::readImage($img_str);
	}

	static function readImage($img_str)
	{
		$img			= imagecreatefromstring($img_str);
		$digit_color	= imagecolorclosest($img, 10, 10, 10); // search 
		$result			= "";
		$tmp			= array();
		$wired			= false;
		$im_x			= imagesx($img);
		$im_y			= imagesy($img);
		$trim_start		= $im_y;
		$trim_end		= 0;

		for ($x=0; $x < $im_x ; $x++)
		{
			$found		= false;
			$lines		= array();
			$start		= null;
			$end		= 0;
			$last_match	= false;
			for ($y=0; $y < $im_y ; $y++)
			{
				$match			= imagecolorat($img, $x, $y) == $digit_color;
				$lines[]		= $match ? 1 : 0;
				if($match)
				{
					if(!$found)
					{
						if(is_null($start)) $start = $y;
						$found	= true;
					}

				}elseif($last_match)
				{
					if($y > $end) $end = $y;
				}
				$last_match	= $match;
			}
			if($found){
				if($start	< $trim_start)	$trim_start	= $start;
				if($end		> $trim_end	)	$trim_end	= $end;
				$tmp[]	= $lines;
				$wired	= true;
			}else
			{
				if($wired){
					// CROP DIGIT
					foreach ($tmp as $tx => &$value)
					{
						$value	= implode('',array_slice($value, $trim_start, $trim_end - $trim_start));
					}
					$str	= implode('', $tmp);
					$found_number	= '_';
					foreach(self::$DIGIT as $number => &$code){
						if($code == $str){
							$found_number	= "$number";
							break;
						}
					}

					$result	.= $found_number;
					$tmp	= array();
					$wired	= false;
					$trim_start	= $im_y;
					$trim_end	= 0;
				}
			}
		}
		return $result;		
	}
}