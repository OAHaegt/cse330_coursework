<?php
session_start();
$filename = basename($_FILES['uploadedfile']['name']);
if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
	echo "Invalid filename";
	exit;
}

// Get the username and make sure it is valid
$username = $_SESSION['username'];
if( !preg_match('/^[\w_\-]+$/', $username) ){
	echo "Invalid username";
	exit;
}

$full_path = sprintf("/var/www/user/%s/%s", $username, $filename);

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path)){
	echo("Uploaded successfully!");
	exit;
} else {
	header("Location: error.html");
	exit;
}

?>
