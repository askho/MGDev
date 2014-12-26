<?php
session_start();
if(!isset($_SESSION['user'])){
    header("location:index.php");
}
?>

<html>
    <body>
        Login Successful
        <html>
            <body>
                <a href="index.php">login page</a>
                <a href="login_success.php">login_success page</a>
                <a href="php/logout.php">click here to logout</a>
                <a href="location:test_admin_only_page1.php">admin page</a>
            </body>
        </html>
    </body>
</html>