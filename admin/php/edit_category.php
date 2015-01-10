<?php
print_r($_POST);

if (isset($_POST['delete'])){
    require 'delete.php';
    deleteCategory($_POST['categoryID']);
    header("location:../edit_categories.php");
} else if (isset($_POST['create_category'])) {
    require 'create.php';
    echo createCategory($_POST['new_category']);
}
?>