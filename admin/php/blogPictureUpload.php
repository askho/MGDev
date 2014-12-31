<?php
require '../../php/connection.php';
$createdNewDir = 0;
$ds          = "/"; 

$storeFolder = ".." .$ds . ".." .$ds . 'images' . $ds . "blogPhotos";

if (!empty($_FILES)) {

    $tempFile = $_FILES['file']['tmp_name'];         

    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds; 

    $randName = uniqid('', true) . "." . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    
    $targetFile =  $targetPath. $randName;
    move_uploaded_file($tempFile,$targetFile);
    compressImage($randName, 512);
    echo $_SERVER['SERVER_NAME']."/mgdev/images/blogPhotos/".$randName;
}

function compressImage($src, $desired_height) {
    /* Get name of file  can set the destination inside of thumbnails*/
    try
    {
            $src = $_SERVER['DOCUMENT_ROOT'] . "/MGDev/images/blogPhotos/" . $src;
            $im = new Imagick();
            $im->readImage( $src );
            $im->setImageCompressionQuality(75);
            $im->thumbnailImage( 0, $desired_height );
            $im->writeImage();

            $im->destroy();
            return true;
            
    }
    catch(Exception $e)
    {
            echo $e->getMessage();
            return false;
    }
    return true;
}
?>

