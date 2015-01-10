<?php
print_r($_POST);

if(isset($_POST['delete'])){
    require 'delete.php';
    deleteAlbum($_POST['albumID']);
} else if(isset($_POST['create_album'])){
    require 'create.php';
    echo createAlbum($_POST['new_album'],$_POST['parent_categoryID']);
}
?>