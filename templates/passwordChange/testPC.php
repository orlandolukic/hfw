<?php 
	
	$prepath = "";
	include "../../functions.php";
	include "../../connect.php";
	include "../../global.php";
	include "../../getDATA.php";
	$domain = "http://localhost:9000/new/";


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
					<div style="width: 70%; margin: 0 auto; height:100%; background: white; border: 1px solid #DBDBDB; margin-top: 50px; border-radius: 3px">
						
						<div style="padding: 15px 20px;">
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
							</table>	
						</div>
						
						<div style="width:100%;">
							<table style="width:100%; text-align:center;">
								<tr>
									<td style="width:45%;" align="center">
										<table>
											<td>
												<h2 style="color:#131A6E; margin:0"> '.$lang->request_changePass.'</h2>
											</td>
										</table>
									</td>									
								</tr>
							</table>
						</div>
						
						<hr style="#131A6E">
						
						<div style="color:#131A6E; padding-left:20px; ">
							<table style="width:100%; padding: 20px 0">
								<tr>
									<td>
										<p style="margin:0;"> '.$lang->dear.' <strong> '.$USER_MAIL->name.' </strong> , </p>
									</td>
								</tr>
								<tr>
									<td>
										<p style="margin:0;"> '.$lang->changePass_pushButton.' </p>
									</td>
								</tr>
								<tr>
									<td style="padding-top: 10px;">									
										<a href="'.$FILE.'user/settings/changePassword?username='.$USER_MAIL->username.'&token='.$USER_MAIL->token.'" target="_blank">
										<button style="background-color:#131A6E; border: 2px solid white; border-radius:5px; display:inline-block; height:50px; margin: 0 auto; color:white; font-size: 18px; padding: 10px 25px; cursor:pointer;"> '.$lang->request_approve.' </button>
										</a>
									</td>	
								</tr>
								<tr>
									<td style="padding-top:20px"> Ukoliko dugme ne radi, prekopirajte ovaj link u vas browser 
											<div style="font-size:12px"> 
												<a href="'.$FILE.'user/settings/changePassword?username='.$USER_MAIL->username.'&token='.$USER_MAIL->token.'">
													'.$FILE.'user/settings/changePassword?username='.$USER_MAIL->username.'&token='.$USER_MAIL->token.'
												</a>
											</div>
									</td>
								</tr>
							</table>
						</div>					
						
						<div style="background-color:#F2F2F2; width:100%; padding-bottom: 20px;">
						<hr style="color:#131A6E;">
							<table style="width:100%; text-align:center;  font-size:70%; padding-top: 10px;">
							<tr>
								<td>
									<p style="margin:0;"> Copyright © 2016 Handmade Fantasy World Ltd, All rights reserved. </p>
									<p style="margin:0;"> <a href="http://www.handmadefantasyworld.com" target="_blank"> handmadefantasyworld.com </a> </p>		
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