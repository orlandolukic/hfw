<?php
//	  LINKING REQUESTS
// ======================
	session_start();
	include "connect.php";
	include "functions.php";
	include "global.php";

	// Check if there has to be a linking request
	if (!$userActive || !isset($_GET['type']) || !isset($_GET['redirect_url'])) header("location: ".$FILE);

	switch(base64_decode($_GET['type']))
	{
	case "manufacturer":
		$sql = mysqli_query($CONNECTION, "SELECT * FROM manufacturers WHERE BINARY manufacturerID = '".$_GET['manufacturerID']."'");
		if (mysqli_num_rows_wrapper($sql))
		{
			$sql = mysqli_query($CONNECTION, "UPDATE manufacturers SET visits = visits + 1 WHERE BINARY manufacturerID = '".$_GET['manufacturerID']."'");
			if ($sql) header("location: ".$_GET['redirect_url']); else header("location: ".$FILE); 
		} else
		{
			header("location: ".$FILE);
		}
		break;

	case "link":
		if (!isset($_GET['lid'])) header("location: ".$FILE);
		$sql = mysqli_query($CONNECTION, "SELECT * FROM links WHERE BINARY linkID = '".$_GET['lid']."'");
		if (mysqli_num_rows_wrapper( $sql))
		{
			$sql = mysqli_query($CONNECTION, "UPDATE links SET visits = visits + 1 WHERE BINARY linkID = '".$_GET['lid']."'");
			if ($sql) header("location: ".$_GET['redirect_url']); else header("location: ".$FILE); 
		}
		break;
	}



	

?>