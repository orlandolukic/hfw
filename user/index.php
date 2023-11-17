<?php 
	session_start();
	$prepath  = '../';
	$REDIRECT = false;
	$page     = "index";
    include $prepath."connect.php";
    include $prepath."functions.php";
	include $prepath."requests/userManagementCheck.php";


	if ($_SESSION['lang'] !== "EN")
	{
		include $prepath.strtolower($_SESSION['lang'])."/global.php";
		include $prepath.strtolower($_SESSION['lang'])."/getDATA.php";
	} else
	{
		include $prepath."global.php";
		include $prepath."getDATA.php";
	};

//	Prep for include
	if (!isset($_SESSION['self']) || (isset($_SESSION['self']) && $_SESSION['self']===false))
	$ACTIONS  = (object) array("redirect"    => true, 
	                           "execute"     => true, 
	                           "valid"       => false,	                           
	                           "redirection" => (object) array("SQL_error"     => $FILE,
	                                                           "GET_error"     => "",
	                                                           "urlToPostData" => true,
	                                                           "error_params"  => $FILE."user/",
	                                                           "success_req"   => $FILE."user/",
	                                                           "SQL_exsist"    => $FILE."user/" 
	                                                           ));

	include $prepath."pages.php";
	include $prepath."lang/func.php";
	include $prepath."lang/user_".strtolower($lang_acr).".php";
	include $prepath."requests/userManagement.php";

	if (!isset($_SESSION['self']) || isset($_SESSION['self']) && $_SESSION['self']===false) {
        include $prepath . "requests/actions.php";
    }

	$SUBSCRIPTION = (object) array();
	$sql = mysqli_query($CONNECTION,"SELECT `subscriptions`.*, `packages`.packageID, `packages`.text_".$USER->lang." AS name, `packages`.flag, `packages`.price_".$USER->currencyID." FROM subscriptions INNER JOIN `packages` ON type=`packages`.packageID WHERE username='".$USER->username."' AND CURDATE()<=date_end AND CURDATE()>=date_start AND `subscriptions`.active=1 ORDER BY date_end DESC LIMIT 1");
	if (mysqli_num_rows_wrapper($sql)) 
	{ 
		$SUBSCRIPTION->found = true; 
		while($t = mysqli_fetch_object_wrapper($sql)) $SUBSCRIPTION->data = $t;
	} else $SUBSCRIPTION->found = false;

//	Show sucess payment
	if (isset($_SESSION["show_succ_payment"]))
	{
		$showSuccPayment = true;
		$showSuccPaymentObj = $_SESSION['show_succ_payment'];
	} else $showSuccPayment = false;

	if (isset($_SESSION['redirection']))
	{
		$sql = mysqli_query($CONNECTION,"SELECT heading_".$USER->lang." AS heading, workshopID FROM workshops WHERE workshopID = '".$_SESSION['redirection']."'");
		while($t = mysqli_fetch_object_wrapper($sql)) $wsh_redirection = $t;
		$sh_redirection = true;
		unset($_SESSION['redirection']);
		unset($_SESSION['self']);
	} else $sh_redirection = false;	

	$sqlINACTIVE_SUB = mysqli_query($CONNECTION,"SELECT `tbl`.* , `packages`.`name_".$USER->lang."` AS name FROM (SELECT `subscriptions`.* FROM `subscriptions` WHERE BINARY username='".$USER->username."' AND user_deact=1 AND active=0) tbl INNER JOIN `packages` ON `tbl`.`type` = `packages`.`packageID` ORDER BY tbl.date_end DESC LIMIT 1");

