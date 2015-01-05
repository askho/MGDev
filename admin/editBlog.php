<?php 
require './php/logged_in.php'; 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
  <link rel="stylesheet" href="../css/bootstrap.css">
  <link rel="stylesheet" href="../css/style.css">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
  <script src="../js/isotope.js"></script>
  <script src="//cdn.ckeditor.com/4.4.6/standard/ckeditor.js"></script>
  <script src="js/admin.js"></script>
  <link href="dropZone/css/dropZone.css" type="text/css" rel="stylesheet" />
  <script src="dropZone/dropzone.min.js"></script>
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic' rel='stylesheet' type='text/css'>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script>
    initDropZoneBlog();
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
        <li><a id = "gallery" href = "#">Gallery</a></li>
        <li><a id = "blog" href = "#">Blog</a></li>
        <li><a id = "booking" href = "#">Booking</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id = "notification">
  <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Upload Finished!</h4>
      </div>
        <div class="modal-body">
        <p>Use this link to for the image you want to insert.</p>
        <br>
        <br>
        <p id = "imageLink"></p>
        
      </div>
    </div>
  </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id = "notification2">
  <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Creating Post</h4>
      </div>
        <div class="modal-body">
        <p id ="notificationMessage"></p>     
      </div>
    </div>
  </div>
</div>
<div class = "content container" id = "content">
  <div class ="well" id ="contentWell">
    <div id = "blogMenu">
	    <h1>Blog Manager</h1>
	    <br>
	    <button type = "button" class ="btn btn-primary" id ="createNewPost">Create New Post</button>
	    <button type = "button" class ="btn btn-primary" id ="editOldPost">Edit Old Posts</button>
    </div>
    <div id = "createPost">
      <br>
      <h2>Create Post</h2>
      <form action="php/createPost.php" method = "POST">
        Post Title<br>
        <input type="text" name="postTitle" placeholder = "Post Title" class = "form-control" id = "postTitle">
        <br>
        Post Contents<br>
        <textarea name = "postBody" id = "postBody">
        </textarea>
        <br><br>
        <button type = "button" class ="btn btn-success" id = "createPostsButton">Create Post</button>
      </form>
      <h2>Drop Photos Here To Upload</h2>
        <form action="php/blogPictureUpload.php" method="post" class="dropzone" id="blog" enctype= "multipart/form-data">
      </form> 
    </div>
    <div id ="editPosts">
    <br />
    <h1>Your Old Posts</h1>
      <table class="table table-striped" id = "postTable">

      </table>
    </div>
    <div id = "postEditor">
      <br>
      <h2>Create Post</h2>
      <form action="php/editPost.php" method = "POST">
        Post Title<br>
        <input type="text" name="postTitle" placeholder = "Post Title" class = "form-control" id = "editPostTitle">
        <br>
        Post Contents<br>
        <textarea name = "postBody" id = "editPostBody">
        </textarea>
        <br><br>
        <button type = "button" class ="btn btn-success" id = "editPostButton">Create Post</button>
      </form>
      <h2>Drop Photos Here To Upload</h2>
        <form action="php/blogPictureUpload.php" method="post" class="dropzone" id="blog" enctype= "multipart/form-data">
      </form> 
    </div>
  </div>
</div>
<div id = "bottomRight">
<address>
  <strong>Mike Gonzales</strong><br>
  (604) 111-1111<br>
  <a href = "mailto:#">Mikegonzales@mail.com</a><br>
</address>
</div>

</body>

</html>

