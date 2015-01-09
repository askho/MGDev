<?php

function deleteCategory($categoryID){
    require '../../php/connection.php';
    echo "<br><br>RUN DELETE CATEGORIES";
    
    // set up query to remove from db
    $sql = "SELECT location FROM Category 
    NATURAL JOIN Albumcategory
    NATURAL JOIN Album
    NATURAL JOIN PhotoAlbum
    NATURAL JOIN Photo
    WHERE categoryID = ".$categoryID.";";

    // connect to database
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    echo "<br><br>connected to db successfully
    <br><br>query returned:";

    // query db and store rows in array
    $photos = getPhotoArray($conn, $sql);
    
    echo "<br><br>DELETE PHOTOS IN FOLDERS";
    deletePhotos($photos);

    // delete category's albums from db
    $sql = "DELETE FROM Album
    USING Category 
    NATURAL JOIN Albumcategory
    NATURAL JOIN Album
    WHERE categoryID = ".$categoryID.";";
    mysqli_query($conn, $sql);

    // delete category from db
    $sql = "DELETE FROM Category WHERE categoryID = ".$categoryID.";";
    mysqli_query($conn, $sql);

    echo "<br><br>Deleted category from database, closing connection";
    
    mysqli_close($conn);
    
}

function deleteAlbum($albumID){
    require '../../php/connection.php';
    echo "<br><br>delalb";

    // set up query to remove from db
    $sql = "SELECT location FROM Photo NATURAL JOIN Photoalbum 
    WHERE albumID = ".$albumID.";";

    // connect to database
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    echo "<br><br>connected to db successfully<br>query returned:";

    // query db and store rows in array
    $photos = getPhotoArray($conn,$sql);
        
    echo "<br><br>DELETE PHOTOS IN FOLDERS";
    deletePhotos($photos);

    // delete album from db
    $sql = "DELETE FROM Album WHERE albumID = ".$albumID.";";
    mysqli_query($conn, $sql);
    
    echo "<br><br>Deleted album from database, closing connection";
    mysqli_close($conn);
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

/*
Returns an array of photo locations given a connection and select query.
*/
function getPhotoArray($conn, $sql){
    //get photos
    $photos = array();
    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
            array_push($photos, $row[0]);
        }
        mysqli_free_result($result);
    }

    if (!empty($photos)){
        foreach($photos as $image){
            echo "<br>found ".$image;
        }
        return $photos;
    } else {
        echo " no photos found";
    }
    return $photos;
}

?>