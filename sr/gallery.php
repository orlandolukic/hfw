<?php 
	session_start();
	$prepath = '../';
	$page = "gallery";
	$ft_mg   = '';
	include $prepath."functions.php";
	include $prepath."connect.php";
	include "global.php";
	include "getDATA.php";
	include $prepath."pages.php";
	include $prepath."lang/func.php";
	include $prepath."lang/write_".strtolower($lang_acr).".php";

	if (!$userActive)
	{
		$sql_pg = mysqli_query($CONNECTION, "SELECT `tbl`.*, COUNT(`likes`.imageID) AS likes FROM (SELECT `images`.imageID, CONCAT(`images`.imageID,'.',`images`.extension) AS image, `workshops`.workshopID, `workshops`.`heading_".$lang_acr."` AS heading, `workshops`.date_publish, `workshops`.date_end FROM images LEFT OUTER JOIN workshops ON `images`.workshopID = `workshops`.workshopID WHERE `images`.workshopID IN (SELECT workshopID FROM workshops WHERE active = 1 AND (date_end = '0000-00-00' OR (date_end != '0000-00-00' AND date_end >= CURDATE())))) tbl LEFT OUTER JOIN likes ON `tbl`.imageID = `likes`.imageID GROUP BY `tbl`.imageID ORDER BY `tbl`.date_publish DESC");
	} else // User is active
	{
		$sql_pg = mysqli_query($CONNECTION, "SELECT `tbl1`.*, COUNT(`likes`.username) AS is_liked FROM (SELECT `tbl`.*, COUNT(`likes`.imageID) AS likes FROM (SELECT `images`.imageID, CONCAT(`images`.imageID,'.',`images`.extension) AS image, `workshops`.workshopID, `workshops`.`heading_".$lang_acr."` AS heading, `workshops`.date_publish, `workshops`.date_end FROM images LEFT OUTER JOIN workshops ON `images`.workshopID = `workshops`.workshopID WHERE `images`.workshopID IN (SELECT workshopID FROM workshops WHERE active = 1 AND (date_end = '0000-00-00' OR (date_end != '0000-00-00' AND date_end >= CURDATE())))) tbl LEFT OUTER JOIN likes ON `tbl`.imageID = `likes`.imageID GROUP BY `tbl`.imageID) tbl1 LEFT OUTER JOIN likes ON `likes`.username = '".$USER->username."' AND `likes`.imageID = `tbl1`.imageID GROUP BY `tbl1`.imageID ORDER BY `tbl1`.date_publish DESC");
	};

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

	<div class="container-fluid margin-t-0 margin-md-t-90 margin-b-30 padding-l-20 padding-r-20 padding-md-l-10 padding-md-r-10">		
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
						<div class="main-image-placeholder text-center"><img id="slideImage" class="main-image" src=""></div>
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