<?php
    require("database.php");
    session_start();
    $story_to_edit_ID = $_SESSION['storyid'];
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="stylesheet.css" type="text/css">
    <title>Editing</title>
</head>

<body>
<br><header class=logintitle><b>Simple News Site</b></header>
  <hr />

  <?php
    require("database.php");
    session_start();
  ?>
  
    <?php
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    ?>
    <div>
        <h1>Edit a Post</h1>
        <form method='POST' id='storyForm'>
            <?php
                 echo "Title: <input type='text' name='newTitle'/><br>";
                 echo "Link: <input type='text' name='newLink'/><br>";
                 echo "<textarea name='storyText' rows='10' cols='80'>Enter story text here...</textarea><br><br>";
                 printf("<input type='hidden' name='s_id' value='%s'/>",htmlspecialchars($_POST['s_id']));
            ?>
            <input type='hidden' name='token' value='<?php echo $_SESSION['token'];?>'/>
            <button type='submit'>Update Story</button>
        </form><br>
        <?php
            if(isset($_POST['newTitle']) && isset($_POST['newLink'])){
                $author = (string)$_SESSION['userid'];
                $title = (string)$_POST['newTitle'];
                $link = (string)$_POST['newLink'];
                $text = (string)$_POST['storyText'];
                $from = (string)$_SESSION['pageFrom'];
                if (strlen($title) < 1){ //someone typed something for the title
                    echo 'Your post must have a title!';
                } else if(!hash_equals($_SESSION['token'], $_POST['token'])){ //CSRF validation
                    die("Request forgery detected");
                } else {
                    $stmt = $mysqli->prepare("update stories set title=?,story_body=?,url=? where storyid=?");
                    if(!$stmt){
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
                    $stmt->bind_param('sssd',$title,$text,$link,$story_to_edit_ID);
                    $stmt->execute();
                    $stmt->close();
                    printf("<p>Story successfully edited! Go <a href='home.php'>back home</a> to view it </p>",htmlspecialchars($from));
                }
            }
        ?>
        <p>Go <a href='home.php'>back home</a></p>
    </div>
</body>
</html>