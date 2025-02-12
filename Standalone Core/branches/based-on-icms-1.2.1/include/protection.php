<?php 
/**
* Config emailprotection.php
*
* @author	Kuba Zygmunt (kuba.zygmunt@gmail.com)
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		LICENSE.txt
* @package	core
* @since		1.2
* @version		$Id: protection.php 8662 2009-05-01 09:04:30Z pesianstranger $
*/

include '../mainfile.php';
$font = ICMS_ROOT_PATH.'/class/captcha/fonts/'.$icmsConfigPersona['email_font'];
// If you use TTF fontLength = 8
// If you don't you may put 7 :-)
$fontSize = intval($icmsConfigPersona['email_font_len']);
$height = $fontSize*1.2+14; // height of image
$emailAddress = urldecode(base64_decode($_GET['p']));

Header( "Content-type: image/png");

$emailAddressLength = strlen($emailAddress);
$width = $emailAddressLength * ($fontSize*1.00);

$image = imagecreate($width,$height);


/********* COLORS ************/
$fg = $icmsConfigPersona['email_cor'];
$red = 100;
$green = 100;
$blue = 100;
if( eregi( "[#]?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})", $fg, $ret ) )
{
	$red = hexdec( $ret[1] );
	$green = hexdec( $ret[2] );
	$blue = hexdec( $ret[3] );
}
if ($icmsConfigPersona['email_shadow']!=""){
	$fg = $icmsConfigPersona['email_shadow'];
	$sred = 100;
	$sgreen = 100;
	$sblue = 100;
	if( eregi( "[#]?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})", $fg, $ret ) )
	{
		$sred = hexdec( $ret[1] );
		$sgreen = hexdec( $ret[2] );
		$sblue = hexdec( $ret[3] );
	}
	$shadow = imagecolorallocate($image, $sred,$sgreen,$sblue);
}
$white = ImageColorAllocate($image,255,255,255);
$frente = imagecolorallocate($image, $red,$green,$blue);


/*****************************/

ImageColorTransparent($image, $white);
ImageFilledRectangle($image,0,0,$width,$height,$white);


// Add the text using TTF
if ($icmsConfigPersona['email_shadow']!=""){
	imagettftext($image, $fontSize, 0, intval($icmsConfigPersona['shadow_y']), $height-intval($icmsConfigPersona['shadow_x'])-10, $shadow , $font, $emailAddress);
}
imagettftext($image, $fontSize, 0, 0, $height-10, $frente, $font, $emailAddress);


// If you don't want to use TTF fonts, and display default font uncomment line above

// Add the text using default font
// ImageString($image,3,2,2,$emailAddress,$fontColor);
ImagePNG($image);

ImageDestroy($image);

?>