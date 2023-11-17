<?php 

//  User attempt to open this page
if (!(isset($REDIRECT) && !$REDIRECT)) header("location: ../index.php"); $REDIRECT = NULL;

	$sql = mysqli_query($CONNECTION,"SELECT * FROM cart WHERE BINARY username = '".$USER->username."' AND workshopID IS NULL");
	$morePackages = mysqli_num_rows_wrapper($sql)<=1 ? false : true;
	$hasActivePackages = false;
	if (mysqli_num_rows_wrapper($sql))
	{
		$sql = mysqli_query($CONNECTION,"SELECT * FROM subscriptions WHERE BINARY username = '".$USER->username."' AND active = 1 AND CURDATE()>=date_start AND CURDATE()<=date_end ORDER BY date_end DESC");
		if (mysqli_num_rows_wrapper($sql)) $hasActivePackages = true;
	}

//	Get data for shopping cart
	$SHOPPING_CART = array();
	$sql = mysqli_query($CONNECTION,"SELECT `subTbl`.*, `packages`.price_".$USER->currencyID." AS pricePackage ,`packages`.`price_RSD` AS pricePackageRSD, `packages`.name_".$USER->lang." AS headingPackage, `packages`.flag FROM 
	                   (SELECT `tbl`.*, CONCAT(`images`.imageID, '.',`images`.`extension`) AS image FROM 
	                   		(SELECT `cart`.*, `workshops`.active AS active , `workshops`.price_".$USER->currencyID." AS price, `workshops`.`price_RSD` AS priceRSD, `workshops`.heading_".$USER->lang." AS heading, `workshops`.subheading_".$USER->lang." AS subheading FROM cart 
	                   			LEFT OUTER JOIN workshops ON `cart`.workshopID = `workshops`.workshopID WHERE BINARY `cart`.username='".$USER->username."'
	                   		) tbl LEFT OUTER JOIN images ON `tbl`.workshopID = `images`.workshopID AND `images`.im_index = 1 ORDER BY `images`.`im_index` ASC
	                   ) subTbl 
	                   LEFT OUTER JOIN packages ON `packages`.packageID = `subTbl`.packageID WHERE `subTbl`.active=1 OR `subTbl`.active IS NULL ORDER BY `subTbl`.workshopID ASC");

	$sql_sum = mysqli_query($CONNECTION,"SELECT COALESCE(SUM(priceRSD),0) + COALESCE(SUM(pricePackageRSD), 0) AS paymentAmountRSD, COALESCE(SUM(priceCurr),0) + COALESCE(SUM(pricePackageCurr), 0) AS paymentAmount FROM (SELECT `subTbl`.*, `packages`.price_".$USER->currencyID." AS pricePackageCurr, `packages`.`price_RSD` AS pricePackageRSD FROM (SELECT `tbl`.*, CONCAT(`images`.imageID, '.',`images`.`extension`) AS image, `images`.extension AS ext FROM (SELECT `cart`.*, `workshops`.active, `workshops`.price_".$USER->currencyID." AS priceCurr, `workshops`.`price_RSD` AS priceRSD,  `workshops`.heading_".$USER->lang." AS heading, `workshops`.subheading_".$USER->lang." AS subheading FROM cart LEFT OUTER JOIN workshops ON `cart`.workshopID = `workshops`.workshopID WHERE BINARY `cart`.username='".$USER->username."') tbl LEFT OUTER JOIN images ON `tbl`.workshopID = `images`.workshopID AND `images`.im_index = 1) subTbl LEFT OUTER JOIN packages ON `packages`.packageID = `subTbl`.packageID) tableResult WHERE `tableResult`.active=1 OR `tableResult`.active IS NULL");
	if (mysqli_num_rows_wrapper($sql) && $sql_sum)
	{
		$i = 0;
		while($t = mysqli_fetch_object_wrapper($sql)) $SHOPPING_CART[$i++] = $t;
		$obj = mysqli_fetch_object_wrapper($sql_sum);
		$RSD_SUM  = $obj->paymentAmountRSD;
		$CURR_SUM = $obj->paymentAmount;

	} else $RSD_SUM = 0;


?>