<!DOCTYPE html>
<html>
<head>
    <title>Contents</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="stylesheet.css" type="text/css">
</head>

<body>
<br><header class=logintitle><b>Simple News Site</b></header>
    <hr />

    <?php
    session_start();
    require("database.php");

    if(isset($_POST["storydetail"])){
        $storyid_POST = $_POST["storyid"];
        $_SESSION['storyid'] = $storyid_POST;
    }

    $stmt = $mysqli->prepare("SELECT title, story_body, users.username, storyid, url, likes FROM stories join users on (stories.userid=users.userid) where storyid = ?");

    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param("d", $_SESSION['storyid']);
    $stmt->execute();
    $stmt->bind_result($title, $text, $username, $storyid, $link, $likes);
    $stmt->fetch();
    $stmt->close();

    echo "<header><table><tr><tb><b>".$title."</b></tb>     <br><a href=\"home.php\">Back</a>";

    if($_SESSION['id'] == $username){ // only the user can edit or delete.
        echo "<form action=\"editstory.php\" method=\"POST\">
        <input type=\"submit\" value=\"edit\" name=\"edit\"/>
        <input type=\"hidden\" value=\"".$storyid."\" name=\"storyid\" />
        <input type=\"hidden\" value=\"".$title."\" name=\"title\" />
        <input type=\"hidden\" value=\"".$text."\" name=\"text\" />
        <input type=\"hidden\" value=\"".$link."\" name=\"text\" />
        <input type=\"hidden\" name=\"token\" value=\"".$_SESSION['token']."\">
        </form>";
        echo "<form action=\"deletestory.php\" method=\"POST\">
        <input type=\"submit\" value=\"deletestory\" name=\"delete\"/>
        <input type=\"hidden\" value=\"".$storyid."\" name=\"storyid\" />
        <input type=\"hidden\" name=\"token\" value=\"".$_SESSION['token']."\">
        </form>";
    }
    echo "</tr></table></header>";
    echo "<tb>  Author: ".$username."</tb><br>";
    echo "<div class=\"contents\">".$text."</div>";
    if ($link != null) {
        echo "<a href=\"$link\">Check out the link</a>";
    }
	
	// creative part: add likes to a story post
	echo "<br><b>Likes: </b>".$likes."<form action=\"likes.php\" method=\"POST\"> 
        <input type=\"submit\" value=\"like\" name=\"like\"/>
        <input type=\"hidden\" value=\"".$storyid."\" name=\"storyid\" />
        <input type=\"hidden\" name=\"token\" value=\"".$_SESSION['token']."\">
        </form>";

    ?>

    <br><br><br><br><br><br>

    <?php
    if(($_SESSION['id'] != NULL)){
        echo "
        <form action=\"addcomment.php\" method=\"POST\" id=\"postComments\">
            <input type=\"text\" name=\"comment\" placeholder=\"Comment here\">
            <input type=\"submit\" name = \"submit\" value = \"Post\">
            <input type=\"hidden\" name=\"token\" value=\"".$_SESSION['token']."\">
        </form>";
    }
    ?>
    <div>
        <?php
        $stmt = $mysqli->prepare("SELECT comment_body, commentid, users.username FROM comments join users on (comments.userid=users.userid) where storyid=?");

        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('d', $_SESSION['storyid']);
        $stmt->execute();
        $stmt->bind_result($comment, $commentid, $usercomment);

        while($stmt->fetch()){
            echo "<div id=\"comment\"><b>".$usercomment."</b>:    ";
            echo $comment;

            if($_SESSION['id'] == $usercomment){ // only the commenter can edit or delete.
                echo "<form action=\"editcomment.php\" method=\"POST\">
                <input type=\"submit\" value=\"edit\" name=\"edit\"/>
                <input type=\"hidden\" value=\"".$commentid."\" name=\"commentid\" />
                <input type=\"hidden\" value=\"".$comment."\" name=\"comment\" />
                <input type=\"hidden\" name=\"token\" value=\"".$_SESSION['token']."\">
                </form>";

                echo "<form action=\"deletecomment.php\" method=\"POST\">
                <input type=\"submit\" value=\"delete\" name=\"delete\"/>
                <input type=\"hidden\" value=\"".$commentid."\" name=\"commentid\" />
                <input type=\"hidden\" name=\"token\" value=\"".$_SESSION['token']."\">
                </form>";
            }

            echo "</div>";
        }
        $stmt->close();
        ?>
    </div>
</body>
</html>