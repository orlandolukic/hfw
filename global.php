<?php 

//	Begin with script
	if (isset($_SESSION['hfw_username']) || isset($ACCESS) && $ACCESS) 
	{
		$userActive = 1;
		$user = isset($ACCESS) ? $ACCESS_USER : $_SESSION['hfw_username'];
	} else $userActive = 0;

	if (isset($_SESSION['hfw_admin']))
	{
		$adminActive = 1;
		$admin = $_SESSION['hfw_admin'];
	} else $adminActive = 0;

	if (isset($_GET['token']) && $_GET['token']=="FGlgtoIzTpfPkyi2UibB")
	{
	   $_SESSION['token'] = $_GET['token'];
	} else
	{
		/* if (!isset($_SESSION['token']) || isset($_SESSION['token']) && $_SESSION['token']!="FGlgtoIzTpfPkyi2UibB") header("location: http://www.handmadefantasyworld.com"); */
	};

//	Global Vars
	$domain     = "http://localhost/pr/";
	$FILE       = "http://localhost/pr/";
    $DIR        = __DIR__;
	$bodyClass  = "theme-vintage"; 
	$year       = 2023;
	$startMonth = 1;
	$dSeparator = '.';

	// Payment data
	$STORE_KEY    = "12345AIK";
	$CLIENT_ID    = "510030000";
	$PAYMENT_LINK = "https://entegrasyon.asseco-see.com.tr/fim/est3Dgate"; 

	include "print_HTML_data.php";

	$COMPANY_INFO = (object) array("PIB"         => "100236547", 
	                               "MB"          => "07821568", 
	                               "contact"     => "/", 
	                               "email"       => "info@handmadefantasyworld.com",
	                               "e_office"    => "office@handmadefantasyworld.com",
	                               "location_SR" => "Beograd, Srbija",
	                               "location_EN" => "Belgrade, Serbia",
	                               "facebook"    => "https://www.facebook.com/handmadefantasybyantony/",
	                               "instagram"   => "https://www.instagram.com/antonistzanidakis/");



?>