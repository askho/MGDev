<?php
readPhoto("../images/photos/1.jpg");
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