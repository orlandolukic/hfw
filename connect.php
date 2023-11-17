<?php 

//	Connect to database
	define("DB_HOST", "localhost");
	define("DB_STORE_USERNAME", "admin");
	define("DB_STORE_PASSWORD", "lukaluka");
    $db_host = "localhost";
	$db_username = "admin";
	$db_pass = "lukaluka";
	$db_name = "hfw";

	// Run the actual connection here  
	$CONNECTION = mysqli_connect(DB_HOST, DB_STORE_USERNAME, DB_STORE_PASSWORD) or die('could not connect to mysql');
	mysqli_query($CONNECTION,"SET NAMES UTF8");
	mysqli_select_db($CONNECTION, $db_name) or die ("no database");
	error_reporting(E_ALL ^ E_DEPRECATED);

?>