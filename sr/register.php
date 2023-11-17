<?php 

	session_start();

	$prepath = '../';
	$ft_mg   = "";
	include $prepath."functions.php";
	include $prepath."connect.php";
	include "global.php";
	include "getDATA.php";
	include $prepath."pages.php";
	include $prepath."lang/func.php";
	include $prepath."lang/write_".strtolower($lang_acr).".php";

    $urlToPostData = $PAGES->register;

    //	Redirect URL Condition
	if (isset($_SESSION['hfw_username'])) header("location: ".$FILE."user/"); elseif (isset($_SESSION['hfw_admin'])) header("location: ".$FILE."admin/");

	//	Register Form - POST data
	if (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['passwordConfirm']) && isset($_POST['lang']) && isset($_POST['currency']))
	{
		if (!isset($_COOKIE['account'])) 
		{
			$sql = mysqli_query($CONNECTION, "INSERT INTO users (name, surname, email, username, password, lang, currencyID, security_token, register_date, last_login) VALUES (
				'".$_POST['name']."', '".$_POST['surname']."', 
				'".$_POST['email']."', '".$_POST['username']."', '".$_POST['password']."', '".$_POST['lang']."',
				'".$_POST['currency']."',
				'".($tok = generate_string(30))."', CURDATE(), CURDATE()
				)");
			if ($sql)
			{			
				$str = generate_string(25);
				setcookie("account", $str, time() + 365 * 24 * 60 * 60, "/"); 
				$_COOKIE['account'] = $str;
				$sql = mysqli_query($CONNECTION, "INSERT INTO cookies(cookie_value, username) VALUES ('".$str."', '".$_POST['username']."')");
//				$TEMPLATE_SELECT = "registration";
//				$REGISTRATION = (object) array("username" => $_POST['username'], "email" => $_POST['email'], "security_token" => $tok, "name" => $_POST['name']);
//				include $prepath."requests/sendEmail.php";
//				send_email();
				$_SESSION['hfw_username'] = $_POST['username'];
				header("location: ".$FILE."user/");
			} else header("location: ".$FILE."register");
		};
	};
?>

