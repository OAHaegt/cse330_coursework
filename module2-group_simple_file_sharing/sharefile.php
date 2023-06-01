<!DOCTYPE HTML>
<html>
	<head>
	<title>File Share</title>	
	</head>
	
<body>
<?php
    session_start();
    if (isset($_POST["share"])){

        $target_directory = "/var/www/user/" . $_POST["userShare"] . "/" . $_POST['fileShare'];
        $origin = "/var/www/user/".$_SESSION['username'].'/' . $_POST['fileShare'];
        if(copy($origin, $target_directory))
        {
            echo("Shared ".$_POST['fileShare']." to ".$_POST["userShare"]."successfully.");
        }
        else
        {
            echo("Error. Sharing failed.");
        }
    }
?>