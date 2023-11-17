<?php

//	Begin with Script
	if (isset($_SESSION['hfw_username'])) 
	{
		$userActive = 1;
		$user = $_SESSION['hfw_username'];
	} else $userActive = 0;

	if (isset($_SESSION['hfw_admin']))
	{
		$adminActive = 1;
		$admin = $_SESSION['hfw_admin'];
	} else $adminActive = 0;

//	Global Vars
	$domain     = "http://localhost/pr/";
	$FILE       = "http://localhost/pr/";
	$bodyClass  = "theme-vintage"; 
	$year       = 2017;
	$startMonth = 3;
	$dSeparator = '.';

	// Payment data
	$STORE_KEY  = "SKEY0012";
	$CLIENT_ID  = "510080012";

	include "../print_HTML_data.php";

	$COMPANY_INFO = (object) array("PIB"         => "100236547", 
	                               "MB"          => "07821568", 
	                               "contact"     => "+381 (11) 31600001", 
	                               "email"       => "info@handmadefantasyworld.com",
	                               "location_SR" => "Beograd, Srbija",
	                               "location_EN" => "Belgrade, Serbia",
	                               "facebook"    => "https://www.facebook.com/handmadefantasybyantony/");



?>