<!DOCTYPE html>
<html>
<head>
<?php print_HTML_data("head","register") ?>
</head>
<body class="register <?= $bodyClass ?>">

	<?php echo $mobileMenu; $mobileMenu = NULL; ?>

	<!-- Header container -->
	<div class="header-container md-dn">
		<?php echo $headerInfo; $headerInfo = NULL; ?>
		<?php printMainMenu(0,0); ?>		
	</div>
	<!-- /Header container -->

	<div class="container-fluid margin-md-t-100 margin-t-30">
		<div class="row">
			<div class="col-md-6 col-md-push-3 col-xs-12 col-sm-12">
				<div class="login-placeholder">
					<div class="text-center margin-b-10"><h3 style="margin: 0"><?php echo $lang->register ?></h3></div>
					<div class="divider width-100"></div>
					<div class="info-placeholder margin-t-20 margin-md-t-30 small dn">
						<i class="fa fa-info-circle"></i> <?php echo $lang->no_emailInSistem ?> <?php echo $lang->request ?> <a id="emailForgotEmailLink" class="underline" href=""><?= mb_strtolower($lang->request_forPassChange) ?></a>?
					</div>
					<div class="mistake margin-t-20 margin-b-20 dn">
						<span class="strong"><i class="fa fa-exclamation-triangle"></i> <?php echo $lang->errors_occurred ?></span>
						<br>
						<div class="smaller">
							<div class="padding-l-40 padding-md-l-30 padding-t-5">
								<ol class="ordered-list-errors">									
									<li class="dn"><?php echo $lang->name_enoughChar ?></li>
									<li class="dn"><?php echo $lang->surname_enoughChar ?></li>
									<li class="dn"><?php echo $lang->error_email ?></li>				
									<li class="dn"><?php echo $lang->user_alreadyExist ?></li>
									<li class="dn"><?php echo $lang->insert_username ?></li>									
									<li class="dn"><?php echo $lang->pass_atLeastMiddle ?></li>
									<li class="dn"><?php echo $lang->pass_notMatch ?></li>	
									<li class="dn"><?php echo $lang->please_insertPass ?></li>							
									<li class="dn"><?php echo $lang->pass_atLeastChars ?></li>
								</ol>
							</div>
						</div>
					</div>
					<form id="registerForm" method="post" action="<?= $urlToPostData; ?>">

						<div class="margin-t-20 strong">
							<i class="fa fa-globe"></i> <?php echo $lang->basic_info ?>
						</div>
						<div class="login-input-group margin-t-10">
							<input id="name" type="text" class="input-theme" name="name" placeholder="<?= $lang->user_name; ?>" autofocus="autofocus" autocomplete="off" />
							<div class="login-input-icon"><i class="fa fa-user"></i></div>
						</div>
						<div class="login-input-group margin-t-10">
							<input id="surname" type="text" class="input-theme" name="surname" placeholder="<?= $lang->user_surname; ?>" autocomplete="off" />
							<div class="login-input-icon"><i class="fa fa-user"></i></div>
						</div>
						<div class="login-input-group margin-t-10">
							<input id="email" type="text" class="input-theme" name="email" placeholder="<?= $lang->email_address ?>" autocomplete="off" />
							<div class="login-input-icon"><i class="fa fa-envelope"></i></div>
						</div>
						<div class="smaller margin-t-10 color-theme">
							<i class="fa fa-info-circle"></i> <?= $lang->email_purp ?>
						</div>

						<div class="margin-t-30 strong">
							<i class="fa fa-id-card"></i> <?php echo $lang->user_info ?>
						</div>

						<div class="login-input-group margin-t-10">
							<input id="username" type="text" class="input-theme" name="username" placeholder="<?= $lang->user_nameone ?>" autocomplete="off" />
							<div class="login-input-icon"><i class="fa fa-user"></i></div>
						</div>
						<div class="row margin-t-10">
							<div class="col-xs-12 col-sm-12 col-sm-6">
								<div class="login-input-group">
									<input id="password" type="password" class="input-theme" name="password" placeholder="<?= $lang->pass ?>" autocomplete="off" />
									<div class="login-input-icon"><i class="fa fa-lock"></i></div>
								</div>
							</div>								
						</div>
						<div class="row">
							<div class="margin-t-10 col-xs-12 col-sm-12 col-sm-6">
								<div class="login-input-group">
									<input id="passwordConfirm" type="password" class="input-theme" name="passwordConfirm" placeholder="<?= $lang->confPass ?>" autocomplete="off" />
									<div class="login-input-icon"><i class="fa fa-lock"></i></div>
								</div>	
							</div>
							<div class="col-xs-12 col-sm-12 col-sm-6 margin-md-t-20 small">
								<div class="strong"><?php echo $lang->pass_Strength ?> <i class="fa fa-info-circle pointer" data-html="true" data-toggle="tooltip" data-placement="top" title="<?= $lang->passwordStrength ?>"></i>
								<div class="fl-right status-html" data-weak="Slabo" data-medium="Srednje" data-hard="Jako" data-extrahard="Veoma jako"></div>
								</div>
								<div class="progress-bar"><div class="bar critical" data-width="0"></div></div>																
							</div>
						</div>	
						<div class="row margin-t-10">
							<div class="col-md-12 col-xs-12 col-md-6">
								<div class="status-mssg strong dn"><i class="fa fa-check"></i> <?php echo $lang->matching_passwords ?></div>
							</div>
						</div>					

						<div class="margin-t-20 margin-b-20 text-center">
							<button id="register" class="btn-initiator-send"><i class="fa fa-sign-in"></i> 
								<span class="small padding-l-5"><?php echo $lang->register_please ?></span></button>
						</div>
						<input type="hidden" name="lang" value="<?= strtoupper($lang_acr); ?>" />
						<input type="hidden" name="currency" value="<?= $curr ?>" />
					</form>
					<?php if (0) { ?>
					<div style="position: relative;" class="text-center margin-t-20">
						<div class="separator-text"><?php echo $lang->or ?></div>
					</div>
					<div class="margin-t-20">
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
				</div>
			</div>
		</div>
	</div>

	<?php print_HTML_data("script","register") ?>

</body>
</html>