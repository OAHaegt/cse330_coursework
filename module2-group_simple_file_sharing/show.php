<?php

session_start();

$dir = "/var/www/user/".$_SESSION['username']; // get user directory
echo "Hello, ".$_SESSION['username'].". Here are your files: <br><br>";
if (is_dir($dir)){
    $files = preg_grep('/^([^.])/', scandir($dir)); // ignores hidden files
    foreach($files as $value) {
        echo "Filename: ".$value."<br>";
    }
}
echo "<br>You can open them up back in the main page.<br>"



?>