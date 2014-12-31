<?php
	require 'connection.php';
	if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
	}
	$output = array();
	$sql = "SELECT title, body, DATE_FORMAT(dateCreated , '%a %b %e %Y %l:%s %p') AS date
	FROM blog
	ORDER BY dateCreated desc";
	$result = mysqli_query($conn, $sql);
	while($rows = mysqli_fetch_assoc($result)) {
		$output[] = $rows;
	}
	echo json_encode($output);
	mysqli_close($conn);
?>