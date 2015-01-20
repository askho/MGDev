<?php 
require './php/logged_in.php'; 
?>

<html>
    <head>
        <title>Edit Categories</title>
        <link rel="stylesheet" href="../css/bootstrap.css">
        <link rel="stylesheet" href="../css/style.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic' rel='stylesheet' type='text/css'>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
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
                    <a class="navbar-brand" href="#">
                        <img class = "hidden-xs" src = "../images/style/logo.png" alt = "logo"/>
                        <img class = "visible-xs" src = "../images/style/logo.png" alt = "logo" width = "auto" height = "50"/>

                    </a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a id = "gallery" href = "../viewCategories.php">Gallery</a></li>
                        <li><a id = "blog" href = "../viewBlog.php">Blog</a></li>
                        <li>            <a class="btn" href="php/logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class = "content container-fluid" id = "content">
            <div class='well'>
                <h1>Control Panel</h1>

                <a class="btn btn-primary btn-lg" href="upload.php">Upload Photos</a>
            
                <a class="btn btn-primary btn-lg" href="edit_categories.php">Manage Photos</a>
              
                <a class="btn btn-primary btn-lg" href="editBlog.php">Manage Blog</a>
                
                <a class="btn btn-primary btn-lg" href="change_password.php">Change Password</a>
            </div>
        </div>
    </body>
</html>