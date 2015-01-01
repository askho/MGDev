<?php

function deleteCategory($categoryID){
    alert("delcat");
}

function deleteAlbum($categoryID){
    alert("delalb");

}

function deletePhotos($photoArray){
    require '../../php/connection.php';

    // check for empty array
    if (empty($photoArray)){
        return "<br><br>no photos selected";
    }

    $curDir = getcwd();
    echo("<br><br>current directory: ". $curDir);


    // delete all fullphotos
    echo "<br><br>found fullsize images:";
    chdir("../../images/fullPhotos/");
    foreach($photoArray as $image){
        if(file_exists(basename($image))){
            echo "<br>deleted ".$image;
            unlink(basename($image));
        } else {
            echo "<br>file does not exist: ".$image;
        } 
    }

    // delete all photos
    echo "<br><br>found scaled images:";
    chdir("../../images/photos/");
    foreach($photoArray as $image){
        if(file_exists(basename($image))){
            echo "<br>deleted ".$image;
            unlink(basename($image));
        } else {
            echo "<br>file does not exist: ".$image;
        } 
    }

    // delete all thumbnails
    echo "<br><br>found thumbnails:";
    chdir("../../images/thumbnails/");
    foreach($photoArray as $image){
        if(file_exists(basename($image))){
            echo "<br>deleted ".$image;
            unlink(basename($image));
        } else {
            echo "<br>file does not exist: ".$image;
        }
    }

    // return to original directory
    chdir($curDir);
    echo("<br><br>returned to: ". $curDir);

    // set up query to remove from db
    $sql="DELETE FROM Photo
        WHERE";
    $i = 0;
    foreach($photoArray as $image){
        if($i != 0){
            $sql .= "' OR location = '".basename($image);
        } else {
            $sql .= " location = '".basename($image);
        }
        $i++;
    }
    $sql.="';";
    echo "<br><br>".$sql;

    // connect to database
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    echo "<br><br>connected to db successfully<br>query returned:";

    // query db
    echo "query success: " . mysqli_query($conn, $sql);    
    
    //return success
    return "deleted";
}
?>