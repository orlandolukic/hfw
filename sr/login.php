<?php 

	session_start();
	//	Redirect URL Condition, if sessions are set
	if (isset($_SESSION['hfw_username'])) header("location: ".$FILE."user/"); elseif (isset($_SESSION['hfw_admin'])) header("location: ".$FILE."admin/");

	$prepath  = '../';
	include $prepath."functions.php";
	include $prepath."connect.php";
	include "global.php";

	$ft_mg    = "";
	$REDIRECT = false;
	$ACTIONS  = (object) array("redirect"    => false, 
	                           "execute"     => false, 
	                           "valid"       => false,	                           
	                           "redirection" => (object) array("SQL_error"     => $domain,
	                                                           "GET_error"     => "",
	                                                           "urlToPostData" => true));
	include "getDATA.php";
	include $prepath."pages.php";
	include $prepath."lang/func.php";
	include $prepath."lang/write_".strtolower($lang_acr).".php";
	include $prepath."requests/actions.php";	

	$ft_set = 0;
	if (!isset($urlToPostData)) { $urlToPostData = $PAGES->login; $ft_set = 1; }

//	Catch POSTed data
    if (isset($_POST['username']) && isset($_POST['password']))
    {
    	$username = htmlentities($_POST['username'], ENT_QUOTES); $password = htmlentities($_POST['password'], ENT_QUOTES);
    	$sql = mysqli_query($CONNECTION, "SELECT * FROM users WHERE BINARY username = '$username' AND BINARY password = '$password' LIMIT 1");
    	if (mysqli_num_rows_wrapper($sql))
    	{
    	// Continue with proccessing data
    		while($m = mysqli_fetch_object_wrapper($sql)) $temp = $m;
    	//	Admin login
    		if ($temp->account_type)
    		{
    			$_SESSION['hfw_admin'] = $username;
    			header("location: ".$FILE."admin/");
    			exit();
    		} else
    		// User login
    		{
    			$GRANT_ACCESS = true;
    			// Check if user is considered to be logged in
    			if (isset($_COOKIE['account']))
    			{
    			//	Check wheather user has the login cookie
    				$sql = mysqli_query($CONNECTION, "SELECT * FROM cookies WHERE BINARY username = '".$username."' AND BINARY cookie_value = '".$_COOKIE['account']."'");
    				if (mysqli_num_rows_wrapper($sql)) $GRANT_ACCESS = true;
    			} else
    			{
    				$sql = mysqli_query($CONNECTION, "SELECT * FROM cookies WHERE BINARY username = '".$username."'");
    				if (mysqli_num_rows_wrapper($sql) === 2) { header("location: ".$FILE."404"); exit(); } else
    				{
    					$GRANT_ACCESS = true;
    					$str = generate_string(25);
    					setcookie("account", $str,time() + 60*60*24*365, "/");
    					$_COOKIE['account'] = $str;
    					$sql = mysqli_query($CONNECTION, "INSERT INTO cookies(cookie_value, username) VALUES ('".$str."', '".$username."')");
    				};
    			};
    			// /Check if user is considered to be logged in

    			if ($GRANT_ACCESS) 
    			{
	    			$sql = mysqli_query($CONNECTION, "SELECT email, name FROM users WHERE BINARY username = '$username' AND active = 0");
	    			if (mysqli_num_rows_wrapper($sql)) 
	    			{ 
	    				while($t = mysqli_fetch_object_wrapper($sql)) $USER = $t;
	    				$sql = mysqli_query($CONNECTION, "UPDATE users SET active = 1 WHERE BINARY username = '$username'");
	    				// send activation email
	    				if ($sql) 
	    				{
	    					$REDIRECT        = false;
	    					$TEMPLATE_SELECT = "reactivation";    
	    					$lang            = return_lang($USER->lang, "");					
	    					include $prepath."requests/sendEmail.php";
	    					send_emai();
	    				};
	    			};
	    			$str = generate_string(20);
	    		// Update data before redirection
	    			$sql = mysqli_query($CONNECTION, "UPDATE users SET timestamp = '".time()."', access_token = '".$str."', last_login = CURDATE() WHERE BINARY username = '".$username."'");
	    			$_SESSION['access_token'] = $str;
	    			$_SESSION['hfw_username'] = $username;
	    			header("location: ".$FILE."user/".(!$ft_set ? $ACTIONS->redirection->urlToPostData : ""));
	    		} else header("location: ".$FILE."404");
    			exit();	
    		}
    	} else
    	{
    		$posted = 1;
    		$found  = 0;
    		$type   = "missmatch";
    		$timeout= 6000;
    	}
    } else { $posted = 0; $found = 0; $type = ""; $timeout= 0; }
//	End of catch data
    
    if (isset($_SESSION['security']) && isset($_GET['security']))
    {
    	$posted = 1;
    	$found  = 0;
    	$type   = $_SESSION['security'];
    	$timeout= 7000;
    	unset($_SESSION['security']);
    }

	$emailSET = 0;
	if (isset($_GET['action']))
	{
		switch($_GET['action'])
		{
		case "emailForgot":
			if (isset($_GET['data']))
			{
				$emailSET = 1; $data = $_GET['data'];
			};
			break;
		};
	};
?>

<!DOCTYPE html>
<html>
<head>
	<?php print_HTML_data("head","login") ?>
