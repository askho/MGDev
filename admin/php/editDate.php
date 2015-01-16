<?php
require '../../php/connection.php';

// update photodesc
$sql = "
    UPDATE photo
    SET dateTaken = ?
    WHERE photoID = ?;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $_POST['date'], $_POST['photoID']);
if (false===$stmt->execute()) {
    echo "fail";
} else {
    echo "success";
}

?>