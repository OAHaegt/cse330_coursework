<?php

session_start();
require("database.php");

$storyid = $_SESSION['storyid'];
$token_POST = $_POST['token'];

if(!hash_equals($token_POST, $_SESSION['token'])){ //CSRF validation
    die("Request forgery detected");
}

$comments = htmlentities($_POST['comment']);

$stmt = $mysqli->prepare("SELECT userid FROM users where username=?");

if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->bind_param('s', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($userid);
$stmt->fetch();
$stmt->close();

$stmt = $mysqli->prepare("INSERT into comments (comment_body, userid, storyid) values (?, ?, ?)");

if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->bind_param('sdd', $comments, $userid, $storyid);
$stmt->execute();
$stmt->close();

header("Location: storydetail.php");
exit;     

?>