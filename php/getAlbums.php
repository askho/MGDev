<?php
require 'connection.php';
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
echo json_encode($rows);
mysqli_close($conn);
?>