<?php
print_r($_POST);

if(isset($_POST['delete'])){
    require 'delete.php';
    deleteAlbum($_POST['albumID']);
} else if(isset($_POST['create_album'])){
    require 'create.php';
    echo createAlbum($_POST['new_album'],$_POST['parent_categoryID']);
} else if (isset($_POST['rename'])){
    echo renameAlbum($_POST['original_album_name'], $_POST['new_name'], $_POST['albumID'],$_POST['parent_categoryID']);
}

function renameAlbum($originalName,$newName,$albumID,$parentCategoryID){
    require '../../php/connection.php';
    echo "<h4>".$originalName.$newName.$parentCategoryID;

    // check if category exists
    $sql = "SELECT albumID FROM Album NATURAL JOIN AlbumCategory 
        WHERE albumName = ? AND categoryID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $newName, $parentCategoryID);
    $stmt->execute();
    $stmt->bind_result($albumID);

    while ($stmt->fetch()) {
        echo "exists already";
        return "<h4><script> 
            window.alert('Album name already exists');
        window.location.replace(\"../editAlbums.php?categoryID=".$parentCategoryID."\");
        </script>";
    }
    
    // update albumName
    $sql = "
    UPDATE album NATURAL JOIN albumcategory
    SET albumName = ?
    WHERE albumName = ?;";
    echo "<h4>$sql";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $newName, $originalName);
    if (false===$stmt->execute()) {
        die('execute() failed');
    }

    header("location:../editAlbums.php?categoryID=".$parentCategoryID);
    
}
?>