<?php 

	session_start();
	if (!isset($_GET['pid']) || (isset($_GET['pid']) && trim($_GET['pid'])==="")) header("location: index.php");
	$pid = $_GET['pid'];
//	Prep for include
	$prepath = '../';
	$INCLUDE = (object) array("getDATA" => false);
    include $prepath."connect.php";
	include $prepath."functions.php";

	// http://localhost/pr/user/receipt?token=7o5sleEKZRj2h62WVihw9i1oFrKEGz&pid=4HhkEtmq2ByeIb4BDMDRgwsQAZgV4ghWFXcdQagB

//	User sent from email to receipt
	if (isset($_GET['token']))
	{
		$sql = mysqli_query($CONNECTION,"SELECT username FROM users WHERE BINARY security_token = '".$_GET['token']."' LIMIT 1");
		if (mysqli_num_rows_wrapper($sql)) { 
			$a = ""; logInUser($a, mysqli_fetch_object_wrapper($sql)); 
			$ACCESS = true;
			$ACCESS_USER = $a;
			$user = $a;
		} else { header("location: ".$domain); exit(); }
	} else
	{
		if (!isset($_SESSION['hfw_username'])) { header("location: ".$domain); exit(); };
	};	
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

	$sql = mysqli_query($CONNECTION,"SELECT `payments`.*, `name_".$USER->lang."` AS paymentMethodName FROM payments INNER JOIN `paymentmethods` ON `paymentmethods`.pmID = `payments`.payment_method WHERE BINARY username = '".$USER->username."' AND BINARY paymentID = '".$pid."' LIMIT 1");
	if (!mysqli_num_rows_wrapper($sql)) header("location: ".$domain); else
	{
		$PAYMENT = (object) array(); $INVOICE = (object) array();

		while($t = mysqli_fetch_object_wrapper($sql)) $PAYMENT = $t;			// Get payment data
//		SELECT boughtworkshops and all necessary fields 
		$sql = mysqli_query($CONNECTION,"SELECT `subTbl1`.*, `paymentmethods`.`name_".$USER->lang."` AS paymentMethodName FROM (SELECT `tblPW`.*, `workshops`.heading_".$USER->lang." AS heading, `workshops`.`subheading_".$USER->lang."` AS subheading, `workshops`.`price_".$PAYMENT->trans_currencyID."` AS price, `workshops`.`m_subscription`, `workshops`.`ind_subscription`, `workshops`.`active`, `workshops`.`forsale` FROM (SELECT `tbl`.*, `boughtworkshops`.`bwID`, `boughtworkshops`.`workshopID` FROM (SELECT * FROM `payments` WHERE username='".$USER->username."' AND paymentID = '".$pid."') tbl LEFT OUTER JOIN `boughtworkshops` ON `boughtworkshops`.`paymentID` = `tbl`.paymentID) tblPW INNER JOIN workshops ON `workshops`.`workshopID` = `tblPW`.`workshopID`) subTbl1 INNER JOIN paymentmethods ON `paymentmethods`.pmID = `subTbl1`.payment_method");
		$obj = array(); $i = 0; $dataSet = 0; 
		while($t = mysqli_fetch_object_wrapper($sql)) $obj[$i++] = $t;
		if (count($obj)) { 
				$INVOICE->bw = $obj;
		} else $INVOICE->bw = NULL;

//		SELECT  subscriptions
		$sql = mysqli_query($CONNECTION,"SELECT `subTbl1`.*, `paymentmethods`.`pmID`, `paymentmethods`.`name_".$USER->lang."` AS paymentMethodName FROM (SELECT `subTbl`.paymentID, `subTbl`.trans_currencyID, `subTbl`.payment_amount, `subTbl`.payment_method, `subTbl`.trans_date, `subTbl`.receiptID, `subTbl`.username, `subTbl`.subscriptionID, `subTbl`.date_start, `subTbl`.date_end, `subTbl`.s_start, `subTbl`.s_end, `packages`.`packageID`, `packages`.`price_".$PAYMENT->trans_currencyID."` AS price, `packages`.`flag` FROM (SELECT `pwd`.*, `subscriptions`.`subscriptionID`, `subscriptions`.type, `subscriptions`.date_start, `subscriptions`.date_end, MONTH(`subscriptions`.`date_start`) AS s_start, MONTH(`subscriptions`.date_end) AS s_end FROM (SELECT * FROM payments WHERE `payments`.username='".$USER->username."' AND BINARY `payments`.paymentID='".$pid."') pwd INNER JOIN subscriptions ON `subscriptions`.`paymentID` = `pwd`.`paymentID`) subTbl INNER JOIN packages ON `subTbl`.type = `packages`.packageID) subTbl1 INNER JOIN paymentmethods ON paymentmethods.pmID = `subTbl1`.payment_method");
		$obj = array(); $i = 0;
		while($t = mysqli_fetch_object_wrapper($sql)) $obj[$i++] = $t;
		if (count($obj)) { 
			$INVOICE->sub = $obj;
		} else $INVOICE->sub = NULL;
	};

	$sql_buyer = mysqli_query($CONNECTION,"SELECT * FROM paymentinfo WHERE piID = (SELECT piID FROM payments WHERE BINARY `payments`.paymentID = '".$pid."') AND BINARY username = '".$USER->username."'");
	$BUYER = (object) array();
	while($t = mysqli_fetch_object_wrapper($sql_buyer)) $BUYER = $t;

	include $prepath."pages.php";
	include $prepath."lang/func.php";
	include $prepath."lang/user_".strtolower($lang_acr).".php";
	include $prepath."requests/userManagement.php";

	$sql = mysqli_query($CONNECTION,"SELECT * FROM currencies WHERE BINARY currencyID = '".$PAYMENT->trans_currencyID."'");
	$currency = mysqli_fetch_object_wrapper($sql);

	$prepath = $domain;

