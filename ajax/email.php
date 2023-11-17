<?php 

if (isset($_POST['data']) && isset($_POST['type']))
{
//	Include files for page usage
	session_start();
	$prepath = '../';
	include "../functions.php";
	include "../connect.php";
	if ($_SESSION['lang'] !== "EN")
	{
		include "../".strtolower($_SESSION['lang'])."/global.php";
		include "../".strtolower($_SESSION['lang'])."/getDATA.php";
	} else
	{
		include "../global.php";
		include "../getDATA.php";
	};

	$type     = $_POST['type'];
	$DATA     = json_decode($_POST['data']);
	$data_arr = array();
	$RESPONSE = array("info" => &$data_arr);
	$IS_SENT  = false;
	$REDIRECT = false;

	switch(base64_decode($type))
	{
	case "sendResetPasswordEmail":		
		$sql = mysqli_query($CONNECTION, "SELECT * FROM users WHERE BINARY email = '".$DATA->email."' LIMIT 1");
		if (mysqli_num_rows_wrapper($sql) === 0) 
		{ 
			$IS_SENT = false; 
			$data_arr["errorCode"] = 404; 
		} else 
		{
//			$TEMPLATE_SELECT = base64_decode($type);
//			$obj             = mysqli_fetch_object_wrapper($sql);
//			$USER_MAIL       = (object) array("email" => $DATA->email, "token" => $obj->security_token, "username" => $obj->username, "name" => $obj->name);
//			include "../requests/sendEmail.php";
//			$IS_SENT = send_email();
            $IS_SENT = true;
		}
		if (!$IS_SENT) { $data_arr["errorCode"] = 409; }
		break;

	case "sendLetter":
//		$TEMPLATE_SELECT = "newMessageFromWebsite";
//		$CONTACT = $DATA;
//		include "../requests/sendEmail.php";
//		$IS_SENT = send_email();
        $IS_SENT = true;
		break;

	case "sendConfirmationMail":
//		$TEMPLATE_SELECT = "registration";
//		$REGISTRATION = $USER;
//		include "../requests/sendEmail.php";
//		$IS_SENT = send_email();
		$IS_SENT = true;
		break;
	};
	$data_arr["response"] = $IS_SENT;

	echo json_encode($RESPONSE);

} else exit();


?>