<!DOCTYPE html>
<html>
<?php

session_start();
require("database.php");

$username_POST = $mysqli->real_escape_string($_POST['username']);
$password_POST = $_POST['password'];
$token_POST = $_POST['token'];
$token_SESSION = $_SESSION['token'];

if(isset($_POST["login"])){ // user and password verification

    if($username_POST == ""){
        header('refresh:3; url=logout.php');
        echo("Please enter a vaild username! Returning you back to login page...");
        exit();
    }

    $stmt = $mysqli->prepare("SELECT password FROM users WHERE username = ?");

    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('s', $username_POST);
    $stmt->execute();
    $stmt->bind_result($pw_check);
    $stmt->fetch();

    if ($pw_check != null) {
        $verify = password_verify($password_POST, $pw_check);
        if($verify){
            $_SESSION['id'] = $username_POST;
        }
        else{
            header('refresh:3; url=logout.php');
            echo("Password incorrect! Returning you back to login page...");
            exit();
        }
        $stmt->close();
    }
    else{
        header('refresh:3; url=logout.php');
        echo("User not found! Returning you back to login page...");
        exit();
    }
}

if ($verify) {
    if(!hash_equals($_SESSION['token'], $_POST['token'])){ //CSRF validation
        die("Request forgery detected");
    }

    $_SESSION['id'] = $username_POST;
}

?>

<head>
    <title>News Sharing Site</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="stylesheet.css" type="text/css">
</head>
<body>
    <br><header class=logintitle><b>Simple News Site</b></header>
    <hr />
        <?php

            if($_SESSION['id'] != NULL){
                echo "<div id=\"login\">Welcome, ".$_SESSION['id']."!
                <form action=\"post.php\" method=\"POST\">
                <input id=\"btn\" type=\"submit\" value=\"post\" name=\"token\"/>
                <input type=\"hidden\" value=\"".$token_SESSION."\" name=\"token\" />
                </form>
                <a id=\"btn\" href=\"logout.php\">Logout</a></div>";
            }
            else{
                header('refresh:3; url=logout.php');
                echo("You are not logged in! Returning you back to login page...");
                exit();
            }
        ?>
    <br>
    <table>

        <th>Author</th>
        <th>Title</th>
        <th>Story ID</th>

        <?php

            if(!isset($_POST["search"])){ //displays all stories
                $stmt = $mysqli->prepare("SELECT title, storyid, users.username FROM stories join users on (stories.userid=users.userid)");

                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }

                $stmt->execute();
                $stmt->bind_result($title, $storyid, $username);
                while($stmt->fetch()){ // list all stories

                    echo "<tr><td>".$username."</td>
                    <td><form action=\"storydetail.php\" method=\"POST\">
                    <input type=\"hidden\" value=\"$storyid\" name=\"storyid\" />
                    <input type=\"hidden\" value=\"".$token_SESSION."\" name=\"token\" />
                    <input id=\"btn\" type=\"submit\" value=\"".$title."\" name=\"storydetail\"/>
                    </form>
                    <td>".$storyid."</td></tr>";

                }
            }
            else if(isset($_POST["clear"])){ // clears search content
                header("Location: home.php");
                exit;
            }
            else{    //search for a particular user
                $user_for_search = $_POST['search_input'];

                $stmt = $mysqli->prepare("SELECT title, storyid, users.username FROM stories JOIN users ON (stories.userid = users.userid) WHERE username = ?");
                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $stmt->bind_param('s', $user_for_search);
                $stmt->execute();
                $stmt->bind_result($title, $storyid, $username);

                while($stmt->fetch()){

                    echo "<tr><td>".$username."</td>
                    <td><form action=\"storydetail.php\" method=\"POST\">
                    <input type=\"hidden\" value=\"$storyid\" name=\"storyid\" />
                    <input type=\"hidden\" value=\"".$token_SESSION."\" name=\"token\" />
                    <input id=\"btn\" type=\"submit\" value=\"".$title."\" name=\"storydetail\"/>
                    </form>
                    <td>".$storyid."</td></tr>";

                }

                $stmt->close();
            }
        ?>
    </table>
    <br><br><br>

    <footer>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST">
        Search for a particular user: <input id="searchuser" type="text" name = "search_input">
        <input type="submit" name = "search" value = "Search">
        <input type="submit" name = "clear" value = "Clear">
    </form>
    </footer>

</body>
</html>