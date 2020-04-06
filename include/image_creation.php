<?php

// we save the new image here
define('PRODUCT_IMG_DIR', '../admin/images/');

/*
	Create a thumbnail of $srcFile and save it to $destFile.
	The thumbnail will be $width pixels.
*/
function createThumbnail($srcFile, $destFile, $width, $height, $quality = 75)
{
	$thumbnail = '';

	if (file_exists($srcFile)  && isset($destFile))
	{
		$size        = getimagesize($srcFile);
		$w           = number_format($width, 0, ',', '');
		$h           = number_format($height, 0, ',', '');

		$thumbnail =  copyImage($srcFile, $destFile, $w, $h, $quality);
	}

	// return the thumbnail file name on sucess or blank on fail
	return basename($thumbnail);
}


/*
	Copy an image to a destination file. The destination
	image size will be $w * $h pixels
*/
function copyImage($srcFile, $destFile, $w, $h, $quality = 75)
{
    $tmpSrc     = pathinfo(strtolower($srcFile));
    $tmpDest    = pathinfo(strtolower($destFile));
    $size       = getimagesize($srcFile);
	$type = $tmpDest['extension'];

    if ($tmpDest['extension'] == "gif" || $tmpDest['extension'] == "jpg" || $tmpDest['extension'] == "jpeg")
    {
       $destFile  = substr_replace($destFile, $type, -3);
       $dest      = imagecreatetruecolor($w, $h);
       //imageantialias($dest, TRUE);
    } elseif ($tmpDest['extension'] == "png") {
       $dest = imagecreatetruecolor($w, $h);
       //imageantialias($dest, TRUE);
    } else {
      return false;
    }

    switch($size[2])
    {
       case 1:       //GIF
           $src = imagecreatefromgif($srcFile);
           break;
       case 2:       //JPEG
           $src = imagecreatefromjpeg($srcFile);
           break;
       case 3:       //PNG
           $src = imagecreatefrompng($srcFile);
           break;
       default:
           return false;
           break;
    }

    imagecopyresampled($dest, $src, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);

    switch($size[2])
    {
       case 1:
       case 2:
           imagejpeg($dest,$destFile, $quality);
           break;
       case 3:
           imagepng($dest,$destFile);
    }
    return $destFile;

}


?>
