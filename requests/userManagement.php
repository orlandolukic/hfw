<?php 
/*
	Included in HandmadeFantasyWorld::"user/..." directories
==================================================================
*/
	
//	Subscription
	$SUBSCRIPTION = (object) array();
	$sql = mysqli_query($CONNECTION,"SELECT * FROM subscriptions WHERE username = '".$USER->username."' AND CURDATE() <= date_end AND CURDATE()>=date_start ORDER BY date_end DESC, date_start ASC LIMIT 1");
	if (mysqli_num_rows_wrapper($sql))
	{
		while($t = mysqli_fetch_object_wrapper($sql)) $temp_obj = $t;
		$SUBSCRIPTION->status = true;
		$SUBSCRIPTION->data   = $temp_obj;
	} else
	{
		$SUBSCRIPTION->status = false;
	};

	$sql = mysqli_query($CONNECTION,"SELECT * FROM cart WHERE username='".$USER->username."'");
	$cart_number = mysqli_num_rows_wrapper($sql);
	

?>