<?php 
	
	$prepath = "";
	include "../../functions.php";
	include "../../connect.php";
	include "../../global.php";
	include "../../getDATA.php";
	$domain = "http://localhost:9000/new/";

	// LINIJA 100, TREBA ZAMENITI LINK ZA SLIKU SA '.$RESP->image.'
	// MALO SAM SE IGRAO OVDE SA OVIM OBJEKTROM REVIEW, POSTO NE ZNAM ODAKLE DA GA UZMEM
	// UZEO SAM SLIKU TVOG PROFILA

	$REVIEW = (object) array("email" => "", "username" => "lidox20", "currencyID" => "RSD", "rating" => "3.5", "lang" => "SR", "workshopID" => "tjOmPXxdBvH2iq48B1Sw", "id" => "1");

			// Tice se samo review-a, odnosno info samo o review
			$stars = determine_rating($REVIEW->rating, 1);
			$sql = mysqli_query("SELECT image,email,name,surname FROM `users` WHERE BINARY username='".$REVIEW->username."'");
			if (mysqli_num_rows_wrapper($sql))
			{
				$RESP = (object) array();
				while($pom = mysqli_fetch_object_wrapper($sql)) $RESP = $pom;
			} 

			$sql = mysqli_query("SELECT `adm`.* , `users`.`lang`  FROM (SELECT * FROM `administrators` WHERE `administrators`.`access_workshop_comments`=1) adm LEFT OUTER JOIN `users` ON `users`.`username`=`adm`.`username`");


				$LANGUAGE = return_lang(strtolower($REVIEW->lang),"");

				$sql1 = mysqli_query("SELECT heading_".$lang_acr." AS heading, workshopID FROM `workshops` WHERE BINARY workshopID='".$REVIEW->workshopID."'");
				if(mysqli_num_rows_wrapper($sql1))
				{
					while($pom1 = mysqli_fetch_object_wrapper($sql1)) $heading_lang = $pom1;	
				}

				$message = '
				<!DOCTYPE html>
				<html>
				<head>
					<meta charset="utf-8">
					<meta name="viewport" content="width=device-width">
				</head>

				<body style="background: #FAFCFC; padding: 0; margin: 0; font-family: Arial; font-size: 100%;">
					<div style="width: 95%; margin: 0 auto; height:100%; background: white; border: 1px solid #DBDBDB; margin-top: 50px; border-radius: 3px">
					
						<div style="padding: 15px 20px;">
							<table style="width:100%;">
								<tr>
									<th>
										<img src="'.$domain.'img/logo.png" style="width: 250px;">
									</th>
								</tr>
								<tr>
									<th>
										<img src="'.$domain.'img/email_logo.png" style="width: 80%; max-width:400px;" >
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
												<p> <div style="background-color:#12DF78; text-align:center; width:25px; font-size:90%; font-weight:bold; border: 3px solid #12DF78; color:white; border-radius:50%; padding: 3px"> +1 </div> </p>
											</td>											
											<td>
													<h1 style="color:#131A6E; "> '.$LANGUAGE->new_review.' </h1>
											</td>											
										</table>
									</td>									
								</tr>
							</table>
						</div>								
						
						
						<div style="background-color:#F2F2F2; height:auto; width:100%; padding: 0 0 15px 0;">
						<hr style="color:#131A6E;">
							<table style="width:350px; height:100%; text-align:center; margin: auto">
								<tr>
									<td style="width: 200px;">
										<img src="'.$domain.'img/'.$stars[0].'" style="margin-right:5px; max-width: 15%; width: 30px">
										<img src="'.$domain.'img/'.$stars[1].'" style="margin-right:5px; max-width: 15%; width: 30px">
										<img src="'.$domain.'img/'.$stars[2].'" style="margin-right:5px; max-width: 15%; width: 30px">
										<img src="'.$domain.'img/'.$stars[3].'" style="margin-right:5px; max-width: 15%; width: 30px">
										<img src="'.$domain.'img/'.$stars[4].'" style="margin-right:5px; max-width: 15%; width: 30px">
									</td>
									<td style="font-size:160%; padding-top: 10px;">
										<div style="padding: 10px 30px; background: #F7F7F7; display:inline-block; border-radius:2px; border: 1px solid #E3E3E3; color:#131A6E; ">'.$REVIEW->rating.'</div>
									</td>
								</tr>								
							</table>
						</div>
						
						<div style="padding: 20px 20px; color:#131A6E;">
							<table style="width:100%; text-align: center; margin: auto">
								<tr>
									<td style="text-align: center">
										<table style="width: 40%; margin: auto">
											<tr>
												<td>
													<img src="'.$domain.'img/users/ol1.jpg" style="height:100px; width:100px; border-radius:50%;">
												</td>									
												<td style="padding-left:10px;">
													<p style="text-align:left; font-size:100%;">
														'.$LANGUAGE->user.' <strong> '.$RESP->name.' '.$RESP->surname.' </strong> '.$LANGUAGE->hasLeft_aReview.' <a href="'.$domain.'workshop/'.$heading_lang->workshopID.'" target="_blank">'.$heading_lang->heading.'</a>.'.$LANGUAGE->see_ApproveDelete.'.
													</p>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>

						<!--
						<div>
							<table style="width:100%; text-align:center;">
								<tr>
									<td>
										<a href="'.$domain.'admin/reviews/overview/'.$REVIEW->id.'" target="_blank">
											<button style="background-color:#131A6E; border: 2px solid white; border-radius:5px; display:inline-block; height:50px; margin: 0 auto; color:white; font-size: 18px; padding: 10px 25px; cursor:pointer;"> '.$LANGUAGE->review_overview.' </button>
										</a>
									</td>
								</tr>
							</table>
						</div>
						-->
						
						<div style="background-color:#F2F2F2; width:100%;">
						<hr style="color:#131A6E;">
							<table style="width:100%; text-align:center;  font-size:70%">
							<tr>
								<td style="padding: 10px 0 20px 0">
									<p style="margin:0;"> Copyright © Handmade Fantasy World Ltd, All rights reserved. </p>
									<p style="margin:0;"> <a href="http://handmadefantasyworld.com" target="_blank"> handmadefantasyworld.com </a> </p>		
								</td>
							</tr>
						</div>						
					</div>
				</body>
				</html>
			';

			echo $message;

?>