//	GET TOP workshops, joined with subscriptions
	$sql = mysqli_query($CONNECTION,"SELECT `tbl7`.*, `coupons`.`couponID` FROM (SELECT `tbl6`.*, `cart`.itemID FROM (SELECT `tbl5`.*, `subscriptions`.subscriptionID, `subscriptions`.`date_start` AS subscription_start, `subscriptions`.`date_end` AS subscription_end, MONTH(`tbl5`.date_publish) AS w_month FROM (SELECT `tbl4`.*, CONCAT(`images`.imageID,'.',`images`.`extension`) AS image FROM (SELECT `tbl3`.*, COUNT(`wishlist`.`workshopID`) AS wishlist FROM (SELECT `tbl2`.*, COUNT(`wishlist`.`username`) AS self_wishlist FROM (SELECT `tbl`.*, COALESCE(SUM(`reviews`.rating) / COUNT(`reviews`.`workshopID`), 0) AS rating, COUNT(`reviews`.workshopID) AS reviews FROM (SELECT workshopID, heading_".$USER->lang." AS heading, subheading_".$USER->lang." AS subheading, date_publish, date_end, views, forsale FROM workshops WHERE active = 1) tbl LEFT OUTER JOIN reviews ON `reviews`.`workshopID` = `tbl`.workshopID GROUP BY `tbl`.workshopID) tbl2 LEFT OUTER JOIN `wishlist` ON `tbl2`.workshopID = `wishlist`.workshopID AND `wishlist`.username = '".$USER->username."' GROUP BY `tbl2`.workshopID) tbl3  LEFT OUTER JOIN `wishlist` ON `wishlist`.`workshopID` = `tbl3`.workshopID GROUP BY `tbl3`.workshopID) tbl4 LEFT OUTER JOIN images ON `tbl4`.workshopID = `images`.`workshopID` AND `images`.im_index = 1) tbl5 LEFT OUTER JOIN `subscriptions` ON `subscriptions`.date_start <= `tbl5`.date_publish AND `subscriptions`.`date_end` >= `tbl5`.date_end AND `subscriptions`.`username` = '".$USER->username."' AND `subscriptions`.active = 1) tbl6 LEFT OUTER JOIN `cart` ON `cart`.start_month = `tbl6`.w_month AND `cart`.username = '".$USER->username."' WHERE CURDATE() >= `tbl6`.date_publish) tbl7 LEFT OUTER JOIN `coupons` ON `coupons`.`workshopID` = `tbl7`.workshopID AND BINARY `coupons`.`username` = '".$USER->username."' AND `coupons`.`experation_date` >= CURDATE() ORDER BY `tbl7`.`date_publish` DESC, `tbl7`.rating DESC LIMIT 3");
	$TW = array(); $y=0;
	while($t = mysqli_fetch_object_wrapper($sql)) $TW[$y++] = $t;	

	$months = array();
	for($i = 0;$i<12;$i++) {
		$sql = mysqli_query($CONNECTION,"SELECT * FROM cart WHERE BINARY start_month= '".($i+1)."' AND username='".$USER->username."'");
		$months[$i] = mysqli_num_rows_wrapper($sql);
	}

	//	Check if user is subscribed for month(s)
	$currentMonth = intval(date("m"));
	$SUBSCRIPTION_MONTHS = array(0,0,0,0,0,0,0,0,0,0,0,0);
	$sql = mysqli_query($CONNECTION,"SELECT `subscriptions`.*, `packages`.flag FROM subscriptions LEFT OUTER JOIN packages ON `packages`.packageID = `subscriptions`.type WHERE BINARY username = '".$USER->username."' AND `subscriptions`.active = 1");
	if (mysqli_num_rows_wrapper($sql))
	{
		$k = 0;
		while($t = mysqli_fetch_object_wrapper($sql))
		{
			$date_start = $t->date_start; $date_end = $t->date_end;
			$M_start = intval(part_date("M", $date_start)); $M_end = intval(part_date("M", $date_end))+1;		
			if (intval($t->flag) > 1)
			{
				for ($j=$M_start-1; $j<$M_end-1; $j++) $SUBSCRIPTION_MONTHS[$j] = array(0 => 1, 1 => $t->paymentID);			
			} else
			{
				$SUBSCRIPTION_MONTHS[$M_start-1] = array(0 => 1, 1 => $t->paymentID);
			}
		}
	}



?>

<!DOCTYPE html>
<html>
<head>
<?php print_HTML_data("head","user/index") ?>
</head>

