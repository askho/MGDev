<?php
require '../../php/connection.php';
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$postID = $_POST['editPostID2'];
$body = mysqli_real_escape_string($conn, $_POST['editBody']);
$title = htmlspecialchars($_POST['editTitle']);
$sql = "UPDATE blog
	SET title = '$title', body = '$body', dateCreated = NOW()
	WHERE postID = '$postID'";

if (mysqli_query($conn, $sql)) {
    echo "1";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>