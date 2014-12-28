<?php
session_start();
if(!isset($_SESSION['user'])){
    header("location:../index.php");
}

// connect to database
require '../../php/connection.php';
require './randHashPass.php';

// check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// variables
$currPass = $_POST["current_pass"];
$newPass = $_POST["new_pass"];

// check that fields aren't empty
if(empty($currPass) || empty($newPass)){
    echo "<script> 
        window.alert('please enter passwords and try again');
        window.location.replace(\"../change_password.php\");
        </script>";
}

// Query database - sql injection protected
$sql = "SELECT username, hash 
        FROM Admin 
        WHERE username = 'mike'
        OR username = 'leon'
        OR username = 'sean'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$stmt->bind_result($fUser, $fHash);

// compare with known admin passwords
while($stmt->fetch()){
    // check that current password was entered
    if ( hash_equals($fHash, crypt($currPass, $fHash)) ) {
        // generate new hashed password
        $hashPass = randHashPass($newPass);
        $stmt->close();

        // update to new password - sql injection protected
        $sql = "UPDATE Admin
        SET hash = ? 
        WHERE username = 'mike'";
        $stmt2 = $conn->prepare($sql);
        $stmt2->bind_param("s", $hashPass);
        $stmt2->execute();
        $stmt2->close();
        
        // diplsay success and return to control panel
        echo "<script> 
        window.alert('password changed successfully');
        window.location.replace(\"../control_panel.php\");
        </script>";
    }
}
?>
