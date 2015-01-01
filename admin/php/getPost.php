<?php
	require '../../php/connection.php';
	if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
	}
	$postID = $_POST['postID2'];
	$temp = array();
	$sql = "SELECT title, body
	FROM blog
	WHERE postID = '$postID'";
	$result = mysqli_query($conn, $sql);
	while($rows = mysqli_fetch_assoc($result)) {
		$temp[] = $rows;
	}
	$output['blogData'] = $temp;
	echo json_encode($output);
	mysqli_close($conn);
?>