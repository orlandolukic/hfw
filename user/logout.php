<?php 
//	Logout page
	session_start();
	$REDIRECTION = "../";

	if (isset($_GET['type']))
	{
		switch($_GET['type'])
		{
		case "access_token":
			if (!isset($_GET['redirect'])) break;
			$REDIRECTION = urldecode($_GET['redirect']);			
			break;
		case "session_expired":
			if (!isset($_GET['redirect'])) break;
			$REDIRECTION = urldecode($_GET['redirect']);	
			break;
		}
	}
	// Destroy all session data
	session_destroy();
	session_start();

	if (isset($_GET['session_name']) && trim($_GET['session_name'])!=="")
	{
		// Initiate session asignment option
		$_SESSION[$_GET['session_name']] = isset($_GET['session_value']) && trim($_GET['session_value'])!=="" ? $_GET['session_value'] : "";
	}

	$_SESSION['token'] = "FGlgtoIzTpfPkyi2UibB";
	header("location: ".$REDIRECTION);

?>