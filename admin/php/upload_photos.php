<?php
require '../../php/connection.php';
$createdNewDir = 0;
$ds          = "/";  //1

$storeFolder = ".." .$ds . ".." .$ds . 'images' . $ds . "fullPhotos";   //2

if (!empty($_FILES)) {

    $tempFile = $_FILES['file']['tmp_name'];          //3             

    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4

    $randName = uniqid('', true) . "." . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    
    $targetFile =  $targetPath. $randName;  //5
    
    move_uploaded_file($tempFile,$targetFile); //6
    
    echo $randName;
    //echo $storeFolder . $ds . $randName;
} else if(!isset($_POST["jsonText"])) {
        header('Location: ../upload.php');
    } else {
    //Output the actual html page if needed. 
    ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
    <html lang="en">
    <head>
        <link rel="stylesheet" href="../../css/bootstrap.css">
        <link rel="stylesheet" href="../../css/style.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic' rel='stylesheet' type='text/css'>
        <script src="../../js/main.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <title>Proccessing Photos</title>
    </head>
    <body>
   <nav class="navbar navbar-default">
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
        <a class="navbar-brand" href="#">
            <img class = "hidden-xs" src = "../../images/style/logo.png" alt = "logo"/>
            <img class = "visible-xs" src = "../../images/style/logo.png" alt = "logo" width = "auto" height = "50"/>

        </a>
        </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
                <li><a href = "#">Proccessing Images</a></li>
            </ul>
        </div>
    </div>
    </nav>
    <!-- Progress bar holder -->
    <div class = "container">
         <div class="panel panel-default" >
          <div class="panel-body">
            <progress id = "progress" value="0">
            </progress>
            <p align = "center" id = "percentDone"></p>
            </div>
        </div>

         <div class="panel panel-default" style ="height: 200px; overflow: auto;">
          <div class="panel-body">
                <h1>Warnings</h1>
                 <div id = "warnings"></div>
            </div>
        </div>
         <div class="panel panel-default" style ="height: 400px; overflow: auto;">
          <div class="panel-body">
                <h1>Status</h1>
                <div id="information" style="width"></div>
            </div>
        </div>

        
        
    </div>

    </body>
    </html>
    <?php
    //Flush the output stream
    echo str_repeat(' ',1024*64);
    ini_set('memory_limit', '256M');
    //Fix json , decode and then proccess the images.
    $json = str_replace("\\r\\n", '', $_POST["jsonText"]);
    $json = json_decode($json);
    if(isset($_POST["albumName"])) {
        $albumName = $_POST["albumName"];
    }
    if(isset($_POST["albumNameDropDown"]) && $_POST["albumNameDropDown"] != "null") {
        $albumName = $_POST["albumNameDropDown"];
    }
    if(isset($_POST["category"])) {
        $categoryName = $_POST["category"];
    }

    if(isset($_POST["categoryDropDown"]) && $_POST["categoryDropDown"] != "null") {
        $categoryName = $_POST["categoryDropDown"];
    }
    processImages($json, $albumName, $categoryName);
}

