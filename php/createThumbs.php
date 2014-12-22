<?php
/**
	To use pass in an array of images into $srcArray.
	Remember to pass in the directory to the image.
	The images have to be either .jpg, .png, or .gif
	Change $dest if you want the final destination to be different
	Change $desired_height to fit the height you want. 
	RETURN VALUES :
		-1 = One of the files was not a png jpg or gif
		-2 = Failure to create image for some reason
		-3 = One of the files was not a valid jpg, png, or gif
		1 = Everything is good
	example call:
	$arrayOfImages = array("../images/photos/0.jpg"
		,"../images/photos/1.jpg"
		,"../images/photos/2.jpg"
		,"../images/photos/3.jpg");
	make_thumb($arrayOfImages);
*/
function makeThumb($srcArray) {
	
	$dest = "../images/thumbnails/"; //Change this if you want the destiation to be different. /
	$desired_height = 512;
	foreach($srcArray as $src) {
		/* Get name of file  can set the destination inside of thumbnails*/
		$startAt = strrpos($src, "/");
		$finalDest = $dest . substr($src, ++$startAt);
		/*
		Getting the file type
		*/
		$fileType = strrpos($src, ".");
		$fileType = substr($src, $fileType);
		if($fileType != ".jpg" && $fileType != ".png" && $fileType != ".gif") {
			return -1;
		}
		/* read the source image */
		if($fileType == ".jpg") {
			if(!($source_image = imagecreatefromjpeg($src))) {
				return -3;
			}
		} else if($fileType == ".png") {
			if(!($source_image = imagecreatefrompng($src))) {
				return -3;
			}
		} else if($fileType == ".gif") {
			if(!($source_image = imagecreatefromgif($src))) {
				return -3;
			}
		}
		$width = imagesx($source_image);
		$height = imagesy($source_image);
		
		/* find the "desired height" of this thumbnail, relative to the desired width  */
		$desired_width = floor($width * ($desired_height / $height));
		
		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
		
		/* copy source image at a resized size */
		imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
		
		/* create the physical thumbnail image to its destination */
		if($fileType == ".jpg") {
			if(!imagejpeg($virtual_image, $finalDest))
				return -2;
		} else if($fileType == ".png") {
			if(!imagepng($virtual_image, $finalDest)) {
				return -2;
			}
		} else if($fileType == ".gif") {
			if(!imagegif($virtual_image, $finalDest)) {
				return -2;
			}
		}
	}
	return 1;
}
?>