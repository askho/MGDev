<?php
print_r($_POST);

if(isset($_POST['delete'])){
    require 'delete.php';
    deleteCategory($_POST['categoryID']);
}
?>