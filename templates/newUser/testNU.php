<?php 
	
	$prepath = "";
	include "../../functions.php";
	include "../../connect.php";
	include "../../global.php";
	include "../../getDATA.php";

	$FILE = "http://localhost/pr/";

	// NE ZNAM STA JE TOKEN , LINIJA 79,88

	$REGISTRATION = (object) array("email" => "", "username" => "lidox20", "name" => "Luka","surname" => "Orlandic" ,"rating" => "3.5", "lang" => "SR", "workshopID" => "tjOmPXxdBvH2iq48B1Sw", "id" => "1", "token" => "sMQu01GrJjbBvqL3MgjTUJac4ALx6L");

	$lang = return_lang(strtolower("SR"),"");

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
						</div>
						
						<div style="padding: 25px 30px; background: white">
							<table style="width:100%; color: #131A6E">	
								<tr>
									<td align="left">
										<table>
											<td>
												<p> <div style="background-color:#12DF78; text-align:center; width:25px; font-size:100%; font-weight:bold; border: 3px solid #12DF78; color:white; border-radius:50%;"> +1 </div> </p>
											</td>
											
											<td>
													<h2 style="color:#131A6E; "> '.$lang->newUser.' </h2>
											</td>
										</table>
									</td>
								</tr>
								<tr> 
									<td>
										<div style="margin-bottom:10px; margin-top: -20px;"> '.$lang->newUser_Register.'. </div>
									</td>
								</tr>
								<tr>
									<td>
										<table style="width:40%; text-align:left;  border: 2px solid #C5C6C7; margin-top: 10px;">
											<tr style="background-color: #FAFAFA;">
												<th style="padding:5px;"> '.$lang->user_name.' </th>
												<th> '.$lang->user_surname.' </th>
												<th> '.$lang->user_nameone.' </th>
											</tr>
											<tr>
												<td style="padding:5px;"> '.$REGISTRATION->name.' </td>
												<td> '.$REGISTRATION->surname.' </td>
												<td> '.$REGISTRATION->username.' </td>
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