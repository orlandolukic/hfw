<?php 
	session_start();
	$prepath = '../';
	$ft_mg   = '';
	$page    = "index";
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
		</div>		
	</div>


	<?php echo $footer; $footer = NULL; ?>
	<?php echo $upBtn; $upBtn = NULL; ?>

	<?php print_HTML_data("script","index") ?>

</body>
</html>