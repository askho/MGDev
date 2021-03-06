<?php 
require './php/logged_in.php'; 
require '../php/connection.php';
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
        <title>Upload Photos - Mike Gonzales Photography</title>
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
                    <a class="navbar-brand" href="control_panel.php">
                        <img class = "hidden-xs" src = "../images/style/logo.png" alt = "logo"/>
                        <img class = "visible-xs" src = "../images/style/logo.png" alt = "logo" width = "auto" height = "50"/>

                    </a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <!--<li><a id = "addPhotos" href = "#">Add Photos</a></li>
<li><a id = "editPhotos" href = "#">Edit Photos</a></li>
<li><a id = "editBlog" href = "#">Edit Blog</a></li>-->
                        <li><a id = "editCategories" href = "edit_Categories.php">Manage Photos</a></li>
                        <li><a id = "controlPanel" href = "control_panel.php">Control Panel</a></li>
                    </ul>
                </div>
            </div>
        </nav>


        <div class = "container">
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="notification" aria-hidden="true" id = "notification">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Your private album link</h4>
                        </div>
                        <div class="modal-body">
                            <?php 
if(isset($_GET['privateLink']))
    $privateID = $_GET['privateLink'];
if(isset($_GET['albumId']))
    $id = $_GET['albumId'];
if(isset($_GET['albumName']))
    $albumName = $_GET['albumName'];
echo $_SERVER['SERVER_NAME']. $homeDir . "viewPrivate.php?albumID=$id&albumName=$albumName&privateLink=$privateID";
                            ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
if(isset($_GET['privateLink']) && isset($_GET['albumName']) && isset($_GET['albumId'])) {
    echo '<script>$("#notification").modal()</script>';
}
            ?>
            <div class = "well">
                <form action="php/upload_photos.php" method="post" class="dropzone" id="testZone" enctype= "multipart/form-data">
                    <div class = "form-group">
                        <label>New Category:<br>
                            <input type="text" class='form-control' name="category" id = "category">
                        </label>
                    </div>
                    <div class = "form-group">
                        <label>
                            Choose Category: <br />
                            <select class='form-control' name="categoryDropDown" id = "categorySelector">
                                <option value="null">Select An Option</option>
                            </select>
                        </label>
                    </div>
                    <div class = "form-group">
                        <label>New Album:<br>
                            <input type="text" class='form-control' name="albumName" id = "albumName">
                        </label>
                    </div>
                    <div class = "form-group">
                        <label>
                            Choose Album: <br />
                            <select name="albumNameDropDown" class='form-control' id = "albumSelector">
                                <option value="null">Select An Option</option>
                            </select>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name = "watermark"> Watermark Photos
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name = "private" id = "privateAlbum"> Create Private Album
                        </label>
                    </div>
                    <input type="hidden" name="jsonText" id="jsonField">
                    <button class="btn btn-danger form-group" type="submit" disabled = "disabled" id = "submit">Waiting For Upload</button>
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