<?php 

	$prepath = "";
	include "../../functions.php";
	include "../../connect.php";
	include "../../global.php";
	include "../../getDATA.php";
	include "../../lang/func.php";
	
	$FILE = "http://localhost/pr/";
	$USER = (object) array("lang" => "EN", "username" => "lidox20", "currencyID" => "RSD", "security_token" => "7o5sleEKZRj2s62WVihw9i1oFrKEGz");
	$PAYMENT_INFO = (object) array("paymentID" => "gLnJZgC70UPRxxZBiwxovFsQTke4EmODx6FDJpLG", "printButton" => false);
	$SOCIAL = (object) array("facebook" => "https://www.facebook.com/handmadefantasybyantony/","twitter" => "", "instagram" => "https://www.instagram.com/antonistzanidakis/");
	
	$sql = mysqli_query("SELECT `tbl1`.*, `boughtworkshops`.`workshopID`   FROM (SELECT `tbl`.*, `packages`.`flag` FROM (SELECT `payments`.`paymentID`, trans_currencyID, payment_amount AS price, receiptID, payment_method, `subscriptions`.`date_start`, `subscriptions`.`date_end`,MONTH(subscriptions.date_start) AS month_start, MONTH(subscriptions.date_end) AS month_end, `subscriptions`.`type` AS packageID  FROM payments INNER JOIN `subscriptions` ON `subscriptions`.`paymentID` = `payments`.`paymentID` WHERE BINARY `payments`.paymentID = '".$PAYMENT_INFO->paymentID."' AND paid =  1) tbl LEFT OUTER JOIN `packages` ON `packages`.`packageID` = `tbl`.`packageID`) tbl1 LEFT OUTER JOIN `boughtworkshops` ON `boughtworkshops`.`paymentID` = `tbl1`.`paymentID` AND `boughtworkshops`.`username` = '".$USER->username."'");
	$pom = array();
	$i = 0;
	while($help_arr = mysqli_fetch_object_wrapper($sql))	$pom[$i++] = $help_arr;

	$sql = mysqli_query("SELECT email,name,surname,username FROM `users` WHERE BINARY username='".$USER->username."'");
	if(mysqli_num_rows_wrapper($sql)){
		$info = mysqli_fetch_object_wrapper($sql);
	}




	//$PAYMENT_INFO = (object) array("paymentID" => $_GET['pid']);
	$USER_MAIL = (object) array("email" => "", "username" => "lidox20", "name" => "Luka" ,  "currencyID" => "RSD", "security_token" => "sMQu01GrJjbBvqL3MgjTUJac4ALx6L");
		
				$message = '
			<!DOCTYPE html>
			<html>
			<head>
				<meta charset="utf-8">
				<meta name="viewport" content="width=device-width">
			</head>

			<body style="background: #FAFCFC; padding: 0; margin: 0; font-family: Arial; font-size: 100%;">
				<div style="width: 90%; margin: 0 auto; height:100%; background: white; border: 1px solid #DBDBDB; margin-top: 50px; border-radius: 3px">
					

					<div style="padding: 15px 20px; color: #131A6E;">
						<table style="width: 100%">
							<tr>
								<td style="width:100%; padding-left: 10px; text-align: center;"> 
									<img src="'.$FILE.'img/logo.png" style="width:300px;"> 
								</td>
							</tr>
							<tr>
								<td style="text-align: center">
									<img src="'.$FILE.'img/email_logo.png" style="width: 80%; max-width: 400px" align="center">
								</td>
							</tr>
							<tr>
								<td colspan="2" style="text-align: right;">
									<table style="width: 100%">
										<tr>
											<td colspan="2" style="font-weight: bold; text-align: right">Handmade Fantasy World</td>
										</tr>
										<tr>
											<td colspan="2">
												'.pop_obj($COMPANY_INFO, "location_".$USER->lang).'
											</td>
										</tr>
										<tr>
											<td style="text-align: right;"> <span style="text-align: right; font-weight: bold; font-size: 90%"> '.$lang->pib.'</span> '.$COMPANY_INFO->PIB.'</td>
										</tr>
										<tr>
											<td style="text-align: right;"> <span style="text-align: right; font-weight: bold; font-size: 90%"> '.$lang->mb.'</span> '.$COMPANY_INFO->MB.'</td>
										</tr>
										<tr>
											<td style="text-align: right;"> <span style="text-align: right; font-weight: bold; font-size: 90%"> '.$lang->email_UP.'</span> <a href="mailto:'.$COMPANY_INFO->email.'">'.$COMPANY_INFO->email.'</a></td>
										</tr>
									</table>
								</td>			
							</tr>
						</table>
					</div>
					
					
					<div style="padding: 15px 20px; color: #131A6E; text-align: left; ">

						<table align="left" style="width: 100%; text-align: left; margin-bottom: 10px">
									<tr style="font-size: 150%">
										<td style="width: 100px;">
											'.$lang->receipt.':
										</td>
										<td>
											IF - '.$pom[0]->receiptID.'
										</td>
										';

										if($pom[0]->payment_method == 1)
										{
										$message .= '
										<td style="text-align: right;">
											<div style="font-size: 60%; display: inline-block; position: relative; bottom: 7px; right: 10px; text-transform: uppercase;">'.$lang->payment_method.'</div>
											<div style="display: inline-block;"><img src="'.$FILE.'img/credit_cards.png" style="width: 25px;"></div>
										</td>
										';
										} else
										{
										$message .= '
										<td style="text-align: right;">
											<div style="font-size: 60%; display: inline-block; position: relative; bottom: 11px; right: 10px; text-transform: uppercase;">'.$lang->payment_method.'</div>
											<div style="display: inline-block;"><img src="'.$FILE.'img/paypal.png" style="width: 120px;"></div>
										</td>
										';	
										};

										$message .='
									</tr>						
							</table>

						<table style="width: 100%; background-color: #FAFAFA; border: 2px solid #C5C6C7">
							<tr style="background-color: #F2F2F2;">
									<th style="width: 5%; font-weight: bold; padding: 5px 10px;">
										'.$lang->number.'
									</th>
									<th style="width: 45%; font-weight: bold; padding: 5px 10px;">
										'.$lang->name_package.'
									</th>
									<th style="width: 8%; font-weight: bold; padding: 5px 10px; text-align: right">
										'.$lang->price_pdvA.'
									</th>
									<th style="width: 8%; font-weight: bold; padding: 5px 10px; text-align: right">
										'.$lang->pdv.'
									</th>
									<th style="width: 8%; font-weight: bold; padding: 5px 10px; text-align: right">
										'.$lang->price.'
									</th>
							</tr>
							';
							$str = "";
							$sum = 0;
							for($j=0;$j<count($pom);$j++)
							{
								if($pom[$j]->workshopID)
								{
									$str .= 
								'
									<tr>
									<td style="padding-left: 10px">'.($j+1).'.</td> <td>'.$pom[$j]->heading.' - '.$lang->workshopUP.'</td> <td style="text-align:right">'.print_money_PLAINTXT($pom[$j]->price - $pom[$j]->price * 0.2, 2).' '.$pom[$j]->trans_currencyID.'</td> <td style="text-align:right">'.print_money_PLAINTXT($pom[$j]->price * 0.2,2).' '.$pom[$j]->trans_currencyID.'</td> <td style="text-align:right">'.print_money_PLAINTXT($pom[$j]->price,2).' '.$pom[$j]->trans_currencyID.'</td>
									</tr>
								';
								}
								else
								{
									$str .= 
									'
									<tr>
										<td>'.($j+1).'</td> 
										<td>'.$lang->package_self.' - '.print_duration($pom[$j]->flag).' <div style="font-size:90%"> '.$lang->subFor.' <span style="font-weight:bold"> '.$lang->c_months[$pom[$j]->month_start-1].'</span></div></td> 
										<td style="text-align:right">'.print_money_PLAINTXT($pom[$j]->price - $pom[$j]->price * 0.2, 2).' '.$pom[$j]->trans_currencyID.'</td> 
										<td style="text-align:right">'.print_money_PLAINTXT($pom[$j]->price * 0.2,2).' '.$pom[$j]->trans_currencyID.'</td> 
										<td style="text-align:right">'.print_money_PLAINTXT($pom[$j]->price,2).' '.$pom[$j]->trans_currencyID.'</td>
									</tr>									
									';
								}
								$sum += $pom[$j]->price;
							}


							$message .= $str.'
						</table>						
					</div>

					<div style="padding: 15px 20px; color: #131A6E;">
						<table style="width: 100%;">
							<tr>
								<td style="width: 60%; padding-bottom: 40px"> ';

								if(!$PAYMENT_INFO->printButton) { $message .= '

									<div style=" font-size:150%; padding-bottom: 10px"> '.$lang->buyer_info.': </div>

									<table style="width: 70%; text-align: left;border: 2px solid #C5C6C7;">
										<tr style="background-color: #F2F2F2">
											<th style="font-weight: bold; padding: 5px 10px;">
												'.$lang->user_nameone.'
											</th>
											<th style="font-weight: bold; padding: 5px 10px;">
												'.$lang->user_name.'
											</th>
											<th style="font-weight: bold; padding: 5px 10px;">
												'.$lang->user_surname.'
											</th>
											<th style="font-weight: bold; padding: 5px 10px;">
												'.$lang->email_UP.'
											</th>
										</tr>

										<tr>
											<td style="text-align:left; padding-left:5px"> '.$info->username.' </td>
											<td style="text-align:left;padding-left:5px"> '.$info->name.' </td>
											<td style="text-align:left;padding-left:5px"> '.$info->surname.' </td>
											<td style="text-align:left;padding-left:5px"> '.$info->email.' </td>
										</tr>

									</table>';	
								}

									$message .= '
								</td>
								<td style="width: 40%">
									<table style="width: 100%; text-align: right;border: 2px solid #C5C6C7">

										<tr style="background-color: #F2F2F2">
											<th style="font-weight: bold; padding: 5px 10px;">
												'.$lang->pdv.' %
											</th>
											<th style="font-weight: bold; padding: 5px 10px;">
												'.$lang->start_price.'
											</th>
											<th style="font-weight: bold; padding: 5px 10px;">
												'.$lang->pdv_amount.'
											</th>
										</tr>

										<tr>
											<td>20,00</td> <td>'.print_money_PLAINTXT($sum - $sum*0.2,2).' '.$pom[0]->trans_currencyID.'</td> <td>'.print_money_PLAINTXT($sum*0.2,2).' '.$pom[0]->trans_currencyID.'</td>
										</tr>

										<tr>
											<td>'.$lang->totalUP.'</td> <td>'.print_money_PLAINTXT($sum - $sum*0.2,2).' '.$pom[0]->trans_currencyID.'</td> <td>'.print_money_PLAINTXT($sum*0.2,2).' '.$pom[0]->trans_currencyID.'</td>
										</tr>
										<tr>
											<th>'.$lang->total_buy.'</th> <th colspan="2">'.print_money_PLAINTXT($sum,2).' '.$pom[0]->trans_currencyID.'</th>
										</tr>

									</table>
								</td>
							</tr>
							';

							if($PAYMENT_INFO->printButton) {

							$message .= '
							<tr>
								<td style="width:60%"></td>
									<td colspan="3" align="right"> 
										<a href="'.$FILE.'user/receipt/'.$PAYMENT_INFO->paymentID.'/'.$USER->security_token.'" target="blank"> 
											<button style="background-color: #131A6E; color: white; margin-top: 10px; margin-right: 10px; padding: 5px; border-radius: 20px; width: 40%; font-weight: bold;"> '.$lang->check_invoice.' 
											</button> 
										</a>
									</td>
							</tr> ';

							}
							$message .= '

							<tr>
								<td style="font-weight: bold; ">'.$lang->warning.':</td>
							</tr>
							<tr>
								<td><span style="font-weight: bold;">Handmade Fantasy World</span> '.$lang->handmade_isin.'. 
								<div>'.$lang->formoreinfo.' <a href="mailto: banking@handmadefantasyworld.com">banking@handmadefantasyworld.com</a></div>
								</td>
							</tr>
							<tr>
							';

									if($PAYMENT_INFO->printButton) {

									$message .= '
										<td style="padding-top:20px"> '.$lang->ifLinkNotWork.'
											<div style="font-size:12px"> 
												<a href="'.$FILE.'user/receipt?pid='.$PAYMENT_INFO->paymentID.'&token='.$USER->security_token.'">
													'.$FILE.'user/receipt?pid='.$PAYMENT_INFO->paymentID.'&token='.$USER->security_token.'
												</a>
											</div>
										</td>
										 ';

									}
									$message .= '
							</tr>
						</table>
					</div>

					<div style="background-color:#F2F2F2; width:100%; padding-bottom: 15px;">
					<hr style="color:#131A6E;">
						<table style="width:100%" align="center">
							<tr>
								<th>
									<div style="text-align:center; margin-top:5px;">
											<a href="'.$SOCIAL->facebook.'" target="blank"><img src="'.$FILE.'img/facebook.png" style="width:30px; margin-right: 5px;"></a>	
											<a href="'.$SOCIAL->instagram.'" target="blank"><img src="'.$FILE.'img/instagram.png" style="width:30px; margin-right: 5px;"></a>										
										</div>
								</th>
							</tr>
						</table>
						
						<table style="width:100%; text-align:center;  font-size:70%">
							<tr>
								<td>
									<p style="margin:0;"> Copyright © Handmade Fantasy World Ltd, All rights reserved. </p>
									<p style="margin:0;"> <a href="http://handmadefantasyworld.com" target="_blank"> handmadefantasyworld.com </a> </p>		
								</td>
							</tr>
						</table>
					</div>
			</div>
			</body>
			</html>
			';

			echo $message;

?>