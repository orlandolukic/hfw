<?php 
	session_start();
	$prepath = "../";
	require "../connect.php";
	require "../functions.php";
	require "../lang/func.php";
	$REDIRECT = false;
	if ($_SESSION['lang'] !== "EN")
	{
		require "../".strtolower($_SESSION['lang'])."/global.php";
		require "../".strtolower($_SESSION['lang'])."/getDATA.php";
	} else
	{
		require "../global.php";
		require "../getDATA.php";
	};
	
	if (!isset($_SESSION['hfw_username'])) header("location: ".$domain);
	if (

		(!isset($_GET['pid']) && !isset($_SESSION['bank_protocol_payment_id'])) || 
		!isset($_GET['paymentType']) || 
		(isset($_GET['paymentType']) && (trim($_GET['paymentType'])!=="credit-card" && trim($_GET['paymentType'])!=="paypal"))

		) header("location: ".$FILE."?access=denied&pg=verifyPayment.php");

	if ($_GET['paymentType']==="credit-card" && isset($_POST['mdStatus'])) 
	{
		$mdStatus = $_POST['mdStatus'];
		if ($mdStatus =="1" || $mdStatus == "2" || $mdStatus == "3" || $mdStatus == "4")
		{ 			   
		   $Response = $_POST["Response"];			   
		   if ( $Response != "Approved")
			{
				$_SESSION['bank_protocol_payment_id'] = $_GET['pid'];
				header("location: ".$FILE."verifyPayment/canceled");
				exit();
			}			
		};	
	} elseif ($_GET['paymentType']==="credit-card" && !isset($_POST['mdStatus']))
	{
		header("location: ".$FILE."verifyPayment/canceled");
		exit();
	};


	$tmp_pid  = ($_GET['paymentType']!=="paypal" ? $_SESSION['bank_protocol_payment_id'] : $_GET['pid']);
	$tmp_curr = ($_GET['paymentType']==="paypal" ? $USER->currencyID : "RSD");
	$sql = mysqli_query($CONNECTION,"SELECT * FROM payments WHERE paymentID = '".$tmp_pid."' AND username = '".$USER->username."' AND paid = 0");
	if (!mysqli_num_rows_wrapper($sql)) header("location: ".$domain."?atm=paymentVerifyParams");

	$sql = mysqli_query($CONNECTION,"UPDATE payments SET paid = 1 WHERE username = '".$USER->username."' AND paymentID = '".$tmp_pid."'");

	$obj = array(); $i=0;
	$sql_add = mysqli_query($CONNECTION,"SELECT `cart_workshops`.*, `packages`.price_".$USER->currencyID." AS pricePackage, `packages`.`price_RSD` AS pricePackageRSD,  `packages`.flag FROM (SELECT `cart`.*, `workshops`.price_".$USER->currencyID." AS priceWorkshop, `workshops`.`price_RSD` AS priceWorkshopRSD FROM cart LEFT OUTER JOIN `workshops` ON `cart`.workshopID = `workshops`.workshopID WHERE BINARY username = '".$USER->username."') cart_workshops LEFT OUTER JOIN `packages` ON `cart_workshops`.packageID = `packages`.`packageID`");
	while($t = mysqli_fetch_object_wrapper($sql_add)) $obj[$i++] = $t;

	$hasPackage = false;

	for ($i=0; $i<count($obj); $i++)
	{
		if ($obj[$i]->workshopID)
		{
			$p_sql = mysqli_query($CONNECTION,"INSERT INTO boughtworkshops(username, workshopID, paymentID) VALUES 
			                     ('".$USER->username."',
			                      '".$obj[$i]->workshopID."',
			                      '".$tmp_pid."'
			                     )");
			if ($_GET['paymentType'] === "credit-card") { $obj[$i]->priceWorkshop = $obj[$i]->priceWorkshopRSD; }			
			// Delete wishlist items
			$sql = mysqli_query($CONNECTION,"DELETE FROM wishlist WHERE BINARY workshopID = '".$obj[$i]->workshopID."'");
		} else if ($obj[$i]->packageID)
		{
			$hasPackage   = true;
			$time         = strtotime("+".$obj[$i]->flag." month -1 day", strtotime($year."-".$obj[$i]->start_month."-01"));			
			$date_end     = date("Y-m-d", $time);
			$p_sql        = mysqli_query($CONNECTION,"INSERT INTO subscriptions(username, type, date_start, date_end, paymentID, start_month) VALUES 
			                     ('".$USER->username."',
			                      '".$obj[$i]->packageID."',
			                      '".$year."-".$obj[$i]->start_month."-01',
			                      '".$date_end."',
			                      '".$tmp_pid."',
			                      '".$obj[$i]->start_month."'
			                     )");
			if ($_GET['paymentType'] === "credit-card") { $obj[$i]->pricePackage = $obj[$i]->pricePackageRSD; }		

			// Notify admin about subscription
			mysqli_query($CONNECTION,"UPDATE coupons SET subscribed = 1 WHERE BINARY `coupons`.username = '".$USER->username."' AND `coupons`.workshopID IN (SELECT `workshops`.workshopID FROM workshops WHERE ".$obj[$i]->start_month." <= MONTH(`workshops`.date_publish) AND ".date("m", $time)." >= MONTH(`workshops`.date_end))");
		};
	};

	if ($sql)
	{
		//	Delete all workshops from wishlist ON forsale = 0;
		if ($hasPackage) 
		{
			mysqli_query($CONNECTION,"DELETE FROM wishlist WHERE BINARY username = '".$USER->username."' AND workshopID IN (SELECT workshopID FROM workshops WHERE forsale = 0 AND active = 1)");

			// Make notification about coupons { ... }
		}

		if ($_GET['paymentType'] === "paypal") 
		{
			$_SESSION['show_succ_payment'] = (object) array("data" => $_GET['pid'], "show" => true, "paymentMethod" => "paypal", "paymentID" => $_GET['pid']);
		} elseif ($_GET['paymentType'] === "credit-card") 
		{
			$_SESSION['show_succ_payment'] = (object) array("data" => $_SESSION['bank_protocol_payment_id'], "show" => true, "paymentMethod" => "credit-card", "paymentID" => $_SESSION['bank_protocol_payment_id']);
		};
		mysqli_query($CONNECTION,"DELETE FROM cart WHERE BINARY username = '".$USER->username."'");
		if (isset($_SESSION['bank_protocol_payment_id'])) unset($_SESSION['bank_protocol_payment_id']);

//		$TEMPLATE_SELECT = "payment_receipt";
//		$PAYMENT_INFO    = (object) array("paymentID" => $_SESSION["show_succ_payment"]->data, "printButton" => true);
//		//include "sendEmail.php";
//		//send_email();
//		$PAYMENT_INFO    = (object) array("paymentID" => $_SESSION["show_succ_payment"]->data, "printButton" => false);
//		$lang = return_lang("en","../");
//		//send_email();
		header("location: ".$FILE."user/");
 	};


?>