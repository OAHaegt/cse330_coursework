<?php

$mysqli = new mysqli('localhost', 'newsadmin', 'newspassword', 'newssite');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>

