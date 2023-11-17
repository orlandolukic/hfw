<?php 

	session_start();
	//	Prep for include
	$prepath = '../';
    include $prepath."connect.php";
	include $prepath."functions.php";

	//	User sent from email to receipt
	if (isset($_GET['token']))
	{
		$sql = mysqli_query($CONNECTION,"SELECT username FROM users WHERE BINARY security_token = '".$_GET['token']."' LIMIT 1");
		if (mysqli_num_rows_wrapper($sql)) { 
			logInUser($_SESSION['hfw_username'], mysqli_fetch_object_wrapper($sql)); 
			$user = $_SESSION['hfw_username'];
		} else { header("location: ../../"); exit(); }
	} else
	{
		if (!isset($_SESSION['hfw_username'])) { header("location: ".$domain); exit(); };
	};
	include $prepath."requests/userManagementCheck.php";	

	if (isset($_SESSION['lang']) && $_SESSION['lang'] !== "EN")
	{
		include $prepath.strtolower($_SESSION['lang'])."/global.php";
		include $prepath.strtolower($_SESSION['lang'])."/getDATA.php";
	} else
	{
		include $prepath."global.php";
		include $prepath."getDATA.php";
	};

	include $prepath."pages.php";
	include $prepath."lang/func.php";
	include $prepath."lang/user_".strtolower($lang_acr).".php";
	include $prepath."requests/userManagement.php";

	$prepath = $domain;
	$message = isset($_SESSION['controlAction']) ? true : false;
?>

<!DOCTYPE html>
<html>
<head>
<?php print_HTML_data("head","settings") ?>
</head>

