<?php
session_start();
$random = rand(1000, 9999);
$_SESSION['rand'] = $random;
$image = imagecreatetruecolor(100, 38);
$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 0, 0, 0);
imagefilledrectangle($image, 0, 0, 0, 0, $black);
$font = dirname(__FILE__) . '/fonts/17330.otf';
imagettftext($image, 25, 7, 10, 40, $white, $font, $random);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s", 10000) . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

header("Content-Type:image/png");
imagegif($image);
imagedestroy($image);