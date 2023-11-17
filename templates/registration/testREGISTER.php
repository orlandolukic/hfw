<?php 
	
	$prepath = "";
	include "../../functions.php";
	include "../../connect.php";
	include "../../global.php";
	include "../../getDATA.php";
	$domain = "http://localhost:9000/new/";

	$SOCIAL = (object) array("facebook" => "https://www.facebook.com/handmadefantasybyantony/","twitter" => "", "instagram" => "");

	// NE ZNAM STA JE TOKEN , LINIJA 79,88

	$REGISTRATION = (object) array("email" => "", "username" => "lidox20", "name" => "Luka", "rating" => "3.5", "lang" => "SR", "workshopID" => "tjOmPXxdBvH2iq48B1Sw", "id" => "1", "token" => "sMQu01GrJjbBvqL3MgjTUJac4ALx6L");

				$message = '
				<!DOCTYPE html>
				<html>
				<head>
					<meta charset="utf-8">
					<meta name="viewport" content="width=device-width">


				</head>

				<body style="background: #FAFCFC; padding: 0; margin: 0; font-family: Arial; font-size: 100%;">
					<div style="width: 90%; margin: 0 auto; height:100%; background: white; border: 1px solid #DBDBDB; margin-top: 50px; border-radius: 3px">
						
						<div style="padding: 15px 20px">
							<table style="width: 100%; text-align: center">
								<tr>
									<td style="width:65%; padding-left: 10px; text-align: center;"> 
										<table style="">
											<tr>
												<td style="width:75%; padding-left: 10px; text-align: center"> 
													<img src="'.$FILE.'img/logo.png" style="width:250px;">
												</td>												
											</tr>
											<tr>
												<td>
													<img src="'.$FILE.'img/email_logo.png" style="width: 80%; max-width:400px;" >
												</td>
											</tr>
										</table>
									</td>
								

									<td style="text-align: right; width:35%">
										<table style="width:100%">
											<tr>
												<td colspan="2" style="font-weight: bold; text-align:center;">Handmade Fantasy World</td>
											</tr>
											<tr>
												<td>
													<table>
														<tr>
															<td width="80" style="text-align: right">'.$lang->city.':</td>
															<td style="text-align: left; padding-left: 10px">'.$lang->belgrade.', '.$lang->serbia.'</td>
														</tr>
														<tr>
															<td style="text-align: right">'.$lang->email.':</td>
															<td style="text-align: left; padding-left: 10px"><a href="mailto: office@handmadefantasyworld.com">office@handmadefantasyworld.com</a></td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>			
								</tr>
							</table>
						</div>
						
						<div style="padding: 25px 30px; background: white">
							<table style="width:100%; color: #131A6E">		
								<tr> 
									<td>
									<h3 style="color: #131A6E; margin: 0; font-weight: normal">'.$lang->dear.' <span style="font-weight: bold;">'.$REGISTRATION->name.'</span>,</h3>								
									<div style="margin-top: 5px">'.$lang->reg_succ.'</div>
									</td>
								</tr>			
							</table>
						</div>						

						<div style="background: white; color: white; font-weight: normal; padding: 15px 30px;">		
							<table style="width: 100%; text-align:left">			
								<tr> 
									<td style="font-weight: normal; color:#131A6E;">
									    '.$lang->thankYou.', <br> <div style="font-weight: bold; margin-top: 5px">Handmade Fantasy World</div>
										
									</td>
									<td style="text-align: right; width: 50%">
										<table style="width: 100%; text-align: right">
											<tr>
												<td style="font-weight: bold; color:#131A6E;">
													'.$lang->contact_Us.'
												</td>
											</tr>
											<tr>
												<td>
													<div style=" margin-top:5px;">
													<a href="'.$SOCIAL->facebook.'" target="blank"><img src="'.$FILE.'img/facebook.png" style="width:30px; margin-right: 5px;"></a>	
													<a href="'.$SOCIAL->facebook.'" target="blank"><img src="'.$FILE.'img/facebook.png" style="width:30px; margin-right: 5px;"></a>													
													</div>
												</td>
											</tr>
										</table>						
									</td>
								</tr>										
							</table>
						</div>


						<div style="background-color:#F2F2F2; width:100%;">
						<hr style="color:#131A6E; margin:0">							
							<table style="width:100%; text-align:center;  font-size:70%">
								<tr>
									<td style="padding: 15px 10px">
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