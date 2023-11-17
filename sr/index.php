<?php 	
	session_start();
	$prepath = '../';
	$page 	 = "index";
	include $prepath."functions.php";
	include $prepath."connect.php";
	include "global.php";
	include "getDATA.php";
	include $prepath."pages.php";
	include $prepath."lang/func.php";
	include $prepath."lang/write_".strtolower($lang_acr).".php";

	$sql_topWorkshop = mysqli_query($CONNECTION, "SELECT `tbl`.*, MAX(`reviews`.rating) AS maxRating FROM (SELECT `workshops`.* FROM workshops WHERE active = 1 AND date_publish<=CURDATE() AND ( date_end = '0000-00-00' OR (date_end != '0000-00-00' AND date_end>=CURDATE()) )) tbl LEFT OUTER JOIN reviews ON `reviews`.`workshopID` = `tbl`.`workshopID` GROUP BY tbl.workshopID LIMIT 1");
	$WORKSHOP = NULL;
	if (mysqli_num_rows_wrapper($sql_topWorkshop)) 
	{
	    while($t = mysqli_fetch_object_wrapper($sql_topWorkshop)) $WORKSHOP = $t;
	    $sql_topWorkshop_images = mysqli_query($CONNECTION, "SELECT * FROM images WHERE workshopID='".$WORKSHOP->workshopID."' ORDER BY im_index ASC LIMIT 1");
	    while($t = mysqli_fetch_object_wrapper($sql_topWorkshop_images)) $image = $t;
	 
	    $sql_topWorkshop_wishlist = mysqli_query($CONNECTION, "SELECT * FROM wishlist WHERE workshopID = '".$WORKSHOP->workshopID."'");
	}

	$BANNERS = (object) array(); 
//  Fetch banners
	$sql = mysqli_query($CONNECTION, "SELECT eventID, date, location, heading_".$lang_acr." AS heading FROM events WHERE active = 1 AND date>CURDATE() ORDER BY date ASC LIMIT 1");
	$BANNERS->events = mysqli_fetch_object_wrapper($sql);

	// Workshop
	$sql = mysqli_query($CONNECTION, "SELECT `workshops`.`heading_".$lang_acr."` AS heading,`workshops`.workshopID, subheading_".$lang_acr." AS subheading, CONCAT(`images`.imageID,'.',`images`.`extension`) AS image, `workshops`.`date_publish` FROM workshops LEFT OUTER JOIN `images` ON `images`.workshopID = `workshops`.workshopID AND `images`.im_index = 1 WHERE `workshops`.active = 1 AND `workshops`.date_publish>=CURDATE() ORDER BY `date_publish` ASC LIMIT 1");
	if (mysqli_num_rows_wrapper($sql)) 
	{
		$BANNERS->workshop = mysqli_fetch_object_wrapper($sql);
	}

?>

<!DOCTYPE html>
<html>
<head>
	<?php print_HTML_data("head","index") ?>	
