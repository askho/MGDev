<?php
	$testString = '["images/photos/w56d938639ba552fee5cd8d4ad82aec3.jpg","images/photos/d56d938639ba552fee5cd8d4ad82aec3.jpg","images/photos/u02bdc57b3cde5194d01136320d83ddb.jpg","images/photos/n02bdc57b3cde5194d01136320d83ddb.jpg"]';
	$test = json_decode($testString);
	echo $test[0];

?>