<?php 
	
	session_start();
	$prepath = '';
	$page 	 = "policy";
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

	<div class="container-fluid padding-t-30 padding-b-30 margin-md-t-50">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 text-center ">
					<h1 class="strong color-theme"><?php echo $header1 ?></h1>
				</div>
			</div>			
		</div>
	</div>

	<div class="container padding-b-20">
		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
				<?php echo $para1 ?>
			</div>
		</div>
	</div>

	<div class="divider"></div>

	<div class="container-fluid padding-t-30 padding-b-30">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 text-center ">
					<h1 class="strong color-theme"><?php echo $header2 ?></h1>
				</div>
			</div>			
		</div>
	</div>

	<div class="container padding-b-20">
		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
				<ul style="list-style-type:none">
					<li><?php echo $pol_info1 ?></li>
					<li><?php echo $pol_info2 ?></li>
					<li><?php echo $pol_info3 ?></li>
					<li><?php echo $pom_info4 ?>: 109863343</li>
					<li><?php echo $pol_info5 ?>: 21256706</li>
				</ul>
			</div>
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 padding-t-20">
				<div><?php echo $para2 ?>
				</div>
			</div>
		</div>
	</div>

	<div class="divider"></div>

		<div class="container-fluid padding-t-30 padding-b-30">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 text-center ">
					<h1 class="strong color-theme"><?php echo $header3 ?></h1>
				</div>
			</div>			
		</div>
	</div>

	<div class="container padding-b-20">
		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
				<ul style="list-style-type:circle" class="padding-l-40">
					<li><a href="#returnPolicy"><?php echo $header4 ?></a></li>
					<li><a href="#cookie"><?php echo $header5 ?></a></li>
					<li><a href="#regist"><?php echo $header6 ?></a></li>
					<li><a href="#disclaimer"><?php echo $header7 ?></a></li>
					<li><a href="#privacy"><?php echo $header8 ?></a></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="divider"></div>

	<div class="container-fluid padding-t-30 padding-b-30" id="returnPolicy">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 text-center ">
					<h1 class="strong color-theme" ><?php echo $header4 ?></h1>
				</div>
			</div>			
		</div>
	</div>

	<div class="container padding-b-20">
		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
				<?php echo $para3 ?>
			</div>
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 padding-t-20">
				<?php echo $para4 ?>
			</div>
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 padding-t-20">
				<?php echo $para5 ?>
			</div>
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 padding-t-20">
				<?php echo $para6 ?>
			</div>
		</div>
	</div>

	<div class="divider"></div>

	<div class="container-fluid padding-t-30 padding-b-30" id="cookie">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 text-center ">
					<h1 class="strong color-theme"><?php echo $header5 ?></h1>
				</div>
			</div>			
		</div>
	</div>

	<div class="container padding-b-20">
		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
				<?php echo $para7 ?>
			</div>
		</div>
	</div>

	<div class="divider"></div>

	<div class="container-fluid padding-t-30 padding-b-30" id="regist">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 text-center ">
					<h1 class="strong color-theme"><?php echo $header6 ?></h1>
				</div>
			</div>			
		</div>
	</div>

	<div class="container padding-b-20">
		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 ">
				<?php echo $para8 ?>  
			</div>
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 padding-t-20">
				<?php echo $para9 ?>
			</div>
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 padding-t-20">
				<?php echo $para10 ?>
			</div>
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 padding-t-20">
				<?php echo $para11 ?>
			</div>
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 padding-t-20">
				<?php echo $para12 ?>
			</div>
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 padding-t-20">
				<?php echo $para13 ?>
			</div>
		</div>
	</div>

	<div class="divider"></div>

	<div class="container-fluid padding-t-30 padding-b-30" id="disclaimer">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 text-center ">
					<h1 class="strong color-theme"><?php echo $header7 ?></h1>
				</div>
			</div>			
		</div>
	</div>

	<div class="container padding-b-20">
		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 padding-t-20">
				<?php echo $para14 ?>
			</div>
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 padding-t-20">
				<?php echo $para15 ?>
			</div>
		</div>
	</div>

	<div class="divider"></div>

	<div class="container-fluid padding-t-30 padding-b-30" id="privacy">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 text-center ">
					<h1 class="strong color-theme"><?php echo $header8 ?></h1>
				</div>
			</div>			
		</div>
	</div>

	<div class="container padding-b-20">
		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 padding-t-20">
				<?php echo $para16 ?>
			</div>
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 padding-t-20">
				<?php echo $para17 ?>
			</div>
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 padding-t-20">
				<?php echo $para18 ?>
			</div>
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 padding-t-20">
				<?php echo $para19 ?>
			</div>

			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 padding-t-20 italic">
				<?php echo $para20 ?>


			</div>
		</div>
	</div>

	<?php echo $footer; $footer = NULL; ?>
	<?php echo $upBtn; $upBtn = NULL; ?>

	<?php print_HTML_data("script","contact") ?>

</body>
</html>