</head>
<body class="<?= $bodyClass ?>">

	<?php echo $mobileMenu; $mobileMenu = NULL; ?>

	<!-- Fixed Menu -->
	<?php printMainMenu(1,1); ?>

	<!-- Header container -->
	<div class="header-container md-dn">
		<?php echo $headerInfo; $headerInfo = NULL; ?>
		<?php printMainMenu(0,1); ?>		
	</div>
	<!-- /Header container -->

	<!-- Main Container -->
	<div class="main-container margin-md-t-50">
		<div class="welcome-note">
			<div id="backgroundImage" class="background-image homepage-special"></div>
			<div class="overlay"></div>
			<div class="content text-center">
				<div class="heading strong heading-special md-heading-special"> <?php echo $lang->workshop_wka ?> </div>					
				
				<div class="margin-t-10 md-dn text-left">
					<div class="container-fluid">
						<div class="row margin-t-20">							
							<div id="elements" class="col-md-push-6 col-md-6 text-left margin-t-50">								
								<h3 id="headingRight" class="strong op-0"><?= $lang->talent_cantBeTaught ?>.</h3>
								<div class="subheading op-0"><?= $lang->join_ourOnline ?>.</div>
								<div class="marked-text strong uppercase margin-t-20 op-0"><?= $lang->lets_haveFun ?>!</div>
								<div class="margin-t-20">
									<a href="<?= $domain ?>packages">
										<div id="subscribeBtn" class="btn-welcome color-theme btn-ord small op-0"><i class="fa fa-video-camera"></i> <span class="padding-l-5"><?= $lang->scrbForMyWS ?></span></div>
									</a>
								</div>
							</div>
						</div>							
					</div>
				</div>				
			</div>
			<div class="banners-right-placeholder md-dn">
				<div class="pinned-news">
					<div class="pin"><img src="<?= $FILE ?>img/pin.png"></div>
					<div class="strong text-large padding-t-10 font-eminenz">
						<i class="fa fa-calendar"></i> Upcoming event
					</div>
					<div class="divider"></div>
					<a class="link-ord color-theme" href="<?= $FILE ?>calendar?eventID=<?= $BANNERS->events->eventID ?>">
						<div><?= $BANNERS->events->heading ?></div>
					</a>
					<div class="smaller margin-t-10"><span class="fa fa-calendar"></span> <?= make_date(-1,$BANNERS->events->date); ?></div>
					<div class="smaller">
						<span class="fa fa-map-marker"></span> <span class="strong"><?= $BANNERS->events->location ?></span>
					</div>					
				</div>

				<div class="pinned-news margin-t-20" style="padding-bottom: 20px">
					<div class="pin"><img src="<?= $FILE ?>img/pin.png"></div>
					<div class="strong text-large padding-t-20 font-eminenz">
						<i class="fa fa-calendar"></i> Next workshop
					</div>
					<div class="divider"></div>
					<div class="padding-r-10 text-center">						
						<img src="<?= $FILE ?>img/content/<?= $BANNERS->workshop->image ?>" style="width: 80%">						
					</div>					
					<div class="padding-t-10 color-theme strong"><?= $BANNERS->workshop->heading ?></div>					
					<div class="smaller margin-t-10"><span class="fa fa-calendar"></span> <?= make_date(-1,$BANNERS->workshop->date_publish); ?></div>								
				</div>

				<div class="pinned-news margin-t-20">
					<div class="pin"><img src="<?= $FILE ?>img/pin.png"></div>
					<div class="strong text-large padding-t-10 font-eminenz">
						<i class="fa fa-paint-brush"></i> Our partners
					</div>
					<div class="divider"></div>
					<div><a href="http://www.google.rs" target="_blank"><span>A&N</span></a></div>
					<div><a href="http://www.google.rs" target="_blank"><span>New Stars</span></a></div>
					<div><a href="http://www.google.rs" target="_blank"><span>New Gallery</span></a></div>				
				</div>
			</div>
		</div>
		<!-- /.welcome-note -->
	</div>
	<!-- /Main Container -->

	<div class="container-fluid padding-t-30 padding-b-30">
		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 text-center">
				<h1 class="strong"><?php echo $lang->talent_alwaysSurprise ?></h1>
			</div>
		</div>
		<div class="row text-center margin-t-10">
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
				<?php echo $INDEX_3 ?>
			</div>
		</div>
		<div class="row text-center margin-t-40">
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
				<a href="<?php echo $domain ?>workshops">
					<div class="btn-theme uppercase"><i class="fa fa-paint-brush"></i> <?php echo $lang->workshops ?></div>
				</a>
				<a href="<?php echo $domain ?>about"><div class="btn-theme-o uppercase"><i class="fa fa-hand-o-right"></i> <?php echo $lang->about ?></div></a>
			</div>
		</div>
	</div>

	<div class="container md-db dn margin-b-30">
		<div class="divider"></div>
		<div class="row margin-t-20 margin-b-20">
			<div class="col-sm-12 col-xs-12 padding-l-20">
				<h2 class="margin-t-10"><?= $lang->talent_cantBeTaught ?>.</h2>
			</div>
		</div>		
		<div class="row">
			<div class="col-sm-12 col-xs-12 margin-t-10">
				 <?= $lang->join_ourOnline ?>.
				 <div class="margin-t-10 strong uppercase md-text-center"><?= $lang->lets_haveFun ?>!</div>
				 <div class="md-text-center margin-md-t-20">
					 <a href="<?= $domain ?>packages">
						<div class="btn-welcome color-theme btn-ord small"><i class="fa fa-video-camera"></i> <span class="padding-l-5">Subscribe to my online workshops</span></div>
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="divider"></div>

	<div class="container margin-b-90 margin-t-20">
		<div class="row">
			<div class="col-md-4 col-xs-12 col-sm-12">				
				<table>
					<tr class="color-theme">
						<td><i class="fa fa-diamond fa-2x"></i></td>
						<td><h3 class="strong"><?php echo $lang->quality ?></h3></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<?php echo $INDEX_4 ?>
						</td>
					</tr>
				</table>
			</div>
			<div class="col-md-4 col-xs-12 col-sm-12">
				<table>
					<tr class="color-theme">
						<td><i class="fa fa-magic fa-2x"></i></td>
						<td><h3 class="strong"><?php echo $lang->enchant_magic ?></h3></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<?php echo $INDEX_5 ?>
						</td>
					</tr>
				</table>
			</div>
			<div class="col-md-4 col-xs-12 col-sm-12">
				<table>
					<tr class="color-theme">
						<td><i class="fa fa-paint-brush fa-2x"></i></td>
						<td><h3 class="strong"><?php echo $lang->popular_workshops ?></h3></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<?php echo $INDEX_6; ?>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="row margin-t-20">
			<div class="col-md-4 col-xs-12 col-sm-12">				
				<table>
					<tr class="color-theme">
						<td><i class="fa fa-thumbs-o-up fa-2x"></i></td>
						<td><h3 class="strong"><?php echo $lang->satisfied_customers ?></h3></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<?php echo $INDEX_7 ?>
						</td>
					</tr>
				</table>
			</div>
			<div class="col-md-4 col-xs-12 col-sm-12">
				<table>
					<tr class="color-theme">
						<td><i class="fa fa-bell-o fa-2x"></i></td>
						<td><h3 class="strong"><?php echo $lang->many_news ?></h3></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<?php echo $INDEX_1 ?>
						</td>
					</tr>
				</table>
			</div>
			<div class="col-md-4 col-xs-12 col-sm-12">
				<table>
					<tr class="color-theme">
						<td><i class="fa fa-smile-o fa-2x"></i></td>
						<td><h3 class="strong"><?php echo $lang->work_relax ?></h3></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<?php echo $INDEX_8 ?>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<?php if ($WORKSHOP) { ?>

	<div class="container-fluid margin-b-60">
		<div class="container">
			<div class="top-workshop padding-md-b-20">
				<div class="row">				
					<div class="col-md-5 col-md-push-1 col-xs-12 col-sm-12 image-optimised">					
						<img style="height: 300px" src="<?= make_image_content($image->imageID,$FILE,$image->extension) ?>" />
						<div class="eticket l-sp-1 uppercase"><?php echo $lang->top_stuff ?></div>
					</div>
					<div class="col-md-6 col-md-push-1 col-xs-12 col-sm-12 padding-t-20">						
						<div class="padding-l-10 padding-r-10 padding-b-30">
							<div><span class="large"><?= pop_obj($WORKSHOP, "heading_".strtoupper($lang_acr)) ?></span><span style="position: relative; top: -5px; left: 5px" class="label label-success"><?php echo $lang->newUP ?></span></div>
                            <div class="text-faded margin-t-10 md-text-center" style="font-size: 150%">
                                <span class="pointer" title="<?= $lang->peopleVisited ?>" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-eye"></i> <?= $WORKSHOP->views ?></span>
                                <?php if (mysqli_num_rows_wrapper($sql_topWorkshop_wishlist)) { ?>
                                <span class="padding-l-20 pointer" title="<?= $lang->wish_list ?>" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-heart"></i> <?= mysqli_num_rows_wrapper($sql_topWorkshop_wishlist); ?></span>
                                <?php }; ?>
                            </div>
							 <div class="margin-t-20"><?= pop_obj($WORKSHOP, "subheading_".strtoupper($lang_acr)) ?></div>
                            <div class="margin-t-20 md-text-center">
                                <a href="<?php echo $domain ?>workshop/<?= $WORKSHOP->workshopID ?>">
                                    <div class="btn-rounded-success btn-padding-small"><i class="fa fa-paint-brush"></i> <?php echo $lang->visit_workshop ?></div>
                                </a>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php }; ?>

	<div class="container-fluid gray-bg margin-b-20 padding-t-30 padding-b-30">
		<div class="container">
			<div class="row">
				<div class="col-md-push-1 col-md-6 col-md-7 col-xs-12 col-sm-12">
					<h3 style="margin: 0" class="strong"><?php echo $lang->challenge_areReady ?></h3>
				</div>
			</div>
			<div class="row margin-t-20">
				<div class="col-md-push-1 col-md-6 col-lg-7 col-xs-12 col-sm-12">
					<?php echo $INDEX_9 ?>				
				</div>
				<div class="col-md-push-1 col-md-2 col-lg-3 col-xs-12 col-sm-12 text-center margin-md-t-20">
					<a href="<?= $FILE ?>about"><div class="btn-theme uppercase"><i class="fa fa-rocket"></i> <?php echo $lang->learn_more ?></div></a>
				</div>
			</div>
		</div>
	</div>	

	<!-- Packages -->
	<a name="packages"></a>
	<div class="container-fluid padding-t-90">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
					<h4 class="strong uppercase"><?php echo $lang->pay_plan ?></h4>
				</div>
			</div>
			<div class="row margin-t-20">
				<?php include $prepath."requests/packages.php"; ?>
			</div>
		</div>
	</div>

	<!-- Secure Payment -->
	<div class="container text-center margin-t-40">
		<div class="row">
			<div class="col-md-3 col-xs-12 col-sm-12 col-lg-3">
				<img src="<?php echo $FILE ?>img/ab_thumb.jpg" width="180" />
			</div>
			<div class="col-md-3 col-xs-12 col-sm-12 col-lg-3 margin-md-t-40">
				<img src="<?php echo $FILE ?>img/master.png" width="80" />
			</div>
			<div class="col-md-3 col-xs-12 col-sm-12 col-lg-3 margin-md-t-40">
				<img src="<?php echo $FILE ?>img/maestro.png" width="80" />
			</div>
			<div class="col-md-3 col-xs-12 col-sm-12 col-lg-3 margin-md-t-40">
				<img src="<?php echo $FILE ?>img/visa.gif" width="100" />
			</div>
		</div>
		<div class="row margin-t-40">
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12 text-center">					
				<div class="col-md-push-3 col-md-3 col-lg-3 col-xs-12 col-sm-12 margin-t-10">
					<img src="<?php echo $FILE ?>img/mcardsec.png" width="130" />
				</div>
				<div class="col-md-push-3 col-md-3 col-lg-3 col-xs-12 col-sm-12 margin-md-t-30">
					<img src="<?php echo $FILE ?>img/vbyvisa.png" width="130" />
				</div>
			</div>				
		</div>
	</div>
	<!-- /Secure Payment -->
	<div class="margin-t-40"><div class="divider"></div></div>
	<div class="container margin-t-60">
		<div class="row">
			<div class="col-md-6 col-xs-12 col-sm-12 text-right md-text-center">
				<h2><?= $lang->cooperation ?>?</h2>
			</div>
			<div class="col-md-6 col-xs-12 col-sm-12 text-left md-text-center">
				<div class="uppercase smaller"><?= $lang->send_usEmail ?></div>
				<div class="margin-t-10"><i class="fa fa-envelope color-theme"></i> <a class="link-ord" href="mailto:office@handmadefantasyworld.com">office@handmadefantasyworld.com</a></div>
			</div>
		</div>
	</div>

	<div class="social-fixed">
		<div class="text-right relative">

			<?php if ($userActive) { ?>
			<div class="soc-pre-placeholder">
				<a href="<?= $FILE ?>user" data-toggle="tooltip" data-placement="left" title="<?= $lang->user_pannel ?>">
					<div class="self-login btn-ord social-fixed-item"><i class="fa fa-user-circle"></i></div>
				</a>
			</div>
			<?php } else { ?>

			<div class="soc-pre-placeholder">
				<a href="<?= $domain ?>register" data-toggle="tooltip" data-placement="left" title="<?= $lang->register ?>">
					<div class="self-login btn-ord social-fixed-item"><i class="fa fa-edit"></i></div>
				</a>
			</div>

			<div class="soc-pre-placeholder">
				<a href="<?= $domain ?>login" data-toggle="tooltip" data-placement="left" title="<?= $lang->login ?>">
					<div class="self-login btn-ord social-fixed-item"><i class="fa fa-sign-in"></i></div>
				</a>
			</div>
			<?php }; ?>
			
			<div class="soc-pre-placeholder">
				<a href="<?= $COMPANY_INFO->facebook ?>" target="_blank" data-toggle="tooltip" data-placement="left" title="<?= $lang->findUsOnFace ?>">
					<div class="soc-facebook btn-ord social-fixed-item"><i class="fa fa-facebook"></i></div>
				</a>
			</div>
		</div>		
	</div>

	<?php echo $footer; $footer = NULL; ?>
	<?php echo $upBtn; $upBtn = NULL; ?>

	<?php print_HTML_data("script","index") ?>

</body>
</html>