<body class="<?= $bodyClass ?>">

	<?php printTopMenu(); ?>		
	<?php printMainMenu(); ?>
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main margin-b-60">			
		<?php printNavigation(); ?>
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header heading"><?php echo $lang->user_pannel ?></h1>
			</div>
		</div><!--/.row-->

		<?php if ($sh_redirection) { ?>
		<div class="row">
			<div class="col-md-6 col-xs-12 col-sm-12">
				<div class="panel bg-info">
					<div class="panel-body">
						<div class="strong" style="font-size: 130%"><i class="fa fa-info-circle"></i> <?php echo $lang->notification ?></div>
						<div class="margin-t-10"><?php echo $lang->have_alreadyBought ?> <span class="strong"><?= $wsh_redirection->heading ?></span> <?= mb_strtolower($lang->workshop_u) ?>.</div>
						<div class="margin-t-10">
							<a href="<?= $FILE ?>user/video/<?= $wsh_redirection->workshopID ?>">
								<div class="btn-white btn-ord bordered small"><i class="fa fa-video-camera"></i> <?php echo $lang->check_workshop ?></div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php }; ?>

		<?php 
		// Inactive subscription
		if (0) { ?>
		<div id="inactiveSubscription" class="row">
			<div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
				<div class="panel">
					<div class="panel-heading bg-warning" style="padding-left: 10px; padding-top: 10px; font-weight: bold;">
						<span class="padding-l-10"><i class="fa fa-refresh"></i> <?php echo $lang->inactive_payment ?></span>
					</div>
					<div class="panel-body">
						<div class="padding-b-20">
							<?php echo $lang->payment_whichis ?> <span class="strong"><?php echo $lang->currently ?></span> <?php echo $lang->inactive ?>. 
							<div class="margin-t-10">
								<?php while($pom = mysqli_fetch_object_wrapper($sqlINACTIVE_SUB)) $inactiveSub = $pom; ?>
								 <div class="row">
								 	<div class="col-md-3 col-xs-6 col-sm-6 smaller uppercase"><?php echo $lang->name ?></div>
								 	<div class="md-db dn col-xs-6 col-sm-6 op-4"><?= $inactiveSub->name ?></div>
								 	<div class="col-md-4 col-xs-6 col-sm-6 smaller uppercase"><?php echo $lang->date_start ?></div>
								 	<div class="md-db dn col-xs-6 col-sm-6 op-4"><?= make_date(-1, $inactiveSub->date_start) ?></div>
								 	<div class="col-md-3 col-xs-6 col-sm-6 smaller uppercase"><?php echo $lang->date_expire ?></div>
								 	<div class="md-db dn col-xs-6 col-sm-6 strong"><?= make_date(-1,$inactiveSub->date_end) ?></div>
								 	<div class="col-md-2 col-xs-6 col-sm-6 smaller uppercase text-error">
								 		<span class="md-dn"><i class="fa fa-arrow-down"></i></span>
								 	</div>
								 	<div class="md-db dn col-xs-6 col-sm-6 text-error">
								 		<span class="md-inlb"><i class="fa fa-arrow-down"></i></span>
								 		<?php 
								 			$dts = strtotime($inactiveSub->date_start);
								 			$dte = strtotime($inactiveSub->date_end);
								 			echo ($dte-$dts)/(60*60*24);
								 		?>								 		
								 	</div>								 	
								 </div>
								 
								 <div class="row md-dn">
									 <div class="divider"></div>
									 <div class="col-md-3 col-xs-12 col-sm-12 strong op-4">
									 	<?= $inactiveSub->name ?>
									 </div>
								 	<div class="col-md-4 col-xs-12 col-sm-12 small op-4"><?= make_date(-1, $inactiveSub->date_start) ?></div>
								 	<div class="col-md-3 col-xs-12 col-sm-12 strong"><?= make_date(-1,$inactiveSub->date_end) ?></div>
								 	<div class="col-md-2 col-xs-12 col-sm-12 strong text-error"><?= ($dte-$dts)/(60*60*24) ?></div>
								 </div>
						
							</div>
						</div>						
					</div>
					<div class="panel-footer padding-t-none text-right">											
						<a id="actSub" data-subscr-id="<?= $inactiveSub->subscriptionID ?>" href="javascript: void(0)">
							<div class="btn-green btn-ord small margin-t-10">
								<i class="fa fa-check-square"></i> <span class="padding-l-5"><?php echo $lang->activate_sub ?></span>
							</div>
						</a>						
					</div>
				</div>
			</div>
		</div>
		 <?php }; ?> 

		<?php if ($showSuccPayment) { 
			$sql = mysqli_query($CONNECTION,"SELECT * FROM payments WHERE paymentID = '".$showSuccPaymentObj->paymentID."' AND username = '".$USER->username."' LIMIT 1");
			$res = mysqli_fetch_object_wrapper($sql);

		?>
		<div class="panel bg-default">
			<div class="panel-heading">
				<span class="strong color-theme">
				<?php if ($res->payment_method == 2) { ?>
					<i class="fa fa-paypal"></i> <?php echo $lang->payment_successfull ?>
				<?php } elseif ($res->payment_method == 1) { ?>
					<i class="fa fa-credit-card"></i> <?php echo $lang->payment_successfull ?>
				<?php }; ?>
				</span>
			</div>
			<div class="panel-body">
				<?php echo $lang->cart_emptyCanProceed ?>
				<div class="margin-t-20">
					<div class=""><i class="fa fa-file"></i> <?php echo $lang->payment_details ?></div>
					<div class="padding-t-10">
						<table>
							<tr>
								<td width="120" class="uppercase small"><?php echo $lang->payment_ID ?></td>
								<td class="strong"><?= $res->paymentID ?></td>
							</tr>
							<tr>
								<td width="120" class="uppercase small"><?php echo $lang->invoice?></td>
								<td class="strong">IF-<?= substr($showSuccPaymentObj->paymentID,4,10) ?></td>
							</tr>
							<tr>
								<td width="120" class="uppercase small"><?php echo $lang->just_date ?></td>
								<td class="strong"><?= make_date(-1,$res->trans_date) ?></td>
							</tr>
							<tr>
								<td width="120" class="uppercase small"><?php echo $lang->ammount ?></td>
								<td class="strong"><?= print_money_PLAINTXT($res->payment_amount,2) ?></td>
							</tr>
							<tr>
								<td width="120" class="uppercase small"><?php echo $lang->currency ?></td>
								<td class="strong"><?= $res->trans_currencyID ?></td>
							</tr>
						</table>
					</div>
					<div class="margin-t-10">
						<a class="link-ord" href="<?= $FILE ?>user/receipt/<?= $res->paymentID ?>">
							<div class="btn-green btn-ord small"><i class="fa fa-bars"></i> <?php echo $lang->show_invoice ?></div>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php }; unset($_SESSION['show_succ_payment']); ?>

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

		<?php } else { ?>

		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12">
				<div class="panel bg-default">
					<div class="panel-heading" style="line-height: inherit;">
						<div class="strong" style="font-size: 120%"><i class="fa fa-shopping-cart"></i> <?= $lang->subscribe_now ?></div>
						<div class="smaller padding-l-30"><?= $lang->chooseMSUB ?></div>
						<div class="divider width-100"></div>
					</div>
					<div class="panel-body container-fluid">
						<?php for ($i=0, $n=0, $go = true; $i<12; $i++, $n++) { ?>	
						<?php if ($n%5===0 && $go) { $n=1; $go = false; ?><div class="row margin-t-20 margin-md-t-none margin-md-b-10 margin-b-40"><?php }; ?>
						<div class="col-md-2 col-lg-2 col-xs-6 col-sm-6 <?= ($i<$startMonth-1 ? "subscription-disabled" : "") ?> text-ceneter margin-md-t-20">
					
							<div class="strong"><?= $lang->c_months[$i]; ?><?= ($SUBSCRIPTION_MONTHS[$i] ? '<i class="fa fa-check-circle color-success relative" style="left: 3px"></i>' : '') ?></div>
							<?php if ($SUBSCRIPTION_MONTHS[$i]) {  ?>
							<div class="color-success strong smaller uppercase"><?= $lang->subscribed ?></div>
							<?php }; ?>				

							<?php if ($SUBSCRIPTION_MONTHS[$i]) { ?>
							<a href="<?= $FILE ?>user/workshops?month=<?= ($i+1) ?>" class="link-ord margin-t-10 small" style="display: inline-block;">			
								<i class="fa fa-video-camera"></i> <?= $lang->videos ?>			
							</a>
						
							<a href="<?= $FILE ?>user/receipt/<?= $SUBSCRIPTION_MONTHS[$i][1] ?>" class="link-ord padding-l-10 small padding-md-l-none" style="display: inline-block;">
								<i class="fa fa-file smaller"></i> <?= $lang->invoice ?>				
							</a>
							<?php } elseif (!$SUBSCRIPTION_MONTHS[$i] && $i>=$startMonth-1) { ?>

							<a class="subscribe-btn <?= ( $months[$i]? "disabled" : "") ?>" href="javascript: void(0)" data-success-message="<?= $lang->succAddedInCart ?>" data-fail-message="<?= $lang->failAddedCart ?>" data-alreadyCart="<?= $lang->inCart ?>" data-toggle="tooltip" data-placement="bottom" title=" <?= ( $months[$i] ? $lang->inCart  : $lang->add_toCart) ?>" data-start-month = "<?= $i+1 ?>"; style="display: inline-block;">				
								<div class="margin-t-10 <?= ( $months[$i]? "disabled" : "") ?> btn-find btn-green btn-ord small"><i class="fa fa-shopping-cart"></i> <span><?= ($months[$i] ? $lang->inCart : $lang->toCart) ?></span></div>						
							</a>
							<?php }; ?>
						</div>
						<?php if ($n%6===0) { $go = true; $n=-1; ?></div><?php }; ?>					
						<?php }; ?>
					</div>
				</div>
			</div>
		</div>

		<?php }; ?>
	

		<?php if (count($TW)) { ?>

		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12"><h2><?php echo $lang->popular_workshops ?></h2></div>
		</div>
		<div class="row">
			<?php for ($i=0; $i < count($TW); $i++) { 

			$grantAccess = false;
	//      Check if there are bought workshops
			$sql = mysqli_query($CONNECTION,"SELECT * FROM (SELECT `payments`.trans_date AS date1 FROM boughtworkshops LEFT OUTER JOIN payments ON `boughtworkshops`.paymentID = `payments`.paymentID WHERE `boughtworkshops`.username = '".$USER->username."' AND `boughtworkshops`.workshopID = '".$TW[$i]->workshopID."') tbl");
			if (mysqli_num_rows_wrapper($sql)) { $grantAccess = true; } else { $grantAccess = (bool) $TW[$i]->subscriptionID; };
			?>
			<div class="col-md-4 col-xs-12 col-sm-12">
				<div class="panel panel-default workshop-ui">
					<div class="panel-heading" style="line-height: 22px;">
						<div class="strong"><?= $TW[$i]->heading ?></div>
						<div class="smaller"><?= $lang->month ?> <span class="strong"><?= $lang->c_months[$TW[$i]->w_month-1] ?></span></div>
					</div>
					<div class="panel-body image-optimised relative" style="padding: 0">
						<div class="eticket-ui uppercase strong">top</div>
						<img class="workshop-image" src="<?= $FILE; ?>img/content/<?= $TW[$i]->image ?>" style="max-height: 260px" />
						<div class="padding-l-10 padding-t-10 padding-r-10 padding-b-10">
							<?= (strlen($TW[$i]->subheading) <= 150 ? $TW[$i]->subheading : substr($TW[$i]->subheading,0,150)."..."); ?>
						</div>
						<?php if ($TW[$i]->couponID) { ?>
						<div class="padding-l-10 padding-r-10 margin-b-10">
							<div class="coupon-placeholder text-center">
								<div class="scissors"><img src="<?= $FILE ?>img/scissors.png"></div>
								<div class="strong smaller"><i class="fa fa-bell-o"></i> You have coupon for this workshop</div>
							</div>
						</div>
						<?php }; ?>
						<div class="text-center margin-b-10 star-color">
							<?php $st = determine_rating($TW[$i]->rating); ?>
							<span class="fa <?= $st[0] ?>"></span>
			    			<span class="fa <?= $st[1] ?>"></span>
			    			<span class="fa <?= $st[2] ?>"></span>
			    			<span class="fa <?= $st[3] ?>"></span>
			    			<span class="fa <?= $st[4] ?>"></span>
						</div>
						<div class="margin-t-10 margin-b-20 padding-l-10 padding-r-10 smaller div-inl text-center">
							<?php if ($grantAccess && $USER->verified || $USER->grant_access) { // Subscribe/Add To Cart Button?>
							<a href="<?= $FILE ?>user/video/<?= $TW[$i]->workshopID ?>">
								<div class="btn-green btn-ord strong"><i class="fa fa-video-camera"></i> <?php echo $lang->watch_video ?></div>
							</a>
							<?php } elseif (!$grantAccess && $USER->verified && $TW[$i]->forsale) { 

								$sql_pom = mysqli_query($CONNECTION,"SELECT * FROM cart WHERE username='".$USER->username."' AND workshopID = '".$TW[$i]->workshopID."'");
								if (mysqli_num_rows_wrapper($sql_pom)) $data_set = 1; else $data_set = 0;
							?>
							<a class="buyBtn" data-workshop-id="<?= $TW[$i]->workshopID ?>" data-set="<?= $data_set ?>" href="javascript: void(0)" <?= ($data_set ? "data-placement=\"bottom\" data-toggle=\"tooltip\" title=\"".$lang->inCart."\"" : ""); ?> data-success-mssg="<?= $lang->succAddedInCart ?>">
								<div class="btn-green btn-ord strong <?= ($data_set ? "disabled" : "") ?>" data-added="<?= $lang->inCart ?>"><i class="fa fa-shopping-cart"></i> <?php echo $lang->add_toCart ?></div>
							</a>
							<?php } elseif (!$TW[$i]->subscriptionID) { // Subscribe for specific month ?>
							<?php if ($TW[$i]->couponID) { ?>
							<a href="<?= $FILE ?>user/video/<?= $TW[$i]->workshopID ?>">
								<div class="btn-green btn-ord strong"><i class="fa fa-video-camera"></i> <?php echo $lang->watch_video ?></div>
							</a>
							<?php } elseif ($TW[$i]->itemID) { ?>
							<a href="javascript: void(0)" data-toggle="tooltip" data-placement="bottom" title="<?= $lang->inCart ?>">
							<div class="btn-green btn-ord <?= ($TW[$i]->itemID ? "disabled" : "") ?>"><i class="fa fa-shopping-cart"></i> <?= $lang->subscribe_for ?> <span class="strong"><?= $lang->c_months[$TW[$i]->w_month-1] ?></span></div></a>								
							<?php } else { ?>
							<a href="<?= $FILE ?>user/addCart/subscription?month=<?= $TW[$i]->w_month ?>&cid=1">
								<div class="btn-green btn-ord <?= ($TW[$i]->itemID ? "disabled" : "") ?>"><i class="fa fa-shopping-cart"></i> <?= $lang->subscribe_for ?> <span class="strong"><?= $lang->c_months[$TW[$i]->w_month-1] ?></span></div>
							</a>
							<?php }; ?>
							<?php }; ?>
							<div class="padding-l-10">
							<a href="<?= $domain; ?>workshop/<?= $TW[$i]->workshopID ?>">
								<div class="btn-white bordered btn-ord strong"><i class="fa fa-info"></i> <?php echo $lang->ws_detail ?></div>
							</a>
							</div>
						</div>						
					</div>
					<div class="panel-footer">
						<span class="text-center text-faded pointer" data-toggle="tooltip" data-placement="bottom" title="<?= $lang->views ?>"><i class="fa fa-eye"></i> <?= $TW[$i]->views ?></span>
		    			<span class="text-center text-faded padding-l-10 pointer" data-toggle="tooltip" data-placement="bottom" title="<?= $lang->wish_list ?>"><i class="fa fa-heart"></i> <?= $TW[$i]->wishlist ?></span>
		    			<span class="text-center text-faded padding-l-10 pointer" data-toggle="tooltip" data-placement="bottom" title="<?= $lang->impressions ?>"><i class="fa fa-comment"></i> <?= $TW[$i]->reviews ?></span>

		    			<?php 		    				
		    				$sql__ = mysqli_query($CONNECTION,"SELECT * FROM boughtworkshops WHERE BINARY username = '".$USER->username."' AND BINARY workshopID = '".$TW[$i]->workshopID."'");
		    				if (!$TW[$i]->subscriptionID && !mysqli_num_rows_wrapper($sql__) && !$TW[$i]->couponID && !$USER->grant_access ) { ?>
		    			<span class="pull-right smaller"><a class="link addWishList <?= ((bool) $TW[$i]->self_wishlist ? "addedWishList strong" : ""); ?>" data-set="<?= ((bool) $TW[$i]->self_wishlist ? "1" : "0") ?>" data-workshop-id="<?= $TW[$i]->workshopID ?>" data-successfully-added="<?= $lang->addedWishList ?>" href="javascript: void(0)"><i class="fa fa-heart"></i> <span><?= ($TW[$i]->self_wishlist ?  $lang->addedWishList : $lang->addToWishList); ?></span></a></span>
		    			<?php }; ?>
					</div>
				</div>
			</div>
			<?php }; ?>		
		</div>		
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
	</div>	<!--/.main-->
	

<?php print_HTML_data("script","user/index") ?>
</body>

</html>
