<?php
require '../../php/connection.php';

// check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// create admin Sean
$user = "sean";
$pass = randHashPass("avena");
insertAdmin($conn,$user,$pass);

// create admin Leon
$user = "leon";
$pass = randHashPass("ho");
insertAdmin($conn,$user,$pass);

// create admin Mike
$user = "mike";
$pass = randHashPass("gonzales");
insertAdmin($conn,$user,$pass);


// ---------- FUNCTIONS ----------

// inserts a new admin into the database
function insertAdmin($connection, $user, $pass){
    $sql = "INSERT INTO Admin (username, hash) VALUES (?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $user, $pass);

    if ($stmt->execute()) {
        echo "<br>Successfully created admin: $user";
    } else {
        echo "Error inserting " . $user;
    }
}

// creates a random hash for a given password using the bcrypt algorithm
function randHashPass($password){
    // higher workload costs processing power but is safer
    $workload = 12;

    // Create random salt
    $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

    // Hash the password with salt using bcrypt algorithm
    $hash = crypt($password, "$2y$" . $workload . "$" . $salt);
    return $hash;
}

?>