<?php
require '../../php/connection.php';
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$postID = $_POST['postID2'];
$sql = "DELETE FROM blog
	WHERE postID = '$postID'";

if (mysqli_query($conn, $sql)) {
    echo "1";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>