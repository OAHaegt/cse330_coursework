<!DOCTYPE html>
<html lang='en'>

<?php

session_start();

$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

require 'database.php';
$username_POST = $_POST['username'];
$password_POST = $_POST['password'];

if(isset($_POST["register"])){
    if($username_POST == ""){
        echo "Please enter a valid username";
        exit;
    }

    if($password_POST == ""){
        echo "Please enter a valid password";
        exit;
    }

    else{
        $exists = false;
        $stmt = $mysqli->prepare("SELECT username FROM users WHERE username = ?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('s', $username);
        $username = $mysqli->real_escape_string($username_POST); //filter input
        $stmt->execute();
        $stmt->bind_result($search);
        $stmt->fetch();
        $stmt->close();

        if($search != NULL){
            $exists = true;
        }

        if(!$exists){
            $hashed = password_hash($password_POST, PASSWORD_DEFAULT); // prepare a hashed password with default random salt
            $stmt = $mysqli->prepare("insert into users (username, password) values (?, ?)");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmt->bind_param('ss', $username_POST, $hashed);
            $stmt->execute();
            $stmt->close();
            echo "user ".$username_POST." has been created. please login to continue.";
            $stmt->close();
        }

        else{
            echo "Username already exists.";
        }
    }
}

?>

<html>
<head>
    <meta charset = "UTF-8">
    <link rel="stylesheet" href="stylesheet.css" type="text/css">
    <title>Simple News Site: Login</title>
</head>
<body>
    <br><header class=logintitle><b>Simple News Site</b></header>
    <hr />
    <div class = "login"> 
    <h1>Login</h1>
    <form action="home.php" method="POST">
        Username: <input type="text" name = "username" /><br />
        Password: <input type="password" name="password" /><br />
        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
        <input type="submit" name="login" value = "login" />
    </form>
    </div>

    <div class = "register"> 
    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
        Sign up here if you do not have an account:<br />
        New username: <input type="text" name="username" /><br />
        Password: <input type="password" name="password" /><br />
        <input type="submit" name="register" value="register" />
    </form>
    </div>


</body>
</html>