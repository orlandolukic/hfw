<?php 
//	All shopping AJAX requests
	if (!isset($_POST['type']) || !isset($_POST['data'])) exit();
	session_start();
	if (!isset($_SESSION['hfw_username'])) exit();
	$prepath  = '../';
	$INCLUDE  = (object) array("getDATA" => false);
	$REDIRECT = false;
	include $prepath."connect.php";
	include $prepath."functions.php";

	if ($_SESSION['lang'] !== "EN")
	{
		include $prepath.strtolower($_SESSION['lang'])."/global.php";
		include $prepath.strtolower($_SESSION['lang'])."/getDATA.php";
	} else
	{
		include $prepath."global.php";
		include $prepath."getDATA.php";
	};
	if ($userActive===0) exit();

	$TYPE     = base64_decode($_POST['type']);
	$DATA     = json_decode($_POST['data']);
	$arr_info = array();
	$arr_data = array();
	$RESPONSE = (object) array("info" => &$arr_info, "data" => &$arr_data);

	switch($TYPE)
	{
	case "addToShoppingCart":
		$sql = mysqli_query($CONNECTION,"SELECT * FROM cart WHERE username='".$USER->username."' AND workshopID = '".$DATA->workshopID."'");
		if ($sql && mysqli_num_rows_wrapper($sql)===0)
		{
			$str = generate_string(20);
			$sq1 = mysqli_query($CONNECTION,"SELECT * FROM cart WHERE itemID = '$str'");
			if (!mysqli_num_rows_wrapper($sq1))
			{
				while(mysqli_num_rows_wrapper($sq1))
				{
					$str = generate_string(20);
					$sq1 = mysqli_query($CONNECTION,"SELECT * FROM cart WHERE itemID = '$str'");
				};
			};
		//	Check if ID exists
			$sql = mysqli_query($CONNECTION,"INSERT INTO cart (itemID, workshopID, username) VALUES ('".$str."','".$DATA->workshopID."', '".$USER->username."')");
			if ($sql)
			{
				$sql = mysqli_query($CONNECTION,"SELECT * FROM cart WHERE username='".$USER->username."'");
				$arr_info = array("response" => true); $arr_data = array("items" => ($sql ? mysqli_num_rows_wrapper($sql) : 0));
			} else
			{
				$arr_info = array("response" => false, "errorCode" => 400); $arr_data = NULL;
			};
		} else
		{
			$arr_info = array("response" => false, "errorCode" => 401); $arr_data = NULL;
		}
		break;

	case "checkPaymentAmount":
		checkUser();
		include $prepath."requests/shopping.php";
		if ($sql && $sql_sum) {
			$arr_info = array("response" => true);
			$SUM = floatval($RSD_SUM);
			$SUM_CURR = isset($CURR_SUM) ? $CURR_SUM : 0;
			$arr_data = array("price" => array( 
												"total"                => print_money_PLAINTXT($SUM,2), 
												"total_noformat"       => $SUM,
												"tax"                  => print_money_PLAINTXT($SUM*0.2,2), 
												"pwt"                  => print_money_PLAINTXT($SUM-$SUM*0.2,2), 
												"totalForeignCurrency" => print_money_PLAINTXT($SUM_CURR,2)
											  ),
							  "items"         => mysqli_num_rows_wrapper($sql), 
							  "morePackages"  => $morePackages
							  );
		};
		break;

	case "removeFromShoppingCart":
	//	Execute remove query
		$sql = mysqli_query($CONNECTION,"DELETE FROM cart WHERE username='".$USER->username."' AND itemID='".$DATA->cartItemID."'");
	//	Begin with business
		if ($sql)
		{
			include $prepath."requests/shopping.php";
			if ($sql && $sql_sum) {
				$arr_info = array("response" => true);
				$SUM = floatval($RSD_SUM);
				$SUM_CURR = isset($CURR_SUM) ? $CURR_SUM : 0;
				$arr_data = array("price" => array( 
													"total"                => print_money_PLAINTXT($SUM,2), 
													"total_noformat"       => $SUM,
													"tax"                  => print_money_PLAINTXT($SUM*0.2,2), 
													"pwt"                  => print_money_PLAINTXT($SUM-$SUM*0.2,2), 
													"totalForeignCurrency" => print_money_PLAINTXT($SUM_CURR,2)
												  ),
								  "items"         => mysqli_num_rows_wrapper($sql), 
								  "morePackages"  => $morePackages
								  );
			} else
			{
				$arr_info = array("response" => false); $arr_data = NULL;
			};
		} else 
		{
			$arr_info = array("response" => false); $arr_data = NULL;
		};
		break;

	case "removeAllFromShoppingCart":
	//	Execute remove query
		$sql = mysqli_query($CONNECTION,"DELETE FROM cart WHERE username='".$USER->username."'");
	//	Begin with business
		if ($sql)
		{
			$arr_info = array("response" => true);
			$arr_data = NULL;			
		} else 
		{
			$arr_info = array("response" => false); $arr_data = NULL;
		};
		break;

	case "likeVideo":
		checkUser();
		$sql_GL = mysqli_query($CONNECTION,"SELECT * FROM likes WHERE BINARY workshopID = '".$DATA->wid."' AND username != '".$USER->username."'");
		$likes = mysqli_num_rows_wrapper($sql_GL);
		$sql = mysqli_query($CONNECTION,"SELECT workshopID FROM workshops WHERE BINARY workshopID = '".$DATA->wid."'");
		if (!mysqli_num_rows_wrapper($sql)) 
		{ 
			$arr_info["response"] = false; $arr_data = NULL; break; 
		} else
		{
			$sql = mysqli_query($CONNECTION,"SELECT * FROM likes WHERE BINARY username = '".$USER->username."' AND BINARY workshopID = '".$DATA->wid."'");
			if (mysqli_num_rows_wrapper($sql))
			{
				$sql = mysqli_query($CONNECTION,"DELETE FROM likes WHERE BINARY workshopID = '".$DATA->wid."' AND BINARY username = '".$USER->username."'");
				$arr_info["response"] = true; $arr_data["liked"] = false; 
				
			// Determine likes for specific languages
				if ($USER->lang === "EN") // For English users, determine likes
				{
					if ($likes > 0)
					{
						$arr_data["message"] = $likes." ".($likes>=2 ? $lang->persons : $lang->person)." ".($likes>=2 ? $lang->like_this : $lang->likes_this);
					} else
					{
						$sql = mysqli_query($CONNECTION,"SELECT * FROM likes WHERE BINARY workshopID = '".$DATA->wid."'");
						if ($num = mysqli_num_rows_wrapper($sql)) {
							$arr_data["message"] = $num." ".($num>=2 && $num<=4 ? $lang->persons : $lang->person)." ".($num===1 ? $lang->likes_this : $lang->like_this);
						} else
						{
							$arr_data["message"] = $lang->beFirstToLike;
						}
					};
					
				} elseif ($USER->lang === "SR")	// For Serbian users, determine likes
				{
					if ($likes > 0)
					{
						$arr_data["message"] = $likes." ".($likes>=2 && $likes<=4 ? $lang->persons : $lang->person)." ".($likes>=2 ? $lang->like_this : $lang->likes_this);
					} else
					{
						$sql = mysqli_query($CONNECTION,"SELECT * FROM likes WHERE BINARY workshopID = '".$DATA->wid."'");
						if ($num = mysqli_num_rows_wrapper($sql)) {
							$arr_data["message"] = $num." ".($num>=2 && $num<=4 ? $lang->persons : $lang->person)." ";
							switch($num)
							{
							case 1: $arr_data["message"] .= $lang->loves;
							case 2: case 3: case 4: $arr_data["message"] .= $lang->love_e;
							default: $arr_data['message'] .= $lang->loves;
							}
						} else
						{
							$arr_data["message"] = $lang->beFirstToLike;
						}
					};
				};				

				break;
			} else
			{
				$sql = mysqli_query($CONNECTION,"INSERT INTO likes(username, workshopID, date) VALUES
				                   ( '".$USER->username."', '".$DATA->wid."', CURDATE() )");
				if ($sql)
				{
					if ($USER->lang === "EN")	// For english people
					{
						if ($likes > 0)
						{
							$arr_data["message"] = $lang->like_yand." ".$likes." ".($likes>=2 ? $lang->persons : $lang->person)." ".($likes>=2 ? $lang->like_this : $lang->likes_this);
						} else
						{
							$arr_data["message"] = $lang->youLikeThis;
						};
					}
					
					$arr_info["response"] = true; $arr_data["liked"] = true;
				} else
				{
					$arr_info["response"] = false; $arr_info["errorCode"] = 401; $arr_data = NULL;
				}
			};
		};
		break;
	}

	echo json_encode($RESPONSE);


?>