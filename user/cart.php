<?php

	session_start();
//	Prep for include
	$prepath  = '../';
	$page 	 = "cart";
	$REDIRECT = false;
	$INCLUDE  = (object) array("getDATA" => true);
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
	}
	include $prepath."pages.php";
	include $prepath."lang/func.php";
	include $prepath."lang/user_".strtolower($lang_acr).".php";
	include $prepath."requests/userManagement.php";
	include $prepath."requests/shopping.php";

//	Redirect if user has privileged access
	if ($USER->grant_access) header("location: ".$FILE."user");

//	Payment failed
	if (isset($_GET['paymentStatus']) && trim($_GET['paymentStatus'])==="canceled" || isset($_SESSION['bank_protocol_payment_id'])) { 
		$sql = mysqli_query($CONNECTION,"SELECT * FROM payments WHERE BINARY paymentID = '".$_SESSION['bank_protocol_payment_id']."' AND BINARY username = '".$USER->username."' AND paid = 0");
		$f = $_SESSION['bank_protocol_payment_id'];
		unset($_SESSION['bank_protocol_payment_id']);
		if (!mysqli_num_rows_wrapper($sql)) header("location: ".$FILE."user/cart");
		$sql = mysqli_query($CONNECTION,"DELETE FROM payments WHERE BINARY paymentID = '".$f."' AND BINARY username = '".$USER->username."'");
	};

	$PINFO = NULL;
	$sql = mysqli_query($CONNECTION,"SELECT * FROM paymentinfo WHERE username = '".$USER->username."' AND defaultInfo = 1 LIMIT 1");
	if (mysqli_num_rows_wrapper($sql))
	{
		$PINFO = mysqli_fetch_object_wrapper($sql);
	};

	$PINFOS = NULL;
	$sql = mysqli_query($CONNECTION,"SELECT * FROM paymentinfo WHERE username = '".$USER->username."' AND piID > 0 ORDER BY defaultInfo DESC");
	if (mysqli_num_rows_wrapper($sql) > 1)
	{
		$PINFOS = array(); $i = 0;
		while($t = mysqli_fetch_object_wrapper($sql)) $PINFOS[$i++] = $t;
	};
	$prepath = $domain;

//	Check if there is available payPal payment
	$payPal = $USER->currencyID != "RSD" && $USER->verified && count($SHOPPING_CART) > 0;

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
				for ($j=$M_start-1; $j<$M_end-1; $j++) $SUBSCRIPTION_MONTHS[$j] = 1;			
			} else
			{
				$SUBSCRIPTION_MONTHS[$M_start-1] = 1;
			}
		}
	}



?>

<!DOCTYPE html>
<html>
<head>
	<?php print_HTML_data("head","cart") ?>
</head>

