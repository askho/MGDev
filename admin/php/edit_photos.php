<?php
print_r($_POST);

require 'delete.php';

// decode images.
$photos = json_decode($_POST["selected_photos"]);

// set destination category and album
if (isset($_POST['categoryDropDown'])){
    $categoryName = $_POST['categoryDropDown'];
} else {
    $categoryName = $_POST['category'];
}
if (isset($_POST['albumNameDropDown'])){
    $albumName = $_POST['albumNameDropDown'];
} else {
    $albumName = $_POST['albumName'];
} 

if(isset($_POST["delete"])){
    echo deletePhotos($photos);
} if (isset($_POST["move"])){
    echo movePhotos($photos, $categoryName, $albumName);
} 

/*
Takes an array of photo locations and moves updates the category and album of the photos in the database. 
If the category or album do not already exist, a new one is created.
*/
function movePhotos($photos, $category, $album){
    require '../../php/connection.php';
    require 'create.php';

    echo "<h3>move photos selected";
    echo "<h4>destination: ".$category." ".$album;    
    echo "<h4>photos: ";

    // get photo basenames
    $arraySize = sizeof($photos);
    for ($i = 0; $i < $arraySize; $i++) {
        $photos[$i] = basename($photos[$i]);
        echo $photos[$i];
    }

    // create category if it doesnt exist
    $categoryID = createCategory($category);
    $albumID = createAlbum($album, $categoryID);
    echo "<h4>categoryID: ".$categoryID;
    echo "<h4>albumID: ".$albumID;

    // update album for each photo
    $sql ="
    UPDATE photoalbum NATURAL JOIN photo
    SET albumID = ".$albumID."
    WHERE";

    $i = 0;
    foreach($photos as $photo){
        if($i != 0){
            $sql .= "' OR location = '".$photo;
        } else {
            $sql .= " location = '".$photo;
        }
        $i++;
    }
    $sql.="';";
    echo "<br><br>".$sql;

    if (!mysqli_query($conn, $sql)) {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }

    header("location:../editPhotos.php?albumID=".$_POST['current_albumID']."&albumName=".$_POST['current_albumName']);
}
?>