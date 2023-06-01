<!DOCTYPE html>
<html lang='en'>

<head>  
    <link rel="stylesheet" type = "text/css" href="stylesheet.css"/>
    <title>Edit Stories</title>
</head>
<body>
<br><header class=logintitle><b>Simple News Site</b></header>
  <hr />
<?php
require("database.php");
session_start();

$comment_to_delete_ID = $_POST['commentid'];

$stmt = $mysqli->prepare("DELETE FROM comments WHERE commentid=?");
if($stmt){
    $stmt->bind_param('i', $comment_to_delete_ID);
    $stmt->execute();
    $stmt->close();
    header('refresh:3; url=home.php');
    echo('Success! back to home...');
}
else{
	printf("Query Prep Failed: %s\n", $mysqli->error);
}


?>

</body>