<body class="<?= $bodyClass ?>">

	<?php printTopMenu(); ?>
	
	<?php printMainMenu(4); ?>
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main margin-b-60">			
		<?php printNavigation(1,[$lang->cart],["javascript: void(0)"]); ?>
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header heading"><?php echo $lang->cart ?></h1>
			</div>
		</div><!--/.row-->	

		<?php if (isset($_GET['paymentStatus']) && trim($_GET['paymentStatus'])==="canceled" || isset($_SESSION['bank_protocol_payment_id'])) { ?>
		<div class="row margin-b-10">
			<div class="col-md-8 col-xs-12 col-sm-12">
				<div class="panel bg-danger">
					<div class="panel-body">
						<a href="javascript: void(0)" class="times pull-right" style="position: relative; top: -5px"><i class="fa fa-times"></i></a>
						<div class="uppercase strong"><i class="fa fa-credit-card"></i> <span class="padding-l-10"><?php echo $lang->transaction_denied ?></span></div>						
						<div class="padding-t-10 small">
							<?php if (isset($_POST['ErrMsg'])) { ?>
								<?php echo $_POST['ErrMsg']; ?>
							<?php } else { ?>
								<?php echo $lang->error_paymentBank ?>
							<?php }; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php }; ?>

		<?php if ($cart_number) { ?>
		<div id="cartHasContent" class="row">
			<div class="col-md-8 col-xs-12 col-sm-12">
				<div class="panel panel-default panel-bordered">					
					<div class="panel-body" style="padding: 0">
						<table class="table table-style-hover" style="border-collapse: collapse;">
							<thead>
								<tr class="heading-row">									
									<th><?php echo $lang->product ?></th>
									<th><?php echo $lang->price ?> / <span class="small uppercase"><?php echo $lang->action ?></span></th>					
								</tr>
							</thead>
							<tbody>
								<?php for ($i=0 ,$zind = 203, $hasPackages = false; $i<count($SHOPPING_CART); $i++) { ?>
								<tr class="tr-cart-item" data-cart-id="<?= $SHOPPING_CART[$i]->itemID ?>" style="vertical-align: top;">
									<td style="max-width: 250px;">
										<div class="cart-item padding-t-10 padding-b-10" data-cart-id="<?= $SHOPPING_CART[$i]->itemID ?>" data-set-length="<?= ($SHOPPING_CART[$i]->packageID ? $SHOPPING_CART[$i]->flag : "null") ?>">
										<?php if (!$SHOPPING_CART[$i]->packageID) { ?>
                                        <a class="link-ord" href="<?= $domain; ?>workshop/<?= $SHOPPING_CART[$i]->workshopID ?>/">
                                            <div class="cart-image-placeholder ">
                                                <img src="<?= $FILE; ?>img/content/<?= $SHOPPING_CART[$i]->image; ?>" />
                                            </div>
                                        </a>
										<?php } ?>
										<div class="padding-l-10" style="vertical-align: top;">
											<?php if ($SHOPPING_CART[$i]->workshopID) { // Workshop printing ?>
											<a class="link-ord" href="<?= $domain; ?>workshop/<?= $SHOPPING_CART[$i]->workshopID ?>/">
												<div class="cart-heading"><?= $SHOPPING_CART[$i]->heading ?></div>
											</a>
											<div class="smaller"><?= $SHOPPING_CART[$i]->subheading ?></div>

											<?php } else { if (!$hasPackages) $hasPackages = true; // Package printing ?>

												<div class="cart-heading"><i class="fa fa-paint-brush"></i> <?= $lang->package_self ?> - <span class="strong"><?= print_duration($SHOPPING_CART[$i]->flag) ?></span></div>
												<div class="smaller">
													<div class="div-inl">
														<?php echo $lang->select_StartMonth ?> > 													
														<div class="month-selection sort-options fold margin-t-10" style="padding-right: 30px; margin-left: 0; z-index: <?php $zind + 1 ?>; min-width: 100px">
															<div class="default-option"><?= $lang->c_months[$SHOPPING_CART[$i]->start_month-1] ?></div>							
															<ul class="options text-left">
																<?php for ($v = $startMonth-1; $v<count($lang->c_months); $v++) { ?>
																<li class="<?= ($SUBSCRIPTION_MONTHS[$v] ? "disabled" : "") ?> <?= (($v+1) == $SHOPPING_CART[$i]->start_month ? "strong" : "") ?> " <?= ($SUBSCRIPTION_MONTHS[$v] ? 'data-placement="right" data-toggle="tooltip" title="'.$lang->subscribed.'"' : "") ?> data-value="<?= $v+1 ?>"><?= $lang->c_months[$v] ?></li>
																<?php }; ?>																		
															</ul>
															<i class="fold fa fa-chevron-down"></i>
															<i class="unfolded fa fa-chevron-up"></i>
														</div>														
														<div class="padding-l-10 strong margin-md-t-20 padding-md-l-none"><span class="start-date"><?= make_date(-1, $year."-".DEC($SHOPPING_CART[$i]->start_month)."-01"); ?></span> - <span class="end-date">
															<?= make_date_format(strtotime("+".$SHOPPING_CART[$i]->flag." month -1 day", strtotime($year."-".DEC($SHOPPING_CART[$i]->start_month)."-01"))) ?>
														</span>
														</div>
														<div class="pull-right margin-t-10">
															<div class="theme-spinner dn small"></div>
														</div>
													</div>
													<div class="margin-t-10">
													<?php echo $lang->will_beAvailable ?>
													</div>
													<span class="text-faded"><?php echo $lang->package_detail ?> <a class="link-ord" href="<?= $domain; ?>packages"><?php echo $lang->here ?></a>.</span>
												</div>
											<?php }; ?>
										</div>
										</div>
									</td>
									<td class="relative cart-price">
										<?php 
											// Generate prices for subscription/workshop
											$RSD_price = ($SHOPPING_CART[$i]->packageID ? $SHOPPING_CART[$i]->pricePackageRSD : $SHOPPING_CART[$i]->priceRSD); 
											$CURR_price = ($SHOPPING_CART[$i]->packageID ? $SHOPPING_CART[$i]->pricePackage : $SHOPPING_CART[$i]->price);
										?>	
										<div>
											<div class="price-placeholder md-dn">
												<div class="strong">
													RSD <?= print_money_PLAINTXT($RSD_price,2)  ?>
												</div>
												<?php if ($USER->currencyID !== "RSD") {  // Print foreign value?>
												<div style="padding: 0" class="strong"><span class="foreign-currency-price">
												<?= $USER->currency ?> <?= print_money_PLAINTXT($CURR_price,2) ?></span></div>
												<?php }; ?>
											 </div>	
											 <!-- Display price for mobile devices -->
											 <div class="price-placeholder md md-db dn-imp">
												<div class="strong">
													RSD <?= print_money_PLAINTXT($RSD_price,2)  ?>
												</div>
												<?php if ($USER->currencyID !== "RSD") {  // Print foreign value?>
												<div class="foreign-currency-price strong"> 
												<?= $USER->currency ?> <?= print_money_PLAINTXT($CURR_price,2) ?>
												</div>
												<?php }; ?>											
											 </div>
										 </div>

										<div class="cart-remove-item" data-cart-id="<?= $SHOPPING_CART[$i]->itemID; ?>" data-toggle="tooltip" data-placement="bottom" title="<?= $lang->removeProductFromCart ?>"><i class="fa fa-trash"></i>
										</div>
									</td>									
								</tr>
								<?php }; ?>								
							</tbody>
						</table>

						<div class="padding-l-20 margin-b-20 smaller">
							<div id="emptyCart" class="btn-white bordered btn-ord" data-toggle="tooltip" data-placement="right" title="<?= $lang->removeAllProductCart ?>" data-delete-all="<?= $lang->succDeletedAll ?>" data-delete-spec="<?= $lang->succDeletedOne ?>"><i class="fa fa-trash"></i> <?php echo $lang->empty_cart ?></div>
						</div>			

						<div class="divider width-100"></div>		

						<form id="paymentDataForm" action="<?= $PAYMENT_LINK ?>" target="_blank" method="post">
							<input type="hidden" name="encoding" value="UTF-8">
							<input type="hidden" name="clientid" value="<?= $CLIENT_ID ?>">
					        <input type="hidden" id="pform_amount" name="amount" value="<?= $RSD_SUM; ?>">
							<input type="hidden" name="islemtipi" value="Auth">
							<input type="hidden" name="taksit" value="">
					        <input type="hidden" name="oid" value="">
					        <input type="hidden" name="okUrl" value="<?= $FILE; ?>verifyPayment/credit-card">
					        <input type="hidden" name="failUrl" value="<?= $FILE; ?>verifyPayment/canceled">
					        <input type="hidden" name="rnd" value="">
					        <input type="hidden" name="hash" value="">
					        <input type="hidden" name="storetype" value="3D_PAY_HOSTING">
					        <input type="hidden" name="lang" value="<?= ($USER->lang !== "SR" ? "en" : "sr") ?>">
					        <input type="hidden" name="currency" value="941">
							<input type="hidden" name="printShipTo" value="false">
							<input type="hidden" name="printBillTo" value="false">							
							<input type="hidden" name="BillToName" value="<?= $USER->name." ".$USER->surname ?>">
							<input type="hidden" name="refreshtime" value="0">		
							<input type="hidden" name="comments" value="Please wait for transaction to finish. DO NOT leave this page.">					
						<div class="margin-t-10 padding-l-10 padding-r-10 margin-b-20">
							<?php if (!$USER->verified) { ?>
							<div class="margin-b-0">
								<div class="panel bg-danger">
									<div class="panel-body">
										<i class="fa fa-exclamation-triangle fa-2x"></i> 
										<span style="position: relative; top: -6px; left: 6px"><?php echo $lang->to_continuePlease ?> <span class="strong"><?php echo $lang->verify_yourAcc ?></span>.</span>
									</div>
								</div>
							</div>
							<?php } else { // If user is verified, than continue with payment options ?>
							<div id="creditCardPayment" class="margin-t-20 margin-b-20">
								<div class="row">
									<div class="col-md-12 col-xs-12 col-sm-12">
										<div class="color-theme strong">
											<i class="fa fa-info-circle"></i> <?php echo $lang->after_payment ?>
										</div>
										<div class="smaller padding-t-5">
											<?php echo $lang->if_paymentSuccess ?> <span class="strong"><?= $USER->email ?></span>. <?php echo $lang->for_infoGoTo ?> <a class="link-ord" href="mailto: info@handmadefantasyworld.com">info@handmadefantasyworld.com</a>
										</div>
									</div>
								</div>																				
							</div>

							<div id="checkPayment" class="margin-b-20 dn">
								<div class="row">
									<div class="col-md-12 col-xs-12 col-sm-12">
										<div class="color-theme strong">
											<i class="fa fa-info-circle"></i> <?php echo $lang->information ?>
										</div>
										<div class="smaller padding-t-5">
											<?php echo $lang->after_order ?>
										</div>
									</div>
								</div>																				
							</div>


							<div class="margin-b-10">
								<h3 style="margin: 0"><?php echo $lang->buyer_info ?></h3>								
								<span class="small"><div class="small"><i class="fa fa-lock"></i> <?php echo $lang->info_forPayment ?></div></span>
							</div>												
							<div id="buyerInfoFinished" class="<?= (!$PINFO ? "dn" : "") ?>" data-status="<?= (!$PINFO ? "false" : "true") ?>">		
													
								<div class="buyer-card margin-t-20">
									<div class="sp-section">
										<div class="margin-r-10">
											<div id="changeBuyerInformation" class="btn btn-white bordered btn-ord smaller"><i class="fa fa-edit"></i> <?php echo $lang->add_newInfo ?></div>
										</div>
										<div class="color-success small">
										<i class="fa fa-circle" style="font-size: 60%; position: relative; top: -3px"></i>
										<?php echo $lang->ready ?>
										</div>
									</div>									
									<table class="margin-md-t-20">
										<tr>
											<td width="<?= ($USER->lang === "SR" ? "90" : "140") ?>"><span class="smaller strong uppercase"><?php echo $lang->name_surname ?></span> </td>
											<td><span id="BIname" class="padding-l-10 small"><?= $PINFO->name ?></span></td>
										</tr>
										<tr>
											<td><span class="smaller strong uppercase"><?php echo $lang->adress ?></span> </td>
											<td><span id="BIaddress" class="padding-l-10 small"><?= $PINFO->address ?></span></td>
										</tr>
										<tr>
											<td><span class="smaller strong uppercase"><?php echo $lang->city ?></span> </td>
											<td><span id="BIcity" class="padding-l-10 small"><?= $PINFO->city ?></span></td>
										</tr>
										<tr>
											<td><span class="smaller strong uppercase"><?php echo $lang->telephone ?></span> </td>
											<td><span id="BItelephone" class="padding-l-10 small"><?= $PINFO->telephone ?></span></td>
										</tr>
										<tr>
											<td><span class="smaller strong uppercase"><?php echo $lang->email_email ?></span> </td>
											<td><span id="BIemail" class="padding-l-10 small"><?= $USER->email ?></span></td>
										</tr>
									</table>	

									<div class="margin-t-10 smaller">
										<a id="showUserDataLst" class="link-ord <?= (!$PINFOS ? "dn" : ""); ?>" href="javascript:void(0)"><i class="fa fa-bars"></i> <?php echo $lang->list_oldInfo ?></a>

										<a id="hideUserDataLst" class="link-ord dn" href="javascript:void(0)"><i class="fa fa-eye-slash"></i> <?php echo $lang->hide_oldInfo ?></a>
									</div>	
									
									<div class="margin-t-10 user-data-info margin-md-t-20 dn">										
										<?php if ($PINFOS) { ?>
										<div id="userPaymentInfoData" class="row margin-t-20">
											<div class="eticket-ui-user-data-pi smaller bg-info">
												<i class="fa fa-refresh"></i> <?php echo $lang->user_info_bef ?>
											</div>
											<div id="paymentInfoOptions">
										<?php for ($i=0; $i<count($PINFOS); $i++) {
										?>

											<div class="payment-info col-md-6 col-xs-12 col-sm-12 margin-b-10" data-value="<?= $PINFOS[$i]->piID; ?>">
												<label class="pointer" style="margin: 0">
												<div>
													<input type="radio" name="usd_radio" value="<?= $i+1 ?>" data-value="<?= $PINFOS[$i]->piID; ?>" <?= ($PINFOS[$i]->defaultInfo ? "checked=\"checked\"" : "") ?> /> <?= $PINFOS[$i]->name ?>
													<span class="badge <?= (!$PINFOS[$i]->defaultInfo ? "dn" : "") ?> bg-info"><span class="small"><?php echo $lang->default ?></span></span>													 
												</div>
												<div class="padding-l-20 smaller" style="font-weight: normal;">
													<div><?= $PINFOS[$i]->address ?></div>
													<div><?= $PINFOS[$i]->city ?></div>
													<div><?= $PINFOS[$i]->telephone ?></div>
													<div><?= $USER->email; ?></div>
													<div><a class="removePIO link-ord" data-value="<?= $PINFOS[$i]->piID; ?>" href="javascript: void(0)"><i class="fa fa-trash"></i> <?php echo $lang->delete ?></a></div>
												</div>
												</label>
											</div>										
										<?php }; ?>
											</div>
										</div> 

										<div class="row">
											<div class="col-md-12 col-xs-12 col-sm-12 text-right">
												<div id="submitPaymentInfo" class="btn-green btn-ord smaller"><i class="fa fa-check"></i> <?php echo $lang->approve_choice ?></div>
											</div>											
										</div>

										<?php } else { ?>
										<div id="userPaymentInfoData" class="row margin-t-20">
											<div id="paymentInfoOptions"></div>
											<div class="eticket-ui-user-data-pi smaller bg-info">
												<i class="fa fa-refresh"></i> <?php echo $lang->user_info ?>
											</div>
										</div> 

										<div class="row">
											<div class="col-md-12 col-xs-12 col-sm-12 text-right">
												<div id="submitPaymentInfo" class="btn-green btn-ord smaller" data-error-delete="<?php echo $lang->could_NotDelete ?>"><i class="fa fa-check"></i> <?php echo $lang->approve_choice ?></div>
											</div>
										</div>
										<?php }; ?>
									</div>
														
								</div>
							</div>

							<div id="buyerInfoProcessing" class="<?= ($PINFO ? "dn" : "") ?>">			
								<div class="mistake smaller dn">
									<i class="fa fa-exclamation-triangle"></i> <span class="padding-l-5"><?php echo $lang->error_happened ?>.</span>
									<div class="padding-l-50 padding-t-5">
										<ol>
											<li class="dn"><?php echo $lang->atleast_threeLetters ?></li>
											<li class="dn"><?php echo $lang->adress_five ?></li>
											<li class="dn"><?php echo $lang->city_five ?></li>
											<li class="dn"><?php echo $lang->phone_seven ?></li>
											<li class="dn"><?php echo $lang->phone_signs ?></li>
										</ol>
									</div>
								</div>
								<span class="smaller color-theme <?= ($PINFO ? "dn" : "") ?>"><i class="fa fa-info-circle"></i> <?php echo $lang->info_onBuyerOnlyOnce ?></span>
								<div class="row">
									<div class="col-md-6 col-xs-12 col-sm-12">
										<div class="login-input-group margin-t-10">
											<input id="name" type="text" class="input-theme" name="order_name" placeholder="<?= $lang->name_surname ?>" autocomplete="off" value="<?= ($PINFO ? $PINFO->name : $USER->name." ".$USER->surname) ?>" />
											<div class="login-input-icon"><i class="fa fa-user"></i></div>
										</div>
									</div>
								</div>							
								<div class="row">
									<div class="col-md-6 col-xs-12 col-sm-12">
										<div class="login-input-group margin-t-10">
											<input id="address" type="text" class="input-theme" name="order_address" placeholder="<?= $lang->adress ?>" autocomplete="off" value="<?= ($PINFO ? $PINFO->address : "") ?>" />
											<div class="login-input-icon"><i class="fa fa-map-marker"></i></div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 col-xs-12 col-sm-12">
										<div class="login-input-group margin-t-10">
											<input id="city" type="text" name="order_city" class="input-theme" placeholder="<?= $lang->city ?>" autocomplete="off" value="<?= ($PINFO ? $PINFO->city : $USER->city) ?>" />
											<div class="login-input-icon"><i class="fa fa-map"></i></div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6 col-xs-12 col-sm-12">
										<div class="login-input-group margin-t-10">
											<input id="telephone" type="text" name="order_telephone" class="input-theme" placeholder="<?= $lang->telephone ?>" autocomplete="off" value="<?= ($PINFO ? $PINFO->telephone : "") ?>" />
											<div class="login-input-icon"><i class="fa fa-phone"></i></div>
										</div>
									</div>									
								</div>

								<div class="row">
									<div class="col-md-6 col-xs-12 col-sm-12">
										<div class="login-input-group margin-t-10">
											<input id="email" type="text" class="input-theme" name="order_email" placeholder="E-mail" autocomplete="off" value="<?= $USER->email ?>" disabled="disabled" />
											<div class="login-input-icon"><i class="fa fa-envelope"></i></div>
										</div>
									</div>
								</div>

								<div class="margin-t-20 text-center div-inl">
									<div id="buyerInfoDeclEdit" class="btn-white bordered btn-ord small <?= ($PINFOS ? "" : "dn-imp") ?>"><i class="fa fa-times"></i> <?php echo $lang->cancel ?></div>
									<div id="buyerInfoSaveEdit" class="btn-green btn-ord small margin-l-10"><i class="fa fa-check-circle"></i> <?php echo $lang->insert_info ?></div>	
								</div>
							</div>	

							<div class="margin-t-20 margin-b-10">
								<label class="pointer smaller" style="font-weight: normal;">
								<input id="terms" type="checkbox" class="js-switch" /> <?php echo $lang->iAgree ?> <a class="link-ord" href="<?= $domain ?>terms"><?php echo $lang->procedure_andUses?></a></label>
							</div>

							<div class="margin-t-30">
								<div class="row">
									<div class="col-md-6 col-md-offset-6 col-xs-12 col-sm-12">
										<div class="panel bg-warning">
											<div class="panel-body">
												<div><i class="fa fa-warning fa-2x"></i> <span class="strong text-large padding-l-5" style="position: relative; top: -4px;"><?= $lang->note ?></span></div>
												<div class="padding-t-5"><?= $lang->AIK_proc ?></div>
											</div>
										</div>
									</div>									
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 col-xs-12 col-sm-12 padding-l-30 padding-r-30">
									<!-- Cart Total -->							
									<div>
										<span class="cart-total uppercase"><?php echo $lang->price_withoutPDV ?></span>
										<div class="cart-total-number fl-right text-right strong">RSD <span id="p_pwt"><?= print_money_PLAINTXT($RSD_SUM-0.2*$RSD_SUM,2); ?></span></div>
									</div>

									<div class="margin-t-10">
										<span class="cart-total uppercase"><?php echo $lang->pdv ?></span>
										<div class="cart-total-number fl-right text-right strong">RSD <span id="p_tax"><?= print_money_PLAINTXT(0.2*$RSD_SUM,2); ?></span></div>
									</div>

									<div class="divider width-100"></div>

									<div class="margin-t-10" style="font-size: 140%">
										<span class="cart-total uppercase strong"><?php echo $lang->total_buy ?></span>
										<div class="cart-total-number fl-right text-right strong color-theme">RSD <span id="p_total"><?= print_money_PLAINTXT($RSD_SUM,2); ?></span>
										<?php if ($USER->currencyID !== "RSD") { ?>
										<span class="padding-l-10"><span class="foreign-currency-price div-inl">
											<?= $USER->currency; ?> <div id="totalForeignCurrency" class="padding-r-10"><?= print_money_PLAINTXT($CURR_SUM,2); ?></div>
										</span></span>
										<?php }; ?>
										</div>
									</div>	
									<!-- /Cart Total -->
								</div>
							</div>							

							<div class="margin-t-20 text-right div-inl md-text-center">
								<?php if ($payPal) { ?>
								<span id="PayPalGroup" class="div-inl">
									<div id="PayPalBtn" class="margin-md-b-40 btn-ord disabled" style="position: relative; top: 25px"></div>
									<div class="pay-or-card smaller md-dn">
										<?= mb_strtolower($lang->or); ?>	
									</div>																
								</span>					
								<?php }; ?>					
					
								<div id="submitPayment" class="btn-green btn-ord btn-padding-large text-center" data-message-terms="<?= $lang->mustAgreeTerms ?>" data-message-user-data="<?= $lang->customerInfoSave ?>" style="font-size: 120%;"><i class="fa fa-check-circle"></i> <span id="paymentText"><?php echo $lang->continue_withPay ?></span></div>								
						
							</div>
							
							<?php }; ?>
						</div>
						</form>
					</div>					
				</div>
			</div>
			<!-- Right side of the shopping cart -->
			<div class="col-md-4 col-xs-12 col-sm-12">
				<div class="panel panel-default">					
					<div class="panel-body">
						<div>
							<h4 class="strong uppercase" style="display: inline-block;"><i class="fa fa-file"></i> <?php echo $lang->overview ?></h4>
							<div id="loader" class="fl-right dn"><i class="fa fa-spinner fa-spin color-theme strong fa-2x"></i></div>
						</div>
					<div id="paymentWrapper" class="wrapper">
						<div class="small"><?php echo $lang->ready_forPayment ?></div>						

						<div class="margin-t-20 small strong text-center uppercase">
									<?php echo $lang->bank_card ?>
						</div>
						<div class="row">
							<div class="margin-t-10">													
								<div class="col-md-6 col-xs-12 col-sm-6 text-center image-optimised  margin-t-10">
									<img src="<?= $FILE ?>img/ab_thumb.jpg" />									
								</div>
								<div class="col-md-4 col-md-offset-1 col-xs-4 col-xs-offset-1 col-sm-4 col-sm-offset-2 text-center">
									<img src="<?= $FILE ?>img/master.png" width="80" />
								</div>
							</div>
						</div>
						<div class="row margin-t-10">
							<div class="margin-t-10">
								<div class="col-md-6 col-xs-12 col-sm-6 text-center">
									<img src="<?= $FILE ?>img/maestro.png" width="80" />
								</div>
								<div class="col-md-6 col-xs-12 col-sm-6 text-center margin-t-10">
									<img src="<?= $FILE ?>img/visa.gif" width="80" />
								</div>							
							</div>
						</div>
						<div class="row margin-t-10">
							<div class="margin-t-10">
								<div class="col-md-6 col-xs-12 col-sm-6 text-center">
									<img src="<?= $FILE ?>img/paypal.png" width="150" />
								</div>															
							</div>
						</div>
						<div class="margin-t-20 smaller text-faded">
							<?php echo $lang->online_buying ?> <a class="link-ord" target="_blank" href="http://www.aikbanka.rs/<?= ($USER->lang !== "SR" ? "en" : "sr") ?>"><?php echo $lang->aik_bank ?></a>.						
						</div>
						<div class="margin-t-10">
							<div class="alert bg-info">
								<div class="">
									<i class="fa fa-lock fa-2x"></i> 
									<span class="relative strong" style="top: -5px; left: 5px; font-size: 110%;"> <?php echo $lang->secure_payment ?></span>
								</div>
								<div class=" smaller">								
									<?php echo $lang->visible_onlyToAIK ?>
									<span class="strong">Handmade Fantasy World</span> <?php echo $lang->responsibility ?>
								</div>
							</div>
						</div>						
						</div> <!-- /.wrapper -->					
					</div>					
				</div>
			</div>
			<div class="col-md-12 col-xs-12 col-sm-12">
				<div class="panel bg-default">
					<div class="panel-body">
						<div class="strong color-theme"><i class="fa fa-exclamation-triangle"></i> <?php echo $lang->info_foreignCurrency ?></div>
						<div class="small padding-t-5">
							<?php echo $Cart_para1 ?>
						</div>
					</div>
				</div>
			</div>	
		</div>	
		<?php } ?>

		<div id="cartEmpty" class="row <?= ($cart_number ? "dn" : "") ?>">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<span class="strong"><i class="fa fa-shopping-cart"></i> <?php echo $lang->empty_yourcart ?></span>
					</div>
					<div class="panel-body">
						<?php echo $lang->no_productsInCart ?>
						<div class="margin-t-10 margin-b-10">
							<a href="<?= $domain; ?>workshops"><div class="btn-ord btn-green small"><i class="fa fa-long-arrow-left"></i> <?php echo $lang->continue_buying ?></div></a>
						</div>
					</div>						
				</div>
			</div>
		</div>

						
	</div>	<!--/.main-->
	
	<?php print_HTML_data("script","cart") ?>
	<script type="text/javascript" data-attr="true" data-value="data_complete=<?= ($PINFO ? "true" : "false") ?>" src="<?= $FILE ?>user/js/cart.js"></script>
	<?php if ($payPal)  { ?>
	<script type="text/javascript" src="https://www.paypalobjects.com/api/checkout.js"></script>
	<script type="text/javascript" src="<?= $FILE ?>user/js/payment.js"></script>
	<?php }; ?>
	
</body>

</html>
