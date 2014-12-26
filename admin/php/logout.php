<?php 
session_start();
session_destroy();
?>

<html>
    <body>
        Logged out

                <a href="../index.php">login page</a>
                <a href="login_success.php">login_success page</a>
                <a href="logout.php">click here to logout</a>
                <a href="location:../test_admin_only_page1.php">admin page</a>
    </body>
</html>