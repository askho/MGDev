<?php
	require 'connection.php';
	if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
	}
	$output = array();
	$sql = "SELECT location FROM photo WHERE photoID IN ( SELECT min(photoID) AS firstPhoto FROM photoalbum GROUP BY albumID )";
	$result = mysqli_query($conn, $sql);
	while($rows = mysqli_fetch_assoc($result)) {
		$output[] = $rows;
	}
	echo json_encode($output);
	mysqli_close($conn);
?>