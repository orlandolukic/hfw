<?php 
	
	$prepath = "";
	include "../../functions.php";
	include "../../connect.php";
	include "../../global.php";
	include "../../getDATA.php";
	$domain = "http://localhost/pr/";


	// SQL RADI. TESTIRAO SAM SA VISE USERA(SA 3 USERA SAM STAVIO NESTO U CART I SVA TRI IMENA SAM ISPISAO. DOHVATIO SAM I NJIHOVA IMENA I EMAIL-OVE)
	// SAMO SREDI TEMPLATE, AKO TI SE NESTO NE SVIDJA
	// ISPISAO SAM U SRED TEMPLATE-A SVA IMENA KOJA HVATA QUERY
	// GLHF

	$sql = mysqli_query("SELECT `users`.`username`, `users`.`name`, `users`.`email` FROM `users` INNER JOIN `cart` ON `users`.`username`=`cart`.`username` GROUP BY `users`.`username`");


	$niz = array();
	$i = 0;
	if(mysqli_num_rows_wrapper($sql) > 0) {
		while($pom = mysqli_fetch_object_wrapper($sql)) {
			
				$niz[$i] = $pom;
				$i++;
		}
	}
	
		$message = '<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width">
		</head>
		<body style="background: #FAFCFC; padding: 0; margin: 0; font-family: Arial; font-size: 100%;">
			<div style="width: 70%; margin: 0 auto; height:100%; background: white; border: 1px solid #DBDBDB; margin-top: 50px; border-radius: 3px">
				
				<div style="padding: 5px 20px;">
					<table style="width:100%;">
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
					</table>	
				</div>
				
				<div style="width:100%;">
					<table style="width:100%; text-align:center;">
						<tr>
							<td style="width:45%;" align="center">
								<table>
									
									<td>
											<h1 style="color:#131A6E; "> New Update </h1>
									</td>
									
								</table>
							</td>
						</tr>
					</table>
				</div>
				
				<hr style="#131A6E">

				
				<div style="width:100%; padding: 0 20px; margin-top:20px; margin-bottom: 20px; color:#131A6E;">
					<table style="width:95%;">
						<tr>
							<td>
								<div>
									Poštovani <span style="font-weight: bold"> ('.$niz[0]->name.')</span>, zbog tehničkih problema niste bili u mogućnosti da koristite svoju kreditnu karticu za plaćanje. Ova opcija vam je sada dozvoljena i možete da nastavite sa plaćanjem. 
								</div>
							</td> 
						</tr>
						<tr>
							<td>
								<div>
									Sva pokupljena imena: ';  for($j=0;$j<$i;$j++) { $message .= ' '.$niz[$j]->name.'	';	} $message .= '		 
								</div>
							</td> 
						</tr>
					</table>
				</div>
				
				<div>
					<table style="width:100%; text-align:left; padding: 0 20px; margin-bottom:20px; color:#131A6E;">
						<tr>
							<td>
								<div>
									Srdačno, <span style="font-weight: bold"> Handmade Fantasy World </span>
								</div>
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
		</html>';

			echo $message;

?>