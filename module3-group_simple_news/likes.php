<?php

session_start();
require("database.php");

$storyid = $_SESSION['storyid'];
$token_POST = $_POST['token'];

if(!hash_equals($token_POST, $_SESSION['token'])){ //CSRF validation
    die("Request forgery detected");
}

$stmt = $mysqli->prepare("SELECT likes FROM stories where storyid=?");

if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->bind_param('d', $storyid);
$stmt->execute();
$stmt->bind_result($likes);
$stmt->fetch();
$stmt->close();

$likes++;

$stmt = $mysqli->prepare("UPDATE stories set likes=? where storyid=?");

if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->bind_param('dd', $likes, $storyid);
$stmt->execute();
$stmt->close();

header("Location: storydetail.php");
exit;     

?>