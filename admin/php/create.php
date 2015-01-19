<?php

function createCategory($categoryName){
    $callerFile = basename(debug_backtrace()[0]["file"]);

    //echo "<h1>Creating Category: ".$categoryName;

    if (isNullOrEmpty($categoryName)){
        return "<h4><script> 
            window.alert('new category name not entered');
        window.location.replace(\"../edit_categories.php\");
        </script>";
    } else {

        // connect to database
        require '../../php/connection.php';
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        echo "<h3>Connected to DB successfully";

        // Query database to check if category exists - sql injection protected
        $sql = "SELECT categoryID FROM Category 
        WHERE categoryName = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $categoryName);
        $stmt->execute();
        $stmt->bind_result($categoryID);

        while ($stmt->fetch()) {
            if (strcmp($callerFile,"edit_photos.php") == 0){
                return $categoryID;
            }
            return "<h4><script> 
            window.alert('found existing category, ID: ".$categoryID."');
        window.location.replace(\"../edit_categories.php\");
        </script>";
        }

        echo "<h3>Adding Category to DB";
        $sql = "INSERT INTO category (categoryName) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $categoryName);
        if (false===$stmt->execute()) {
            die('execute() failed');
        }

        if (strcmp($callerFile,"edit_photos.php") == 0){
            $stmt->close();
            $sql = "SELECT categoryID FROM Category 
        WHERE categoryName = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $categoryName);
            $stmt->execute();
            $stmt->bind_result($categoryID);

            while ($stmt->fetch()) {
                return $categoryID;
            }
        }
    }
    header("location:../edit_categories.php");
}

function createAlbum($albumName, $parentCategoryID){
    $callerFile = basename(debug_backtrace()[0]["file"]);

    if (isNullOrEmpty($albumName)){
        return "<h4><script> 
            window.alert('new album name not entered');
        window.location.replace(\"../editAlbums.php?categoryID=".$parentCategoryID."\");
        </script>";
    } else {

        // connect to database
        require '../../php/connection.php';
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        //echo "<h3>Connected to DB successfully";

        // Query database to check if album exists in category - sql injection protected
        $sql = "SELECT albumID FROM Album NATURAL JOIN AlbumCategory 
        WHERE albumName = ? AND categoryID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $albumName, $parentCategoryID);
        $stmt->execute();
        $stmt->bind_result($albumID);

        while ($stmt->fetch()) {
            if (strcmp($callerFile,"edit_photos.php") == 0){
                return $albumID;
            }
            return "<h4><script> 
            window.alert('Album name already exists');
        window.location.replace(\"../editAlbums.php?categoryID=".$parentCategoryID."\");
        </script>";
        }

        echo "<h3>Adding album to DB";
        $sql = "INSERT INTO album (albumName,albumDate) VALUES (?, CURDATE());";
        echo "<h4>$sql";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $albumName);
        if (false===$stmt->execute()) {
            die('execute() failed');
        }

        $sql = "SELECT MAX(albumID) FROM Album;";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($newAlbumID);

        if($stmt->fetch()){
            echo "<h4>newest albumID: ".$newAlbumID;
            $stmt->close();
        }

        echo "<h4>".$parentCategoryID. " ". $newAlbumID;

        echo "<h4>inserting into albumcategory";
        $sql = "INSERT INTO albumCategory (categoryID, albumID) VALUES (?, ?);";
        echo "<h4>";
        $stmt = $conn->prepare($sql);
        //var_dump($stmt);
        $stmt->bind_param("ss", $parentCategoryID, $newAlbumID);
        if (false===$stmt->execute()) {
            die('execute() failed');
        }

        if (strcmp($callerFile,"edit_photos.php") == 0){
            return $newAlbumID;
        }

        header("location:../editAlbums.php?categoryID=".$parentCategoryID);
    }
}
?>