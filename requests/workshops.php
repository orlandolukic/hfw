<?php 

//  User attempt to open this page
if (!(isset($REDIRECT) && !$REDIRECT)) header("location: ../index.php"); $REDIRECT = NULL;

//  For page "workshops.php"
//	Handling sort requests
//	Items per page, LIMIT ipp
$ip = 0;
if (isset($_GET['ipp']) && is_numeric($_GET['ipp']) && intval($_GET['ipp']) > 16 && intval($_GET['ipp']) % 16 === 0) { 
	$ipp = intval($_GET['ipp']); 
	$ip = 1;
} else $ipp = 16;

//	Already showed items, OFFSET $off
$page = 0;
if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
	$off = intval($_GET['page']) * $ipp; 
	$GET_PAGE = intval($_GET['page']);
	$page = 1;
	if (!mysqli_num_rows_wrapper(mysqli_query($CONNECTION,"SELECT * FROM workshops WHERE active = 1 AND (date_end = '0000-00-00' OR (CURDATE() <= date_end AND date_end != '0000-00-00')) ORDER BY sale DESC, date_publish DESC LIMIT ".$ipp." OFFSET ".$off)))
	{
		header("location: workshops.php");
	};
} else $off = 0;

$URL_to_redirect   = $PAGES->workshops;
$DATA_SQL          =  (object) array("addDATA"     => false, 
                                     "sort"        => false, 
                                     "counter"     => 0,
                                     "pages"       => 0,
                                     "read"        => true,
                                     "sortType"    => "",
                                     "sortMain"    => "",
                                     "sortName"    => ""
                                    );

