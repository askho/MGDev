<?php
	require '../../php/connection.php';
	if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
	}
	$output = array();
	$sql = "SELECT categoryID, categoryName FROM category";
	$result = mysqli_query($conn, $sql);
	while($rows = mysqli_fetch_assoc($result)) {
		$output[] = $rows;
	}
	echo json_encode($output);
	mysqli_close($conn);
?>