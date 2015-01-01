<?php
	require '../../php/connection.php';
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
	ORDER BY dateCreated desc";

	$result = mysqli_query($conn, $sql);
	$temp = array();
	while($rows = mysqli_fetch_assoc($result)) {
		$temp[] = $rows;
	}
	$output["blog"] = $temp;
	echo json_encode($output);
	mysqli_close($conn);
?>