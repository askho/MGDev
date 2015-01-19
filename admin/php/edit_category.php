<?php
//print_r($_POST);

if (isset($_POST['delete'])){
    require 'delete.php';
    deleteCategory($_POST['categoryID']);
    header("location:../edit_categories.php");
} else if (isset($_POST['create_category'])) {
    require 'create.php';
    echo createCategory($_POST['new_category']);
} else if (isset($_POST['rename'])){
    echo renameCategory($_POST['original_category_name'],$_POST['new_name']);
}

function isNullOrEmpty($str){
    if (trim($str)==='' || empty($str) || $str == "null"){
        return 1;
    }
}

function renameCategory($originalName,$newName){
    require '../../php/connection.php';
    //echo "<h4>".$originalName.$newName;

    if (isNullOrEmpty($newName)){
        return "<h4><script> 
            window.alert('new category name not entered');
        window.location.replace(\"../edit_categories.php\");
        </script>";
    } else {

        // check if category exists
        $sql = "
    SELECT categoryID FROM Category
    WHERE categoryName = ?;";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $newName);
        $stmt->execute();
        $stmt->bind_result($categoryID);

        while ($stmt->fetch()) {
            return "<h4><script> 
            window.alert('Category name already exists');
        window.location.replace(\"../edit_categories.php\");
        </script>";
        }

        // update categoryName
        $sql = "
    UPDATE Category
    SET categoryName = ?
    WHERE categoryName = ?;";
        echo "<h4>$sql";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $newName, $originalName);
        if (false===$stmt->execute()) {
            die('execute() failed');
        }
    }
    header("location:../edit_categories.php");
}
?>