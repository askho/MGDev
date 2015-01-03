<?php

function createCategory($categoryName){
    echo "<h1>Creating Category: ".$categoryName;
    
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
    
    header("location:../edit_categories.php");
}

?>