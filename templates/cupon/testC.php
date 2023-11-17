<?php 
	
	$prepath = "";
	include "../../functions.php";
	include "../../connect.php";
	include "../../global.php";
	include "../../getDATA.php";
	$domain = "http://localhost/pr/";

	$SOCIAL   = (object) array("facebook" => "https://www.facebook.com/handmadefantasybyantony/","twitter" => "", "instagram" => "https://www.instagram.com/antonistzanidakis/");


	//$PAYMENT_INFO = (object) array("paymentID" => $_GET['pid']);
	$USER = (object) array("email" => "oluki1996@gmail.com", "username" => "lidox20", "lang" => "SR");

	$sql = mysqli_query("SELECT `users`.`lang`,`users`.`security_token`, `users`.`username`, `users`.`email` FROM `users` WHERE BINARY `users`.`username` = 'lidox20'");
	if(mysqli_num_rows_wrapper($sql)) {
		$user_coupon = mysqli_fetch_object_wrapper($sql);
	}else exit();
 
	$sql_main = mysqli_query("SELECT `workshops`.`workshopID`, `workshops`.`heading_".$user_coupon->lang."` AS header,`subheading_".$user_coupon->lang."` AS subheader, `coupons`.`couponID`, `coupons`.`username`, `coupons`.`experation_date`, `coupons`.`active` FROM `coupons` LEFT OUTER JOIN `workshops` ON `coupons`.`workshopID` = `workshops`.`workshopID` AND `coupons`.`username` = '".$user_coupon->username."' WHERE `coupons`.`send_coupon` = '1'");

			$i=0; $coupon = array();
				while($sql_pom = mysqli_fetch_object_wrapper($sql_main)) $coupon[$i++] = $sql_pom;
					

				$LANGUAGE = return_lang("sr","");

				$to = $user_coupon->email;
				$subject = "Cupon - Handmade Fantasy World";
				$message = '
				<!DOCTYPE html>
				<html>
				<head>
					<meta charset="utf-8">
					<meta name="viewport" content="width=device-width">
				</head>

				<body style="background: #FAFCFC; padding: 0; margin: 0; font-family: Arial; font-size: 100%;">
					<div style="width: 80%; margin: 0 auto; height:100%; background: white; border: 1px solid #DBDBDB; margin-top: 50px; border-radius: 3px">
					
						<div style="padding: 30px 20px; color:#19999C; text-align:center;">
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
									<td style="text-align: left;">
										<div style="font-size: 140%">'.$LANGUAGE->won_coupons.'</div>
									</td>
								</tr>
								<tr>
									<td style="text-align: left;">
										<div style="font-size: 90%">'.$LANGUAGE->check_coupons.'</div>
									</td>
								</tr>
							</table>	
						</div>

						';


						for($j=0;$j<$i;$j++) {

						$message .= '
						<div style="">
							<div>
								<div style="width:100%; color:#131A6E;">
									<table style="width:60%; margin:0 auto; text-align: left;">
										<tr>
											<td>
												<div style="margin: 10px; border-style: dashed; border-color:#19999C; border-width: 2px; background-color: rgba(25,200,209, 0.13); color: #0D4447; border-radius: 8px; position: relative; padding: 15px">
													<div style="position: absolute; right: 50px; top: -11px">
														<img src="'.$FILE.'img/scissors.png" style="width: 20px; transform: rotate(-90deg)">
													</div>
													<table style="width: 80%; margin: 0 auto;">
														<tr >
															<td style="padding-right:20px">
																<div style="width: 100%; ">
																	<table>
																		<tr style="vertical-align: top">
																			<td>
																				<img src="'.$FILE.'img/camera.png" style="width: 45px;padding-right:5px"> 
																			</td>
																			<td>
																				<table style="line-height: 12px">
																					<tr>
																						<td>
																							<span style="font-size: 120%; font-weight:bold;">'.$coupon[$j]->header.' </span>
																						</td>
																					</tr>
																					</tr>
																						<td>
																							<span style="text-transform:uppercase; font-size:80%"> '.$LANGUAGE->online_ws.'</span>
																						</td>
																					</tr>
																				</table>
																				
																			</td>
																		</tr>
																	</table>
																</div>
																
																<div style="width: 100%; ">
																	 '.$coupon[$j]->subheader.'
																</div>
															</td>
															<td style="text-align: right; width: 120px">
																<div style="display: inline-block; position: relative; vertical-align: bottom;">
																	<a href="'.$FILE.'user/video/'.$coupon[$j]->workshopID.'?action=login&security='.$user_coupon->security_token.'" target="_blank">
																		'.$LANGUAGE->watch_Now.'
																	</a>
																</div>
															</td>	
														</tr>
													</table>
												</div>								
											</td>							
										</tr>
										
									</table>
								</div>
							</div>
						</div>

						';


						}

						$message .= '

						<div style="padding: 20px; color:#19999C; text-align:left;">
							'.$LANGUAGE->we_hopeYoullEnjoy.'. 
							<br>
							'.$LANGUAGE->see_you.',
							<br>
							<span style="font-weight:bold;"> Handmade Fantasy World</span>
						</div>
						
						<div style="background-color:#F2F2F2; width:100%;">
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
							
							<table style="width:100%; text-align:center;  font-size:70%; padding-bottom:25px">
								<tr>
									<td>
										<p style="margin:0;"> Copyright © Handmade Fantasy World Ltd, All rights reserved. </p>
										<p style="margin:0;"> <a href="http://handmadefantasyworld.com" target="_blank"> handmadefantasyworld.com </a> </p>		

										<br>
										<p style="margin:0;"> Want to change how you receive these emails? </p>
										<p style="margin:0;"> You can <a href="'.$FILE.'user/unsubscribe/'.$user_coupon->username.'/'.$user_coupon->email.'" target="blank">unsubscribe from this list</a> </p>
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