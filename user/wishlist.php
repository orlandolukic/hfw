<?php 
	
	session_start();
//	Prep for include
	$prepath = '../';
	include $prepath."requests/userManagementCheck.php";
	$INCLUDE = (object) array("getDATA" => true);
    include $prepath."connect.php";
	include $prepath."functions.php";

	if ($_SESSION['lang'] !== "EN")
	{
		include $prepath.strtolower($_SESSION['lang'])."/global.php";
		include $prepath.strtolower($_SESSION['lang'])."/getDATA.php";
	} else
	{
		include $prepath."global.php";
		include $prepath."getDATA.php";
	};
	include $prepath."pages.php";
	include $prepath."lang/func.php";
	include $prepath."lang/user_".strtolower($lang_acr).".php";
	include $prepath."requests/userManagement.php";

//	Redirect if user has privileged access
	if ($USER->grant_access) header("location: ".$FILE."user");

	$sql_WISHLIST = mysqli_query($CONNECTION,"SELECT `tbl1`.*, CONCAT(`images`.imageID,'.',`images`.extension) AS image FROM (SELECT `wishlist`.workshopID, `wishlist`.date AS dateAdded, `wishlist`.username, COUNT(`wishlist`.workshopID) AS numWishList, `workshops`.heading_SR AS heading, `workshops`.subheading_SR AS subheading, `wishlist`.wishID, `workshops`.`date_publish`, `workshops`.`date_end`, `workshops`.forsale, `workshops`.price_RSD AS priceRSD, `workshops`.`price_EUR` AS price, MONTH(`workshops`.date_publish) AS start_month FROM `wishlist` INNER JOIN workshops ON `workshops`.`workshopID` = `wishlist`.`workshopID` GROUP BY `wishlist`.workshopID) tbl1 LEFT OUTER JOIN images ON `tbl1`.workshopID = `images`.workshopID WHERE `images`.im_index = 1 AND `tbl1`.username = '".$USER->username."' ORDER BY `tbl1`.dateAdded DESC, `tbl1`.date_publish DESC");
	if (mysqli_num_rows_wrapper($sql_WISHLIST))
	{
		$i = 0; $WISHLIST = array(); while($t = mysqli_fetch_object_wrapper($sql_WISHLIST)) $WISHLIST[$i++] = $t;
	} else $WISHLIST = array();

	$prepath = $domain;

	$sql = mysqli_query($CONNECTION,"SELECT flag, packageID FROM packages");
	$PACKAGES = array(); $i = 0;
	while($t = mysqli_fetch_object_wrapper($sql)) $PACKAGES[$i++] = $t;


?>

<!DOCTYPE html>
<html>
<head>
<?php print_HTML_data("head","wishlist") ?>
</head>

<body class="<?= $bodyClass ?>">
	<?php printTopMenu(); ?>	
	<?php printMainMenu(3); ?>
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main margin-b-60">			
		<?php printNavigation(1,[$lang->my_wishlist],["javascript: void(0)"]); ?>
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header heading"><?php echo $lang->my_wishlist ?> <div class="fl-right padding-r-10 padding-t-10 dn"><div class="theme-spinner"></div></div></h1>
			</div>
		</div><!--/.row-->

		<div class="row <?= (!mysqli_num_rows_wrapper($sql_WISHLIST) ? "" : "dn") ?> empty-wishlist">
			<div class="col-md-12 col-xs-12 col-sm-12">
				<div class="panel">
					<div class="panel-body">
						<div class="small"><?php echo $lang->noWS_inYourWishList ?>.</div>
						<a href="<?= $domain ?>workshops">
						<div class="margin-t-10">
							<div class="btn-white btn-ord bordered smaller"><i class="fa fa-paint-brush"></i> <?php echo $lang->all_workshops ?></div>
						</div>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php for ($i=0; $i<count($WISHLIST); $i++) { ?>
		<div class="row wishitem" data-wish-item="<?= $WISHLIST[$i]->wishID; ?>">
			<div class="col-md-12 col-xs-12 col-sm-12">
				<div class="panel">
					<div class="panel-body">
						<?php if ($WISHLIST[$i]->forsale) { ?>
						<div class="row margin-b-10" style="padding: 0">
							<div class="col-md-12 col-xs-12 col-sm-12">
								<div class="small uppercase color-success strong"><span class="small"><i class="fa fa-shopping-cart relative" style="top: -1px"></i></span> <span class="small"><?php echo $lang->workshop_forSale ?></span></div>
							</div>
						</div>
						<?php }; ?>
						<div class="row">
							<div class="col-md-2 col-xs-12 col-sm-12 image-optimised">
								<a href="<?= $domain ?>workshop/<?= $WISHLIST[$i]->workshopID; ?>">
									<img src="<?= $FILE ?>img/content/<?= $WISHLIST[$i]->image ?>">
								</a>
							</div>
							<div class="col-md-5 col-xs-12 col-sm-12 margin-md-t-20">
								<a href="<?= $domain ?>workshop/<?= $WISHLIST[$i]->workshopID; ?>">
									<div class="color-theme strong" style="font-size: 120%;"><?= $WISHLIST[$i]->heading ?></div>
								</a>
								<div class="small padding-t-5">
									<span><i class="fa fa-calendar"></i> <?= make_date(-1,$WISHLIST[$i]->date_publish); ?></span>
									<span class="padding-l-20 color-red strong"><i class="fa fa-heart"></i> <?= make_date(-1, $WISHLIST[$i]->dateAdded); ?></span>
								</div>
								<div class="margin-t-10 small">
									<?= $WISHLIST[$i]->subheading ?>
								</div>
							</div>
							<div class="col-md-3 col-xs-6 col-sm-6 text-right margin-md-t-10">
								<?php if ($WISHLIST[$i]->numWishList > 0) { ?>
								<div class="small"><?php echo $lang->others_like ?>: <span class="strong color-red"><?= $WISHLIST[$i]->numWishList; ?> <i class="fa fa-heart"></i></span></div>
								<?php }; ?>
							</div>							
							<div class="col-md-2 col-xs-12 col-sm-12 margin-md-t-20">							
							<?php if (!$WISHLIST[$i]->forsale) { ?>
							
								<div class="smaller strong uppercase"><i class="fa fa-shopping-cart"></i> <?php echo $lang->subscription ?></div>
								<div class="small"><div class="small"><?php echo $lang->select_package ?></div></div>
								
								<a class="subscription-button" href="<?= $FILE; ?>user/addCart/subscription?cid=1&month=<?= $WISHLIST[$i]->start_month ?>" data-base-uri="<?= $FILE; ?>user/addCart/subscription/">
									<div class="btn-green btn-ord small margin-t-10 btn-block text-center"><i class="fa fa-shopping-cart"></i> <?php echo $lang->subscribe_for ?> <span class="strong"><?= $lang->c_months[$WISHLIST[$i]->start_month-1] ?></span></div>
								</a>
							<?php } else { ?>		
								<a href="<?= $FILE; ?>user/addCart/workshop/<?= $WISHLIST[$i]->workshopID ?>">
									<div class="btn-green btn-ord small btn-block text-center"><i class="fa fa-shopping-cart"></i> <?php echo $lang->addToCart ?></div>
								</a>
							<?php }; ?>

							<div class="margin-t-10">
								<div class="btn-red btn-ord small btn-block text-center delete-wishitem"><i class="fa fa-trash"></i> <?php echo $lang->delete_fromWishlist ?></div>
							</div>
							</div>							
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php }; ?>

						
	</div>	<!--/.main-->
	

	<?php print_HTML_data("script","wishlist") ?>
</body>

</html>
