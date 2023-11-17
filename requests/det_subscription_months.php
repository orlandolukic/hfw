<?php 

//	Check if user is subscribed for month(s)
	$currentMonth = intval(date("m"));
	$SUBSCRIPTION_MONTHS = array(0,0,0,0,0,0,0,0,0,0,0,0);
	$sql = mysqli_query($CONNECTION,"SELECT `subscriptions`.*, `packages`.flag FROM subscriptions LEFT OUTER JOIN packages ON `packages`.packageID = `subscriptions`.type WHERE BINARY username = '".$USER->username."' AND `subscriptions`.active = 1");
	if (mysqli_num_rows_wrapper($sql))
	{
		$k = 0;
		while($t = mysqli_fetch_object_wrapper($sql))
		{
			$date_start = $t->date_start; $date_end = $t->date_end;
			$M_start = intval(part_date("M", $date_start)); $M_end = intval(part_date("M", $date_end))+1;
		
			if (intval($t->flag) > 1)
			{
				for ($j=$M_start-1; $j<$M_end-1; $j++) $SUBSCRIPTION_MONTHS[$j] = 1;			
			} else
			{
				$SUBSCRIPTION_MONTHS[$M_start-1] = 1;
			}
		}
	}

?>