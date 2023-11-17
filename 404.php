<?php 
	
	session_start();
	$prepath = '';
	include "functions.php";
	include "connect.php";
	if (isset($_SESSION['lang']) && $_SESSION['lang'] !== "EN")
	{
		include "../".strtolower($_SESSION['lang'])."/global.php";
		include "../".strtolower($_SESSION['lang'])."/getDATA.php";
	} else
	{
		include "global.php";
		include "getDATA.php";
	}
	include "pages.php";
	include "lang/func.php";
	include "lang/write_".strtolower($lang_acr).".php";

?>

<!DOCTYPE html>
<html>
<head>
	<?php print_HTML_data("head","404") ?>
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

	<!-- Main Container -->
	<div class="main-container margin-md-t-80">
		<div class="welcome-note" style="background: white; height: 500px">
			<div class="content color-theme content-error" style="color: black;">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-3 col-xs-12 col-sm-12 large"><span class="large"><i class="fa fa-meh-o fa-5x color-theme fa-spin"></i></span></div>
						<div class="col-md-9 col-xs-12 col-sm-12 margin-md-t-none margin-t-50">
							<h1 class="uppercase strong"><?= $lang->h404 ?></h1>
							<label class="label label-danger"><?= $lang->error ?> 404</label>
							<div class="text-faded margin-t-10"><?= $lang->_404 ?></div>
						</div>
					</div>
				</div>

			</div>			
		</div>
	</div>
	<!-- /Main Container -->

	<?php echo $footer; $footer = NULL; ?>
	<?php echo $upBtn; $upBtn = NULL; ?>

	<?php print_HTML_data("script","404") ?>

</body>
</html>