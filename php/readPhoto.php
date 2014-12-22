<?php
/**
    Pass the soruce of a photo into the the function and it will return the important EXIF values back
*/
function readPhoto($src) {
	$exif = exif_read_data($src, "EXIF");
    echo "FileName : " . $exif['FileName']. "<br />";
	echo "Aperture: f" . $exif['ApertureValue'] . "<br />";
	echo "ISO: " . $exif['ISOSpeedRatings'] . "<br />";
	echo "Exposure time: " . $exif['ExposureTime'] . "<br />";
	echo "Focal Length: " . $exif['FocalLength']. "<br />";
	echo "Resolution: " . $exif['COMPUTED']['Width'] ." x " . $exif['COMPUTED']['Height'] . "<br />";
    $date = strtotime($exif['DateTimeOriginal']);
    echo "Date Taken: " . gmdate("D F j Y g:i:A", $date) . "<br />";
}
?>