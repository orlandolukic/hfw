<?php 


// Workshop views statistic
$timestamp = time();
if (!$adminActive)
{
	$exeption =  isset($_GET['fr_pg']) && (trim($_GET['fr_pg']) === "workshops");
	$exeption &= isset($_SESSION['user_view']) && !$_SESSION['user_view']->from->status;
	if (!isset($_SESSION['user_view']))
	{
		$sql = mysqli_query($CONNECTION, "UPDATE workshops SET views = views + 1 WHERE workshopID = '$wid'");
		$_SESSION['user_view']       = (object) array("time" => $timestamp, "workshopID" => $wid);
		$_SESSION['user_view']->from = (object) array("status" => false, "data" => NULL);
	} else
	{
		if ($timestamp - $_SESSION['user_view']->time >= 300 || $_SESSION['user_view']->workshopID != $wid || $exeption)
		{
			if ($exeption) $_SESSION['user_view']->from = (object) array("status" => true,"data" => trim($_GET['fr_pg'])); 
			$sql = mysqli_query($CONNECTION, "UPDATE workshops SET views = views + 1 WHERE workshopID = '$wid'");
			$_SESSION['user_view']->time = $timestamp;
			$_SESSION['user_view']->workshopID = $wid;			
		}
	}
};

?>