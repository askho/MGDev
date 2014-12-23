<?php
require 'connection.php';
/**
    Pass in an array of images and this will create the thumbnails and 
    add the exif data into the database!
    It will return false if one of the files fail to make a thumb; however it will 
    try to continue to read the rest of the array. If it fails it will not added to the database. 
    It will not delete the failed images. 

    It will not rip EXIF meta data from non jpeg files because they don't have any.
    EX:
        processImages($arrayOfImages);
    RETURN VALUES:
        true : Everything ran smoothly
        Array: Returns array of the file names of the failed images. (Could be used to delete them);
*/
function processImages($arrayOfImages) {
    $return = true;
    $failedFiles = array();
    //Connect to the database!
    global $conn;
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    //Make thumbnails for each photo, then rip the EXIF data and insert it into the database
    foreach($arrayOfImages as $image) {
        if(makeThumb($image) !== true) {
            echo "Failed to proccess $image <br />";
            $return = false;
            array_push($failedFiles, $image);
            continue;
        }
        /*
            Checking file type we will only rip data if it is a jpeg. 
            Only jpeg holds metadata :(
        */
        $fileType = strrpos($image, ".");
        $fileType = substr($image, $fileType);
        if($fileType == ".jpg") {
            $temp = readPhoto($image);
            /*
                Checking if the EXIF exists, if it doesn't we will not save
                the data.
            */
            if($temp["model"] == NULL) {
                $query = sprintf("INSERT INTO photo (photoDate, location) VALUES (CURDATE(), '%s')" , $image);
            } else {
               $query = sprintf("INSERT INTO photo (photoDate, dateTaken, aperture, ISO, focalLength, camera, location) VALUES (CURDATE(), '%s', '%s', '%d', '%s', '%s', '%s')" ,
                    $temp['date'], $temp['aperture'], $temp['iso'], $temp['focal'], $temp['model'], $image); 
            }
        } else {
            $query = sprintf("INSERT INTO photo (photoDate, location) VALUES (CURDATE(), '%s')" , $image);
        }
        /*
            Running the SQL query. 
        */
        if (!mysqli_query($conn, $query)) {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        } 
    }
    echo "Done";
    if($return) {
        return $return;
    }
    return $failedFiles;
    
}
/**
	To use pass in an image
	Remember to pass in the directory to the image.
	The images have to be either .jpg, .png, or .gif
	Change $dest if you want the final destination to be different
	Change $desired_height to fit the height you want. 
	RETURN VALUES :
		-1 = One of the files was not a png jpg or gif
		-2 = Failure to create image for some reason
		-3 = One of the files was not a valid jpg, png, or gif
		true = Everything is good
	example call:
	$arrayOfImages = array("../images/photos/0.jpg"
		,"../images/photos/1.jpg"
		,"../images/photos/2.jpg"
		,"../images/photos/3.jpg");
	make_thumb($arrayOfImages);
*/
function makeThumb($src) {
	
	$dest = "../images/thumbnails/"; //Change this if you want the destiation to be different. /
	$desired_height = 512;
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
	return true;
}
/**
    Pass the soruce of a photo into the the function and it will return the important EXIF values back
    RETURN
        array: Returns an array of the the following:
            filename,
            iso,
            exposure
            focal length
            resolution
            date
            model of camera
*/
function readPhoto($src) {
    $exif = exif_read_data($src, "EXIF");
    $return = array( "name" =>  $exif['FileName']
                    ,"aperture" => $exif['COMPUTED']['ApertureFNumber']
                    ,"iso" => $exif['ISOSpeedRatings']
                    ,"exposure" => $exif['ExposureTime']
                    ,"focal" => $exif['FocalLength']
                    ,"resolution" => $exif['COMPUTED']['Width'] ." x " . $exif['COMPUTED']['Height']
                    ,"date" =>  $exif['DateTimeOriginal']
                    ,"model" => $exif['Model']
    );
    /*echo "<pre>";
        var_dump($exif);
        var_dump($return);
    echo "</pre";*/
    return $return;
}
?>