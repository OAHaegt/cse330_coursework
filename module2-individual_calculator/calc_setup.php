<!DOCTYPE html>

<html lang="en">

<head><title>Calculation Result</title></head>

<body>

<?php

  $first = $_GET['firstnumber'];
  $second = $_GET['lastnumber'];
  $operation = $_GET['operation'];

  if($first=="" || $second=="" || $operation=="") {
    echo "You need to fill in both numbers.";
    exit();
  }else{

  switch($operation){
    case "add":
      $answer = $first + $second;
      $string = "+";
      break;
    case "subtract":
      $answer = $first - $second;
      $string = "-";
      break;
    case "multiply":
      $answer = $first * $second;
      $string = "*";
      break;
    case "divide":
      if($second!=0){
        $answer = $first / $second;
        $string = "/";
      }else{
        echo "You cannot devide a number with zero.";
        exit();
      }
    break;
  }

  printf("<p>%f %s %f = %f </p>\n", htmlentities($first), $string, htmlentities($second), $answer);

}

?>

</body>

</html>
