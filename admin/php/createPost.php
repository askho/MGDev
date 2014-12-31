<?php
	require '../../php/connection.php';
	if (!$conn) {
	    die("Connection failed: " . mysqli_connect_error());
	}
	$title = htmlspecialchars($_POST['postTitle']);
	$body = mysqli_real_escape_string($conn, $_POST['postBody']);
	$sql = "INSERT INTO blog (title, body)
	VALUES ('$title','$body')";

	if (mysqli_query($conn, $sql)) {
	    echo "1";
	} else {
	    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	mysqli_close($conn);
?>