<?php 
	session_start();
	$prepath = '';
	$page = "gallery";
	$ft_mg   = '';
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
	<?php print_HTML_data("head","gallery") ?>
</head>
<body class="<?= $bodyClass ?> gallery-page">

	<?php echo $mobileMenu; $mobileMenu = NULL; ?>

	<!-- Fixed Menu -->
	<?php printMainMenu(1,5); ?>

	<!-- Header container -->
	<div class="header-container md-dn">
		<?php echo $headerInfo; $headerInfo = NULL; ?>
		<?php printMainMenu(0,5); ?>		
	</div>
	<!-- /Header container -->	

	<div class="container-fluid gallery-placeholder-full margin-t-0 margin-md-t-90 margin-b-30 padding-l-20 padding-r-20 padding-md-l-10 padding-md-r-10">		
		<div class="container">
			<div class="row gallery-placeholder">
				<div class="col-md-12 col-xs-12 col-sm-12">
					<div class="gallery-wrapper">
						<div class="navigation left-navigation color-theme">
							<i class="fa fa-chevron-left"></i>
						</div>
						<div class="navigation right-navigation color-theme">
							<i class="fa fa-chevron-right"></i>
						</div>
						<div class="no-images-placeholder">
							<img src="<?= $FILE ?>img/logo.png">
							<div class="margin-t-20 font-eminenz strong" style="font-size: 120%">
								<?= $lang->noPhotos ?>
							</div>
						</div>
						<div class="gallery-loader theme-spinner"></div>
						<div id="select_div" class="main-image-placeholder text-center"><img id="slideImage" class="main-image" src=""></div>
						<div class="description-placeholder color-theme">
							<div class="smaller uppercase"><?= $lang->workshop ?></div>
							<div class="div-inl">
								<a id="workshopName" class="link-ord" href="<?= $domain ?>/workshop/ihPnQ53JSFidYVc2ezOy" style="display: inline-block;"><span class="strong">Micius Presaentem Roting</span></a>
								<?php if ($userActive) { ?>
								<div class="padding-l-40 padding-md-l-10 color-red">
									<div class="like-image"><i class="fa fa-heart-o"></i> <span><?= $lang->likeItImage ?></span></div>							
								</div>								
								<?php }; ?>
								<span class="margin-t-30">
									<div class="image-likes small color-red"><span>12</span> <i class="fa fa-heart"></i></div>
								</span>
							</div>							
						</div>
					</div>
				</div>				
			</div>	
		</div>
	</div>

	<div class="container-fluid list-of-images margin-t-40" data-page="0" data-images="">
		<div class="smaller strong uppercase margin-t-10 padding-l-40"><?= $lang->moreImages ?></div>
		<div class="navigation left-navigation btn-ord"><i class="fa fa-chevron-left"></i></div>
		<div class="navigation right-navigation btn-ord"><i class="fa fa-chevron-right"></i></div>
		<div id="images" class="div-inl padding-l-20 padding-t-10">					
		</div>
	</div>



	<?php echo $upBtn; $upBtn = NULL; ?>

	<?php print_HTML_data("script","gallery") ?>

</body>
</html>