<?php
session_start();

if (isset($_POST["delete"])){

  $delete = $_POST["deletefile"]; // Get the name of file that user want to delete

  if(unlink('/var/www/user/'.$_SESSION['username'].'/'.$delete)){
    echo("Deleted successfully!");
    exit;
  } else {
    header('Location: error.html'); // If error occurs, the page would jump to error.html
    exit;
  }
}

?>
