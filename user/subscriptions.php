<?php 

	session_start();
//	Prep for include
	$prepath = '../';
	include $prepath."requests/userManagementCheck.php";
	$INCLUDE = (object) array("getDATA" => false);
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

	$prepath = $domain;

	$sql = mysqli_query($CONNECTION,"SELECT * FROM payments WHERE BINARY username = '".$USER->username."'");
	if (mysqli_num_rows_wrapper($sql)) 
	{
		$has_subscr = true;
		$SUBSCRIPTIONS = array(); $i=0;
		while($t = mysqli_fetch_object_wrapper($sql)) $SUBSCRIPTIONS[$i++] = $t;

	} else $has_subscr = false;


?>

<!DOCTYPE html>
<html>
<head>
<?php print_HTML_data("head","subscriptions"); ?>
</head>

<body class="<?= $bodyClass ?>">

	<?php printTopMenu(); ?>		
	<?php printMainMenu(5); ?>
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main margin-b-60">			
		<?php printNavigation(1,[$lang->all_subs]); ?>
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header heading"><?php echo $lang->all_subs ?></h1>
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12">
				<div class="panel bg-default"> 
					<div class="panel-body">
						<?php echo $lang->allSubs_List ?>
					</div>
				</div>

				<div class="panel bg-default">
					<div class="panel-body" style="padding: 0">
						<div class="table padding-b-10 border-b-shadow-eff">
							<div class="table-row padding-t-10 padding-l-20 padding-r-20">
								<div class="row">
									<div class="col-md-8 col-xs-12 col-sm-12">
										<div class="padding-t-s"><?php echo $lang->data_product ?></div>
									</div>
									<div class="col-md-2 col-xs-6 col-sm-6">
										<?php echo $lang->status ?>
									</div>
									<div class="col-md-2 col-xs-6 col-sm-6">
										<?php echo $lang->sales ?>
									</div>
								</div>								
							</div>
						</div>
						<div class="table-row padding-l-20 padding-b-40 padding-r-20 relative">
							<?php if (!$has_subscr) { ?>
							<div class="row">
								<div class="col-md-12 col-xs-12 col-sm-12 margin-b-20 strong">
									<?php echo $lang->noSubs_ATM ?>.
								</div>
							</div>
							<?php } else { // Ispis pretplata ?>
							<?php for ($i=0; $i<count($SUBSCRIPTIONS); $i++) { // FOR ?>
							<div class="subscription relative margin-t-20" sid="<?= $SUBSCRIPTIONS[$i]->subscriptionID ?>">							
							<?php if (0) { ?>
							<div class="deactivate-subscription">
								<div class="cont padding-l-20">
									<h4 class="strong"><i class="fa fa-power-off"></i> <?php echo $lang->deactivate_sub ?>?</h4>
									<div class="small"><?php echo $lang->if_youDeactivateSub ?>.</div>
									<div class="margin-t-10 div-inl">
										<div class="btn-deactivate-subscription btn-red btn-ord small"><i class="fa fa-power-off"></i> <?php echo $lang->deact_yourSub ?></div>
										<div class="margin-md-t-10">
											<a class="btn_deactivate_subscription_decl link-ord padding-l-10 small" href="javascript: void(0)">
												<i class="fa fa-times"></i> <?php echo $lang->cancel ?>
											</a>
										</div>
									</div>
								</div>
							</div>
							<?php }; ?>
							
							<div class="row padding-b-10">								
								<div class="col-md-8 col-xs-12 col-sm-12 <?= ($showClss ? "subscription-disabled" : "") ?>">
									<div>
										<span><i class="fa fa-file"></i> <?php echo $lang->payment ?> - IF - <?= $SUBSCRIPTIONS[$i]->receiptID ?></span></span>
									</div>																	
									<div class="padding-t-10 small">										
										<div class="smaller uppercase">
											<?php echo $lang->payment_amount ?>: <span class="strong"><?= print_money_PLAINTXT($SUBSCRIPTIONS[$i]->payment_amount,2) ?> <?= print_curr($SUBSCRIPTIONS[$i]->trans_currencyID); ?></span>
										</div>	
										<div class="smaller uppercase">
											<?php echo $lang->payment_date ?>: <span class="strong"><?= make_date(-1,$SUBSCRIPTIONS[$i]->trans_date) ?></span>
										</div>																													
									</div>
								</div>
								<div class="col-md-2 col-xs-5 col-sm-6 margin-md-t-10">
									
									<span class="sub-status strong small uppercase">
										<?php if ($SUBSCRIPTIONS[$i]->paid) { ?>
										<span class="text-success">
											<i class="fa fa-circle relative" style="font-size: 60%; top: -3px;"></i> <span><?php echo $lang->paid ?></span>
										</span>
										<?php } else { ?>
										<span class="text-danger">
											<i class="fa fa-circle-o relative" style="font-size: 60%; top: -3px;"></i> <span><?php echo $lang->in_process ?></span>
										</span>
										<?php }; ?>
									</span>													
									
									<?php if ($SUBSCRIPTIONS[$i]->payment_method == 2) { ?>
									<div class="smaller strong">
										<i class="fa fa-paypal"></i> <?php echo $lang->paypal ?>
									</div>
									<?php } elseif ($SUBSCRIPTIONS[$i]->payment_method == 1) { ?>
									<div class="smaller strong">
										<i class="fa fa-credit-card"></i> <?php echo $lang->credit_card ?>
									</div>
									<?php } elseif ($SUBSCRIPTIONS[$i]->payment_method == 3) { ?>
									<div class="smaller strong">
										<i class="fa fa-money"></i> <?php echo $lang->cash ?>
									</div>
									<?php }; ?>

									<div class="margin-t-10 <?= ($SUBSCRIPTIONS[$i]->active==0 && $SUBSCRIPTIONS[$i]->user_deact==1 ? "" : "dn") ?>">
										<a class="renewSub smaller link-ord" href="javascript:void(0)"><i class="fa fa-refresh"></i> <?php echo $lang->activate_sub ?></a>
									</div>								
								</div>
								<div class="col-md-2 col-xs-7 col-sm-6 margin-md-t-10">							
									<a href="<?= $domain ?>user/receipt/<?= $SUBSCRIPTIONS[$i]->paymentID ?>">
									<div class="subscription-receipt btn-green btn-ord small btn-block text-center"><i class="fa fa-bars"></i> <?php echo $lang->invoice_view ?></div>
									</a>
								</div>
							</div>
							</div>					
							<?php if ($i<count($SUBSCRIPTIONS)-1) { ?>
							<div class="margin-t-10 margin-b-20">
								<div class="divider width-100"></div>
							</div>
							<?php }; ?>
							<?php }; 	 // for ($SUBSCRIPTIONS) { ... } ?>
						<?php };  		// if ($has_subscr) { ... } ?>						
						</div>
					</div>
				</div>
			</div>
		</div>

					
	</div>	<!--/.main-->
	

	<?php print_HTML_data("script","subscriptions"); ?>
</body>

</html>
