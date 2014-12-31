<?php
	require 'connection.php';
	if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
	}
	$output = array();
	$categoryID = $_POST['category'];
	$sql = "
	SELECT MIN(location) AS FirstPhoto,
       albumname, albumID 
	FROM   photo 
	       natural JOIN photoalbum 
	       natural JOIN album 
	WHERE  albumid IN (SELECT albumid 
	                   FROM   album 
	                          natural JOIN albumcategory 
	                   WHERE  categoryID = '$categoryID') 
	GROUP BY albumid";
	$result = mysqli_query($conn, $sql);
	while($rows = mysqli_fetch_assoc($result)) {
		$output[] = $rows;
	}
	echo json_encode($output);
	mysqli_close($conn);
?>