<?php 
require './php/logged_in.php'; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Categories</title>
  <link rel="stylesheet" href="../css/bootstrap.css">
  <link rel="stylesheet" href="../css/style.css">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
  <script src="../js/isotope.js"></script>
  <script src="js/mainAdmin.js"></script>
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic' rel='stylesheet' type='text/css'>
  <meta name="viewport" content="width=device-width, initial-scale=1">
<?php
  /**
    Calling the corresponding JS functino to build the page. 
  */
  echo "<script>$(document).ready(function() {
    getCategories();
  });</script>";
?>
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
          <li><a id = "controlPanel" href = "control_panel.php">Control Panel</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class = "content container-fluid" id = "content">


</div>
</body>

</html>

