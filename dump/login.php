<?php 

// Check if user is considered to be logged in
if (isset($_COOKIE['account']))
{
//	Check wheather user has the login cookie
	$sql = mysqli_query("SELECT * FROM cookies WHERE BINARY username = '".$username."' AND BINARY cookie_value = '".$_COOKIE['account']."'");
	if (mysqli_num_rows_wrapper($sql)) $GRANT_ACCESS = true;
} else
{
	$sql = mysqli_query("SELECT * FROM cookies WHERE BINARY username = '".$username."'");
	if (mysqli_num_rows_wrapper($sql) === 2) { header("location: ".$FILE."404"); exit(); } else
	{
		$GRANT_ACCESS = true;
		$str = generate_string(25);
		setcookie("account", $str,time() + 60*60*24*365, "/");
		$_COOKIE['account'] = $str;
		$sql = mysqli_query("INSERT INTO cookies(cookie_value, username) VALUES ('".$str."', '".$username."')");
	};
};
// /Check if user is considered to be logged in


?>