<?php
session_start();
if(!isset($_SESSION['user'])){
    header("location:index.php");
}
?>

<html>
    <body>
        admin only page
        <a href="login.php">login page</a>
        <a href="login_success.php">login_success page</a>
        <a href="logout.php">click here to logout</a>
        <a href="location:../test_admin_only_page1.php">click here to logout</a>
    </body>
</html>