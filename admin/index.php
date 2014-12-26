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
        <a href="php/login.php">login page</a>
        <a href="login_success.php">login_success page</a>
        <a href="php/logout.php">click here to logout</a>
        <a href="test_admin_only_page1.php">admin page</a>
    </body>

</html>