<?php
	require 'connection.php';
	if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
	}
	$output = array();
	$albumID = $_POST['album'];
	$sql = "SELECT photoID, photoName, dateTaken, aperture, ISO, focalLength, camera, description, location
	FROM photo NATURAL JOIN photoalbum
	WHERE albumID = $albumID";
	$result = mysqli_query($conn, $sql);
	while($rows = mysqli_fetch_assoc($result)) {
		$output[] = $rows;
	}
	echo json_encode($output);
	mysqli_close($conn);
?>