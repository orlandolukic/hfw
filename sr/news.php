<?php 
	session_start();
	$prepath = '../';
	$ft_mg   = '';
	include $prepath."functions.php";
	include $prepath."connect.php";
	include "global.php";
	include "getDATA.php";
	include $prepath."pages.php";
	include $prepath."lang/func.php";
	include $prepath."lang/write_".strtolower($lang_acr).".php";

	$sql = mysqli_query($CONNECTION, "SELECT `news`.newsID, `news`.`heading_".$lang_acr."` AS heading, `news`.`text_".$lang_acr."` AS text, date_publish, valid_to, highlight, highlight_from, highlight_to FROM news WHERE active = 1 AND (valid_to='0000-00-00' OR (valid_to != '0000-00-00' AND valid_to >= CURDATE())) ORDER BY highlight DESC, date_publish DESC LIMIT 10");
	$i = 0; $NEWS = array();
	while ($t = mysqli_fetch_object_wrapper($sql)) $NEWS[$i++] = $t;

?>
<!DOCTYPE html>
<html>
<head>
	<?php print_HTML_data("head","news") ?>
</head>
<body class="<?= $bodyClass ?>">

	<?php echo $mobileMenu; $mobileMenu = NULL; ?>

	<!-- Fixed Menu -->
	<?php printMainMenu(1,5); ?>

	<!-- Header container -->
	<div class="header-container md-dn">
		<?php echo $headerInfo; $headerInfo = NULL; ?>
		<?php printMainMenu(0,6); ?>		
	</div>
	<!-- /Header container -->

	<!-- Main Container -->
	<div class="main-container margin-md-t-60">
		<div class="welcome-note welcome-note-min welcome-artist">
			<div class="overlay"></div>
			<div class="content text-center">
				<div class="heading strong heading-special heading-special-rest md-heading-special padding-l-60 padding-r-60" style="display: inline-block;"> <?php echo $lang->news ?> </div>				
			</div>
		</div>
	</div>
	<!-- /Main Container -->

	<div class="container margin-t-80 margin-b-80">
		<div class="row">
			<div class="col-md-8 col-xs-12 col-sm-12">
				<?php for ($i=0; $i<count($NEWS); $i++) {
				  $highlighted = $NEWS[$i]->highlight && 							     
							     $NEWS[$i]->highlight_from !== "0000-00-00" && 
							     strtotime($NEWS[$i]->highlight_from) <= time() &&
							     (
							     	$NEWS[$i]->highlight_to !== "0000-00-00" && 
							    	strtotime($NEWS[$i]->highlight_to, time()) >= time()
							    	||
							    	$NEWS[$i]->highlight_to === "0000-00-00"
							     );
				?>
				<div class="news <?= ($i>0 ? "margin-t-40 " : "") ?><?= ($highlighted ? "highlighted" : "") ?>">
					<?php if ($highlighted) { ?><div class="news-highlight smaller uppercase strong"><?= $lang->topical ?></div><?php }; ?>
					<h3 class="color-theme strong margin-t-10"><?= $NEWS[$i]->heading ?></h3>
					<div class="smaller"><i class="fa fa-calendar"></i> <?= make_date(-1, $NEWS[$i]->date_publish); ?></div>
					<div class="margin-t-20 margin-b-10 <?= ($highlighted ? "margin-md-t-40" : "") ?>"><?= $NEWS[$i]->text ?></div>
				</div>
				<?php }; ?>
				<?php if (!count($NEWS)) { ?>				
				<h4 class="strong color-theme"><i class="fa fa-info-circle"></i> Trenutno nema novosti.</h4>
				<?php }; ?>
			</div>
			<div class="col-md-4 col-xs-12 col-sm-12 margin-md-t-30">
				<div class="popular-workshops">
					<div class="padding-10 uppercase padding-l-20 strong pw-heading"><?= $lang->popular_workshops ?></div>
					<?php for ($i=0; $i<count($DATA_HEADER); $i++) { ?>
					<div class="row">
						<div class="col-md-6 col-xs-12 col-sm-12">
							<div class="image-optimised">
								<a href="<?= $domain ?>workshop/<?= $DATA_HEADER[$i]->workshopID ?>"><img src="<?= make_image_content($DATA_HEADER[$i]->image, $FILE); ?>"></a>
							</div>
						</div>
						<div class="col-md-6 col-xs-12 col-sm-12 padding-l-none padding-md-l-30 margin-md-t-10">							
							<div class="workshop-placeholder">
									<div class="small padding-t-10 strong"><a class="link-ord" href="<?= $domain ?>workshop/<?= $DATA_HEADER[$i]->workshopID ?>"><?= $DATA_HEADER[$i]->heading ?></a></div>
								<div class="padding-t-5 padding-b-10">
									<table class="color-theme" style="margin: auto">
										<tr>
											<td><i class="fa fa-2x fa-eye"></i></td>
											<td><i class="fa fa-2x fa-comments"></i></td>
											<td><i class="fa fa-2x fa-heart color-red"></i></td>
										</tr>
										<tr class="text-center strong">
											<td><?= $DATA_HEADER[$i]->views ?></td>
											<td><?= $DATA_HEADER[$i]->reviews ?></td>
											<td class="color-red"><?= $DATA_HEADER[$i]->wishlist ?></td>
										</tr>
									</table>
								</div>
							</div>							
						</div>
					</div>
					<?php if ($i<count($DATA_HEADER)-1) { ?><div class="divider"></div><?php }; ?>
					<?php }; ?>
				</div>
			</div>
		</div>
	</div>


	<?php echo $footer; $footer = NULL; ?>
	<?php echo $upBtn; $upBtn = NULL; ?>

	<?php print_HTML_data("script","news") ?>

</body>
</html>