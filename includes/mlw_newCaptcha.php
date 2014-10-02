<?php 
$string = '';
for ($i = 0; $i < 5; $i++) {
    $string .= chr(rand(97, 122));
}
 
$_SESSION['captcha'] = $string; //store the captcha
 
$image = imagecreatetruecolor(165, 50); //custom image size
$color = imagecolorallocate($image, 113, 193, 217); // custom color
$white = imagecolorallocate($image, 255, 255, 255); // custom background color
imagefilledrectangle($image,0,0,399,99,$white);
imagettftext ($image, 30, 0, 10, 40, $color, '', $_SESSION['captcha']);

header("Content-type: image/png");
imagepng($image);
 
?>