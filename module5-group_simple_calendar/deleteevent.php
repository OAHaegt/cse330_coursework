<?php
session_start();
require 'database.php';
header("Content-Type: application/json"); //json derulo

if (!isset($_SESSION['user_id'])){
	echo json_encode(array("success" => false));
	exit;
}
else {
	$eventid = $_POST['eventid'];
		
	//check the security token and see if it's a-ok
    if($_SESSION['token'] !== $_POST['token']){
		die("Request forgery detected"); 
	}

	$stmt = $mysqli->prepare("DELETE FROM events WHERE eventid=?");
	if(!$stmt){
		echo json_encode(array("success" => false));
		exit;
	}
	//bind params
	$stmt->bind_param('i',$eventid);

	$stmt->execute();
	$stmt->close();
	echo json_encode(array("success" => true));
	exit;


}
?>