error_reporting(0); //Turn off error reporting because we already deal with it manually. 
/**
    Pass in an array of images and this will create the thumbnails and 
    add the exif data into the database!
    It will return false if one of the files fail to make a thumb; however it will 
    try to continue to read the rest of the array. If it fails it will not added to the database. 
    It will not delete the failed images. 

    It will not rip EXIF meta data from non jpeg files because they don't have any.
    EX:
        processImages($arrayOfImages, "Album1", "Wedding Photos");
    RETURN VALUES:
        true : Everything ran smoothly
        Array: Returns array of the file names of the failed images. (Could be used to delete them);
*/
function processImages($arrayOfImages, $albumName, $categoryName) {
    ini_set('memory_limit','128M');
    $return = true;
    $failedFiles = array();
    $count = 0;
    $sizeOfArray =  sizeof($arrayOfImages);
    $query = "INSERT INTO photo (photoDate, dateTaken, aperture, ISO, focalLength, camera, location) VALUES";
    $last;
    $first = null;
    $photoDest = "images/photos/"; //Change this if you want the destiation to be different. /
    $fullHeight = 2048;
    $thumbnailDest = "images/thumbnails/";
    $thumbHeight = 256;
    //Connect to the database!
        
    global $conn;
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    //Make thumbnails for each photo, then rip the EXIF data and insert it into the database
    foreach($arrayOfImages as $image) {
        set_time_limit(60);
        $src = "/images/fullPhotos/".$image;
        if(makeImages($src, $photoDest, $fullHeight, false) !== true) {
            echo '<script>document.getElementById("warnings").innerHTML = "Failed to proccess'. $image . '<br />" 
                + document.getElementById("warnings").innerHTML</script>';
            $return = false;
            array_push($failedFiles, $image);
            $sizeOfArray--;
            continue;
        }
        makeImages($src, $thumbnailDest, $thumbHeight, true);
        /*
            Checking file type we will only rip data if it is a jpeg. 
            Only jpeg holds metadata :(
        */
        $fileType = strrpos($src, ".");
        $fileType = substr($src, $fileType);
        if($fileType == ".jpg" || $fileType == ".JPG") {
            $temp = readPhoto($src);
            /*
                Checking if the EXIF exists, if it doesn't we will not save
                the data.
            */
            if($temp["model"] == NULL) {
                $query = $query . sprintf("(CURDATE(), null, null, null, null, null, '%s'), " , $image);
            } else {
               $query = $query . sprintf("(CURDATE(), '%s', '%s', '%d', '%s', '%s', '%s'), " , $temp['date'], $temp['aperture'], $temp['iso'], $temp['focal'], $temp['model'], $image); 
            }
        } else {
            $query =  $query . sprintf("(CURDATE(), null, null, null, null, null, '%s'), " , $image);
        }
        //Update the last and first so we know what to put in which album.
        $last = $image;
        if($first == null) {
            $first = $image;
        }
        /*
            Update the progress bar
        */
        $percent = ++$count / $sizeOfArray;
        echo '<script language="javascript">
            document.getElementById("progress").value = '.$percent.';
            document.getElementById("information").innerHTML="'.$image.' processed.<br />" + document.getElementById("information").innerHTML;
            $("#percentDone").html("'.floor($percent * 100) .' %");
            </script>';
        echo str_repeat(' ',1024*64);
        flush();
    }
    $query = substr($query,0,strlen($query)-2); //We need to trim off the extra comma that we put in.
    /*
        Running the SQL query. 
    */
    if (!mysqli_query($conn, $query)) {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
    putIntoAlbum($first, $last, $albumName, sizeof($arrayOfImages));
    putIntoCategory($categoryName, $albumName);
    /*
        Say that we are done!
    */
    //echo '<script language="javascript">window.location.replace("../upload.php");</script>';
    mysqli_close($conn);
    if($return) {
        return $return;
    }
    return $failedFiles;
    
}
/**
    This functin adds the photos found from the start to end into the 
    photo album in the database.
    Parameters 
        $start = The directory to the first image uploaded
        $end   = The directory to the last image uploaded
        $albumName = The album to reference
        $length = length of array that will be added
    Return values:
        -1 = Sql failure
        true = everything good
*/
function putIntoAlbum($start, $end, $albumName, $length) {
    global $conn;
    $id;
    $query = "SELECT albumID FROM album WHERE albumName = '$albumName'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 0) {
        $query = "INSERT INTO album (albumName, albumDate) VALUES ('$albumName', CURDATE())";
        $result = mysqli_query($conn, $query);
        $query = "SELECT albumID FROM album WHERE albumName = '$albumName'";
        $result = mysqli_query($conn, $query);
    } 
    while($row = mysqli_fetch_assoc($result)) {
        $id = $row["albumID"];
    }
    $query = "
    INSERT INTO photoalbum (photoID, albumID) 
    SELECT photoID, '$id'
    FROM photo
    WHERE photoID >= (SELECT photoID FROM photo WHERE location = '$start')
    AND photoID <= (SELECT photoID FROM photo WHERE location = '$end')";
    if($length == 1) {
        $query = "
    INSERT INTO photoalbum (photoID, albumID) 
    SELECT photoID, '$id'
    FROM photo
    WHERE photoID = (SELECT photoID FROM photo WHERE location = '$start')";
    }
    if (!mysqli_query($conn, $query)) {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
            return -1;
    } 
    return true;
}
/**
        Puts the album into the right category

*/
function putIntoCategory($categoryName, $albumName){
    global $conn;
    $catID;
    $albumID;
    $query = "SELECT categoryID FROM category WHERE categoryName = '$categoryName'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 0) {
        $query = "INSERT INTO category (categoryName) VALUES ('$categoryName')";
        $result = mysqli_query($conn, $query);
        $query = "SELECT categoryID FROM category WHERE categoryName = '$categoryName'";
        $result = mysqli_query($conn, $query);
    } 
    while($row = mysqli_fetch_assoc($result)) {
        $catID = $row["categoryID"];
    }
    $query = "SELECT albumID FROM album WHERE albumName = '$albumName'";
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_assoc($result)) {
        $albumID = $row["albumID"];
    }
    $query = "
    INSERT INTO albumCategory (categoryID, albumID) 
    VALUES('$catID', $albumID)";
    if (!mysqli_query($conn, $query)) {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
            return -1;
    } 
    return true;
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
    makeImages($arrayOfImages, 1024, "../../images/photos/");
*/
function makeImages($src, $dest, $desired_height, $isThumb) {
    /* Get name of file  can set the destination inside of thumbnails*/
    $startAt = strrpos($src, "/");
    $finalDest = $dest . substr($src, ++$startAt);
    try
    {
            $src = $_SERVER['DOCUMENT_ROOT'] . "/MGDev/" . $src;
            $dest = $_SERVER['DOCUMENT_ROOT'] . "/MGDev/" . $finalDest;
            $im = new Imagick();
            $im->readImage( $src );
            $imageHeight = $im->getImageHeight();
            $imageWidth = $im->getImageWidth();
            $im->setImageCompressionQuality(75);
            $im->thumbnailImage( 0, $desired_height );

            $im->setImageFileName($dest);
            if($isThumb == false) {
                $watermarkPath = $_SERVER['DOCUMENT_ROOT'] . "/MGDev/images/style/watermark.png"; 
                $watermark = new Imagick();
                $watermark->readImage($watermarkPath);
                if($imageHeight > $imageWidth) {
                    $watermark->scaleImage(0, $imageHeight/5);
                } else {
                    $watermark -> scaleImage($imageWidth/5, 0);
                }
                
                $im->compositeImage($watermark, imagick::COMPOSITE_OVER, 0, 0);
            }
            $im->writeImage();
            $im->destroy();
            return true;
            
    }
    catch(Exception $e)
    {
            echo '<script>document.getElementById("warnings").innerHTML = " '.$e->getMessage(). '<br />" 
                + document.getElementById("warnings").innerHTML</script>';
            return $file;
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
    $src = "../../" .$src;
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

