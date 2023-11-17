<?php 
	session_start();
	$prepath = '';
	$ft_mg   = '';
	$page    = "about";
	include $prepath."functions.php";
	include $prepath."connect.php";
	include "global.php";
	include "getDATA.php";
	include $prepath."pages.php";
	include $prepath."lang/func.php";
	include $prepath."lang/write_".strtolower($lang_acr).".php";

?>
<!DOCTYPE html>
<html>
<head>
	<?php print_HTML_data("head","about") ?>
</head>
<body class="<?= $bodyClass ?>">

	<?php echo $mobileMenu; $mobileMenu = NULL; ?>

	<!-- Fixed Menu -->
	<?php printMainMenu(1,2); ?>

	<!-- Header container -->
	<div class="header-container md-dn">
		<?php echo $headerInfo; $headerInfo = NULL; ?>
		<?php printMainMenu(0,2); ?>		
	</div>
	<!-- /Header container -->

	<!-- Main Container -->
	<div class="main-container margin-md-t-60">
		<div class="welcome-note welcome-note-minified">
			<div class="overlay"></div>			
			<div id="select_div" class="welcome-optimised-background welcome-optimised-background-about"></div>
		</div>
	</div>
	<!-- /Main Container -->

	<div class="container-fluid about-background">		
		<div class="container padding-t-40 padding-b-40 container-theme">
			<div class="row text-center margin-t-10">
				<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
					<h2 class="margin-t-none"><?php echo $lang->my_nameIS ?></span></h2>			
				</div>
			</div>	
			<div class="row margin-t-10">				
				<div class="col-md-12 col-xs-12 col-sm-12 margin-t-20">					
					<div class="margin-t-10">
						<?= $introduction ?>					
					</div>
					<div class="margin-t-10">
						<?= $story_1; ?>	
					</div>
				</div>
			</div>	
			<div class="row margin-t-20">					
				<div class="col-md-4 col-xs-12 col-sm-12 image-optimised">
					<div><img src="<?= $FILE ?>img/antonis_transparent.png"></div>
				</div>
				<div class="col-md-8 col-xs-12 col-sm-12 margin-md-t-20">
					<div>
						<?= $story_2 ?>	
					</div>
					<div class="margin-t-20">					
						 <?= $story_3 ?>					
					</div>

					<div class="margin-t-20">
						<?= $story_4 ?>	
					</div>

					<div class="margin-t-60">
						<div class="divider width-100"></div>
					</div>	

					<div class="row margin-t-30">
						<div class="col-md-12 col-xs-12 col-sm-12">							
							<div class="text-center"><h3 class="strong color-theme margin-t-none margin-md-t-10"><?= $story_5; ?></h3></div>					
						</div>
					</div>

					<div class="row margin-t-20">
						<div class="col-md-12 col-xs-12 col-sm-12 text-center">
							<a href="<?= $domain ?>packages">
								<div id="subscribeBtn" class="paper-btn small"><i class="fa fa-video-camera"></i> <span class="padding-l-5">
									<?= $lang->scrbForMyWS ?>
								</span></div>
							</a>
						</div>
					</div>
				</div>			
			</div>

			<div class="margin-t-40">
				<div class="divider width-100"></div>
			</div>

			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12">
					<div class="margin-t-20"><?php echo $lang->be_inTouch ?>!</div>
					<div><?php echo $lang->thank_you ?></div>
				</div>
			</div>			
		</div>
	</div>

	<?php printFixedMenuSidebar(); ?>
	<?php  echo $footer; $footer = NULL; ?>
	<?php echo $upBtn; $upBtn = NULL; ?>

	<?php print_HTML_data("script","about") ?>

</body>
</html>