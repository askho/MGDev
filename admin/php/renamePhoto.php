<?php
require '../../php/connection.php';

// update photoName
$sql = "
UPDATE photo
SET photoName = ?
WHERE photoID = ?;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $_POST['name'], $_POST['photoID']);
if (false===$stmt->execute()) {
    echo "failed to update the database";
} else {
    echo "success";
}

$stmt->close();

?>