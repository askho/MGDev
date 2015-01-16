<?php
require '../../php/connection.php';

// update photodesc
$sql = "
    UPDATE photo
    SET description = ?
    WHERE photoID = ?;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $_POST['desc'], $_POST['photoID']);
if (false===$stmt->execute()) {
    echo "fail";
} else {
    echo "success";
}

?>