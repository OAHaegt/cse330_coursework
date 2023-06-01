
<!DOCTYPE html>
<html lang='en'>

<head>  
    <link rel="stylesheet" type = "text/css" href="main_stylesheet.css"/>
    <title>Delete Stories</title>
</head>
<body>
<br><header class=logintitle><b>Simple News Site</b></header>
  <hr />
<?php
require("database.php");
session_start();
$story_to_delete_ID = $_POST['storyid'];

$stmt2 = $mysqli->prepare("DELETE FROM stories WHERE storyid=?");
    if($stmt2){
        $stmt2->bind_param('d', $story_to_delete_ID);
        $stmt2->execute();
        $stmt2->close();
        header('refresh:3; url=home.php');
        echo("The story has been deleted! Returning you back to home page...");
    
    }
else{
	printf("Query Prep Failed: %s\n", $mysqli->error);
}


?>

</body>