<?php
	/**
		POST in a category name with key "categoryName" and it
		will echo out the albumID, and album name of all albums 
		inside that category! If no category is inputed it will output all
		albums.
	*/
	require '../../php/connection.php';
	if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
	}
	$output = array();
	$sql = "SELECT albumID, albumName FROM album NATURAL JOIN albumCategory NATURAL JOIN category";
	if(isset($_POST['categoryName'])) {
		$categoryName = $_POST['category'];
		$sql = $sql." WHERE categoryName = '$categoryName'";
	}
	$result = mysqli_query($conn, $sql);
	while($rows = mysqli_fetch_assoc($result)) {
		$output[] = $rows;
	}
	echo json_encode($output);
	mysqli_close($conn);
?>