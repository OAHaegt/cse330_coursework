
 <?php
	session_start();
	require 'database.php';
	header("Content-Type: application/json"); //sending json response

                $user_input = (string) $_POST['user'];
                $password = (string) $_POST['password'];

                $stmt = $mysqli->prepare("SELECT userid, password FROM users WHERE username=?");
			
			//exit if query prep fails
			if(!$stmt)
			{
				echo json_encode(array(
					"success" => false,
					"message" => "Query Prep Failed"
				));
				exit;
			}
                $stmt->bind_param('s', $user_input);
                $stmt->execute(); 
                $stmt->bind_result($retuser,$retpassword);
				$stmt->fetch();

                if($retuser == 0 && $retpassword == 0)
                {
                    $pass_secure = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                        
                    if(!$stmt){
                       	echo json_encode(array(
							"success" => false,
							"message" => "Query Prep Failed"
						));
					exit;
                    }
        
                    $stmt->bind_param('ss',$user_input, $pass_secure);
                    $stmt->execute();
                    $stmt->close();
                    echo json_encode(array(
						"success" => true
					));
					exit;
                    
                }
                else
                {
					echo json_encode(array(
						"success" => false,
						"message" => " Not Available"
					));
				exit;
				}           
?>
