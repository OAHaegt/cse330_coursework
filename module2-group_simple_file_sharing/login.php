<!DOCTYPE html>
<html lang='en'>

<head>  
    <link rel="stylesheet" type = "text/css" href="style.css"/>
    <title>File Sharing System</title>
</head>

<body>
    <br><header class=logintitle><b>Simple File Sharing Site</b></header>
    <hr /><br>
    <div class = "login"> 
        <h1>Login</h1>

        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method = "POST">
        Username: <input type = "text" name = "username" />
        <input type = "submit" name = "operation" value = "Login"/>
        <input type = "submit" name = "operation" value = "Register">
    </form>
    </div>
<?php

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $operation = $_POST['operation'];
        switch($operation) { // determine if the entered username is for login or for sign up
            case "Login": 
                session_start();
                $users = fopen("/var/www/user/users.txt", "r");
                $user_input = (string) $_POST['username'];
                while(!feof($users)){
                    $user_read = trim(fgets($users));
                    if(strcmp($user_input, $user_read) == 0){ 
                        if($user_input == ""){
                            echo(htmlentities("Please input a username"));
                            exit;
                        }
                        $_SESSION['username'] = $user_input;
                        fclose($users);
                        header("Location: page.html"); // proceed to file manage page                    
                        exit;
                }
                }
                printf("%s",htmlentities("User not found."));
                fclose($users);
                break;

                
            case "Register":
                session_start();
                
                $txt = ""; // prepare new contents for users.txt

                if(isset($_POST['username'])){
                    $users = fopen("/var/www/user/users.txt","r");
                    $user_input = (string) $_POST['username'];
                    while(!feof($users)){
                        $user_read = fgets($users);
                        $txt = $txt.$user_read."\n"; // adds original contents 
                        if(strcmp($user_input, $user_read) == 0){
                            if($user_input == ""){
                                echo(htmlentities("Please input a username"));
                            }
                            else{
                                echo (htmlentities("That is not a valid username. ".$user_input." has already been taken."));
                            }
                        exit;
                        }
                    }
                    fclose($users);
                    if(preg_match('/^[\w_\-]+$/', $_POST['username'])){
                        $users = fopen("/var/www/user/users.txt","w");
                        $txt = $txt.$user_input."\n"; // adds new user
                        mkdir("/var/www/user/".$_POST['username'], 0755); // creates the directory for new user and set permission
                        fwrite($users,$txt);
                        echo(htmlentities("You have successfully created a user called ".$user_input."."));
                        fclose($users);
                    }
                    else{
                        echo(htmlentities("That is not a valid username. Take care of the format!"));
                    }

                }
                break;

        }
    }
    
?>
</body>

</html>