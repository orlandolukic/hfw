<?php 
	session_start();
	$prepath = '';
	$ft_mg   = '';
	$page    = "index";
    include $prepath."connect.php";
	include $prepath."functions.php";
	include "global.php";
	include "getDATA.php";
	include $prepath."pages.php";
	include $prepath."lang/func.php";
	include $prepath."lang/write_".strtolower($lang_acr).".php";

?>
<!DOCTYPE html>
<html>
<head>
	<?php print_HTML_data("head","index") ?>
</head>
<body class="<?= $bodyClass ?>">

	<?php echo $mobileMenu; $mobileMenu = NULL; ?>

	<!-- Fixed Menu -->
	<?php printMainMenu(1,-1); ?>

	<!-- Header container -->
	<div class="header-container md-dn">
		<?php echo $headerInfo; $headerInfo = NULL; ?>
		<?php printMainMenu(0,-1); ?>		
	</div>
	<!-- /Header container -->

	<div class="container-fluid margin-t-30 margin-md-t-100 margin-b-80">
		<div class="container">
			<!-- .row -->
			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12 text-center">
					<h2 class="margin-t-none strong color-theme"><?= $lang->select_package ?></h2>
					<div class="small"><?= $lang->bestPackage ?></div>
				</div>
			</div>	
			<!-- /.row -->
			<!-- .row -->
			<div class="row margin-t-40 margin-md-t-30">
				<?php include $prepath."requests/packages.php"; ?>
			</div>
			<!-- /.row -->
			<!-- Secure Payment -->
			<div class="margin-t-30">
				<div class="divider width-100"></div>
			</div>
			<div class="row margin-t-30 text-center">
				<div class="col-md-3 col-xs-12 col-sm-12 col-lg-3">
					<a href="http://www.aikbanka.rs/" target="_blank">
						<img src="<?php echo $FILE ?>img/ab_thumb.jpg" width="180" />
					</a>
				</div>
				<div class="col-md-3 col-xs-12 col-sm-12 col-lg-3 margin-md-t-40">
					<img src="<?php echo $FILE ?>img/master.png" width="80" />
				</div>
				<div class="col-md-3 col-xs-12 col-sm-12 col-lg-3 margin-md-t-40">
					<img src="<?php echo $FILE ?>img/maestro.png" width="80" />
				</div>
				<div class="col-md-3 col-xs-12 col-sm-12 col-lg-3 margin-md-t-40">
					<img src="<?php echo $FILE ?>img/visa.png" width="100" />
				</div>
			</div>
			<div class="row margin-t-40 text-center">
				<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 text-center">
					<div class="col-md-3 col-xs-12 col-sm-6 text-center margin-md-b-20">
						<a href="http://www.paypal.com/" target="_blank">
							<img src="<?php echo $FILE ?>img/paypal.png" width="160" />
						</a>
					</div>					
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 margin-t-10">
						<a href="http://www.mastercardbusiness.com/mcbiz/index.jsp?template=/orphans&content=securecodepopup" target="_blank">
							<img src="<?php echo $FILE ?>img/mcardsec.png" width="130" />
						</a>
					</div>
					<div class="col-md-3 col-lg-3 col-xs-12 col-sm-12 margin-md-t-30">
						<a href="http://www.visa.ca/verified/infopane/index.html" target="frame">
							<img src="<?php echo $FILE ?>img/vbyvisa.png" width="130" />
						</a>
					</div>
				</div>		
			</div>
			<!-- /Secure Payment -->
		</div>		
	</div>


	<?php echo $footer; $footer = NULL; ?>
	<?php echo $upBtn; $upBtn = NULL; ?>

	<?php print_HTML_data("script","index") ?>

</body>
</html>