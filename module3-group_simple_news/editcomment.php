<?php
    require("database.php");
    session_start();
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
    if (isset($_POST["commentid"])) {
        $comment_to_edit_ID = $_POST['commentid'];
        $_SESSION["commentid"] = $_POST['commentid'];
    }
    ?>

    <?php
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    ?>
    <div>
        <h1>Edit a comment</h1>
        <form method='POST' id='commentForm'>
            <textarea name='commentText' rows='5' cols='30'>Enter comment text here...</textarea><br><br>
            <input type='hidden' name='token' value='<?php echo $_SESSION['token'];?>'/>
            <button type='submit' name='submit'>Update Comment</button>
        </form><br>
        <?php
            if(isset($_POST['commentText'])){
                session_start();
                $text = (string)$_POST['commentText'];
                if(!hash_equals($_SESSION['token'], $_POST['token'])){ //CSRF validation
                    die("Request forgery detected");
                } else {
                    $stmt = $mysqli->prepare("update comments set comment_body=? where commentid=?");
                    if(!$stmt){
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    }
                    $stmt->bind_param('si',$text,$_SESSION["commentid"]);
                    $stmt->execute();
                    $stmt->close();
                    printf("<p>Comment successfully edited! Go <a href='home.php'>back home</a> to view it </p>",htmlspecialchars($from));
                }
            }
        ?>
        <p>Go <a href='home.php'>back home</a></p>
    </div>
</body>
</html>
