<?php

session_start();
require("database.php");

if(!hash_equals($_SESSION['token'], $_POST['token'])){ //CSRF validation
    die("Request forgery detected");
}

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

if(isset($_POST["post"])){
    $title_POST = $_POST['title'];
    $contents_POST = $_POST['contents'];
    $link_POST = $_POST['link'];
    $stmt = $mysqli->prepare("INSERT into stories (title, story_body, userid, url) values (?, ?, ?, ?)");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('ssss', $title_POST, $contents_POST, $userid, $link_POST);
    $stmt->execute();
    $stmt->close();

    header('refresh:3; url=home.php');
    echo("The story has been posted! Returning you back to home page...");
}

?>

<html>
<head>
    <title>Upload</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="stylesheet.css" type="text/css">
</head>
<body>
    <br><header class=logintitle><b>Simple News Site</b></header>
    <hr />
        <header>
            Upload a story:<div id="login"><form action="home.php"><input type="submit" value="return" /></form><br>
        </header>

        <form class="postStory" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
            Title: <input type="text" name="title" required/><br><br>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            Body: <textarea name='contents' rows='10' cols='80'></textarea><br><br>
            Link: <input type="text" name="link" />
            <input type="submit" name="post" value="post" />
        </form>

    </div>

</body>
</html>