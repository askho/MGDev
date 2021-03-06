<?php
	require 'connection.php';
	if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
	}
	$output = array();
	$page = 0;
	if(isset($_POST['page'])) {
		$page = $_POST['page'] - 1;
	}
	$pageLength = 10;
	$loadFrom = $page * $pageLength;
	$sql = "SELECT title, postID, DATE_FORMAT(dateCreated , '%a %b %e %Y %l:%i %p') AS date
	FROM blog
	ORDER BY dateCreated desc
	LIMIT $loadFrom, $pageLength";

	$result = mysqli_query($conn, $sql);
	$temp = array();
	while($rows = mysqli_fetch_assoc($result)) {
		$temp[] = $rows;
	}
	$output["posts"] = $temp;
	$sql = "SELECT CEIL((COUNT(*) / 10)) AS totalPages
	FROM blog";

	$result = mysqli_query($conn, $sql);
	$temp = array();
	while($rows = mysqli_fetch_assoc($result)) {
		$temp[] = $rows;
	}
	$output["totalPages"] = $temp[0];

	echo json_encode($output);
	mysqli_close($conn);
?>