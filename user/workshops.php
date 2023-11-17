<?php 
	
	session_start();
//	Prep for include
	$prepath = '../';
	include $prepath."requests/userManagementCheck.php";
	$INCLUDE = (object) array("getDATA" => true);
	include $prepath."functions.php";
	include $prepath."connect.php";
	if ($_SESSION['lang'] !== "EN")
	{
		include $prepath.strtolower($_SESSION['lang'])."/global.php";
		include $prepath.strtolower($_SESSION['lang'])."/getDATA.php";
	} else
	{
		include $prepath."global.php";
		include $prepath."getDATA.php";
	}
	include $prepath."pages.php";
	include $prepath."lang/func.php";
	include $prepath."lang/user_".strtolower($lang_acr).".php";
	include $prepath."requests/userManagement.php";
	include $prepath."requests/det_subscription_months.php";

	//	Check if user is subscribed for month(s)
	$currentMonth = intval(date("m"));
	$sql = mysqli_query($CONNECTION,"SELECT `tbl2`.*, CONCAT(`images`.imageID,'.',`images`.`extension`) AS image FROM (SELECT `tbl`.*, COUNT(`reviews`.workshopID) AS reviews, COALESCE(SUM(`reviews`.rating) / COUNT(`reviews`.`workshopID`),0) AS rating FROM (SELECT `worksh`.*, COUNT(`wishlist`.workshopID) AS wishlist FROM (SELECT `user`.*, `workshops`.heading_".$USER->lang." AS heading, `workshops`.subheading_".$USER->lang." AS subheading, `workshops`.date_publish, `workshops`.date_end, `workshops`.views FROM (SELECT * FROM `boughtworkshops` WHERE BINARY username = '".$USER->username."') user INNER JOIN `workshops` ON `user`.`workshopID` = `workshops`.`workshopID`) worksh LEFT OUTER JOIN `wishlist` ON `worksh`.workshopID = `wishlist`.`workshopID` GROUP BY `worksh`.workshopID) tbl LEFT OUTER JOIN reviews ON `reviews`.workshopID = `tbl`.workshopID AND `reviews`.status = 1 GROUP BY `tbl`.workshopID) tbl2 LEFT OUTER JOIN `images` ON `tbl2`.workshopID = `images`.`workshopID` AND `images`.`im_index` = 1 ORDER BY date_publish DESC");
	if (mysqli_num_rows_wrapper($sql)) 
	{
		$bought_workshops = true;
		$i = 0; $WBOUGHT = array();
		while($t = mysqli_fetch_object_wrapper($sql)) $WBOUGHT[$i++] = $t;

	} else $bought_workshops = false;

	$GET_month = isset($_GET['month']) && trim($_GET['month'])!=="" && is_numeric($_GET['month']) && intval($_GET['month'])>0 && intval($_GET['month'])<13;

	$sql = mysqli_query($CONNECTION,"SELECT * FROM subscriptions WHERE BINARY username = '".$USER->username."' AND active = 1");
	$subscriptions = NULL;
	if (mysqli_num_rows_wrapper($sql) || $USER->grant_access)
	{
		if ($USER->grant_access) 
		{
			$sql = mysqli_query($CONNECTION,"SELECT `wshrw`.*, CONCAT(`images`.`imageID`,'.',`images`.`extension`) AS image, MONTH(`wshrw`.date_publish) AS w_month FROM (SELECT `wshr`.*, COUNT(`wishlist`.workshopID) AS wishlist FROM (SELECT `wsh`.*, COUNT(`reviews`.`workshopID`) AS reviews, COALESCE(SUM(`reviews`.`rating`) / COUNT(`reviews`.`workshopID`),0) AS rating FROM (SELECT workshopID, heading_".$USER->lang." AS heading, subheading_".$USER->lang." AS subheading, date_publish, date_end, forsale, sale, views, price_".$USER->currencyID." AS price, video_id AS videoID FROM workshops WHERE `workshops`.active = 1 AND `workshops`.date_publish <= CURDATE()) wsh LEFT OUTER JOIN reviews ON `reviews`.`workshopID` = `wsh`.workshopID AND `reviews`.status = 1 GROUP BY `wsh`.workshopID) wshr LEFT OUTER JOIN `wishlist` ON `wishlist`.`workshopID` = `wshr`.workshopID GROUP BY `wshr`.workshopID) wshrw LEFT OUTER JOIN images ON `wshrw`.workshopID = `images`.workshopID AND `images`.`im_index` = 1 ".($GET_month ? "WHERE `wshrw`.w_month = '".$_GET['month']."'" : "")." ORDER BY `wshrw`.rating DESC");
		} else
		{
			$subscriptions = array(); $h = 0;
			while($t = mysqli_fetch_object_wrapper($sql)) $subscriptions[$h++] = $t;
			$query = "";
			for ($i=0; $i<count($subscriptions); $i++)
			{
				$query .= ($i>0 ? " OR " : "")."(date_publish >= '".$subscriptions[$i]->date_start."' AND ( date_end = '0000-00-00' OR ( date_end != '0000-00-00' AND date_end <= '".$subscriptions[$i]->date_end."'))) ";
			};
			$sql = mysqli_query($CONNECTION,"SELECT `tbl7`.*, `coupons`.`couponID` FROM (SELECT `tbl6`.* FROM (SELECT `outer_tbl`.*, `subscriptions`.subscriptionID, MONTH(`outer_tbl`.date_publish) AS w_month FROM (SELECT `wshrw`.*, CONCAT(`images`.`imageID`,'.',`images`.`extension`) AS image FROM (SELECT `wshr`.*, COUNT(`wishlist`.workshopID) AS wishlist FROM (SELECT `wsh`.*, COUNT(`reviews`.`workshopID`) AS reviews, COALESCE(SUM(`reviews`.`rating`) / COUNT(`reviews`.`workshopID`),0) AS rating FROM (SELECT workshopID, heading_".$USER->lang." AS heading, subheading_".$USER->lang." AS subheading, date_publish, date_end, forsale, sale, views, price_".$USER->currencyID." AS price, video_id AS videoID FROM workshops WHERE active = 1 AND forsale = 0) wsh LEFT OUTER JOIN reviews ON `reviews`.`workshopID` = `wsh`.workshopID AND `reviews`.status = 1 GROUP BY `wsh`.workshopID) wshr LEFT OUTER JOIN `wishlist` ON `wishlist`.`workshopID` = `wshr`.workshopID GROUP BY `wshr`.workshopID) wshrw LEFT OUTER JOIN images ON `wshrw`.workshopID = `images`.workshopID AND `images`.`im_index` = 1 ORDER BY `wshrw`.rating DESC) outer_tbl LEFT OUTER JOIN `subscriptions` ON `outer_tbl`.date_publish >= `subscriptions`.date_start AND `subscriptions`.`date_end`>= `outer_tbl`.date_end AND CURDATE() >= `outer_tbl`.date_publish AND username = '".$USER->username."') tbl6) tbl7 LEFT OUTER JOIN `coupons` ON `coupons`.`workshopID` = `tbl7`.workshopID AND `coupons`.username = '".$USER->username."' ".($GET_month ? "WHERE `tbl7`.w_month = '".$_GET['month']."'" : "")." ORDER BY `tbl7`.w_month ASC");
		}
		$i = 0; $WAVAILABLE = array();
		while($t = mysqli_fetch_object_wrapper($sql)) $WAVAILABLE[$i++] = $t;
	};