</head>
<body class="login <?= $bodyClass ?>">

	<?php echo $mobileMenu; $mobileMenu = NULL; ?>

	<!-- Header container -->
	<div class="header-container md-dn">
		<?php echo $headerInfo; $headerInfo = NULL; ?>
		<?php printMainMenu(0,0); ?>		
	</div>
	<!-- /Header container -->

	<div class="container margin-md-t-110 margin-t-50">
		<div class="row">
			<div class="col-md-6 col-md-push-3 col-xs-12 col-sm-12 login-placeholder">
				<!-- .login-placeholder -->
				<div id="login" class="<?= ($emailSET ? "dn" : ""); ?>">
					<div class="text-center margin-b-10"><h3 style="margin: 0"><?php echo $lang->please_login ?></h3></div>				
					<div class="divider width-100"></div>
					<?php if ($ACTIONS->valid) { ?>
					<div class="info-panel margin-t-20">
						<div class="strong" style="font-size: 120%"><i class="fa fa-shopping-cart"></i> <?php echo $lang->add_productToCart ?></div>
						<div class="padding-t-5 small"><?php echo $lang->login_toBuy ?></div>
					</div>
					<?php }; ?>
					<div id="loginError" class="mistake margin-t-20 margin-b-20 smaller dn">
						<i class="fa fa-exclamation-triangle"></i> 
						<span class="padding-l-10">
							<?php
								if ($type === "missmatch") 
								{ 
									echo $lang->error_usernameorPass;
								} elseif ($type === "token")
								{
									echo $lang->sec_token; 
								} elseif ($type === "session_expired")
								{
									echo $lang->sess_expired;
								}
							?>						
						</span>
					</div>
					<form method="post" action="<?= $urlToPostData; ?>">
						<div class="login-input-group margin-t-20">
							<input id="username" type="text" class="input-theme" name="username" placeholder="<?= $lang->user_nameone ?>" autofocus="autofocus" autocomplete="off" />
							<div class="login-input-icon"><i class="fa fa-user"></i></div>
						</div>
						<div class="login-input-group margin-t-10">
							<input id="password" type="password" class="input-theme" name="password" placeholder="<?= $lang->pass ?>" />
							<div class="login-input-icon"><i class="fa fa-lock"></i></div>
						</div>
						<div class="margin-t-20 text-center">
							<button class="btn-initiator-send"><i class="fa fa-sign-in"></i> 
							<span class="small padding-l-5"><?php echo $lang->please_login ?></span></button>
						</div>
					</form>
					<?php if (0) { ?>
					<div style="position: relative;" class="text-center margin-t-20">
						<div class="separator-text"><?php echo $lang->or ?></div>
					</div>
					
					<div class="margin-t-10">
						<div class="row">
							<div class="col-md-6 col-xs-12 col-sm-12">
								<div class="btn-social btn-facebook btn-ord"><i style="position: relative; z-index: 200" class="fa fa-facebook fa-2x padding-l-10"></i> <span style="position: relative; top: -6px; left: 30px" class="small"><?php echo $lang->facebook_login ?></span></div>
							</div>
							<div class="col-md-6 col-xs-12 col-sm-12">
								<div class="btn-social btn-google-plus btn-ord margin-md-t-20"><i style="position: relative; z-index: 200" class="fa fa-google-plus fa-2x"></i> <span style="position: relative; top: -6px; left: 20px" class="small"><?php echo $lang->google_login ?></span></div>
							</div>
						</div>
					</div>
					<?php }; ?>
					<div class="margin-t-20 small text-center">
						<?php echo $lang->not_registered ?>
						<span class="padding-l-5"><a class="login-link" href="<?= $PAGES->register ?>"><?php echo $lang->become_member ?></a></span>
					</div>
					<div class="small text-center">
						<?php echo $lang->forgot_pass ?>
						<span class="padding-l-5"><a id="resetEmailLink" class="login-link" href="javascript: void(0)"><?php echo $lang->send_passChange ?></a></span>
					</div>
				</div>
				<!-- /.login-placeholder -->




				<!-- #resetEmail -->
				<div id="emailReset" class="<?= ($emailSET ? "" : "dn"); ?>">
					<div class="text-center margin-b-10 btn-placeholder">
						<h3 style="margin: 0"><?php echo $lang->reset_pass ?></h3>
						<div id="btnBack" class="btn-back" title="<?= $lang->comeBack ?>" data-placement="right" data-toggle="tooltip"><i class="fa fa-chevron-left"></i></div>
					</div>
					<div class="divider width-100"></div>					
					<div class="margin-t-10 small"><?php echo $lang->register_email ?></div>
					<div  id="emailError" class="mistake margin-t-10 margin-b-20 smaller dn">
						<i class="fa fa-exclamation-triangle"></i> <span class="padding-l-10">
							<span class="dn"><?php echo $lang->error_invalidEmail ?></span>
							<span class="dn"><?php echo $lang->no_emailInDatabase ?> <span class="strong" id="emailMistake"></span></span>
						</span>
					</div>
					<div class="form">
						<div class="margin-t-10 login-input-group">
							<input id="email" autocomplete="off" type="text" value="<?= ($emailSET ? $data : ""); ?>" class="input-theme" placeholder="<?= $lang->emailExample ?>" />
							<div class="login-input-icon"><i class="fa fa-envelope"></i></div>
						</div>
						<div class="margin-t-10 text-center">
							<div id="submitEmailRequest" data-success-mssg="<?= $lang->mssg_sentReq ?>" data-error-mssg="<?= $lang->mssg_failReq ?>" class="btn-initiator-send">
								<i class="fa fa-paper-plane"></i> <span class="small"><?php echo $lang->send_request ?></span>
							</div>
						</div>
					</div>
				</div>
				<!-- /#resetEmail -->
			</div>
		</div>
	</div>

	<?php print_HTML_data("script","login") ?>
	<script type="text/javascript" src="<?= $FILE; ?>js/login.js" has-data="<?= ($posted || $found ? "true" : "false") ?>" data-set="var login=<?= $posted; ?>, found=<?= $found; ?>; type = '<?= $type ?>'; timeout = <?= $timeout; ?>;"></script>

</body>
</html>