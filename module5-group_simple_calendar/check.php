<?php

session_start();

require 'database.php';

header("Content-Type: application/json");

if(isset($_SESSION['user_id'])){
	echo json_encode(array("login" => true, "token" => $_SESSION['token'], "username" => $_SESSION['user_name']));
	exit;
}
else {
	echo json_encode(array("login" => false));
}
exit;








?>