if (isset($_GET['sort']))
{
	switch($_GET['sort'])
	{
	case "latest":
		$sql_DATA = mysqli_query($CONNECTION, "SELECT * FROM (SELECT `workshops_new`.*, COUNT(`wishlist`.`workshopID`) AS num_wish FROM (SELECT `LFTTbl1`.*, CONCAT(`images`.imageID,'.',`images`.extension) AS image FROM (SELECT `LFTTbl`.*, COUNT(`reviews`.workshopID) AS comments FROM (SELECT `Tbl`.result, `workshops`.workshopID, `workshops`.`heading_".$lang_acr."` AS heading, `workshops`.`date_publish`, `workshops`.`date_end`, `workshops`.views, `workshops`.active FROM (SELECT COALESCE(SUM(rating) / COUNT(*),0) AS result, workshopID FROM reviews WHERE status = 1 AND `reviews`.username IN (SELECT `users`.username FROM users WHERE active = 1) GROUP BY `reviews`.workshopID) Tbl RIGHT OUTER JOIN workshops ON `Tbl`.workshopID = `workshops`.workshopID) LFTTbl LEFT OUTER JOIN reviews ON `LFTTbl`.workshopID = `reviews`.workshopID AND `reviews`.status=1 AND `reviews`.username IN (SELECT `users`.username FROM users WHERE active = 1) GROUP BY `LFTTbl`.workshopID) LFTTbl1 LEFT OUTER JOIN images ON `images`.workshopID = `LFTTbl1`.workshopID WHERE im_index=1 ORDER BY `LFTTbl1`.result DESC) workshops_new LEFT OUTER JOIN `wishlist` ON `wishlist`.workshopID = `workshops_new`.workshopID AND `wishlist`.username IN (SELECT `users`.username FROM users WHERE active = 1) WHERE active = 1 AND date_publish<=CURDATE() AND (date_end = '0000-00-00' OR (CURDATE() <= date_end AND date_end != '0000-00-00')) GROUP BY `workshops_new`.workshopID) tblEN WHERE MONTH(date_publish) = ".$month." AND YEAR(date_publish) = ".$year." ORDER BY date_publish DESC LIMIT ".$ipp." OFFSET ".$off);
		$DATA_SQL->sortName = $lang->newest;
		$DATA_SQL->pages = mysqli_num_rows_wrapper($sql_DATA);
		break;

	case "oldest":
		$DATA_SQL->sortName = $lang->oldest;
		$sql_DATA = mysqli_query($CONNECTION, "SELECT * FROM (SELECT `workshops_new`.*, COUNT(`wishlist`.`workshopID`) AS num_wish FROM (SELECT `LFTTbl1`.*, CONCAT(`images`.imageID,'.',`images`.extension) AS image FROM (SELECT `LFTTbl`.*, COUNT(`reviews`.workshopID) AS comments FROM (SELECT `Tbl`.result, `workshops`.workshopID, `workshops`.`heading_".$lang_acr."` AS heading, `workshops`.`date_publish`, `workshops`.`date_end`, `workshops`.views, `workshops`.active FROM (SELECT COALESCE(SUM(rating) / COUNT(*),0) AS result, workshopID FROM reviews WHERE status = 1 AND `reviews`.username IN (SELECT `users`.username FROM users WHERE active = 1) GROUP BY `reviews`.workshopID) Tbl RIGHT OUTER JOIN workshops ON `Tbl`.workshopID = `workshops`.workshopID) LFTTbl LEFT OUTER JOIN reviews ON `LFTTbl`.workshopID = `reviews`.workshopID AND `reviews`.status=1 AND `reviews`.username IN (SELECT `users`.username FROM users WHERE active = 1) GROUP BY `LFTTbl`.workshopID) LFTTbl1 LEFT OUTER JOIN images ON `images`.workshopID = `LFTTbl1`.workshopID WHERE im_index=1 ORDER BY `LFTTbl1`.result DESC) workshops_new LEFT OUTER JOIN `wishlist` ON `wishlist`.workshopID = `workshops_new`.workshopID AND `wishlist`.username IN (SELECT `users`.username FROM users WHERE active = 1) WHERE active = 1 AND date_publish<=CURDATE() AND (date_end = '0000-00-00' OR (CURDATE() <= date_end AND date_end != '0000-00-00')) GROUP BY `workshops_new`.workshopID) tblEN WHERE MONTH(date_publish) = ".$month." AND YEAR(date_publish) = ".$year." ORDER BY date_publish ASC LIMIT ".$ipp." OFFSET ".$off);
		$DATA_SQL->pages = mysqli_num_rows_wrapper($sql_DATA);
		break;

	case "topRated":	
		$sql = mysqli_query($CONNECTION, "SELECT * FROM (SELECT `workshops_new`.*, COUNT(`wishlist`.`workshopID`) AS num_wish FROM (SELECT `LFTTbl1`.*, CONCAT(`images`.imageID,'.',`images`.extension) AS image FROM (SELECT `LFTTbl`.*, COUNT(`reviews`.workshopID) AS comments FROM (SELECT `Tbl`.result, `workshops`.workshopID, `workshops`.`heading_".$lang_acr."` AS heading, `workshops`.`date_publish`, `workshops`.`date_end`, `workshops`.views, `workshops`.active FROM (SELECT COALESCE(SUM(rating) / COUNT(*),0) AS result, workshopID FROM reviews WHERE status = 1 AND `reviews`.username IN (SELECT `users`.username FROM users WHERE active = 1) GROUP BY `reviews`.workshopID) Tbl RIGHT OUTER JOIN workshops ON `Tbl`.workshopID = `workshops`.workshopID) LFTTbl LEFT OUTER JOIN reviews ON `LFTTbl`.workshopID = `reviews`.workshopID AND `reviews`.status=1 AND `reviews`.username IN (SELECT `users`.username FROM users WHERE active = 1) GROUP BY `LFTTbl`.workshopID) LFTTbl1 LEFT OUTER JOIN images ON `images`.workshopID = `LFTTbl1`.workshopID WHERE im_index=1 ORDER BY `LFTTbl1`.result DESC) workshops_new LEFT OUTER JOIN `wishlist` ON `wishlist`.workshopID = `workshops_new`.workshopID AND `wishlist`.username IN (SELECT `users`.username FROM users WHERE active = 1) WHERE active = 1 AND date_publish<=CURDATE() AND (date_end = '0000-00-00' OR (CURDATE() <= date_end AND date_end != '0000-00-00')) GROUP BY `workshops_new`.workshopID) tblEN WHERE MONTH(date_publish) = ".$month." AND YEAR(date_publish) = ".$year." ORDER BY result DESC LIMIT ".$ipp." OFFSET ".$off);
		$sql_DATA = $sql;
		$DATA_SQL->pages    = mysqli_num_rows_wrapper($sql);
		$DATA_SQL->sortName = $lang->best_rated;			
		break;

	case "alpha":
		if (isset($_GET['sortType']))
		{
			switch($_GET['sortType'])
			{
			case "asc":
				$DATA_SQL->sortName = $lang->a_toZ;
				$sql_DATA = mysqli_query($CONNECTION, "SELECT * FROM (SELECT `workshops_new`.*, COUNT(`wishlist`.`workshopID`) AS num_wish FROM (SELECT `LFTTbl1`.*, CONCAT(`images`.imageID,'.',`images`.extension) AS image FROM (SELECT `LFTTbl`.*, COUNT(`reviews`.workshopID) AS comments FROM (SELECT `Tbl`.result, `workshops`.workshopID, `workshops`.`heading_".$lang_acr."` AS heading, `workshops`.`date_publish`, `workshops`.`date_end`, `workshops`.views, `workshops`.active FROM (SELECT COALESCE(SUM(rating) / COUNT(*),0) AS result, workshopID FROM reviews WHERE status = 1 AND `reviews`.username IN (SELECT `users`.username FROM users WHERE active = 1) GROUP BY `reviews`.workshopID) Tbl RIGHT OUTER JOIN workshops ON `Tbl`.workshopID = `workshops`.workshopID) LFTTbl LEFT OUTER JOIN reviews ON `LFTTbl`.workshopID = `reviews`.workshopID AND `reviews`.status=1 AND `reviews`.username IN (SELECT `users`.username FROM users WHERE active = 1) GROUP BY `LFTTbl`.workshopID) LFTTbl1 LEFT OUTER JOIN images ON `images`.workshopID = `LFTTbl1`.workshopID WHERE im_index=1 ORDER BY `LFTTbl1`.result DESC) workshops_new LEFT OUTER JOIN `wishlist` ON `wishlist`.workshopID = `workshops_new`.workshopID AND `wishlist`.username IN (SELECT `users`.username FROM users WHERE active = 1) WHERE active = 1 AND date_publish<=CURDATE() AND (date_end = '0000-00-00' OR (CURDATE() <= date_end AND date_end != '0000-00-00')) GROUP BY `workshops_new`.workshopID) tblEN WHERE MONTH(date_publish) = ".$month." AND YEAR(date_publish) = ".$year." ORDER BY heading ASC LIMIT ".$ipp." OFFSET ".$off);
				$DATA_SQL->pages = mysqli_num_rows_wrapper($sql_DATA);
				break;

			case "desc":
				$DATA_SQL->sortName = $lang->z_toa;
				$sql_DATA = mysqli_query($CONNECTION, "SELECT * FROM (SELECT `workshops_new`.*, COUNT(`wishlist`.`workshopID`) AS num_wish FROM (SELECT `LFTTbl1`.*, CONCAT(`images`.imageID,'.',`images`.extension) AS image FROM (SELECT `LFTTbl`.*, COUNT(`reviews`.workshopID) AS comments FROM (SELECT `Tbl`.result, `workshops`.workshopID, `workshops`.`heading_".$lang_acr."` AS heading, `workshops`.`date_publish`, `workshops`.`date_end`, `workshops`.views, `workshops`.active FROM (SELECT COALESCE(SUM(rating) / COUNT(*),0) AS result, workshopID FROM reviews WHERE status = 1 AND `reviews`.username IN (SELECT `users`.username FROM users WHERE active = 1) GROUP BY `reviews`.workshopID) Tbl RIGHT OUTER JOIN workshops ON `Tbl`.workshopID = `workshops`.workshopID) LFTTbl LEFT OUTER JOIN reviews ON `LFTTbl`.workshopID = `reviews`.workshopID AND `reviews`.status=1 AND `reviews`.username IN (SELECT `users`.username FROM users WHERE active = 1) GROUP BY `LFTTbl`.workshopID) LFTTbl1 LEFT OUTER JOIN images ON `images`.workshopID = `LFTTbl1`.workshopID WHERE im_index=1 ORDER BY `LFTTbl1`.result DESC) workshops_new LEFT OUTER JOIN `wishlist` ON `wishlist`.workshopID = `workshops_new`.workshopID AND `wishlist`.username IN (SELECT `users`.username FROM users WHERE active = 1) WHERE active = 1 AND date_publish<=CURDATE() AND (date_end = '0000-00-00' OR (CURDATE() <= date_end AND date_end != '0000-00-00')) GROUP BY `workshops_new`.workshopID) tblEN WHERE MONTH(date_publish) = ".$month." AND YEAR(date_publish) = ".$year." ORDER BY heading DESC LIMIT ".$ipp." OFFSET ".$off);
				$DATA_SQL->pages = mysqli_num_rows_wrapper($sql_DATA);
				break;

			default: header("location: workshops.php"); break;
			};
		} else header("location: workshops.php");
		break;

	default: header("location: workshops.php"); break;
	};
	$DATA_SQL->sort     = true;
	$DATA_SQL->sortMain = $_GET['sort'];

} else
{
	if ($show) 
	{
		$sql_DATA = mysqli_query($CONNECTION, "SELECT * FROM (SELECT `workshops_new`.*, COUNT(`wishlist`.`workshopID`) AS num_wish FROM (SELECT `LFTTbl1`.*, CONCAT(`images`.imageID,'.',`images`.extension) AS image FROM (SELECT `LFTTbl`.*, COUNT(`reviews`.workshopID) AS comments FROM (SELECT `Tbl`.result, `workshops`.workshopID, `workshops`.`heading_".$lang_acr."` AS heading, `workshops`.`date_publish`, `workshops`.`date_end`, `workshops`.views, `workshops`.active FROM (SELECT COALESCE(SUM(rating) / COUNT(*),0) AS result, workshopID FROM reviews WHERE status = 1 AND `reviews`.username IN (SELECT `users`.username FROM users WHERE active = 1) GROUP BY `reviews`.workshopID) Tbl RIGHT OUTER JOIN workshops ON `Tbl`.workshopID = `workshops`.workshopID) LFTTbl LEFT OUTER JOIN reviews ON `LFTTbl`.workshopID = `reviews`.workshopID AND `reviews`.status=1 AND `reviews`.username IN (SELECT `users`.username FROM users WHERE active = 1) GROUP BY `LFTTbl`.workshopID) LFTTbl1 LEFT OUTER JOIN images ON `images`.workshopID = `LFTTbl1`.workshopID WHERE im_index=1 ORDER BY `LFTTbl1`.result DESC) workshops_new LEFT OUTER JOIN `wishlist` ON `wishlist`.workshopID = `workshops_new`.workshopID AND `wishlist`.username IN (SELECT `users`.username FROM users WHERE active = 1) WHERE active = 1 AND date_publish<=CURDATE() AND (date_end = '0000-00-00' OR (CURDATE() <= date_end AND date_end != '0000-00-00')) GROUP BY `workshops_new`.workshopID) tblEN WHERE MONTH(date_publish) = ".$month." AND YEAR(date_publish) = ".$year." ORDER BY date_publish DESC LIMIT ".$ipp." OFFSET ".$off);
	} else $sql_DATA = mysqli_query($CONNECTION, "SELECT workshopID FROM workshops");
	$DATA_SQL->sortName = $lang->newest;
	$DATA_SQL->pages = mysqli_num_rows_wrapper(mysqli_query($CONNECTION, "SELECT * FROM workshops WHERE active = 1 AND (date_end = '0000-00-00' OR (CURDATE() <= date_end AND date_end != '0000-00-00')) ORDER BY sale DESC, date_publish DESC"));
};

//	URL TO REDIRECT IF CONDITIONS ARE OK
	if ($show) $URL_to_redirect .= "/".$month."/".$year;
	if ($DATA_SQL->sort) $URL_to_redirect .= "?sort=".$DATA_SQL->sortMain; else $URL_to_redirect .= "?sort=latest";
	if ($DATA_SQL->sortType !== "") $URL_to_redirect .= "&sortType=".$DATA_SQL->sortType;
	$DATA_SQL->pages = ceil( $DATA_SQL->pages / $ipp );



?>