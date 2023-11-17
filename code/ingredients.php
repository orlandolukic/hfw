<?php 
//	Fetch Ingredients
// SELECT `tbl2`.*, COUNT(`ingredients`.`manufacturerID`) AS count FROM (SELECT `tbl2`.`ingredientID`,`tbl2`.text, `tbl2`.quantity, `tbl2`.measure_acr, `tbl2`.name AS m_name, `tbl2`.logo AS m_logo, `tbl2`.logo_width, `tbl2`.logo_height, `tbl2`.website AS m_website, `tbl2`.website_show AS m_website_show, `tbl2`.mid AS m_id, `tbl2`.manufacturerID FROM (SELECT `tbl1`.*, `manufacturers`.* FROM (SELECT `tbl`.*, `measures`.`text_SR` AS measure_acr FROM (SELECT ingredientID,text_SR AS text, quantity, measureID, manufacturerID AS mid FROM ingredients WHERE BINARY workshopID = 'lmL3VtOcxRGXypC8wLm5') tbl LEFT OUTER JOIN measures ON `tbl`.measureID = `measures`.measureID AND active = 1) tbl1 LEFT OUTER JOIN manufacturers ON `manufacturers`.`manufacturerID` = `tbl1`.mid AND active = 1) tbl2 ORDER BY manufacturerID IS NOT NULL DESC) tbl2 LEFT OUTER JOIN `ingredients` ON `ingredients`.manufacturerID = `tbl2`.manufacturerID GROUP BY `tbl2`.ingredientID ORDER BY count DESC, text ASC
	$sql_ingr = mysqli_query($CONNECTION,"SELECT `tbl2`.*, COUNT(`ingredients`.`manufacturerID`) AS count FROM (SELECT `tbl2`.`ingredientID`,`tbl2`.text, `tbl2`.quantity, `tbl2`.measure_acr, `tbl2`.name AS m_name, `tbl2`.logo AS m_logo, `tbl2`.logo_width, `tbl2`.logo_height, `tbl2`.website AS m_website, `tbl2`.website_show AS m_website_show, `tbl2`.mid AS m_id, `tbl2`.manufacturerID FROM (SELECT `tbl1`.*, `manufacturers`.* FROM (SELECT `tbl`.*, `measures`.`text_".$lang_acr."` AS measure_acr FROM (SELECT ingredientID,text_".$lang_acr." AS text, quantity, measureID, manufacturerID AS mid FROM ingredients WHERE BINARY workshopID = '".$_GET['wid']."') tbl LEFT OUTER JOIN measures ON `tbl`.measureID = `measures`.measureID AND active = 1) tbl1 LEFT OUTER JOIN manufacturers ON `manufacturers`.`manufacturerID` = `tbl1`.mid AND active = 1) tbl2 ORDER BY manufacturerID IS NOT NULL DESC) tbl2 LEFT OUTER JOIN `ingredients` ON `ingredients`.manufacturerID = `tbl2`.manufacturerID GROUP BY `tbl2`.ingredientID ORDER BY count DESC, text ASC");
	$has_ingr = (bool) mysqli_num_rows_wrapper($sql_ingr);
    $manufacturers       = array(); $r = 0;
    $manufacturers_list  = array();
    $count_manufacturers = array();
    $ingredients         = array(); $i = 0;
	if ($has_ingr)
	{
		while($t = mysqli_fetch_object_wrapper($sql_ingr))
		{
			if ($t->manufacturerID)
			{
				if (!in_array($t->m_name, $manufacturers)) 
				{ 
					$manufacturers[$r]        = $t->m_name; 
					$manufacturers_list[$r++] = array("m_name" => $t->m_name, "m_logo" => $t->m_logo, "m_website" => $t->m_website, "m_website_show" => $t->m_website_show, "id" => $t->m_id, "dimensions" => 
					                                  array("hasDimensions" => ($t->logo_width || $t->logo_height), "width" => $t->logo_width, "height" => $t->logo_height)); 
					$count_manufacturers[$t->m_id] = 1;
				} else
				{
					$count_manufacturers[$t->m_id]++;
				}
				$ingredients[$i++] = $t;
			} else
			{
				$ingredients[$i++] = $t;
			}
		};
	}
?>