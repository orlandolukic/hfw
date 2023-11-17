<?php 
	
	$prepath = "";
	include "../../functions.php";
	include "../../connect.php";
	include "../../global.php";
	include "../../getDATA.php";
	$domain = "http://localhost:9000/new/";

	//$PAYMENT_INFO = (object) array("paymentID" => $_GET['pid']);
	$USER = (object) array("email" => "", "username" => "lidox20", "name" => "Luka", "currencyID" => "RSD");

				$message = '
				<!DOCTYPE html>
				<html>
				<head>
				<meta charset="utf-8">
				<meta name="viewport" content="width=device-width">
				</head>

				<body style="background: #FAFCFC; padding: 0; margin: 0; font-family: Arial; font-size: 100%;">
					<div style="width: 90%; margin: 0 auto; height:100%; background: white; border: 1px solid #DBDBDB; margin-top: 50px; border-radius: 3px">
						
						<div style="padding: 15px 20px 0 20px; text-align: center; color:#131A6E;" >
							<table style="width: 100%;">
								<tr>
									<td style="width:75%; padding-left: 10px"> 
									<img src="'.$domain.'img/logo.png" style="width:250px;" align="center"> 
								</td>
								</tr>
								<tr>
									<td>
										<img src="'.$domain.'img/email_logo.png" style="width: 80%; max-width: 400px;">
									</td>
									
								</tr>
								
							</table>
						</div>
						
						<div style="padding: 25px 30px; background: white;border-top: 1px solid #131A6E;">
							<table style="width:100%; color: #131A6E">		
								<tr> 
									<td>
									<h3 style="color: #131A6E; margin: 0; font-weight: normal">'.$lang->dear.' <span style="font-weight: bold;">'.$USER->name.'</span>,</h3>
								
									<div style="margin-top: 5px">'.$lang->success_Activation.'</div>
									</td>
								</tr>			
							</table>
						</div>

						<div style="background: white; color: #131A6E; font-weight: normal; padding: 15px 30px; border-top: 1px solid #131A6E; ">		
							<table style="width: 100%; text-align:left">			
								<tr> 
									<td style="font-weight: normal;">
									    '.$lang->happy_YoureBack.', <br> <div style="font-weight: bold; margin-top: 5px">Handmade Fantasy World</div>
										
									</td>
									<td style="text-align: right; width: 50%">
										<table style="width: 100%; text-align: right">
											<tr>
												<td style="font-weight: bold">
													'.$lang->contact_Us.'
												</td>
											</tr>
											<tr>
												<td>
													<div style="text-align: right; display: inline-block; margin-top: 5px">									
														<a href="http://facebook.com" target="_blank"> <img src="'.$domain.'img/facebook.png" style="width:30px; margin-right: 10px;" align="left" > </a>						
														<a href="http://facebook.com" target="_blank"> <img src="'.$domain.'img/instagram.png" style="width:30px; margin-right: 10px;" align="left" > </a>
														
														<a href="http://facebook.com" target="_blank"> <img src="'.$domain.'img/twitter.png" style="width:30px" align="left" > </a>

													</div>
												</td>
											</tr>
										</table>						
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