<?php
//displayPostData();

if(isset($_SESSION['user'])){
    header("location:../control_panel.php");
}


// set up database connection
ob_start();
session_start();

require '../../php/connection.php';

// login
if(isset($_POST['submit']))
{
    echo login();
}


/* Logs the user into a session and returns an appropriate message on the
page if login information is incorrect.
*/
function login() {

    // Connect to database   
    global $conn;
    checkDBConnection($conn);

    // variables
    $user = $_POST["user"];
    $pass = $_POST["password"];

    // check that fields aren't empty
    if(empty($user) || empty($pass)){
        return "<br>Please enter your username and password and try again"; 
    }

    // query database for username and password
    $sql = "SELECT username, password 
        FROM Admin 
        WHERE username = '$user' AND password = '$pass'";
    $result = mysqli_query($conn, $sql);

    // check if query returned one row for given user
    if (mysqli_num_rows($result) == 1) {
        // log user into session
        session_regenerate_id();
        $_SESSION['user']= $user;         
        $_SESSION['pass']= $pass;       
        session_write_close();
        
        // redirect to control panel
        header("location:../control_panel.php");
    } else {
        return "<br>Wrong username/password, please try again."; 
    }
}

function checkDBConnection($connection){
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        //echo "<br>Connected to database successfully";
    }
    ob_end_flush();
}

// displays form data received as post
function displayPostData(){
    echo "Login info received";
    echo "<br>Username: " . $_POST["user"]; 
    echo "<br>Password: " . $_POST["password"]; 

}

?>
<html>
    <body>
        <br>
        <a href="../index.php">Back to login</a>
    </body>
</html>