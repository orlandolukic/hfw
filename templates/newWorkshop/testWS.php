<?php 
	
	$prepath = "";
	include "../../functions.php";
	include "../../connect.php";
	include "../../global.php";
	include "../../getDATA.php";
	$domain = "http://localhost/hfw/";


	//$PAYMENT_INFO = (object) array("paymentID" => $_GET['pid']);
	$SENDER = (object) array("email" => "", "username" => "lidox20", "currencyID" => "RSD");
			
			
	$pom = (object) array("currencyID" => "RSD", "lang" => "SR", "email" => "oluki1996@gmail.com", "username" => "lidox20");	

	$sql_main = mysqli_query("SELECT workshopID, heading, subheading, date_publish, date_end,image,price, priceRSD, forsale FROM (SELECT `wsh`.*, CONCAT(`images`.imageID,'.',`images`.`extension`) AS image, `images`.`im_index` FROM (SELECT `workshops`.`workshopID` , `workshops`.`heading_".$pom->lang."` AS heading ,`workshops`.`price_".$pom->currencyID."` AS price, `workshops`.`price_RSD` AS priceRSD ,  `workshops`.`subheading_".$pom->lang."` AS subheading , `workshops`.`date_publish`, `workshops`.forsale , `workshops`.`date_end` FROM workshops WHERE `workshops`.active=1 AND BINARY workshopID = 'tjOmPXxdBvH2iq48B1Sw' AND (`workshops`.`date_end` = '0000-00-00' OR (CURDATE() <= `workshops`.`date_end`))) wsh LEFT OUTER JOIN images ON `images`.`workshopID` = `wsh`.`workshopID` ORDER BY im_index ASC LIMIT 3) tbl");

			$i=0; $workshop = array();
			while($sql_pom = mysqli_fetch_object_wrapper($sql_main)) $workshop[$i++] = $sql_pom;

				$LANGUAGE = return_lang(strtolower($pom->lang),"");

				$to = $pom->email;
				$subject = " - ".$workshop[0]->heading." - Handmade Fantasy World";
				$message = '
				<!DOCTYPE html>
				<html>
				<head>
					<meta charset="utf-8">
					<meta name="viewport" content="width=device-width">
				</head>

				<body style="background: #FAFCFC; padding: 0; margin: 0; font-family: Arial; font-size: 100%;">
					<div style="width: 80%; margin: 0 auto; height:100%; background: white; border: 1px solid #DBDBDB; margin-top: 50px; border-radius: 3px">
					
						<div style="padding: 30px 20px; color:#131A6E; text-align:center;">
							<table style="width:100%;">
								<tr>
									<th>
										<img src="'.$FILE.'img/logo.png" style="width:300px;">
									</th>
								</tr>
								<tr>
									<td style="text-align: center">
										<img src="'.$FILE.'img/email_logo.png" style="max-width: 80%, width: 400px" >
									</td>
								</tr>
								<tr>
									<td>
										<h1 style="margin: 0"> Handmade Fantasy World </h1>
									</td>								
								</tr>
							</table>	
						</div>
						
						<hr style="color:#131A6E;">
						
						<div style="width:100%; color:#131A6E; padding: 30px 0">
							<table style="width:100%;">
								<tr>
									<th>
										<h1 style="margin:10px 0 0 0;">
											*** '.$workshop[0]->heading.' ***
										</h1>
									</th>
								</tr>

								
									<tr>
										<td>
											<div style="text-align: center; padding-bottom: 20px; color:#3D3F40">
												'.$workshop[0]->subheading.'
											</div>
										</td>
									</tr>
								
							</table>
						</div>
						
						<div style="width:100%">
							<table style="width:100%; border-collapse: collapse">
								<tr>
									<th>
										<img src="'.$FILE.'img/content/'.$workshop[0]->image.'" style="width:100%; max-height:600px;">
									</th>
								</tr>
							</table>
						</div>
						
						<div style="width:100%; color:#131A6E; text-size:100%;">
							<table style="width:100%; text-align:left; padding: 40px 30px;">
								<tr>
									<td style="text-transform: uppercase; font-weight: bold; font-size: 80%"> Opis radionice </td>	
								</tr>
								<tr>
									<td>
										<div style="padding-top: 5px">
										'.$workshop[0]->subheading.'
										</div>
									</td>
								</tr>
							</table>
						</div>

						';

						if(mysqli_num_rows_wrapper($sql_main)==2)
						{
							$message .='
						
								<div style="width:100%">
									<table style="width:100%; border-collapse: collapse; height: 180px">
											<tr style="vertical-align: top">
												<td style="width:100%; margin: 0 auto; text-align:center"> <img src="'.$FILE.'img/content/'.$workshop[1]->image.'" style="width: 50%; margin: auto"> </td>
											</tr>
									</table>
								</div>

							';
						} else if(mysqli_num_rows_wrapper($sql_main)==3)
						{
							$message .='
						
								<div style="width:100%;">
									<table style="width:100%; border-collapse: collapse; height: 180px">
											<tr style="vertical-align: top">
												<td style="width:50%; padding-right:5px;"> <img src="'.$FILE.'img/content/'.$workshop[1]->image.'" style="width:100%; max-height:350px;"> </td>
												<td style="width:50%;"> <img src="'.$FILE.'img/content/'.$workshop[2]->image.'" style="width:100%; max-height:350px;"> </td>
											</tr>
									</table>
								</div>

							';
						};

						//$workshop[0]->forsale = 1;
						//$workshop[0]->priceRSD = 4200;
						//$workshop[0]->price = 35;
						//	If workshop is for sale
						if ($workshop[0]->forsale) {
						$message .='
						<div style="padding-right: 40px; padding-top: 20px; text-align: right;">
							<div style=" color: #36BD31">
								<div style="background: #36BD31; width:10px; height: 10px; position: relative; top: -1px; display: inline-block; border-radius: 50%"></div> <span style="font-weight: bold;">'.$LANGUAGE->workshop_forSale.'</span>
							</div>

							<div style="font-size: 70px; color: #131A6E; position: relative;">';

							if ($SENDER->currencyID !== "RSD")
							{
								$message .= '
								<div style="background: #3AB5DE; color: white; display: inline-block; padding: 5px 10px; font-size: 20px; border-radius: 3px; box-shadow: -1px -1px 10px -4px rgba(0,0,0,0.75);">$ '.print_money_PLAINTXT($workshop[0]->price,2).'</div>
								';
							}

							$message .= '
								<span style="padding-right: 45px; ">'.print_money_PLAINTXT($workshop[0]->priceRSD,2).'</span> <span style="position:absolute; top:11px; right:0; font-size: 20px;">RSD</span>
							</div>
						</div>';
						};

						$message .= '						
						<div style="width:100%;">
							<table style="width:100%;">
								<tr>
									<th style="text-align: center;">
										<div style="margin: 20px 5px; display: inline-block; position: relative;">
											<a href="'.$domain.'workshop/'.$workshop[0]->workshopID.'" target="_blank">
												<button style=" background-color:#131A6E; border: 2px solid white; border-radius:5px; display:inline-block; height:50px; margin: 0 auto; color:white; font-size: 18px; padding: 10px 25px; cursor:pointer;"> <img src="info.png" width="22" style="position: absolute; left: 18px"><span style="padding-left: 20px;">'.$LANGUAGE->details.'</span> </button>
											</a>
										</div>
										';

									if ($workshop[0]->forsale) {
										$message .= '
										<div style="margin: 20px 5px; display: inline-block; position:relative;">
											<a href="'.$domain.'login.php?action=addCart&type=workshop&cid='.$workshop[0]->workshopID.'" target="_blank">
												<button style=" background-color:#36BD31; border: 2px solid white; border-radius:5px; display:inline-block; height:50px; margin: 0 auto; color:white; font-size: 18px; padding: 10px 25px; cursor:pointer;"> <img src="cart.png" width="22" style="position: absolute; left: 15px"><span style="padding-left: 15px;"> '.$LANGUAGE->add_toCart.'</span></button>
											</a>
										</div>';
									};
									$message .= '</th>
								</tr>
							</table>
						</div>
						
						
						
						<div style="background-color:#F2F2F2; width:100%;">
						<hr style="color:#131A6E;">
							<table style="width:100%" align="center">
								<tr>
									<th>
										<div style="text-align:center; margin-top:5px;">
											<a href="'.$SOCIAL->facebook.'" target="blank"><img src="'.$FILE.'img/facebook.png" style="width:30px; margin-right: 5px;"></a>
											<a href="'.$SOCIAL->facebook.'" target="blank"><img src="'.$FILE.'img/facebook.png" style="width:30px; margin-right: 5px;"></a>												
										</div>
									</th>
								</tr>
							</table>
							
							<table style="width:100%; text-align:center;  font-size:70%; padding-bottom:25px">
								<tr>
									<td>
										<p style="margin:0;"> Copyright © Handmade Fantasy World Ltd, All rights reserved. </p>
										<p style="margin:0;"> <a href="http://handmadefantasyworld.com" target="_blank"> handmadefantasyworld.com </a> </p>		

										<br>
										<p style="margin:0;"> Want to change how you receive these emails? </p>
										<p style="margin:0;"> You can <a href="'.$FILE.'user/unsubscribe/'.$pom->username.'/'.$pom->email.'" target="blank">unsubscribe from this list</a> </p>
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