<?php 
// Begin with cookie check

$GRANT_ACCESS = true;	// Forbid initially access to the video
//if (isset($_COOKIE['account']))
//{
////	Check wheather user has the access cookie
//	$sql = mysqli_query($CONNECTION,"SELECT * FROM cookies WHERE BINARY username = '".$USER->username."' AND BINARY cookie_value = '".$_COOKIE['account']."'");
//	if (mysqli_num_rows_wrapper($sql))
//	{
//		$GRANT_ACCESS = true;
//		$cookie = generate_string(25);
//		$sql = mysqli_query($CONNECTION,"UPDATE cookies SET cookie_value = '".$cookie."' WHERE BINARY cookie_value = '".$_COOKIE['account']."' AND username = '".$USER->username."'");
//		setcookie("account", $cookie, time() + 60*60*24*365, "/");
//		$_COOKIE['account'] = $cookie;
//	};
//} else
//{
//	$sql = mysqli_query($CONNECTION,"SELECT * FROM cookies WHERE BINARY username = '".$USER->username."'");
//	if (mysqli_num_rows_wrapper($sql) < 2)
//	{
//		$GRANT_ACCESS = true;
//		$str = generate_string(25);
//		setcookie("account", $str, time() + 60*60*24*365, "/");
//		$_COOKIE['account'] = $str;
//		$sql = mysqli_query($CONNECTION,"INSERT INTO cookies(cookie_value, username) VALUES ('".$str."', '".$USER->username."')");
//	};
//};
// /Check if user is considered to be logged in


?>