?>

<!DOCTYPE html>
<html>
<head>
<?php print_HTML_data("head","user/workshops") ?>
</head>

<body class="<?= $bodyClass ?>">
	<?php printTopMenu(); ?>	
	<?php printMainMenu(2); ?>
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main margin-b-60">			
		<?php printNavigation(1,[$lang->my_workshops]); ?>
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header heading"><?php echo $lang->my_workshops ?></h1>
			</div>
		</div><!--/.row-->		

		<?php if ($USER->grant_access) { ?>
	
			<div class="row">
				<div class="col-md-4 col-xs-12 col-sm-12">
					<div class="panel bg-success">
						<div class="panel-body">
							<h4 style="color: white; margin: 0" class="strong"><i class="fa fa-check"></i> <?= $lang->accessAllVideos ?></h4>
						</div>
					</div>
				</div>
			</div>

		<?php }; ?>


		<?php if ($bought_workshops) { ?>

		<div class="row">
			<div class="col-lg-12">
				<h3 class="page-header heading strong"><?php echo $lang->allowed_WS ?></h3>
			</div>
		</div><!--/.row-->
			
		<div class="row">			
			<?php for ($i=0; $i < count($WBOUGHT); $i++) { 

			$grantAccess = false;
	//      Check if there are bought workshops
			$sql = mysqli_query($CONNECTION,"SELECT * FROM (SELECT `payments`.trans_date AS date1 FROM boughtworkshops LEFT OUTER JOIN payments ON `boughtworkshops`.paymentID = `payments`.paymentID WHERE `boughtworkshops`.username = '".$USER->username."' AND `boughtworkshops`.workshopID = '".$WBOUGHT[$i]->workshopID."') tbl WHERE date1<=CURDATE()");
			if (mysqli_num_rows_wrapper($sql)) { $grantAccess = true; } else
			{
				$sql = mysqli_query($CONNECTION,"SELECT * FROM subscriptions WHERE date_start<= CURDATE() AND date_end>=CURDATE() AND username='".$USER->username."' AND active=1 ORDER BY date_end DESC LIMIT 1");
				if (mysqli_num_rows_wrapper($sql)) {	$grantAccess = true; }
			};

			?>
			<div class="col-md-4 col-xs-12 col-sm-12">
				<div class="panel panel-default workshop-ui">
					<div class="panel-heading"><span class="strong"><?= $WBOUGHT[$i]->heading ?></span></div>
					<div class="panel-body image-optimised" style="padding: 0">						
						<img class="workshop-image" src="<?= make_image_content($WBOUGHT[$i]->image, $FILE); ?>" />
						<div class="padding-l-10 padding-t-10 padding-r-10 padding-b-10">
							<?= (strlen($WBOUGHT[$i]->subheading)<=150 ? $WBOUGHT[$i]->subheading : substr($WBOUGHT[$i]->subheading,0,150)."..."); ?>
						</div>
						<div class="text-center margin-b-10 star-color">
							<?php $st = determine_rating($WBOUGHT[$i]->rating) ?>
							<span class="fa <?= $st[0] ?>"></span>
			    			<span class="fa <?= $st[1] ?>"></span>
			    			<span class="fa <?= $st[2] ?>"></span>
			    			<span class="fa <?= $st[3] ?>"></span>
			    			<span class="fa <?= $st[4] ?>"></span>
						</div>

						<div class="margin-t-10 margin-b-20 padding-l-10 padding-r-10 smaller div-inl text-center">
							<?php if ($grantAccess) { ?>
							<a href="video/<?= $WBOUGHT[$i]->workshopID ?>">
								<div class="btn-green btn-ord strong"><i class="fa fa-video-camera"></i> <?php echo $lang->watch_video?></div>
							</a>
							<?php } elseif (!$grantAccess && $WBOUGHT[$i]->forsale) { 

								$sql_pom = mysqli_query($CONNECTION,"SELECT * FROM cart WHERE username='".$USER->username."' AND workshopID = '".$WBOUGHT[$i]->workshopID."'");
								if (mysqli_num_rows_wrapper($sql_pom)) $data_set = 1; else $data_set = 0;
							?>
							<a class="buyBtn" data-workshop-id="<?= $WBOUGHT[$i]->workshopID ?>" data-set="<?= $data_set ?>" href="javascript: void(0)" <?= ($data_set ? "data-placement=\"bottom\" data-toggle=\"tooltip\" title=\"".$lang->inCart."\"" : ""); ?> data-success-mssg="<?= $lang->succAddedInCart ?>">
								<div class="btn-green btn-ord strong <?= ($data_set ? "disabled" : "") ?>" data-added="<?= $lang->inCart ?>"><i class="fa fa-shopping-cart"></i> <?php echo $lang->add_toCart ?></div>
							</a>							
							<?php }; ?>
							<div class="padding-l-10">
							<a href="<?= $FILE; ?>workshop/<?= $WBOUGHT[$i]->workshopID ?>">
								<div class="btn-white bordered btn-ord strong"><i class="fa fa-info"></i> <?php echo $lang->ws_detail ?></div>
							</a>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<span class="text-center text-faded pointer" data-toggle="tooltip" data-placement="bottom" title="<?= $lang->views ?>"><i class="fa fa-eye"></i> <?=  $WBOUGHT[$i]->views ?></span>
		    			<span class="text-center text-faded padding-l-10 pointer" data-toggle="tooltip" data-placement="bottom" title="<?= $lang->wish_list ?>"><i class="fa fa-heart"></i> <?=  $WBOUGHT[$i]->wishlist ?></span>
		    			<span class="text-center text-faded padding-l-10 pointer" data-toggle="tooltip" data-placement="bottom" title="<?= $lang->impressions ?>"><i class="fa fa-comment"></i> <?=  $WBOUGHT[$i]->reviews ?></span>

						
					</div>
				</div>
			</div>
			<?php }; ?>	
		</div>

		<?php }; if ($subscriptions || $USER->grant_access) { ?>

		<div class="row">
			<div class="col-lg-6 col-md-6">
				<div class="div-inl">
				<h3 class="page-header heading strong" style="line-height: inherit; display: inline-block;"><?php echo $lang->allowed_WS ?></h3>
				<?php if ($GET_month) { ?>
				<div class="margin-l-20 div-inl">
					<?= $lang->selectMonth ?> >
					<div>					
						<div class="month-selection sort-options fold margin-t-10 small" style="padding-right: 70px; margin-left: 0; z-index: 203; min-width: 100px">
							<div class="default-option"><?= $lang->c_months[intval($_GET['month'])-1] ?></div>							
							<ul class="options text-left">
								<?php for ($v = $startMonth-1, $print = false; $v<count($lang->c_months); $v++, $print = true) { ?>
								<?php if (!$SUBSCRIPTION_MONTHS[$v] || $v == $_GET['month']-1) continue; ?>
								<li class="link"><a href="<?= $FILE ?>user/workshops?month=<?= ($v+1) ?>"><div><?= $lang->c_months[$v] ?></div></a></li>
								<?php }; ?>	
								<?php if (!$print) { ?>																	
								<li class="small"><?= $lang->no_subsMonth ?></li>
								<?php }; ?>
							</ul>
							<i class="fold fa fa-chevron-down"></i>
							<i class="unfolded fa fa-chevron-up"></i>
						</div>
					</div>					
				</div>	
				<a href="<?= $FILE ?>user/workshops" class="link-ord">			
					<div class="btn-initiator-send small margin-l-20 color-white"><i class="fa fa-bars"></i> <?= $lang->all_workshops ?></div>
				</a>
				<?php }; ?>
				</div>				
			</div>
		</div><!--/.row-->
		

		<?php if (count($WAVAILABLE)) { ?>	
			
			<?php for ($h=0, $month = "", $first_time = true; $h<count($WAVAILABLE); $h++) { 
			if (!$WAVAILABLE[$h]->subscriptionID && !$WAVAILABLE[$h]->couponID) continue;
			if ($WAVAILABLE[$h]->w_month != $month)
			{
				$month = $WAVAILABLE[$h]->w_month;
				if ($SUBSCRIPTION_MONTHS[$month-1]) $SUBSCRIPTION_MONTHS[$month-1] = 2;
			    if (!$first_time) { ?></div><?php }; ?>
			    <?php if (!$GET_month) { ?>
			<div class="row <?= ($h>0 ? "margin-t-40" : "") ?>">
				<div class="col-md-3 col-xs-12 col-sm-12">
					<div class="panel bg-default">
						<div class="panel-body" style="font-size: 120%"><?= $lang->month ?> <span class="strong"><?= $lang->c_months[$month-1] ?></span></div>
					</div>
				</div>
			</div>
			<?php }; ?>
			
			<div class="row">	
			<?php  $first_time = false; }; ?>
			
			<div class="col-md-6 col-xs-12 col-sm-12">
				<div class="panel bg-default border-styled">
					<div class="panel-body" style="padding-top: 0; padding-bottom: 0">
						<div class="row" style="padding: 0">
							<div class="image-optimised col-md-5 col-sm-12 col-xs-12" style="padding: 0">
								<img class="workshop-image h-180" src="<?= make_image_content($WAVAILABLE[$h]->image, $FILE) ?>" width="200" height="180">
							</div>
							<div class="col-md-7 col-xs-12 col-sm-12 margin-t-20">							
								<div class="strong"><a href="<?= $domain ?>workshop/<?= $WAVAILABLE[$h]->workshopID ?>"><?= $WAVAILABLE[$h]->heading ?></a></div>
								<div class="smaller"><i class="fa fa-calendar"></i> <?= make_date(-1,$WAVAILABLE[$h]->date_publish) ?></div>
								<?php if ($WAVAILABLE[$h]->couponID) { ?>
								<div class="margin-b-20 margin-t-20">
									<div class="coupon-placeholder text-center">
										<div class="scissors"><img src="<?= $FILE ?>img/scissors.png"></div>
										<div class="strong smaller"><i class="fa fa-bell-o"></i> <?= $lang->have_coupon ?></div>
									</div>
								</div>
								<?php }; ?>
								<div class="small margin-t-10">
									<?= substr($WAVAILABLE[$h]->subheading,0,100)."..."; ?>
								</div>								
								<div class="margin-t-10 div-inl">
									<a href="<?= $FILE ?>user/video/<?= $WAVAILABLE[$h]->workshopID ?>">
										<div class="btn-green small btn-ord"><i class="fa fa-video-camera"></i> <?php echo $lang->watch_video ?></div>
									</a>
									<a href="<?= $FILE ?>workshop/<?= $WAVAILABLE[$h]->workshopID ?>">
										<div class="btn-white btn-ord bordered smaller"><i class="fa fa-paint-brush"></i> <?php echo $lang->ws_lowchar ?></div>
									</a>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-xs-12 col-sm-12 padding-t-10 padding-b-10">
								<div class="small">
									<span class="strong"><i class="fa fa-star star-color"></i> <?= ($WAVAILABLE[$h]->rating==0 ? "<span class=\"small relative\" style=\"top: -1px\">".$lang->no_rating."</span>" : round($WAVAILABLE[$h]->rating, 2)); ?></span>
									<span class="padding-l-10"><i class="fa fa-eye"></i> <?= $WAVAILABLE[$h]->views ?></span>
									<span class="padding-l-10"><i class="fa fa-heart"></i> <?= $WAVAILABLE[$h]->wishlist ?></span>
									<span class="padding-l-10">
										<i class="fa fa-comments"></i> <?= $WAVAILABLE[$h]->reviews ?>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>			
		<?php }; ?>
		</div>
		<?php for ($h=0; $h<count($SUBSCRIPTION_MONTHS) && !$GET_month; $h++) { ?>
		<?php if ($SUBSCRIPTION_MONTHS[$h]===1) { ?>
		<div class="row margin-t-10">
			<div class="col-md-5 col-xs-12 col-sm-12">
				<div class="panel bg-default">
					<div class="panel-body">
						<div style="font-size: 120%"><?= $lang->month ?> <span class="strong"><?= $lang->c_months[$h] ?></span></div>
						<div class="padding-t-10 small"><?= $lang->currNoWSMonth ?></div>
					</div>
				</div>
			</div>
		</div>
		<?php }; ?>
		<?php }; ?>
		<?php } else { ?>
		<div class="row">
			<div class="col-md-4 col-xs-12 col-sm-12">
				<div class="panel bg-default">
					<div class="panel-body">
						<div class="strong" style="font-size: 120%"><i class="fa fa-ban"></i> <?= $lang->no_WS ?></div>
						<div class="small padding-t-5"><?= $lang->currNoWS ?></div>
					</div>
				</div>
			</div>
		</div>
		<?php }; ?>

		<?php } elseif (!$subscriptions && !$bought_workshops) { ?>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<span class="strong"><i class="fa fa-ban"></i> <?php echo $lang->noWS_ATM ?>.</span>
						</div>
						<div class="panel-body">
							<?php echo $lang->no_WSOnyourAcc ?> <a class="link-ord" href="<?= $domain ?>packages"><?php echo $lang->pachages_low ?></a>.
							<div class="margin-t-10 margin-b-10">
								<a href="<?= $domain; ?>workshops"><div class="btn-ord btn-green small"><i class="fa fa-paint-brush"></i> <?php echo $lang->all_workshops ?></div></a>
							</div>
						</div>						
					</div>
				</div>
			</div>
		<?php }; ?>

						
	</div>	<!--/.main-->
	

	<?php print_HTML_data("script","user/workshops") ?>
</body>

</html>
