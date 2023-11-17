<?php 

	session_start();
	$prepath = "../";
	require $prepath."connect.php";
	require $prepath."functions.php";
	if ($_SESSION['lang'] !== "EN")
	{
		require "../".strtolower($_SESSION['lang'])."/global.php";
		require "../".strtolower($_SESSION['lang'])."/getDATA.php";
	} else
	{
		require "../global.php";
		require "../getDATA.php";
	}

	if (!isset($_SESSION['hfw_username']) || !isset($_POST['type']) || !isset($_POST['data'])) exit();
	$arr_data = array(); $arr_info = array();
	$TYPE     = $_POST['type'];
	$DATA     = json_decode($_POST['data']);
	$RESPONSE = array("info" => &$arr_info, "data" => &$arr_data);

	switch(base64_decode($TYPE))
	{
	case "addNewPayment":
		if ($DATA->state === "approved")
		{
			$sql = mysqli_query($CONNECTION, "INSERT INTO payments ( paymentID, 
			                   						   trans_currencyID, 
			                   						   trans_date, 
			                   						   receiptID,
			                   						   payment_amount,
			                   						   payment_method,
			                   						   username,
			                   						   piID,
			                   						   paid
		                   						  ) VALUES 
		                   						  (
		                   						     '".$DATA->id."',
		                   						     '".$USER->currencyID."',
		                   						     CURDATE(),
		                   						     '".substr($DATA->id, 4, 10)."',
		                   						     '".$DATA->transactions[0]->amount->total."',
		                   						     2,
		                   						     '".$USER->username."',
		                   						     (SELECT piID FROM paymentinfo WHERE username = '".$USER->username."' AND defaultInfo = 1 LIMIT 1),
		                   						     0
			                   					  )");
			if ($sql)
			{
				$arr_info["response"] = true; $arr_info["verify"] = true;
				$arr_data["redirectURI"] = $FILE."verifyPayment/paypal/?pid=";
			}
		} else
		{
			$arr_info["response"] = true; $arr_info["verify"] = false;
				$arr_data = NULL;
		};
		break;
	}

	echo json_encode($RESPONSE);

?>