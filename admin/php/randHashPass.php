<?php

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