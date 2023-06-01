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
	$event_title = (string) $_POST['title'];
	if(isset($_POST['tag'])){
		$event_tag = (string) $_POST['tag'];
	}
	$event_time = (string) $_POST['time'];
	$cut_time = date("H:i", strtotime($event_time)) . ":00";
	$event_day = (string) $_POST['day'];
	$event_month = (string) $_POST['month'];
	$event_year = (string) $_POST['year'];
	$fulldatetime = $event_year . "-" . $event_month . "-" . $event_day . " " . $cut_time;
	if ($_POST['month'] < 10){
		$event_month = "0" . $_POST['month'];}
	if ($_POST['day'] < 10){
		$event_day = "0" . $_POST['day'];}
		
	//check the security token and see if it's a-ok
    // if($_SESSION['token'] !== $_POST['token']){
	// 	die("Request forgery detected"); 
	// }

	if(isset($event_tag)){
		$stmt = $mysqli->prepare("UPDATE events SET title=?,eventdatetime=?,tagname=? where eventid=?");
		if(!$stmt){
			echo json_encode(array("success" => false));
			exit;
		}
		//bind params
		$stmt->bind_param('sssi',$event_title,$fulldatetime,$event_tag,$eventid);

	}
	else{
		$stmt = $mysqli->prepare("UPDATE events SET title=?,eventdatetime=? where eventid=?");
		//if the query fails, get angry
		if(!$stmt){
			echo json_encode(array("success" => false));
			exit; 
		}
		//bind our parameters
		$stmt->bind_param('ssi',$event_title,$fulldatetime,$eventid);

                
	}
	$stmt->execute();
	$stmt->close();
	// $message = 'UPDATE events SET title='.$event_title.',eventdatetime='.$event_datetime.',tagname='.$event_tag.' where eventid='.$eventid;
	echo json_encode(array("success" => true));
	exit;

	
}
?>