<?php
require 'connection.php';
$output = array();
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT albumName FROM album";
$result = mysqli_query($conn, $sql);

$rows = [];
while($row = mysqli_fetch_array($result))
{
    $rows[] = $row;
}

$sql = "SELECT categoryName FROM category";
$result = mysqli_query($conn, $sql);

$rows2 = [];
while($row = mysqli_fetch_array($result))
{
    $rows2[] = $row;
}
array_push($output, $rows);
array_push($output, $rows2);
echo json_encode($output);
mysqli_close($conn);
?>