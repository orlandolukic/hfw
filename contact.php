<?php 
	
	session_start();
	$prepath = '';
	include $prepath."functions.php";
	include $prepath."connect.php";
	include "global.php";
	include "getDATA.php";
	include $prepath."pages.php";
	include $prepath."lang/func.php";
	include $prepath."lang/write_".strtolower($lang_acr).".php";

	$sql_topWorkshop = mysqli_query($CONNECTION, "SELECT * FROM workshops WHERE workshopID = (SELECT workshopID FROM reviews WHERE rating=5 ORDER BY date DESC LIMIT 1)");
    while($t = mysqli_fetch_object_wrapper($sql_topWorkshop)) $WORKSHOP = $t;
    $sql_topWorkshop_images = mysqli_query($CONNECTION, "SELECT * FROM images WHERE workshopID='".$WORKSHOP->workshopID."' ORDER BY im_index ASC LIMIT 1");
    while($t = mysqli_fetch_object_wrapper($sql_topWorkshop_images)) $image = $t;
    $sql_topWorkshop_wishlist = mysqli_query($CONNECTION, "SELECT * FROM wishlist WHERE workshopID = '".$WORKSHOP->workshopID."'");
?>

<!DOCTYPE html>
<html>
<head>
	<?php print_HTML_data("head","contact") ?>
</head>
<body class="<?= $bodyClass ?>">

	<?php echo $mobileMenu; $mobileMenu = NULL; ?>

	<!-- Fixed Menu -->
	<?php printMainMenu(1,4); ?>

	<!-- Header container -->
	<div class="header-container md-dn">
		<?php echo $headerInfo; $headerInfo = NULL; ?>
		<?php printMainMenu(0,4); ?>		
	</div>
	<!-- /Header container -->

	<!-- Main Container -->
	<div class="main-container margin-md-t-60">
		<div class="welcome-note welcome-note-minified">
			<div class="overlay"></div>			
			<div id="select_div" class="welcome-optimised-background welcome-optimised-background-contact"></div>
		</div>
	</div>
	<!-- /Main Container -->

	<div class="container-fluid padding-t-30 padding-b-30">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 text-center">
					<div class="paper-btn" style="display: inline-block;"><h3 class="strong"><?php echo $lang->always_forNewContact ?></h3></div>
				</div>
			</div>			
		</div>
	</div>

	<div class="divider"></div>

	<div class="container-fluid margin-t-30 margin-b-80">
		<div class="container">
			<div class="row">
				<!-- Contact Form -->
				<div class="col-md-8 col-xs-12 col-sm-12">
					<div>
						<h4 class="strong uppercase"><i class="fa fa-envelope"></i> <?php echo $lang->sayHello ?></h4>
					</div>
					<div class="small"><?php echo $lang->wont_postEmail ?></div>
					<div id="errors" class="mistake margin-t-20 margin-b-20 dn">
						<span class="strong"><i class="fa fa-exclamation-triangle"></i> <?php echo $lang->errors_occurred ?></span>
						<br>
						<div class="smaller">
							<div class="padding-l-40 padding-md-l-30 padding-t-5">
								<ol class="ordered-list-errors">
									<li class="dn"><?php echo $lang->error_email ?></li>
									<li class="dn"><?php echo $lang->error_name ?></li>
									<li class="dn"><?php echo $lang->min_tenCharacters ?></li>
								</ol>
							</div>
						</div>
					</div>
					<div class="margin-t-10">
						<input id="name" type="text" class="input-theme" placeholder="<?= $lang->user_name ?> *" />
					</div>
					<div class="margin-t-10">
						<input id="email" type="email" class="input-theme" placeholder="<?= $lang->email ?> *" />
					</div>
					<div class="margin-t-10">
						<input id="subject" type="text" class="input-theme" placeholder="<?= $lang->subject ?>" />
					</div>
					<div class="margin-t-10">
						<textarea id="message" class="input-theme" placeholder="<?= $lang->message ?> *"></textarea>
					</div>
					<div class="margin-t-10 text-right">
						<div id="sendEmail" data-success-send="<?= $lang->soonContact ?>" class="paper-btn small pointer"><i class="fa fa-send"></i> <span class="padding-l-5"><?php echo $lang->send_message ?></span></div>
					</div>
				</div>
				<!-- /Contact Form -->
				<div class="col-md-4 col-xs-12 col-sm-12 margin-md-t-20">
					<h4 class="strong uppercase"><i class="fa fa-phone"></i> <?php echo $lang->contactinfo ?></h4>
					<div class="small"><?php echo $lang->welcome_message ?></div>									
					<div class="margin-t-10">
						<?php echo $lang->email ?>: 
						<span class="small">
							<a class="underline" href="mailto:office@handmadefantasyworld.com">office@handmadefantasyworld.com</a>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php echo $footer; $footer = NULL; ?>
	<?php echo $upBtn; $upBtn = NULL; ?>

	<?php print_HTML_data("script","contact") ?>

</body>
</html>