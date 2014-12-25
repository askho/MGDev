<?php

$createdNewDir = 0;
$ds          = DIRECTORY_SEPARATOR;  //1

$storeFolder = ".." .$ds . ".." .$ds . 'images' . $ds . "photos";   //2

if (!empty($_FILES)) {

    $tempFile = $_FILES['file']['tmp_name'];          //3             

    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4

    $randName = chr( mt_rand( 97 ,122 ) ) .substr( md5( time( ) ) ,1 ) . "." . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    
    $targetFile =  $targetPath. $randName;  //5
    
    move_uploaded_file($tempFile,$targetFile); //6
    
    echo 'images' . $ds . "photos" . $ds .$randName;
    //echo $storeFolder . $ds . $randName;
} else {
    echo $_POST["jsonText"];
    echo $_POST["albumName"];

}












// not needed atm

function createTempDir(){
    // get currrent directory
    $curDir = getcwd();
    echo "current directory: " . $curDir ;
    echo "<br>";

    // generate temp folder

    // create new directory name
    $randomString = chr( mt_rand( 97 ,122 ) ) .substr( md5( time( ) ) ,1 );
    echo "name for new directory: " . "\\uploads\\" . $randomString;
    echo "<br>";

    // create new directory
    $newDir = $curDir . "\\uploads\\" . $randomString;
    while(!$createdNewDir){
        if (!file_exists($newDir)) {
            mkdir($newDir, 0777, true);
            $createdNewDir = 1;
        }
    }
    echo "created new directory";
    echo "<br>";

    // set location to store photos
    $storeFolder = "\\uploads\\" . $randomString . "\\";   //2
    echo $storeFolder;
    echo "<br>";
}

?> 
