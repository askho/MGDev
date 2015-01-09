<?php
print_r($_POST);

require 'delete.php';

// decode and delete images.

$photos = json_decode($_POST["selected_photos"]);

if(isset($_POST["delete"])){
    echo deletePhotos($photos);
}
?>