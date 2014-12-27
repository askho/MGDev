<?php
session_start();
if(isset($_SESSION['user'])){
    header("location:control_panel.php");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    </head>
    <body >
        <h1>Login Page</h1>
        <form action="php/login.php" method="post" id="loginForm" enctype=”multipart/form-data>
            Username: <input type="text" name="user">
            <br>
            Password: <input type="password" name="password">
            <br>
            <input type="submit" value="Submit" name="submit">
        </form>
    </body>
</html>