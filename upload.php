<?php
$ds          = DIRECTORY_SEPARATOR;  //1

$storeFolder = 'uploads';   //2

if (!empty($_FILES)) {
    
    $tempFile = $_FILES['file']['tmp_name'];          //3             

    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4

    $targetFile =  $targetPath. $_FILES['file']['name'];  //5

    $imageFileType = pathinfo($targetFile,PATHINFO_EXTENSION);
    
    
    if($imageFileType == "jpg" OR $imageFileType == "png" OR $imageFileType == "gif" OR $imageFileType == "PNG"){

        move_uploaded_file($tempFile,$targetFile); //6


    }else {
       
    }
}
?> 
- See more at: http://www.startutorial.com/articles/view/how-to-build-a-file-upload-form-using-dropzonejs-and-php#sec1