<body class="<?= $bodyClass ?>">
	<?php printTopMenu(); ?>	
	<?php printMainMenu(-1); ?>
		
	<div id="rightContent" class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main margin-b-60 relative">	
		<?php if (!isset($_GET['action'])) { printNavigation(1,[$lang->acc_settings]); } else { 
			switch($_GET['action'])
			{
			case "changePassword": $act = $lang->password_change; break;
			case "emailChange":    $act = $lang->email_change; break;
			case "changeUsername": $act = $lang->changeUsername; break;
			}
			printNavigation(2,[$lang->acc_settings, $act],[$FILE."user/settings/"]);
		 } ?>		
		<?php  ?>

		<div class="message-placeholder"></div>

		<?php if (isset($_GET['action']) && trim($_GET['action'])==="changePassword") { // CHANGE PASSWORD ?>

		<div class="row margin-t-10">
			<div class="col-md-12 col-xs-12 col-sm-12">
				<div class="panel bg-default">
					<div class="panel-heading">
						<div class="strong"><i class="fa fa-refresh"></i> <?php echo $lang->password_change ?></div>
						<div class="divider width-100"></div>				
					</div>
					<div class="panel-body">						
						<div class="row">
							<div class="col-md-6 col-xs-12 col-sm-12">
								<div id="mistakes" class="mistake dn margin-b-30 margin-t-10">
									<span class="strong small"><i class="fa fa-exclamation-triangle"></i> <?php echo $lang->error_happened ?></span>
									<div class="padding-l-20 padding-t-5">
										<ol class="padding-l-20 small">
											<li class="dn"><?php echo $lang->oldPass_notValid ?>.</li>
											<li class="dn"><?php echo $lang->pass_notMatch ?></li>
											<li class="dn"><?php echo $lang->old_passCantBeEmpty ?>.</li>
											<li class="dn"><?php echo $lang->new_passCantBeEmpty ?>.</li>
										</ol>
									</div>
								</div>
							</div>
						</div>
						<?php if ($USER->password_last_change !== NULL) { // For mobile devices ?>
							<div class="row">
								<div class="col-md-7 col-xs-12 col-sm-12 small margin-b-20 dn md-db">
									<i class="fa fa-exclamation-triangle"></i> <?php echo $lang->last_timeYouChanged ?> <?= make_date(-1,$USER->password_last_change); ?> <?php echo $lang->yearLOW ?>.
								</div>
							</div>
							<?php }; ?>						
						<div class="row margin-t-10">
							<div class="col-md-2 col-xs-12 col-sm-12">
								<div><?php echo $lang->new_pass ?>:</div>
							</div>
							<div class="col-md-3 col-xs-12 col-sm-12">
								<div><input id="newPassword" type="password" class="input-theme" placeholder="<?= $lang->enterNewPass ?>" autocomplete="off" /></div>
							</div>
						</div>
						<div class="row margin-t-10">
							<div class="col-md-2 col-xs-12 col-sm-12">
								<div><?php echo $lang->repeat_newPAss ?>:</div>
							</div>
							<div class="col-md-3 col-xs-12 col-sm-12">
								<div><input id="newPasswordConfirm" type="password" class="input-theme" placeholder="<?= $lang->confirmNewPass ?>" autocomplete="off" /></div>
							</div>
							<div class="col-md-3 col-xs-12 col-sm-12 margin-md-t-10">
								<div class="status-mssg strong dn"><i class="fa fa-check"></i> <?php echo $lang->passwords_areMatching ?></div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-7 col-md-push-5 col-xs-12 col-sm-12">
								<div class="strong"><?php echo $lang->pass_Strength ?> <i class="fa fa-info-circle pointer" data-html="true" data-toggle="tooltip" data-placement="top" title="<?= $lang->passwordStrength ?>"></i>
								<div class="fl-right status-html" data-weak="<?= $lang->weak ?>" data-medium="<?= $lang->medium ?>" data-hard="<?= $lang->strong ?>" data-extrahard="<?= $lang->very_strong ?>"></div>
								</div>
								<div class="progress-bar"><div class="bar critical" data-width="0"></div></div>				
							</div>
						</div>
						<div class="row margin-t-20">
							<div class="col-md-6 col-xs-12 col-sm-12 div-inl">
								<div id="changePassword" class="btn-green btn-ord small"><i class="fa fa-check"></i> <?php echo $lang->approve_passChange ?></div>
								<div class="padding-l-10">
									<a href="<?= $FILE; ?>user/settings/">
										<div class="btn-white bordered btn-ord small"><i class="fa fa-times"></i> <?php echo $lang->cancel ?></div>
									</a>
								</div>
							</div>
						</div>

						<div class="row margin-t-20">													
							<?php if ($USER->password_last_change !== NULL) { ?>
							<div class="col-md-7 col-xs-12 col-sm-12 small md-dn">
								<i class="fa fa-exclamation-triangle"></i> <?php echo $lang->last_timeYouChanged ?> <?= make_date(-1,$USER->password_last_change); ?>. <?php echo $lang->yearLOW ?>
							</div>
							<?php }; ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php } // End of change password
		elseif (isset($_GET['action']) && trim($_GET['action']) === "emailChange") { // CHANGE EMAIL ADDRESS ?>

		<div class="row margin-t-10">
			<div class="col-md-12 col-xs-12 col-sm-12">
				<div class="panel bg-default">
					<div class="panel-heading">
						<div class="strong"><i class="fa fa-refresh"></i> <?php echo $lang->change_emailAdress ?></div>
						<div class="divider width-100"></div>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-6 col-xs-12 col-sm-12">
								<div class="mistake dn margin-b-20">
									<div class="small"><i class="fa fa-exclamation-triangle"></i> <?php echo $lang->error_happenedOne ?></div>
									<ol class="padding-l-40 padding-t-5 small">
										<li><?php echo $lang->error_invalidEmail ?>.</li>
									</ol>
								</div>
							</div>
						</div>
						<div><?php echo $lang->please_enterEmail ?>:</div>
						<div class="row">
							<div class="col-md-4 col-xs-12 col-sm-12">
								<div class="padding-t-10">
									<input id="emailReset" type="text" class="input-theme" placeholder="<?= $lang->enterNewEmail ?>" autofocus="autofocus" autocomplete="off" />
								</div>
							</div>
						</div>
						<div class="row margin-t-20">
							<div class="col-md-12 col-xs-12 col-sm-12 div-inl">
								<div id="emailResetBtn" class="btn-green btn-ord small">
									<i class="fa fa-check"></i> <?php echo $lang->confirm ?>
								</div>
								<span class="padding-l-10 padding-md-l-none margin-md-t-10">
									<a href="<?= $FILE ?>user/settings/">
										<div class="btn-white bordered btn-ord small">
											<i class="fa fa-times"></i> <?php echo $lang->cancel ?>
										</div>
									</a>
								</span>
							</div>
						</div>
						<div class="row margin-t-20">
							<div class="col-md-12 col-xs-12 col-sm-12">
								<div class="small">
									<i class="fa fa-info-circle"></i> <?php echo $lang->after_emailChange ?>.
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
		} elseif (isset($_GET['action']) && trim($_GET['action']) === "changeUsername") { // CHANGE USERNAME ?>
		<div class="row margin-t-10">
			<div class="col-md-12 col-xs-12 col-sm-12">
				<div class="panel bg-default">
					<div class="panel-heading">
						<div class="strong"><i class="fa fa-refresh"></i> <?php echo $lang->changeUsername ?></div>
						<div class="divider width-100"></div>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-6 col-xs-12 col-sm-12">
								<div class="mistake dn margin-b-20">
									<div class="small"><i class="fa fa-exclamation-triangle"></i> <?php echo $lang->error_happenedOne ?></div>
									<ol class="padding-l-40 padding-t-5 small">
										<li class="dn"><?php echo $lang->user_alreadyExist ?>.</li>
										<li class="dn"><?php echo $lang->insert_username ?></li>
										<li class="dn"><?php echo $lang->equal_usernames ?></li>
									</ol>
								</div>
							</div>
						</div>
						<div><i class="fa fa-user-circle"></i> Active username: <span class="strong"><?= $USER->username ?></span></div>
						<div class="margin-t-10"><?php echo $lang->enterNewUsername ?>:</div>
						<div class="row">
							<div class="col-md-4 col-xs-12 col-sm-12">
								<div class="padding-t-10">
									<input id="usernameReset" type="text" class="input-theme" placeholder="<?= $lang->enterNewUsername_s ?>" autofocus="autofocus" autocomplete="off" />
								</div>
							</div>
						</div>
						<div class="row margin-t-20">
							<div class="col-md-12 col-xs-12 col-sm-12 div-inl">
								<div id="usernameResetBtn" class="btn-green btn-ord small">
									<i class="fa fa-check"></i> <span><?php echo $lang->confirm_change ?></span>
								</div>
								<span class="padding-l-10 padding-md-l-none margin-md-t-10">
									<a href="<?= $FILE ?>user/settings/">
										<div class="btn-white bordered btn-ord small">
											<i class="fa fa-times"></i> <?php echo $lang->cancel ?>
										</div>
									</a>
								</span>
							</div>
						</div>						
					</div>
				</div>
			</div>
		</div>
		<?php } // End of email change
		else { // All Settings ?>
		<?php if ($message) { ?>
		<div class="row margin-t-20">
			<div class="col-md-8 col-md-push-2 col-xs-12 col-sm-12">
				<div id="panelMessage" class="panel panel-body bg-info text-center">
					<i class="fa fa-check"></i> <?= $_SESSION['controlAction']->message; ?>
				</div>
			</div>
		</div>
		<?php unset($_SESSION['controlAction']);  }; ?>
		<div class="row <?= (!$message ? "margin-t-10" : "") ?>">
			<div class="col-md-12 col-xs-12 col-sm-12">
				<div class="panel bg-default">
					<div class="white-overlay" data-value="deactivation">
						<div class="content" style="width: 40%">
							<div>
								<h2 class="text-danger strong"><i class="fa fa-power-off"></i> <?php echo $lang->deactivate_Acc ?></h2>
							</div>
							<div><?php echo $lang->areYouSure ?>.</div>
							<div class="margin-t-20 div-inl text-md-center deactivation-buttons">
								<div id="deactivateAccount" class="btn-red btn-ord small"><i class="fa fa-power-off"></i> <?php echo $lang->acc_deactivate ?></div>
								<div class="decline-white-overlay padding-l-10"><div class="btn-white btn-ord bordered small"><i class="fa fa-times"></i> <?php echo $lang->cancel ?></div></div>
							</div>
							<div class="margin-t-20">
								<div class="panel dn message-success-deactivation">
									<div class="panel-body bg-success text-center">
										<i class="fa fa-check"></i> <?php echo $lang->deact_success ?>										
									</div>
								</div>
								<div class="padding-t-10 dn message-success-deactivation">
									<?php echo $lang->redirect_toMain ?> <span class="timer-seconds">5</span>.
								</div>
							</div>
						</div>
					</div>
					<div class="panel-heading">
						<span class="strong"><i class="fa fa-cog"></i> <?php echo $lang->acc_settings ?></span>
						<div class="divider width-100"></div>
					</div>
					<div class="panel-body padding-t-40 padding-md-t-20 padding-b-60">	
						<div class="row">					
							<div class="col-md-3 col-xs-12 col-sm-12 text-center">								
								<div class="profile-picture-loading dn">
									<div class="theme-spinner"></div>
								</div>
								<form id="profilePictureFORM" method="post" action="" enctype="multipart/form-data">									
									<div class="profile-picture-placeholder">									
										<div class="overlay">
											
											<div id="removePictureParent" class="content-bottom smaller <?= ($USER->image ? "" : "dn") ?>">
												<div class="inner-bottom">
													<a id="removePicture" href="javascript:void(0)" style="color: white">
														<span class="strong"><i class="fa fa-times"></i> <?php echo $lang->remove_profilePic ?></span>					
													</a>
												</div>
											</div>
											
											<div class="content">
												<i class="fa fa-camera"></i>
												<div style="color: white" class="padding-t-10 smaller"><?php echo $lang->change_profilePic ?></div>
												<a href="javascript: void(0)">									
													<input id="inputPicture" type="file" name="picture" style="opacity: 0; top: -20px; width: 100%; position: absolute; height: 100px" />	
												</a>
											</div>																
										</div>
										<div class="image-optimised">
											<img id="profilePictureMain" src="<?= make_image($USER->image, $FILE); ?>" style="width: 100%;" />
										</div>	
										<div class="hide-elems"></div>												
									</div>										
								</form>																
								<div class="margin-t-10" style="font-size: 120%"><?= $USER->username ?> <a href="<?= $FILE ?>user/settings/changeUsername/" data-toggle="tooltip" data-placement="bottom" title="<?= $lang->changeUsername ?>"><i class="fa fa-cog"></i></a></div>
							</div>
							<div class="col-md-4 col-xs-12 col-sm-12 margin-md-t-20">
								<h2 class="color-theme strong" style="margin: 0"><?= $USER->name." ".$USER->surname; ?></h2>								
								<div class="margin-t-10">
									<span class="strong">@</span> <span class="small"><?= $USER->email ?></span> 
									<a href="<?= $FILE ?>user/settings/emailChange/" class="link-ord">
										<span class="padding-l-10 padding-md-l-none padding-md-t-20">
											<div class="btn-white bordered btn-ord small"><i class="fa fa-pencil"></i> <?php echo $lang->change_emailAdress ?></div>
										</span>
									</a>
								</div>
								<div class="margin-t-10">
									<span class="uppercase strong small"><?php echo $lang->your_language ?></span>
									<div class="div-inl">
										<div id="sort" class="sort-options fold small" style="padding-right: 30px; margin-left: 0; z-index: 203;">
											<div class="default-option"><?= print_language($USER->lang); ?></div>
											<ul id="languageSelect" class="options text-left">
												<li data-value="EN"><a href="javascript: void(0)"><?= $lang->L_english; ?></a></li>
												<?php if (0) { ?>
												<li data-value="RU"><a href="javascript: void(0)"><?= $lang->L_russian; ?></a></li>
												<li data-value="SP"><a href="javascript: void(0)"><?= $lang->L_spanish; ?></a></li>
												<li data-value="SR"><a href="javascript: void(0)"><?= $lang->L_serbian; ?></a></li>			
												<?php }; ?>
												
											</ul>
											<i class="fold fa fa-chevron-down"></i>
											<i class="unfolded fa fa-chevron-up"></i>
										</div>
									</div>
								</div>

								<div class="margin-t-10">
									<span class="uppercase strong small"><?php echo $lang->currency ?></span>
									<div class="div-inl">
										<div id="sort" class="sort-options fold small padding-r-30" style="margin-left: 0;">
											<div class="default-option"><?= $USER->currencyID ?> - <?= print_currency_name($USER->currencyID) ?></div>
											<ul id="currencySelect" class="options text-left" style="z-index: 202">
												<li data-value="EUR">
													<a href="javascript: void(0)">EUR - <?= print_currency_name("EUR");?></a>
												</li>
												<?php if (0) { ?>
												<li data-value="RUB">
													<a href="javascript: void(0)">RUB - <?= print_currency_name("RUB") ?></a>
												</li>
												<li data-value="USD">
													<a href="javascript: void(0)">USD - <?= print_currency_name("USD") ?></a>
												</li>
												<li data-value="PLN">
													<a href="javascript: void(0)">PLN - <?= print_currency_name("PLN") ?></a>
												</li>
												<?php }; ?>
												<li data-value="RSD">
													<a href="javascript: void(0)">RSD - <?= print_currency_name("RSD") ?></a>
												</li>							
											</ul>
											<i class="fold fa fa-chevron-down"></i>
											<i class="unfolded fa fa-chevron-up"></i>
										</div>
									</div>
								</div>								
							</div>
							<div class="col-md-5 col-xs-12 col-sm-12 margin-md-t-20">
								<div><label class="pointer" style="font-weight: normal;"><input id="newsletterCheck" type="checkbox" class="js-switch" <?= ($USER->e_newsletter==1 ? "checked=\"checked\"" : "") ?> /> <span class="padding-l-10"><?php echo $lang->want_toRecieve ?> <span class="italic"><?php echo $lang->newsletter ?></span><?php echo $lang->s ?></span></label></div>
								<div class="margin-t-20">
									<div class="panel bg-info">
										<div class="panel-body small" style="padding: 10px 15px">
											<i class="fa fa-info-circle"></i> <?php echo $lang->maxPicSize ?> <span class="strong">5MB</span>.
										</div>
									</div>
								</div>								
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-xs-12 col-sm-12 margin-t-10">
								<h3 class="strong"><?php echo $lang->pass ?></h3>
								<div>
									<a href="<?= $FILE; ?>user/settings/changePassword/">
										<div class="btn-white bordered btn-ord small"><i class="fa fa-key"></i> <?php echo $lang->change_yourPass ?></div>
									</a>
								</div>
								<?php if ($USER->password_last_change !== NULL) { ?>
								<div class="padding-t-5 smaller"><i class="fa fa-info-circle"></i> <?php echo $lang->passChange_LastTime ?> <span class="strong"><?= make_date(-1,$USER->password_last_change); ?>.</span> <?php echo $lang->yearLOW ?></div>
								<?php }; ?>
							</div>
						</div>	
						<div class="row margin-t-20">
							<div class="col-md-12 col-xs-12 col-sm-12">
								<h3><?php echo $lang->deactivate_Acc ?></h3>
								<div>
									<div id="initiate_deactivation" class="btn-white btn-ord bordered small"><i class="fa fa-power-off"></i> <?php echo $lang->acc_deactivate ?></div>
								</div>
								<div class="padding-t-5 div-inl">
									<div class="smaller"><i class="fa fa-exclamation-triangle"></i> <?php echo $lang->after_deactivation ?>.</div>
								</div>
							</div>
						</div>			
					</div>
				</div>
			</div>
		</div>
		<?php }; // not set $_GET['action'] ?>

						
	</div>	<!--/.main-->
	

	<?php print_HTML_data("script","settings") ?>
	<?php if ($message) { ?>
	<script type="text/javascript" src="<?= $FILE; ?>user/js/messageTimeout.js"></script>
	<?php }; ?>
</body>

</html>
