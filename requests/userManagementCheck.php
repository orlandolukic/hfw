<?php 
/*
	Check whather user is logged in.
*/
	if (isset($ALLOW_LOGIN) && $ALLOW_LOGIN) {
		if (isset($_GET['action']) && trim($_GET['action'])!== "" && isset($_GET['security']) && trim($_GET['security'])!== "") {
			if($_GET['action'] == "login")
			{
				$ACCESS = true;

				$sql = mysqli_query($CONNECTION,"SELECT * FROM `users` WHERE BINARY `users`.`security_token` = '".$_GET['security']."'");
				if(mysqli_num_rows_wrapper($sql)===0)
				{
					$ACCESS = false;
				}else 
				{
					$pom = mysqli_fetch_object_wrapper($sql);
					$ACCESS_USER = $pom->username;
					$acc = generate_string(20);
					$sql = mysqli_query($CONNECTION, "UPDATE `users` SET `users`.`access_token` = '".$acc."', `users`.`timestamp` = '".time()."' WHERE BINARY `users`.`username` = '".$ACCESS_USER."' ");
					$_SESSION['hfw_username'] = $ACCESS_USER;
					$_SESSION['access_token'] = $acc;
				}
			}
		}
	};

	if (!isset($ACCESS) || isset($ACCESS) && !$ACCESS) 
	{
		if (!isset($_SESSION['hfw_username'])) header("location: ../");
	};


?>