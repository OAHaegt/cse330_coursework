<?php
session_start();

$fileDir = '/var/www/user/'.$_SESSION['username'].'/'.$_POST["openfile"];

if (file_exists($fileDir)) {
  $ext = pathinfo($fileDir, PATHINFO_EXTENSION);
  // some extensions that will load directly in browser
  $extensions = array("txt", "html", "png", "jpg", "jpeg", "img", "bmp", "css", "pdf"); 

  if (in_array($ext, $extensions)) {
    header("Content-Type: ".mime_content_type($_POST["openfile"]));
    readfile($fileDir);
  } else {
    // force download unsopported types. reference: https://www.php.net/manual/en/function.readfile.php;
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($_POST["openfile"]).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($_POST["file"]));
    readfile($_POST["file"]);
  }

} else {
  echo "The file does not exist.";
}

?>