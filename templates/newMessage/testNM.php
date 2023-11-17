<?php 
	
	$prepath = "";
	include "../../functions.php";
	include "../../connect.php";
	include "../../global.php";
	include "../../getDATA.php";
	$domain = "http://localhost:9000/new/";


	// OVAKO, SLIKU SAM MANUELNO STAVIO. TO TREBA SREDITI


	$CONTACT = (object) array("email" => "oluka@NEgmail.com", "username" => "lidox20", "name" => "Luka", "subject" => "Pozdrav", "message" => "Imas kupusa?", "currencyID" => "RSD");
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
									<img src="'.$domain.'img/logo.png" style="width:300px;">
								</th>
							</tr>
						</table>	
					</div>
					
					<div style="width:100%;">
						<table style="width:100%; text-align:center;">
							<tr>
								<td style="width:45%;" align="center">
									<table>
										<td>
											<p> <div style="background-color:#12DF78; text-align:center; width:25px; font-size:90%; font-weight:bold; border: 3px solid #12DF78; color:white; border-radius:50%;"> +1 </div> </p>
										</td>
										
										<td>
												<h1 style="color:#131A6E; "> '.$lang->new_Message.' </h1>
										</td>
										
									</table>
								</td>
								
								
							</tr>
						</table>
					</div>
					
					<hr style="#131A6E">
					
					<div style="width:100%; text-align:left;">
						<table style="width:100%; padding-left:20px;">
							<tr>
								<th>
									<img src="'.$domain.'img/user.png" style="width:50px;height: 50px;">
								</th>
							</tr>
						</table>
					</div>
					
					<div style="width:100%; color:#131A6E;  padding-left:20px;">
						<table style="width:100%; text-align:left;">
							<tr>
								<td>
									<p  style="margin:0">'.$lang->user_name.': <strong> '.$CONTACT->name.' </strong> </p>
								</td>
							</tr>
							
							<tr>
								<td>
									<p  style="margin:0">E-mail: <strong> <a href="mailto:'.$CONTACT->email.'"> '.$CONTACT->email.' </a> </strong> </p>
								</td>
							</tr>
							
							<tr>
								<td>
									<p  style="margin:0">'.$lang->subject.': '.$CONTACT->subject.' </p>
								</td>
							</tr>
						</table>
					</div>
					
					<div style="width:100%; padding: 0 20px; margin:auto; color:#131A6E;">
						<table style="width:95%;">
							<tr>
								<td>
									<p> '.$CONTACT->message.'</p>
								</td> 
							</tr>
						</table>
					</div>
					
					<div>
						<table style="width:100%; text-align:center;">
							<tr>
								<td>
									<a href="mailto:'.$CONTACT->email.'" target="_blank">
										<button style="background-color:#131A6E; border: 2px solid white; border-radius:5px; display:inline-block; height:50px; margin: 0 auto; color:white; font-size: 18px; padding: 10px 25px; cursor:pointer;"> '.$lang->message_Reply.' </button>
									</a>
								</td>
							</tr>
						</table>
					</div>
					
					<div style="background-color:#F2F2F2; width:100%;">
					<hr style="color:#131A6E;">
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