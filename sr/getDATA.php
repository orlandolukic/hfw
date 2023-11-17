<?php 
	
//	Already included connect.php
	if ($userActive)
	{
		$sql = mysqli_query($CONNECTION, "SELECT `users`.*, `currencies`.name AS currency FROM users INNER JOIN currencies ON `users`.currencyID=`currencies`.currencyID WHERE BINARY `users`.username='$user' AND `users`.active = 1 LIMIT 1");
		while($t = mysqli_fetch_object_wrapper($sql)) $USER = $t;
		
		// Check if access_token is the same
		if ($_SESSION['access_token'] !== $USER->access_token) 
		{
			// Logout user based on access_token
			$URL = $domain."login?security=token";
			header("location: ".$FILE."user/logout.php?type=access_token&redirect=".urlencode($URL)."&session_name=security&session_value=token");
		};
		// Check Session Timeout
		if (time() > intval($USER->timestamp)+30*60)
		{
			$URL = $domain."login?security=session_expired";
			header("location: ".$FILE."user/logout.php?type=session_expired&redirect=".urlencode($URL)."&session_name=security&session_value=session_expired");
		} else
		{
			// Update Session Timeout
            $currentTime = time();
			$sql = mysqli_query($CONNECTION, "UPDATE users SET timestamp = '".$currentTime."' WHERE BINARY username = '".$USER->username."'");
		};

		$lang_acr = strtolower($USER->lang);
		$curr     = $USER->currencyID;
		$currName = $USER->currency;
		$_SESSION['lang'] = $USER->lang;
	} elseif ($adminActive)
	{
		$sql = mysqli_query($CONNECTION, "SELECT `tbl`.*, `currencies`.name AS currency FROM (SELECT `administrators`.*,`users`.currencyID, `users`.lang FROM administrators INNER JOIN users ON `administrators`.username = `users`.username  WHERE `administrators`.username=(SELECT username FROM users WHERE username='$admin' AND account_type=1) LIMIT 1) tbl INNER JOIN currencies ON `currencies`.`currencyID` = `tbl`.`currencyID`");
		if (!mysqli_num_rows_wrapper($sql)) { $adminActive = 0; $admin = NULL; } else
		{
			while($t = mysqli_fetch_object_wrapper($sql)) $ADMIN = $t; 
			$lang_acr = strtolower($ADMIN->lang);
			$curr     = $ADMIN->currencyID;
			$currName = $ADMIN->currency;
			$_SESSION['lang'] = $ADMIN->lang;
		};
	} else
	{
		$lang_acr = $_SESSION['lang'] = "SR";
		$curr      = "RSD";
		$currName  = "RSD";
	}

	//	Create Language Condition - for dates
	$lang_cond = strtoupper($lang_acr)==="SR" || strtoupper($lang_acr)==="PL" || strtoupper($lang_acr)==="RU";	

	//	Check Language condition
	include_once $prepath."lang/".strtolower($lang_acr).".php";


if ((isset($INCLUDE) && $INCLUDE->getDATA === true) || !isset($INCLUDE))
{
/*
	     GET DATA FOR HEADER AND FOOTER QUERIES
	====================================================
*/

//	1. Header - Popular Workshops
	$DATA_HEADER =  array(); $i = 0;
	$sql = mysqli_query($CONNECTION, "SELECT `workshops_new`.*, COUNT(`wishlist`.`workshopID`) AS wishlist FROM (SELECT `LFTTbl1`.*, CONCAT(`images`.imageID,'.',`images`.extension) AS image FROM (SELECT `LFTTbl`.*, COUNT(`reviews`.workshopID) AS reviews FROM (SELECT `Tbl`.rating, `workshops`.workshopID, `workshops`.`heading_".$lang_acr."` AS heading, `workshops`.`subheading_".$lang_acr."` AS subheading, `workshops`.`date_publish`, `workshops`.`date_end`, `workshops`.forsale, `workshops`.views, `workshops`.active FROM (SELECT SUM(rating)/COUNT(*) AS rating, workshopID FROM reviews WHERE status=1 GROUP BY `reviews`.workshopID) Tbl RIGHT OUTER JOIN workshops ON `Tbl`.workshopID = `workshops`.workshopID) LFTTbl LEFT OUTER JOIN reviews ON `LFTTbl`.workshopID = `reviews`.workshopID AND `reviews`.status = 1 GROUP BY `LFTTbl`.workshopID) LFTTbl1 LEFT OUTER JOIN images ON `images`.workshopID = `LFTTbl1`.workshopID WHERE im_index=1 ORDER BY `LFTTbl1`.rating DESC) workshops_new LEFT OUTER JOIN `wishlist` ON `wishlist`.workshopID = `workshops_new`.workshopID WHERE  active = 1 AND (date_end = '0000-00-00' OR (CURDATE() <= date_end AND date_end != '0000-00-00')) GROUP BY `workshops_new`.workshopID ORDER BY `workshops_new`.rating DESC, `workshops_new`.views DESC LIMIT 3");
	while($t = mysqli_fetch_object_wrapper($sql)) $DATA_HEADER[$i++] = $t;

//	2. Footer - Recent Workshops
	$DATA_FOOTER = array(); 
	$i = 0;
	$sql = mysqli_query($CONNECTION, "SELECT `workshops_new`.*, COUNT(`wishlist`.`workshopID`) AS wishlist FROM (SELECT `LFTTbl1`.*, CONCAT(`images`.imageID,'.',`images`.extension) AS image FROM (SELECT `LFTTbl`.*, COUNT(`reviews`.workshopID) AS comments FROM (SELECT `Tbl`.rating, `workshops`.workshopID, `workshops`.`heading_".$lang_acr."` AS heading, `workshops`.`subheading_".$lang_acr."` AS subheading, `workshops`.`date_publish`, `workshops`.`date_end`, `workshops`.forsale, `workshops`.views, `workshops`.active FROM (SELECT SUM(rating)/COUNT(*) AS rating, workshopID FROM reviews WHERE status=1 GROUP BY `reviews`.workshopID) Tbl RIGHT OUTER JOIN workshops ON `Tbl`.workshopID = `workshops`.workshopID) LFTTbl LEFT OUTER JOIN reviews ON `LFTTbl`.workshopID = `reviews`.workshopID AND `reviews`.status = 1 GROUP BY `LFTTbl`.workshopID) LFTTbl1 LEFT OUTER JOIN images ON `images`.workshopID = `LFTTbl1`.workshopID WHERE im_index=1 ORDER BY `LFTTbl1`.rating DESC) workshops_new LEFT OUTER JOIN `wishlist` ON `wishlist`.workshopID = `workshops_new`.workshopID WHERE  active = 1 AND (date_end = '0000-00-00' OR (CURDATE() <= date_end AND date_end != '0000-00-00')) GROUP BY `workshops_new`.workshopID ORDER BY `workshops_new`.date_publish  DESC LIMIT 2");
	while($t = mysqli_fetch_object_wrapper($sql)) $DATA_FOOTER[$i++] = $t;

//	3. News - Get information about recent news
	$sql = mysqli_query($CONNECTION, "SELECT * FROM news WHERE active = 1 AND highlight = 1 AND highlight_from != '0000-00-00' AND (highlight_to = '0000-00-00' OR (highlight_to != '0000-00-00' AND highlight_to>CURDATE()))");
	$news = mysqli_num_rows_wrapper($sql);
};	// #endif (isset($INCLUDE) && ... || ...) { ... }

?>