<?php 
require './php/logged_in.php'; 
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Upload</title>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

        <link href="dropZone/css/dropzone.css" type="text/css" rel="stylesheet" />
        <script src="dropZone/dropzone.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <script src="js/admin.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="../css/bootstrap.css">
        <link rel="stylesheet" href="../css/style.css">
        <script>
        initDropZoneGallery();
        </script>
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
                    <li><a id = "addPhotos" href = "#">Add Photos</a></li>
                    <li><a id = "editPhotos" href = "#">Edit Photos</a></li>
                    <li><a id = "editBlog" href = "#">Edit Blog</a></li>
                    <li><a id = "controlPanel" href = "control_panel.php">Control Panel</a></li>
                    <li><a id = "logout" href = "php/logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
        </nav>


    <div class = "container">
        <div class = "well">
            <form action="php/upload_photos.php" method="post" class="dropzone" id="testZone" enctype= "multipart/form-data">
                <div class = "form-group">
                <label>New Category:<br>
                <input type="text" name="category" id = "category">
                </label>
                </div>
                <div class = "form-group">
                    <label>
                        Choose Category: <br />
                        <select name="categoryDropDown" id = "categorySelector">
                            <option value="null">Select An Option</option>
                        </select>
                    </label>
                </div>
                <div class = "form-group">
                <label>New Album:<br>
                <input type="text" name="albumName" id = "albumName">
                </label>
                </div>
                <div class = "form-group">
                    <label>
                    Choose Album: <br />
                    <select name="albumNameDropDown" id = "albumSelector">
                        <option value="null">Select An Option</option>
                    </select>
                    </label>
                </div>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox"> Watermark
                    </label>
                  </div>
                    <div class="checkbox">
                    <label>
                      <input type="checkbox"> Private Album
                    </label>
                  </div>
                <input type="hidden" name="jsonText" id="jsonField">
                <button class="btn btn-danger" type="submit" disabled = "disabled" id = "submit">Waiting For Upload</button>
                <button class="btn btn-info" type = "button" id = "reset">Reset Form</button>
            </form>

        </div>
        <div class = "well" id ="previewWell">
            <progress id = "progress" value="0" max = "100">
            </progress>
            <h1>Uploaded:</h1>
            <div id = "previewArea" class = "dropzone-previews"></div>
        </div>
    </div>
    </body>

</html>