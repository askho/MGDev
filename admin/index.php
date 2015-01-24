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
        <link rel="stylesheet" href="../css/bootstrap.css">
        <link rel="stylesheet" href="../css/style.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <script src="js/mainAdmin.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic' rel='stylesheet' type='text/css'>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body >
        <nav class="navbar navbar-default">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="control_panel.php">
                        <img class = "hidden-xs" src = "../images/style/logo.png" alt = "logo"/>
                        <img class = "visible-xs" src = "../images/style/logo.png" alt = "logo" width = "auto" height = "50"/>

                    </a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li>                <a href="../index.html">Main Site</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class = "content container-fluid" id = "content">
            <div class='well'>

                <h1>Login Page</h1>
                <form action="php/login.php" method="post" id="loginForm" enctype=”multipart/form-data>
                    <div class = "form-group">
                        <label>
                    Username: <input class='form-control' type="text" name="user">
                        </label>
                    </div>
                    <div class = "form-group">
                        <label>
                    Password: <input class='form-control' type="password" name="password">
                        </label>
                    </div>
                    <input class='btn btn-success' type="submit" value="Submit" name="submit">
                </form>
            </div>
        </div>
    </body>
</html>