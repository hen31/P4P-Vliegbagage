<?php
session_start();
$klinkers = array("a", "e", "i", "o", "u");
$medeklinkers = array("b", "c", "d", "f", "g", "h", "k", "l", "m", "n", "p", "r", "s", "t", "v", "w", "z");
$randomnr = null;
for($i = 0; $i < 7; $i++){
    if($i & 1) {
        $rand = array_rand($klinkers, 1);
        $randomnr .= $klinkers[$rand];
    }
    else{
        $rand = array_rand($medeklinkers, 1);
        $randomnr .= $medeklinkers[$rand];
    }
}
$_SESSION["hash"] = sha1(strtolower($randomnr) ."iuherkdjcby8rhb");

$im = imagecreatetruecolor(300, 60);

$white = imagecolorallocate($im, 255, 255, 255);
$grey = imagecolorallocate($im, 128, 128, 128);
$background = imagecolorallocate($im, 240, 240, 240);

imagefilledrectangle($im, 0, 0, 300, 60, $background);

$font = dirname(__FILE__) .'/font.ttf';

imagettftext($im, 35, 0, 0, 40, $grey, $font, $randomnr);


header("Expires: Wed, 1 Jan 1997 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

header("Content-Type: image/png");
imagepng($im);
imagedestroy($im);
?>
