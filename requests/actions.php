<?php 

//  User attempt to open this page
if (!(isset($REDIRECT) && !$REDIRECT) || !isset($ACTIONS)) { header("location: ".$domain); exit(); }

// Begin with specified action
if (isset($_GET['action']) && trim($_GET['action']) !== "")
{

switch($_GET['action'])
{
/* 
	ADD TO CART ACTION
 =======================
 Prefered od page /user/index.php. Can be included on every other page with $REDIRECT set to false and previously
 connected to database.
*/
case "addCart":
//	Regular request
	if (isset($_GET['type']) && trim($_GET['type'])!=="" && isset($_GET['cid']) && trim($_GET['cid'])!=="")
	{
		switch($_GET['type'])
		{
		case "subscription":
			$sql = mysqli_query($CONNECTION, "SELECT * FROM packages WHERE BINARY packageID = '".$_GET['cid']."'");
			if ($sql && mysqli_num_rows_wrapper($sql) && isset($_GET['month']) && trim($_GET['month']) !== "" && is_numeric($_GET['month']) && intval($_GET['month'])>0 && intval($_GET['month'])<13)
			{
				if ($ACTIONS->execute)
				{
					$sql1 = mysqli_query($CONNECTION, "SELECT * FROM (SELECT MONTH(`subscriptions`.date_start) AS smonth, MONTH(`subscriptions`.date_end) AS emonth, `subscriptions`.paymentID FROM subscriptions WHERE BINARY username = '".$USER->username."') tbl1 INNER JOIN payments ON `payments`.paymentID = `tbl1`.paymentID AND `payments`.paid = 1 WHERE  `tbl1`.smonth <= '".$_GET['month']."' AND `tbl1`.emonth >= '".$_GET['month']."'");
                    if (mysqli_num_rows_wrapper($sql1) && $ACTIONS->redirect)
					{
						if (isset($_GET['wid']) && trim($_GET['wid'])!=="") {
							header("location: ".$FILE."user/video/".$_GET['wid']); 
						} else 
						{ 
							header("location: ".$FILE."user/workshops?month=".$_GET['month']); 
						};
						exit();
					}
					$sql2 = mysqli_query($CONNECTION, "SELECT * FROM cart WHERE start_month = '".$_GET['month']."' AND BINARY username = '".$USER->username."'");
					if (mysqli_num_rows_wrapper($sql2) && $ACTIONS->redirect) {
                        header("location: ".$FILE."user/cart");
                        exit();
                    }
					
					$itemID = _SQL_escape_exstring(["SELECT * FROM cart WHERE BINARY itemID = '","'"], 20);

					$sql = mysqli_query($CONNECTION, "INSERT INTO cart (itemID, workshopID, packageID, username, start_month) VALUES 
					                   ('".$itemID."', NULL, '".$_GET['cid']."', '".$USER->username."', ".$_GET['month'].")");
					if ($sql && $ACTIONS->redirect) { header("location: ".$FILE."user/cart"); exit(); }
				} else
				{
					$ACTIONS->valid = true;
				}
			} else header("location: ".$ACTIONS->redirection->SQL_error."?response=false");

			if (isset($ACTIONS->redirection->urlToPostData) && $ACTIONS->redirection->urlToPostData)
			{
				// Set month condition
				$monthCond = isset($_GET['month']) && trim($_GET['month']) !== "" && is_numeric($_GET['month']) && intval($_GET['month'])>0 && intval($_GET['month']) < 13;
				$workshopCond = isset($_GET['wid']) && trim($_GET['wid']) !== "";

				$urlToPostData = "login.php?action=addCart&type=".$_GET['type']."&cid=".$_GET['cid'];
				$ACTIONS->redirection->urlToPostData = "?action=addCart&type=".$_GET['type']."&cid=".$_GET['cid'];
				if ($monthCond)
				{
					$urlToPostData .= "&month=".$_GET['month'];
					$ACTIONS->redirection->urlToPostData .= "&month=".$_GET['month'];
				} 				
				if ($workshopCond) 
				{
					$urlToPostData .= "&wid=".$_GET['wid'];
					$ACTIONS->redirection->urlToPostData .= "&wid=".$_GET['wid'];
				}
			};
			break;

        case "workshop":
			$sql = mysqli_query($CONNECTION, "SELECT * FROM workshops WHERE BINARY workshopID = '".$_GET['cid']."' AND active = 1");

			if ($sql && mysqli_num_rows_wrapper($sql))
            {
				if ($ACTIONS->execute)
				{		
				//	Check if workshop already exsist in bought workshops
					$sql_control = mysqli_query($CONNECTION, "SELECT * FROM boughtworkshops WHERE BINARY username = '".$USER->username."' AND BINARY workshopID = '".$_GET['cid']."'");
					$itemID = _SQL_escape_exstring(["SELECT * FROM cart WHERE BINARY itemID = '","'"], 20);

					if (!mysqli_num_rows_wrapper($sql_control))
					{
						$sql = mysqli_query($CONNECTION, "INSERT INTO cart (itemID, workshopID, packageID, username) VALUES 
					                   ('".$itemID."', '".$_GET['cid']."', NULL, '".$USER->username."')");
						if ($sql && $ACTIONS->redirect) { header("location: ".$FILE."user/cart"); }
					} else
					{
						$_SESSION['redirection'] = $_GET['cid'];
						$_SESSION['self'] = true;
						header("location: ".$ACTIONS->redirection->SQL_exsist);
						exit();
					}									
				} else
				{
					$ACTIONS->valid = true;
				}
			} else header("location: ".$ACTIONS->redirection->SQL_error);

			if (isset($ACTIONS->redirection->urlToPostData) && $ACTIONS->redirection->urlToPostData)
			{
				$urlToPostData = "login.php?action=addCart&type=".$_GET['type']."&cid=".$_GET['cid'];
				$ACTIONS->redirection->urlToPostData = "?action=addCart&type=".$_GET['type']."&cid=".$_GET['cid'];
			};
			break;
		}
	} else 
	{
		if (isset($ACTIONS->redirection->GET_error) && $ACTIONS->redirection->GET_error!=="") header("location: ".$ACTIONS->redirection->GET_error);
	};
	break;

/* 
	USER VALIDATION
 =======================

*/

case "userValidate":
	if (!isset($_GET['username']) || !isset($_GET['token'])) header("location: ".$ACTIONS->redirection->error_params);
	$sql = mysqli_query($CONNECTION, "UPDATE users SET verified = 1 WHERE BINARY username = '".$_GET['username']."' AND BINARY security_token = '".$_GET['token']."'");

	if ($sql)
	{
		$_SESSION['hfw_username'] = $_GET['username'];
		if ($ACTIONS->redirect) header("location: ".$ACTIONS->redirection->success_req);
		exit();
	} else header("location: ".$ACTIONS->redirection->SQL_error);

	exit();
	break;

};

};

?>