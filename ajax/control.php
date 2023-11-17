<?php
	// Begin with..
	if (!isset($_POST['HttpRequest']) || !isset($_POST['data'])) exit();
	session_start();
	$prepath  = "../";
	$REDIRECT = false;
	$INCLUDE  = (object) array("getDATA" => false);
	include_once "../functions.php";
	include_once "../connect.php";
	if ($_SESSION['lang'] !== "EN")
	{
		include_once "../".strtolower($_SESSION['lang'])."/global.php";
		include_once "../".strtolower($_SESSION['lang'])."/getDATA.php";
	} else
	{
		include_once "../global.php";
		include_once "../getDATA.php";
	};

//	Prepare for data conditions check
	$arr_info = array(); $arr_data = array();
	$DETAIL   = json_decode($_POST['HttpRequest']);
	$DATA     = json_decode($_POST['data']);
	$RESPONSE = array("info" => &$arr_info, "data" => &$arr_data);

	switch($DETAIL->type)
	{
	//	 Leave review section
	case base64_encode("leaveReview"):
		if (base64_decode($DETAIL->action) === "new")
		{ 
			$str = _SQL_escape_exstring(["SELECT reviewID FROM reviews WHERE reviewID = '","'"], 20);			
			$sql = mysqli_query($CONNECTION, "INSERT INTO reviews(reviewID, title, text, date, time, rating, username, workshopID) VALUES
			                   (
			                   '$str',
			                   '".htmlspecialchars($DATA->title)."', 
			                   '".htmlspecialchars($DATA->review, ENT_QUOTES, "UTF-8")."',
			                   CURDATE(),
			                   ".time().",
			                   '".$DATA->rating."',
			                   '".$_SESSION['hfw_username']."',
			                   '".htmlspecialchars($DATA->workshopID)."'
			                   )");
		//	Successfully made query
			if ($sql)
			{
				$arr_info = array("response" => true); 
				$arr_data = array("reviewID" => $str,
				                  "stars"    => (object) array("each" => determine_rating($DATA->rating),
				                                               "num"  => number_format($DATA->rating,1,'.',',')
				                                              ),
				                  "date"     => make_date(-1,date("Y-m-d")),
				                  "time"     => make_time(time())
				                  );
			} else
			{
				$arr_info = array("response" => false); $arr_data = NULL;
			}
		} else // Change
		{
			$sql = mysqli_query($CONNECTION, "UPDATE reviews SET title = '".$DATA->title."', text = '".$DATA->review."', status = -1, time=".time().", date=CURDATE(), rating = '".$DATA->rating."' WHERE username='$user' AND workshopID='".$DATA->workshopID."' AND reviewID = '".$DATA->reviewID."'");
			if ($sql)
			{
				$arr_info = array("response" => true); 
				$arr_data = array(
				 "stars" => array("num"  => number_format($DATA->rating,1,'.',','), 
				                  "each" => determine_rating($DATA->rating)
				                 ),
				 "date"  => make_date(-1, date("Y-m-d")),
				 "time"  => make_time(time())				 
				 				 );
			} else
			{
				$arr_info = array("response" => false); $arr_data = NULL;
			}
		};
		break;


	case base64_encode("deleteUserReview"):
		$sql1 = mysqli_query($CONNECTION, "DELETE FROM comments WHERE BINARY reviewID = '".$DATA->reviewID."'");
		$sql = mysqli_query($CONNECTION, "DELETE FROM reviews WHERE BINARY reviewID='".$DATA->reviewID."'");
		if ($sql && $sql1)
		{
			$sql = mysqli_query($CONNECTION, "SELECT SUM(`reviews`.rating)/COUNT(`reviews`.reviewID) AS count FROM reviews WHERE BINARY reviewID  = '".$DATA->reviewID."'");
			$rating = mysqli_fetch_object_wrapper($sql)->count;
			$arr_info = array("response" => mysqli_num_rows_wrapper($sql)); 
			$arr_data = array("rating" => $rating, 
			                  "num_comments" => mysqli_num_rows_wrapper($sql), 
			                  "stars" => determine_rating($rating));

			$arr_info = array("response" => true);
		} else
		{
			$arr_info = array("response" => false); $arr_data = NULL;
		}
		break;


	case base64_encode("submitComment"):
		 $dat    = '';
		 $user_p = ($userActive ? $user : $admin);

		 $sql = mysqli_query($CONNECTION, "SELECT * FROM reviews WHERE reviewID = '".$DATA->reviewID."'");
		 if (!mysqli_num_rows_wrapper($sql))
		 {
		 	$arr_info = array("response" => false); $arr_data = NULL;
		 	break;
		 }

	  // Ensuring there's no duplicate
		 $str    = generate_string(20);
		 $sql    = "SELECT commentID FROM comments WHERE commentID='$str'";
		 if (mysqli_num_rows_wrapper(mysqli_query($CONNECTION, $sql)))
		 {
		 	while(mysqli_num_rows_wrapper(mysqli_query($CONNECTION, $sql)))
		 	{
		 		$str = generate_string(20);
		 		$sql = "SELECT commentID FROM comments WHERE commentID='".$str."'";
		 	}
		 };
		 $sql = mysqli_query($CONNECTION, "INSERT INTO comments(commentID, text, username, date, time, reviewID) VALUES 
		                        				    ('$str', '".htmlentities($DATA->text, ENT_QUOTES, "UTF-8")."', '$user_p', CURDATE(), '".time()."', '".htmlspecialchars($DATA->reviewID, ENT_QUOTES, "UTF-8")."')");
		 if ($sql)
		 {
		 	$sql_comments = mysqli_query($CONNECTION, "SELECT * FROM comments WHERE commentID = '$str'");
		 } else 
		 { 
		 	$arr_info = array("response" => $str, "error_code" => "0x0002"); $arr_data = NULL;
		 	break; 
		 };
		
		$dat = '';
		$op  = 1;
		$offset = array("margin-b-10 margin-md-b-20");
		require "printComment.php";		

		$sql1 = mysqli_query($CONNECTION, "SELECT * FROM comments WHERE BINARY reviewID = '".$DATA->reviewID."'");

		$arr_info = array("response" => true); 
		$arr_data = array("more_comments" => $more, 
		                  "data_comments" => intval($DATA->displayedComments)+mysqli_num_rows_wrapper($sql_comments),
		                  "text"          => $dat,
		                  "comment_id"    => $commID,
		                  "hasData"       => (bool) $numberSQL,
		                  "restComments"  => mysqli_num_rows_wrapper($sql1)
		                  );	


		break;

	case base64_encode("allowReview"):
		if (!$adminActive) { 
			$arr_info = array("response" => false); $arr_data = NULL; break;
		 }
		$sql = mysqli_query($CONNECTION, "SELECT * FROM administrators WHERE username='$admin' AND access_allow_commenting=1 LIMIT 1");
		if (!mysqli_num_rows_wrapper($sql))
		{
			$arr_info = array("response" => false); $arr_data = NULL; break;
		}

		$sql = mysqli_query($CONNECTION, "UPDATE reviews SET status = 1 WHERE reviewID = '".$DATA->reviewID."'");
		if ($sql)
		{
			$sql = mysqli_query($CONNECTION, "SELECT rating FROM reviews WHERE  reviewID  = '".$DATA->reviewID."'");
			$rating = 0;
			while($t = mysqli_fetch_object_wrapper($sql)) $rating += $t->rating;
			$rating /= mysqli_num_rows_wrapper($sql);
			$arr_info = array("response" => mysqli_num_rows_wrapper($sql)); 
			$arr_data = array("rating" => $rating, 
			                  "num_comments" => mysqli_num_rows_wrapper($sql), 
			                  "stars" => determine_rating($rating));
		} else
		{
			$arr_info = array("response" => false); $arr_data = NULL;
		}
		break;


//		 Delete specified comment
	case base64_encode("deleteComment"):
		if (!$userActive && !$adminActive) exit();
		$sql1 = mysqli_query($CONNECTION, "SELECT * FROM comments WHERE reviewID = '".$DATA->reviewID."'");
		$sql = mysqli_query($CONNECTION, "SELECT * FROM comments WHERE reviewID = '".$DATA->reviewID."' AND commentID = '".$DATA->commentID."'");
		if (!$sql || !($num_init=mysqli_num_rows_wrapper($sql)))
		{
			$arr_info = array("response" => false, "error" => "ERR_0x0000"); $arr_data = NULL; break;
		};

		$sql = mysqli_query($CONNECTION, "DELETE FROM comments WHERE reviewID = '".$DATA->reviewID."' AND commentID = '".$DATA->commentID."'");

		if ($sql)
		{				
			// Exit if there's no need of fetching data
			if (mysqli_num_rows_wrapper($sql1)===1) { $arr_info = array("response" => true, "exec" => false); $arr_data = array("hasData" => false, "more_comments" => (bool) 0, "data_comments" => 0, "restComments" => 0); break; }

		//	Rest of code ...
			$sql_comments = mysqli_query($CONNECTION, "SELECT * FROM comments WHERE reviewID='".$DATA->reviewID."' ORDER BY time DESC, date DESC LIMIT 1 OFFSET ".$DATA->displayedComments."");

		//	Prepare for printing
			$dat = '';
			$DATA->displayedComments = intval($DATA->displayedComments);
			$op     = 1;
			$offset = array(0 => "margin-t-10 margin-md-t-10");
			require "printComment.php";

			$sql_rest = mysqli_query($CONNECTION, "SELECT * FROM comments WHERE BINARY reviewID = '".$DATA->reviewID."'");

			$arr_info = array("response" => true);
			$arr_data = array("more_comments"      => $more, 
			                  "data_comments"      => $DATA->displayedComments+mysqli_num_rows_wrapper($sql_comments),
			                  "sql_query_comments" => mysqli_num_rows_wrapper($sql1),
			                  "htmlProperty"       => $dat,
			                  "commentID"          => ((bool) $numberSQL ? ($numberSQL===1 ? $commID : $comments) : -1),
			                  "hasData"            => (bool) $numberSQL,
			                  "restComments"       => mysqli_num_rows_wrapper($sql_rest)
			                  );				

		} else { $arr_info = array("response" => false, "error" => "ERR_0x0001"); $arr_data = NULL; }

		break;

	case base64_encode("addWishList"):
		checkUser();
		$sql = mysqli_query($CONNECTION, "SELECT * FROM wishlist WHERE workshopID = '".$DATA->workshopID."' AND username='$user'");
		if (!$sql || mysqli_num_rows_wrapper($sql)===1)
		{
			$arr_info = array("response" => false, "error" => "ERR_0x0002", "reload" => true); $arr_data = NULL; break;
		};

		$sql = mysqli_query($CONNECTION, "INSERT INTO wishlist(workshopID, date, username) VALUES('".htmlspecialchars($DATA->workshopID, ENT_QUOTES, "UTF-8")."', CURDATE(), '$user')");

		if ($sql)
		{
			$arr_info = array("response" => true); $arr_data = NULL; break;
		};

		$arr_info = array("response" => false, "error" => "ERR_0x0001"); $arr_data = NULL;
		break;

	case base64_encode("savePredefinedPaymentDetails"):
		checkUser();
		$sql = mysqli_query($CONNECTION, "SELECT * FROM paymentinfo WHERE BINARY name='".htmlspecialchars($DATA->name, ENT_QUOTES, "UTF-8")."' AND BINARY address = '".htmlspecialchars($DATA->address, ENT_QUOTES, "UTF-8")."' AND BINARY city = '".htmlspecialchars($DATA->city, ENT_QUOTES, "UTF-8")."' AND BINARY telephone = '".htmlspecialchars($DATA->telephone, ENT_QUOTES, "UTF-8")."' AND BINARY username='".$USER->username."' LIMIT 1");

		if ($sql && mysqli_num_rows_wrapper($sql)===1)
		{
			$m    = true;
			$xxy  = "m";
			$obj  = mysqli_fetch_object_wrapper($sql);

			$sql  = mysqli_query($CONNECTION, "UPDATE paymentinfo SET defaultInfo = 2 WHERE username = '".$USER->username."' AND defaultInfo = 1");
			$sql  = mysqli_query($CONNECTION, "UPDATE paymentinfo SET defaultInfo = 1 WHERE username = '".$USER->username."' AND piID = '".$obj->piID."'");
			$sql  = mysqli_query($CONNECTION, "UPDATE paymentinfo SET defaultInfo = 0 WHERE username = '".$USER->username."' AND defaultinfo = 2");


			$arr_info = array("response" => $$xxy);
			$arr_data = array("row" => true);


		} elseif ($sql && mysqli_num_rows_wrapper($sql)===0)
		{
			$sql_add = mysqli_query($CONNECTION, "INSERT INTO paymentinfo (name, address, city, telephone, username, defaultInfo) VALUES (
			                    '".htmlspecialchars($DATA->name, ENT_QUOTES, "UTF-8")."', '".htmlspecialchars($DATA->address, ENT_QUOTES, "UTF-8")."', '".htmlspecialchars($DATA->city, ENT_QUOTES, "UTF-8")."', '".htmlspecialchars($DATA->telephone, ENT_QUOTES, "UTF-8")."',
			                    '".$USER->username."', 2
			                   )");
			$sql_up1  = mysqli_query($CONNECTION, "UPDATE paymentinfo SET defaultInfo = 0 WHERE username = '".$USER->username."' AND defaultinfo = 1");
			$sql_up2  = mysqli_query($CONNECTION, "UPDATE paymentinfo SET defaultInfo = 1 WHERE username = '".$USER->username."' AND defaultInfo = 2");

			$arr_info = array("response" => $sql_add && $sql_up1 && $sql_up2);
			$arr_data = array("row" => false, "paymentInfo" => array("name"      => htmlspecialchars($DATA->name, ENT_QUOTES, "UTF-8"),
			                                                         "address"   => htmlspecialchars($DATA->address, ENT_QUOTES, "UTF-8"),
			                                                         "city"      => htmlspecialchars($DATA->city, ENT_QUOTES, "UTF-8"),
			                                                         "telephone" => htmlspecialchars($DATA->telephone, ENT_QUOTES, "UTF-8"),
			                                                         "email"     => htmlspecialchars($USER->email, ENT_QUOTES, "UTF-8")
			                                                         ));
			$sql = mysqli_query($CONNECTION, "SELECT * FROM paymentinfo WHERE username='".$USER->username."' AND piID > 0 ORDER BY defaultInfo DESC");
			$arr_data["count_pi"] = ($sql ? mysqli_num_rows_wrapper($sql) : -1);

			$i=0; $r = array();
			while($t = mysqli_fetch_object_wrapper($sql)) $r[$i++] = $t;
			$r[$i] = $USER->email;
			$arr_data["paymentInfoOptions"] = $r;
			$arr_data["words"] = array("default" => $lang->predefined,
			                           "delete"  => $lang->delete);


		} else
		{	// Query did not go through
			$arr_info = array("response" => false, "errorCode" => 404); $arr_data = NULL;
		}
		break;

	case base64_encode("selectDefaultPaymentInfo"):
		checkUser();
		$sql1 = mysqli_query($CONNECTION, "UPDATE paymentinfo SET defaultInfo = 2 WHERE username = '".$USER->username."' AND piID = '".$DATA->paymentInfoID."'");
		$sql2 = mysqli_query($CONNECTION, "UPDATE paymentinfo SET defaultInfo = 0 WHERE username = '".$USER->username."' AND defaultInfo = 1");
		$sql3 = mysqli_query($CONNECTION, "UPDATE paymentinfo SET defaultInfo = 1 WHERE username = '".$USER->username."' AND defaultInfo = 2");

		if ($sql1 && $sql2 && $sql3)
		{
			$sql = mysqli_query($CONNECTION, "SELECT name, address, city, telephone FROM paymentinfo WHERE username = '".$USER->username."' AND piID = '".$DATA->paymentInfoID."' LIMIT 1");
			$arr_info = array("response" => true);
			$arr_data = array("paymentInfo" => mysqli_fetch_object_wrapper($sql));
		} else
		{
			$arr_info = array("response" => false); $arr_data = NULL;
		};
		break;

	case base64_encode("deletePaymentInfoOption"):
		checkUser();
		$sql_GL = mysqli_query($CONNECTION, "SELECT * FROM paymentinfo WHERE username = '".$USER->username."'");

		if ($sql_GL)
		{
			$sql1 = mysqli_query($CONNECTION, "SELECT * FROM paymentinfo WHERE username = '".$USER->username."' AND piID = '".$DATA->id."' AND defaultInfo = 1 LIMIT 1");

			$sql2 = mysqli_query($CONNECTION, "UPDATE payments SET piID = '-".$DATA->id."' WHERE BINARY piID = '".$DATA->id."' AND BINARY username = '".$USER->username."'");
			$sql3 = mysqli_query($CONNECTION, "UPDATE paymentinfo SET piID = '-".$DATA->id."' WHERE BINARY piID = '".$DATA->id."'");

			// Delete default paymentinfo
			if ($sql1 && mysqli_num_rows_wrapper($sql1)===1) 
			{
				$arr_data["deleteDefault"] = true;
				$arr_info["response"] = true;

			} elseif ($sql1 && !mysqli_num_rows_wrapper($sql1))
			{
				$arr_data["deleteDefault"] = false;
				$arr_info["response"] = true;
			};

			$arr_info["allowAction"] = mysqli_num_rows_wrapper(mysqli_query($CONNECTION, "SELECT * FROM paymentinfo WHERE username = '".$USER->username."' AND piID > 0")) !== 0;
			$arr_info["hideOptions"] = mysqli_num_rows_wrapper(mysqli_query($CONNECTION, "SELECT * FROM paymentinfo WHERE username = '".$USER->username."' AND piID > 0")) === 1;
	
		} else
		{
			$arr_info["response"] = false; $arr_data = NULL;
		}
		break;

	case base64_encode("deactivateSubscription"):
		checkUser();
		$sql = mysqli_query($CONNECTION, "SELECT * FROM subscriptions WHERE BINARY username = '".$USER->username."' AND BINARY subscriptionID = '".$DATA->sid."' ");
		if (!mysqli_num_rows_wrapper($sql)) 
		{
			$arr_info["response"] = false; $arr_data = NULL;
		} else
		{
			$sql = mysqli_query($CONNECTION, "UPDATE subscriptions SET active = 0, user_deact = 1 WHERE BINARY username = '".$USER->username."' AND BINARY subscriptionID = '".$DATA->sid."' ");
			if ($sql) { $arr_info["response"] = true; $arr_data = NULL; }
		};

		break;

	case base64_encode("renewSubscription"):
		checkUser();
		$sql = mysqli_query($CONNECTION, "SELECT * FROM subscriptions WHERE BINARY subscriptionID = '".$DATA->sid."'");
		if (mysqli_num_rows_wrapper($sql))
		{
			$sql = mysqli_query($CONNECTION, "UPDATE subscriptions SET user_deact = 0, active = 1 WHERE BINARY username = '".$USER->username."' AND BINARY subscriptionID = '".$DATA->sid."'");
			if ($sql) { $arr_data = NULL; $arr_info["response"] = true; }
		} else
		{
			$arr_data = NULL; $arr_info["response"] = false;
		}
		break;

	case base64_encode("activateSubscription"):
		checkUser();
		$sql = mysqli_query($CONNECTION, "SELECT * FROM subscriptions WHERE BINARY subscriptionID = '".$DATA->sid."' AND BINARY username = '".$USER->username."'");
		if (mysqli_num_rows_wrapper($sql))
		{
			$sql = mysqli_query($CONNECTION, "UPDATE subscriptions SET user_deact = 0, active = 1 WHERE BINARY username = '".$USER->username."' AND BINARY subscriptionID = '".$DATA->sid."'");
			if ($sql) { $arr_data = NULL; $arr_info["response"] = true; }
		} else
		{
			$arr_data = NULL; $arr_info["response"] = false; $arr_info["errorCode"] = 404;
		};

		break;

	case base64_encode("updateNewsletterSubscription"):
		checkUser();
		$sql = mysqli_query($CONNECTION, "UPDATE users SET e_newsletter = ".($DATA->value===true ? 1 : 0)." WHERE BINARY username = '".$USER->username."'");
		if ($sql) { $arr_info["response"] = true; $arr_data = NULL; }
		break;

	case base64_encode("deactivateAccount"):
		checkUser();
		$sql = mysqli_query($CONNECTION, "UPDATE users SET active = 0 WHERE BINARY username = '".$USER->username."'");
		$arr_data = NULL;
		if ($sql) $arr_info["response"] = true; else $arr_info["response"] = false;
		break;

	case base64_encode("changePassword"):
		checkUser();
		$sql = mysqli_query($CONNECTION, "UPDATE users SET password = '".$DATA->value."', password_last_change = CURDATE() WHERE BINARY username = '".$USER->username."'");
		if ($sql)
		{
			$arr_info["response"] = true;
			$_SESSION["controlAction"] = (object) array("action" => "changePassword", "message" => $lang->aj_sucChangePass);
			$arr_data["redirect"] = $FILE."user/settings/";
			// send email
			//include "../requests/sendEmail.php";
			//$REQUEST = (object) array("name" => $USER->name, "email" => $USER->email);
			//$TEMPLATE_SELECT = "password_successfully_changed";
			//send_email();

		} else { $arr_info["response"] = false; $arr_data = NULL; }
		break;

	case base64_encode("resetEmailAddress"):
		checkUser();
		$sql = mysqli_query($CONNECTION, "UPDATE users SET email = '".htmlspecialchars($DATA->sendValue, ENT_QUOTES)."' WHERE BINARY username = '".$USER->username."'");
		if ($sql)
		{
			$arr_info["response"]      = true;
			$_SESSION["controlAction"] = (object) array("action" => "emailChange", "message" => $lang->succChangedEmail);
			$arr_data["redirect"]      = $FILE."user/settings/";
//			// send email
//			$EMAIL = (object) array("name" => $USER->name, "email" => $DATA->sendValue);
//			$TEMPLATE_SELECT = "email_change";
//			include "../requests/sendEmail.php";
//			send_email();
			
		} else { $arr_info["response"] = false; $arr_data = NULL; }
		break;

	case base64_encode("resetUsername"):
		checkUser();
		$sql = mysqli_query($CONNECTION, "SELECT * FROM users WHERE BINARY username = '".$USER->username."' AND BINARY username = '".htmlspecialchars($DATA->sendValue, ENT_QUOTES)."'");
		if (mysqli_num_rows_wrapper($sql) === 1)
		{
			$arr_info["response"]     = true;
			$arr_data["equal_param"]  = true;
			$arr_data["other_params"] = false;
		} else
		{
			$arr_data["equal_param"]  = false;
			$arr_data["other_params"] = true;
			$sql = mysqli_query($CONNECTION, "SELECT * FROM users WHERE BINARY username = '".htmlspecialchars($DATA->sendValue, ENT_QUOTES)."'");
			if (!mysqli_num_rows_wrapper($sql))
			{
				$sql = mysqli_query($CONNECTION, "UPDATE users SET username = '".htmlspecialchars($DATA->sendValue, ENT_QUOTES)."' WHERE BINARY username = '".$USER->username."'");
				if ($sql)
				{
					$arr_info["response"]      = true;
					$_SESSION["controlAction"] = (object) array("action" => "usernameChange", "message" => $lang->aj_sucChangeUsername);
					$arr_data["redirect"]      = $FILE."user/settings/";
					$arr_data["free"]          = true;
					$arr_data["loading"]       = $lang->loading;
					$_SESSION['hfw_username']  = htmlspecialchars($DATA->sendValue, ENT_QUOTES);
				} else { $arr_info["response"] = false; $arr_data = NULL; }
			} else
			{
				$arr_info["response"] = true;
				$arr_data["free"]     = false;
			}
		}		
		
		break;

	case base64_encode("changeLanguage"):
		checkUser(); if (!in_array(htmlspecialchars($DATA->language, ENT_QUOTES), ["SR", "RU", "SP", "EN", "GR"])) exit();
		$sql = mysqli_query($CONNECTION, "SELECT lang FROM users WHERE BINARY username = '".$USER->username."' AND lang = '".htmlspecialchars($DATA->language, ENT_QUOTES)."'");
		if (!mysqli_num_rows_wrapper($sql))
		{
			$arr_data = NULL;
			$arr_info["redirect"] = true;
			$sql = mysqli_query($CONNECTION, "UPDATE users SET lang = '".htmlspecialchars($DATA->language, ENT_QUOTES)."' WHERE BINARY username = '".$USER->username."'");
			$_SESSION["controlAction"] = (object) array("action" => "changeLanguage", "message" => $lang->aj_sucChangeLang);
		} else
		{
			$arr_info["redirect"] = false;
		}
		break;

	case base64_encode("changeCurrency"):
		checkUser(); if (!in_array($DATA->currency, ["RSD", "RUB", "EUR", "USD", "PLN"])) exit();
		$sql = mysqli_query($CONNECTION, "SELECT currencyID FROM users WHERE BINARY username = '".$USER->username."' AND currencyID = '".$DATA->currency."'");
		if (!mysqli_num_rows_wrapper($sql))
		{
			$arr_data = NULL; $arr_info["redirect"] = true;
			$sql = mysqli_query($CONNECTION, "UPDATE users SET currencyID = '".$DATA->currency."' WHERE BINARY username = '".$USER->username."'");
			$_SESSION["controlAction"] = (object) array("action" => "changeCurrency", "message" => $lang->aj_sucChangeCurr); 
		} else
		{
			$arr_info["redirect"] = false;
		}
		break;

	case base64_encode("createPaymentID"):
		if (isset($_SESSION['bank_protocol_payment_id']))
		{
			$str                   = $_SESSION['bank_protocol_payment_id'];
			$okURL                 = $FILE."verifyPayment/credit-card/?pid=".$_SESSION['bank_protocol_payment_id'];
			$failURL               = $FILE."verifyPayment/canceled";
			$rnd                   = microtime();
			$hash                  = $CLIENT_ID.$str.$DATA->amount.$okURL.$failURL."Auth".$rnd.$STORE_KEY;
			$arr_info["submit"]    = true; 
			$arr_data["paymentID"] = $_SESSION['bank_protocol_payment_id']; 
			$arr_data["hash"]      = base64_encode(pack("H*",sha1($hash)));
			$arr_data["okUrl"]     = $okURL;
			$arr_data["rnd"]       = $rnd;
			$arr_data["oid"]       = $_SESSION['bank_protocol_payment_id'];
		} else
		{
			$str = _SQL_escape_exstring(["SELECT * FROM payments WHERE BINARY paymentID = '","' AND BINARY username = '".$USER->username."'"],40);
			$sql = mysqli_query($CONNECTION, "INSERT INTO payments(paymentID, trans_currencyID, trans_date, receiptID, payment_amount, payment_method, username, piID) VALUES ('$str', 'RSD', CURDATE(), '".substr($str,0,10)."', '".$DATA->amount."', 1, '".$USER->username."', (SELECT piID FROM paymentinfo WHERE BINARY username = '".$USER->username."' AND defaultInfo = 1))");
			if ($sql)
			{
				$_SESSION['bank_protocol_payment_id'] = $str;
				$okURL                                = $FILE."verifyPayment/credit-card/?pid=".$str;
				$failURL                              = $FILE."verifyPayment/canceled";
				$rnd                                  = microtime();
				$hash                                 = $CLIENT_ID.$str.$DATA->amount.$okURL.$failURL."Auth".$rnd.$STORE_KEY;
				$arr_info["submit"]                   = true; 
				$arr_data["paymentID"]                = $str; 
				$arr_data["hash"]                     = base64_encode(pack("H*",sha1($hash)));
				$arr_data["okUrl"]                    = $okURL;
				$arr_data["rnd"]                      = $rnd;
				$arr_data["oid"]                      = $str;				
			} else
			{
				$arr_info["submit"] = false; $arr_data = NULL;
			}
		};
		break;

	case base64_encode("deleteWishItem"):
		$sql = mysqli_query($CONNECTION, "SELECT * FROM wishlist WHERE BINARY wishID = '".$DATA->itemID."' AND BINARY username = '".$USER->username."'");
		if (!mysqli_num_rows_wrapper($sql))
		{
			$arr_info["response"] = false; $arr_data = NULL;
		} else
		{
			$sql = mysqli_query($CONNECTION, "DELETE FROM wishlist WHERE BINARY wishID = '".$DATA->itemID."'");
			$arr_info["response"] = false;
			if ($sql) 
			{
				$arr_info["response"] = true;
				$sql = mysqli_query($CONNECTION, "SELECT * FROM wishlist WHERE BINARY username = '".$USER->username."'");
				$arr_data["wishlist_items_count"] = mysqli_num_rows_wrapper($sql);
			} else $arr_data = NULL;
		};
		break;

	case base64_encode("removeProfilePicture"):
		$sql = mysqli_query($CONNECTION, "UPDATE users SET image = NULL WHERE BINARY username = '".$USER->username."'");
		if ($sql)
		{
			unlink($_SERVER['DOCUMENT_ROOT']."/img/users/".$USER->image);
			$arr_info["response"] = true; $arr_data["image"] = make_image(NULL, $FILE);
		};
		break;

	case base64_encode("likeImage"):
		checkUser();
		$sql = mysqli_query($CONNECTION, "SELECT * FROM likes WHERE BINARY imageID = '".$DATA->imageID."' AND BINARY username = '".$USER->username."'");
		if (mysqli_num_rows_wrapper($sql))
		{
			$sql = mysqli_query($CONNECTION, "DELETE FROM likes WHERE BINARY imageID = '".$DATA->imageID."' AND BINARY username = '".$USER->username."'");
			if ($sql)
			{
				$sql = mysqli_query($CONNECTION, "SELECT likeID FROM likes WHERE BINARY imageID = '".$DATA->imageID."'"); // Sum of likes
				$arr_info["response"] = true; 
				$arr_data["action"]   = "dislike";
				$arr_data["self"]     = $lang->likeItImage;
				$arr_data["num"]      = mysqli_num_rows_wrapper($sql);
			} else
			{
				$arr_info["response"] = false; $arr_info["errorCode"] = 403;
			}
		} else
		{
			$sql = mysqli_query($CONNECTION, "INSERT INTO likes(date, username, imageID) VALUES (CURDATE(), '".$USER->username."', '".$DATA->imageID."')");
			if ($sql)
			{
				$sql = mysqli_query($CONNECTION, "SELECT * FROM likes WHERE BINARY imageID = '".$DATA->imageID."'");
				$arr_info["response"] = true; 
				$arr_data["action"]   = "like";
				$arr_data["num"]      = mysqli_num_rows_wrapper($sql);
				$arr_data["self"]     = $lang->dislikeItImage;
			} else
			{
				$arr_info["response"] = false; $arr_info["errorCode"] = 404;
			};
		}
		break;


	case base64_encode("sendQuestionFromVideo"):
		$arr_data = NULL;
		checkUser();		
		unset($lang);
		$lang = return_lang(strtolower($USER->lang), "../");
		$sql = mysqli_query($CONNECTION, "SELECT heading_".$USER->lang." AS heading FROM workshops WHERE BINARY workshopID='".$DATA->workshopID."' ");
		if(mysqli_num_rows_wrapper($sql))
		{
			$heading = mysqli_fetch_object_wrapper($sql)->heading;
//			$TEMPLATE_SELECT = "question";
//			$CONTACT = (object) array("email"    =>  $USER->email,
//			                          "name"     =>  $USER->name,
//			                          "subject"  =>  htmlspecialchars($DATA->questionHeading, ENT_QUOTES, "UTF-8"),
//			                          "message"  =>  htmlspecialchars($DATA->questionMessage, ENT_QUOTES, "UTF-8"),
//			                          "image"    =>  $USER->image);
//			include "../requests/sendEmail.php";
//			$IS_SEND = send_email();
            $IS_SENT = true;
			$arr_info["response"] = true;
			$arr_info["sent"] = $IS_SEND;
		} else {
			$arr_info["response"] = false;
			$arr_info["errorCode"] = 404;
		}
		break;

	case base64_encode("changePackageStartMonth"):
		checkUser();
		$DATA->itemID = base64_decode($DATA->itemID);
		if (!is_numeric($DATA->month)) exit();
		if (intval($DATA->month) > 12 || intval($DATA->month) < 1)
		{
			$arr_info["response"] = false; $arr_data["errorCode"] = 403; break;
		};
		$sql = mysqli_query($CONNECTION, "SELECT `cart`.*, `packages`.flag AS months FROM cart LEFT OUTER JOIN packages ON `cart`.packageID = `packages`.packageID WHERE BINARY itemID = '".$DATA->itemID."' AND username = '".$USER->username."'");
		if ($sql && mysqli_num_rows_wrapper($sql))
		{
			$ITEM = mysqli_fetch_object_wrapper($sql);
			$sql  = mysqli_query($CONNECTION, "UPDATE cart SET start_month = ".$DATA->month." WHERE BINARY itemID = '".$DATA->itemID."' AND username = '".$USER->username."'");
			$arr_info["response"] = (bool) $sql;

		//	Print start and out date
			$month  = intval($DATA->month);
			$c      = $year."-".($month < 10 ? "0".$month : $month)."-01";
			$cTime  = strtotime($c);
			$s_date = make_date(-1, $c);
			$e_date = make_date_format(strtotime("+".$ITEM->months." month -1 day", $cTime));

			$arr_data = array("start_date" => $s_date, "end_date" => $e_date);
		} else
		{
			$arr_info["response"] = false;
			$arr_data = NULL;
		}
		break;

		case base64_encode("addToCart"):
		
			$itemID = _SQL_escape_exstring(["SELECT * FROM cart WHERE BINARY itemID = '", "'"], 20);

			$sql = mysqli_query($CONNECTION, "SELECT start_month FROM cart WHERE BINARY username = '".$USER->username."' AND start_month = '".$DATA->start_month."'");
			if(mysqli_num_rows_wrapper($sql) >0 )
			{ 

				$arr_info["response"] = true;
				$arr_info["found"] = true;

			} else {
				
				$arr_info["found"] = false;
				$sql = mysqli_query($CONNECTION, "INSERT INTO cart (itemID, packageID, start_month, username) VALUES ( '".$itemID."' , '1' , ".$DATA->start_month." , '".$USER->username."')");
				
				$sql = mysqli_query($CONNECTION, "SELECT * FROM cart WHERE BINARY username='".$USER->username."'");
				
				if( ($rows = mysqli_num_rows_wrapper($sql)) > 0){
					$arr_info["response"] = true;
				} else{
					$arr_info["response"] = false;
				}

				$arr_data = array("rows" => $rows);
			}


				
		break;
	}
	
	echo json_encode($RESPONSE);

?>