?>

<!DOCTYPE html>
<html>
<head>
<?php print_HTML_data("head","receipt") ?>
</head>

<body class="<?= $bodyClass ?>">

	<?php printTopMenu(); ?>		
	<?php printMainMenu(-1); ?>
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main margin-b-60">			
		<?php printNavigation(2,[$lang->invoice_view,"IF - ".$PAYMENT->receiptID], [$FILE."user/subscriptions"]); ?>
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header heading"><?php echo $lang->invoice_view ?></h1>
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="col-md-8 col-xs-12 col-sm-12">
				<div class="panel bg-default">
					<div class="panel-body">
						<div class="text-right">
							<div class=""><img src="<?= $FILE ?>img/logo.png" title="Handmade Fantasy World Logo" width="220" /></div>
							<div class="strong uppercase margin-t-10">Handmade Fantasy World</div>
							<div class="smaller"><?php echo $lang->belgrade ?>, <?php echo $lang->serbia ?></div>
							<div class="small"><span class="uppercase smaller strong"><?php echo $lang->pib ?></span> <?= $COMPANY_INFO->PIB ?></div>
							<div class="small"><span class="uppercase smaller strong"><?php echo $lang->mb ?></span> <?= $COMPANY_INFO->MB ?></div>					
							<div class="small"><span class="uppercase smaller strong"><?php echo $lang->email_email ?></span> <a class="link-ord" href="mailto:<?= $COMPANY_INFO->email ?>"><?= $COMPANY_INFO->email ?></a></div>
						</div>

						<div class="margin-t-20 strong" style="font-size: 160%">IF - <?= $PAYMENT->receiptID ?></div>
						<div class="margin-t-20">
							<div class="uppercase small"><?php echo $lang->invoice_individual ?></div>
							<?php if (mysqli_num_rows_wrapper($sql_buyer)) { ?>
							<div class="margin-t-10">
								<div class="strong"><?= $BUYER->name ?></div>
								<div class="small"><?php echo $lang->adress ?>: <span class="strong"><?= $BUYER->address ?></div>
								<div class="small"><?php echo $lang->city ?>: <span class="strong"><?= $BUYER->city ?></div>
								<div class="small"><?php echo $lang->contact_phone ?>: <span class="strong"><?= $BUYER->telephone ?></div>
								<div class="small"><?php echo $lang->user_nameone ?>: <span class="strong"><?= $USER->username ?></span></div>
								<div class="small"><?php echo $lang->email_email ?>: <span class="strong"><?= $USER->email ?></span></div>
							</div>
							<?php }; ?>
						</div>
						<div class="smaller margin-t-20"><span class="strong"><?php echo $lang->warning ?>:</span> <?php echo $lang->pdv_sistem ?></div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-xs-12 col-sm-12">
				<div class="panel bg-default">
					<div class="panel-body">
						<div>
							<span class="uppercase small"><?php echo $lang->payment_status ?></span> 
							<?php if ($PAYMENT->paid) { ?>
							<div class="fl-right text-success strong uppercase">
								<span class="smaller"><i class="fa fa-circle smaller" style="position: relative; top: -2px"></i></span> <span class="small"><?php echo $lang->paid ?></span>
							</div>
							<?php } else { ?>
							<div class="fl-right text-danger uppercase strong">
								<span class="smaller"><i class="fa fa-circle-o smaller" style="position: relative; top: -2px"></i></span> <span class="small"><?php echo $lang->in_process ?></span>
							</div>
							<?php }; ?>
						</div>
						<div>
							<span class="uppercase small"><?php echo $lang->payment_date ?></span> 
							<div class="fl-right small">
								<?= make_date(-1, $PAYMENT->trans_date) ?>
							</div>
						</div>						
						<div>
							<span class="uppercase small"><?php echo $lang->payment_method ?></span> 
							<div class="fl-right small">								
								<?php 
								// Credit Card - AIK checkout
								if ($PAYMENT->payment_method == 1) {  ?>
								<i class="fa fa-credit-card"></i> <?= $PAYMENT->paymentMethodName; ?>
								<?php
								// PayPal Express Checkout
								} elseif ($PAYMENT->payment_method == 2) { ?>
								<span class="paypal-text strong"><i class="fa fa-paypal"></i> <?= $PAYMENT->paymentMethodName; ?></span>
								<?php } elseif ($PAYMENT->payment_method == 3) { ?>
								<span class="paypal-text strong"><i class="fa fa-money"></i> <?= $lang->cash ?></span>
								<?php }; ?>
							</div>
						</div>

						<div class="margin-t-20 text-right">
							<div class="uppercase small strong"><?php echo $lang->id_payment?></div> 
							<div class="small"><?= $PAYMENT->paymentID ?></div>
						</div>

						<div class="margin-t-10 text-right">
							<div class="uppercase small strong"><?php echo $lang->invoice_number ?></div> 
							<div class="small"><?= $PAYMENT->receiptID ?></div>
						</div>

						<div class="divider"></div>

						<div class="margin-t-10">
							<div>
								<span class="uppercase"><?php echo $lang->price_withoutPDV ?></span> <div class="fl-right">
									<?= $PAYMENT->trans_currencyID." ".print_money_PLAINTXT($PAYMENT->payment_amount - $PAYMENT->payment_amount*0.2,2); ?>
								</div>
							</div>
							<div>
								<span class="uppercase"><?php echo $lang->pdv ?></span> <div class="fl-right">
									<?= $PAYMENT->trans_currencyID." ".print_money_PLAINTXT($PAYMENT->payment_amount*0.2,2); ?>
								</div>
							</div>
							<div class="color-theme strong" style="font-size: 150%">
								<span class="uppercase"><?php echo $lang->total ?></span> <div class="fl-right">
									<?= $PAYMENT->trans_currencyID." ".print_money_PLAINTXT($PAYMENT->payment_amount,2); ?>
								</div>
							</div>
						</div>
					</div>
				</div>				
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12">
				<div class="panel bg-default">
					<div class="panel-body" style="padding: 0">
						<table class="table table-hover tbl-theme">
							<tr class="heading-row">
								<th width="20"><?php echo $lang->number ?></th>								
								<th><?php echo $lang->name_package ?></th>
								<th width="100" class="text-right"><?php echo $lang->price_pdvA ?> <span class="small">[<?= $currency->name ?>]</span></th>
								<th width="80" class="text-right"><?php echo $lang->pdv ?> <span class="small">[<?= $currency->name ?>]</span></th>
								<th width="80" class="text-right"><?php echo $lang->price ?> <span class="small">[<?= $currency->name ?>]</span></th>
							</tr>

							<?php
                            $arr = $INVOICE->sub !== NULL ? $INVOICE->sub : $INVOICE->bw;
                            if ($INVOICE->bw !== NULL && $INVOICE->sub !== NULL) {
                                $arr = array_merge($INVOICE->sub, $INVOICE->bw);
                            } else if ($INVOICE->bw !== null) {
                                $arr = $INVOICE->bw;
                            } else {
                                $arr = $INVOICE->sub;
                            }
                            for ($i=0; $i<count($arr); $i++) { ?>
							<tr>
								<td><?= $i+1 ?></td>
								<td class="small">
									<?php if (isset($arr[$i]->packageID) && $arr[$i]->packageID) { ?>
									<div class="strong"><?php echo $lang->package ?> - <?php echo print_duration($arr[$i]->flag) ?></div>
									<div class="small"><?php echo $lang->sub_lasts ?> <?= print_duration($arr[$i]->flag); ?> [<?= make_date(-1,$arr[$i]->date_start) ?> - <?= make_date(-1,$arr[$i]->date_end) ?>]
									</div>
									<?php } elseif ($arr[$i]->workshopID) { ?>
									<a href="<?= $domain ?>workshop/<?= $arr[$i]->workshopID ?>"><?php echo $arr[$i]->heading ?></a> - <span class="color-theme uppercase small"><?php echo $lang->ws_lowchar ?></span>
									<?php }; ?>
								</td>
								<td class="text-right"><?= print_money_PLAINTXT($arr[$i]->price - $arr[$i]->price*0.2,2); ?></td>
								<td class="text-right"><?= print_money_PLAINTXT($arr[$i]->price*0.2,2); ?></td>
								<td class="text-right"><?= print_money_PLAINTXT($arr[$i]->price,2); ?></td>
							</tr>
							<?php }; ?>							
						</table>
					</div>
				</div>
			</div>			
		</div>
		
		
	

					
	</div>	<!--/.main-->
	
	<?php print_HTML_data("script","receipt") ?>
</body>

</html>
