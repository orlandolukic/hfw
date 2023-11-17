<?php

// !$REDIRECT = true === 1;
if( !isset($REDIRECT) ||  isset($REDIRECT) && $REDIRECT == true )
{
	header("location: ../index.php");
};

function send_email() {

global $TEMPLATE_SELECT;

// HEADERS
$headers  = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: Handmade Fantasy World <no-reply@handmadefantasyworld.com>';
$IS_SENT  = false;
/*
$owner    = "office@handmadefantasyworld.com";
$antonis  = "antonis@handmadefantasyworld.com";
$banking  = "banking@handmadefantasyworld.com"; 
*/
$owner    = "oluki1996@gmail.com";
$antonis  = "oluki1996@gmail.com";
$banking  = "oluki1996@gmail.com"; 
$SOCIAL   = (object) array("facebook" => "https://www.facebook.com/handmadefantasybyantony/","twitter" => "", "instagram" => "https://www.instagram.com/antonistzanidakis/");

switch($TEMPLATE_SELECT)
{
	case "newMessageFromWebsite":
		global $FILE, $lang, $CONTACT;
		$to = $owner;
		$subject = $lang->new_Message." - Handmade Fantasy World";
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
											<p> <div style="background-color:#12DF78; text-align:center; width:25px; font-size:90%; font-weight:bold; border: 3px solid #12DF78; color:white; border-radius:50%;"> +1 </div> </p>
										</td>										
										<td>
											<h1 style="color:#131A6E;"> '.$lang->new_Message.' </h1>
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
									<img src="'.$FILE.'img/user.png" style="width:50px;height:50px;">
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
									<p>'.$CONTACT->text.'</p>
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
		break;


		case "question":
		global $FILE,$heading,$lang,$CONTACT;
		$to = $antonis;
		$subject = $lang->question_WS;
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
						</table>	
					</div>
					
					<hr style="#131A6E">
						<div>
							<h1 style="color:#131A6E; text-align:center; margin:0px; "> '.$heading.' </h1>
							<div style=" color:#131A6E; text-align:center; margin-top:5px;">'.$lang->question_WS.'</div>
						</div>

						<div style="width:100%; text-align:left;">
						<table style="width:100%; padding-left:20px;">
							<tr>
								<th style="width:10px;">
									<img src="'.make_image($CONTACT->image, $FILE).'" style="width:50px;height: 50px; border-radius:50px;">
								</th>

								<td style="padding-left:10px;">

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

								</td>
							</tr>
						</table>
					</div>
					
				
					<div style="width:100%; padding: 20px; margin:auto; color:#131A6E;">
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
							<tr>
								<td>
									<p style="font-size:100%; color:#131A6E;"> '.$lang->ifNot_ButtonRespond.' <a href="mailto:'.$CONTACT->email.'" target="_blank">'.$lang->here.'</a>. </p>
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
		break;

		// Pormenljive koje moraju da budu setovane pre ovog zahteva su : 
		// ==============================================================
		// $NEWWORKSHOP
		// workshopID
		// prepath
		case "newWorkshop":
			global $FILE, $CONNECTION;

			$sql  = mysqli_query($CONNECTION,"SELECT username,email,currencyID,lang FROM `users`  WHERE e_newsletter=1");
			$send = true;
			if(mysqli_num_rows_wrapper($sql))
			{
				while($pom = mysqli_fetch_object_wrapper($sql))
				{

				$sql_main = mysqli_query($CONNECTION,"SELECT workshopID, heading, subheading, date_publish, date_end,image,price FROM (SELECT `wsh`.*, CONCAT(`images`.imageID,'.',`images`.`extension`) AS image, `images`.`im_index` FROM (SELECT `workshops`.`workshopID` , `workshops`.`heading_".$pom->lang."` AS heading ,`workshops`.`price_".$pom->currencyID."` AS price, `workshops`.`subheading_".$pom->lang."` AS subheading , `workshops`.`date_publish` , `workshops`.`date_end` FROM workshops WHERE `workshops`.active=1 AND workshopID = '".$NEWWORKSHOP->workshopID."' AND (`workshops`.`date_end` = '0000-00-00' OR (CURDATE() <= `workshops`.`date_end`))) wsh LEFT OUTER JOIN images ON `images`.`workshopID` = `wsh`.`workshopID` ORDER BY im_index ASC LIMIT 3) tbl");

				$i=0; $workshop = array();
				while($sql_pom = mysqli_fetch_object_wrapper($sql_main)) $workshop[$i++] = $sql_pom;

				$LANGUAGE = return_lang(strtolower($pom->lang),$NEWWORKSHOP->prepath);

				$to = $pom->email;
				$subject = $LANGUAGE->new_workshop." - ".$workshop[0]->heading." - Handmade Fantasy World";
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
										<p style="margin:0;"> You can <a href="'.$FILE.'user/unsubscribe/'.$pom->username.'/'.$pom->email.'" target="blank">unsubscribe from this list</a> </p>
									</td>
								</tr>
							</table>
						</div>
						
					</div>
				</body>
				</html>
			';

				$IS_SENT = $IS_SENT && mail($to, $subject, $message, $headers);
				}// while
			}// if
		break;

		case "sendResetPasswordEmail":
			global $FILE,$lang,$USER_MAIL;
			$to = $USER_MAIL->email;
			$subject = $lang->change_pass." - Handmade Fantasy World";
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
									<td style="padding-top:20px"> '.$lang->btnEmailTmplNoWork.'
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

			break;

			case "registration":
			global $FILE, $lang,$REGISTRATION;
			$to = $REGISTRATION->email;
			$subject = $lang->request_approve." - Handmade Fantasy World";
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
													<div style="text-align:right; margin-top:5px;">
													<a href="'.$SOCIAL->facebook.'" target="blank"><img src="'.$FILE.'img/facebook.png" style="width:30px; margin-right: 5px;"></a>			
													<a href="'.$SOCIAL->instagram.'" target="blank"><img src="'.$FILE.'img/instagram.png" style="width:30px; margin-right: 5px;"></a>													
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
			break;

			case "email_change":
			global $FILE, $lang,$EMAIL;
			$to = $EMAIL->email;
			$subject = $lang->emailChange." - Handmade Fantasy World";
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
								<td style="width:75%; padding-left: 10px; text-align: center;"> 
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
							

							<td style="text-align: right">
								<table style="width: 240px">
									<tr>
										<td colspan="2" style="font-weight: bold;">Handmade Fantasy World</td>
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
							
						</table>
					</div>
					
					<div style="padding: 25px 30px; background: white">
						<table style="width:100%; color: #131A6E">		
							<tr> 
								<td>
								<h3 style="color: #131A6E; margin: 0; font-weight: normal">'.$lang->dear.' <span style="font-weight: bold;">'.$EMAIL->name.'</span>,</h3>								
								<div style="margin-top: 5px">'.$lang->succChangedEmail.'</div>
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
												<div style="text-align:center; margin-top:5px;">
												<a href="'.$SOCIAL->facebook.'" target="blank"><img src="'.$FILE.'img/facebook.png" style="width:30px; margin-right: 5px;"></a>	
												<a href="'.$SOCIAL->instagram.'" target="blank"><img src="'.$FILE.'img/instagram.png" style="width:30px; margin-right: 5px;"></a>													
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
								<td style="padding: 30px 10px">
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
			break;

			case "password_successfully_changed":
			global $FILE, $lang, $REQUEST;
			$to = $REQUEST->email;
			$subject = $lang->password_changed." - Handmade Fantasy World";
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
								<td style="width:75%; padding-left: 10px; text-align: center;"> 
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
							

							<td style="text-align: right">
								<table style="width: 240px">
									<tr>
										<td colspan="2" style="font-weight: bold;">Handmade Fantasy World</td>
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
							
						</table>
					</div>
					
					<div style="padding: 25px 30px; background: white">
						<table style="width:100%; color: #131A6E">		
							<tr> 
								<td>
								<h3 style="color: #131A6E; margin: 0; font-weight: normal">'.$lang->dear.' <span style="font-weight: bold;">'.$REQUEST->name.'</span>,</h3>								
								<div style="margin-top: 5px">'.$lang->aj_sucChangePass.'.</div>
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
												<div style="text-align:center; margin-top:5px;">
												<a href="'.$SOCIAL->facebook.'" target="blank"><img src="'.$FILE.'img/facebook.png" style="width:30px; margin-right: 5px;"></a>	
												<a href="'.$SOCIAL->instagram.'" target="blank"><img src="'.$FILE.'img/instagram.png" style="width:30px; margin-right: 5px;"></a>												
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
								<td style="padding: 30px 10px">
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
			break;

			case "review":
			global $FILE, $REVIEW, $CONNECTION;

			// Tice se samo review-a, odnosno info samo o review
			$stars = determine_rating($REVIEW->rating, 1);
			$sql = mysqli_query($CONNECTION,"SELECT image,email,name,surname FROM `users` WHERE BINARY username='".$REVIEW->username."'");
			if (mysqli_num_rows_wrapper($sql))
			{
				$RESP = (object) array();
				while($pom = mysqli_fetch_object_wrapper($sql)) $RESP = $pom;
			} 

			$sql = mysqli_query($CONNECTION,"SELECT `adm`.* , `users`.`lang`  FROM (SELECT * FROM `administrators` WHERE `administrators`.`access_workshop_comments`=1) adm LEFT OUTER JOIN `users` ON `users`.`username`=`adm`.`username`");

			if(mysqli_num_rows_wrapper($sql))
			{
			while($pom = mysqli_fetch_object_wrapper($sql))
			{
				$LANGUAGE = return_lang(strtolower($pom->lang),$REVIEW->prepath);

				$sql1 = mysqli_query($CONNECTION,"SELECT heading_".$lang_acr." AS heading, workshopID FROM `workshops` WHERE BINARY workshopID='".$REVIEW->workshopID."'");
				if(mysqli_num_rows_wrapper($sql1))
				{
					while($pom1 = mysqli_fetch_object_wrapper($sql1)) $heading_lang = $pom1;	
				}

				$to = $pom->email;
				$subject = $LANGUAGE->new_review." - ".$REVIEW->rating." - ".$heading_lang;
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
										<img src="'.$FILE.'img/logo.png" style="width: 250px;">
									</th>
								</tr>
								<tr>
									<th>
										<img src="'.$FILE.'img/email_logo.png" style="width: 80%; max-width:400px;" >
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
										<img src="'.$FILE.'img/'.$stars[0].'" style="margin-right:5px; max-width: 15%; width: 30px">
										<img src="'.$FILE.'img/'.$stars[1].'" style="margin-right:5px; max-width: 15%; width: 30px">
										<img src="'.$FILE.'img/'.$stars[2].'" style="margin-right:5px; max-width: 15%; width: 30px">
										<img src="'.$FILE.'img/'.$stars[3].'" style="margin-right:5px; max-width: 15%; width: 30px">
										<img src="'.$FILE.'img/'.$stars[4].'" style="margin-right:5px; max-width: 15%; width: 30px">
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
													<img src="'.$FILE.'img/users/ol1.jpg" style="height:100px; width:100px; border-radius:50%;">
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
				$IS_SENT = $IS_SENT && mail($to, $subject, $message, $headers);
			};	
			$send = true;
			};
			break;


			// When user comes back
			case "reactivation":
			global $FILE, $lang, $USER;
			$to = $USER->email;
			$subject = $lang->re_Activation." - Handmade Fantasy World";
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
									<img src="'.$FILE.'img/logo.png" style="width:250px;" align="center"> 
								</td>
								</tr>
								<tr>
									<td>
										<img src="'.$FILE.'img/email_logo.png" style="width: 80%; max-width: 400px;">
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
													<div style="text-align:center; margin-top:5px;">
													<a href="'.$SOCIAL->facebook.'" target="blank"><img src="'.$FILE.'img/facebook.png" style="width:30px; margin-right: 5px;"></a>
													<a href="'.$SOCIAL->instagram.'" target="blank"><img src="'.$FILE.'img/instagram.png" style="width:30px; margin-right: 5px;"></a>
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
		break;

		
		case "payment_receipt":
			global $FILE, $USER, $COMPANY_INFO, $lang, $PAYMENT_INFO, $CONNECTION;

			$sql = mysqli_query($CONNECTION,"SELECT `tbl1`.*, `boughtworkshops`.`workshopID`   FROM (SELECT `tbl`.*, `packages`.`flag` FROM (SELECT `payments`.`paymentID`, trans_currencyID, payment_amount AS price, receiptID, payment_method, `subscriptions`.`date_start`, `subscriptions`.`date_end`,MONTH(subscriptions.date_start) AS month_start, MONTH(subscriptions.date_end) AS month_end, `subscriptions`.`type` AS packageID  FROM payments INNER JOIN `subscriptions` ON `subscriptions`.`paymentID` = `payments`.`paymentID` WHERE BINARY `payments`.paymentID = '".$PAYMENT_INFO->paymentID."' AND paid =  1) tbl LEFT OUTER JOIN `packages` ON `packages`.`packageID` = `tbl`.`packageID`) tbl1 LEFT OUTER JOIN `boughtworkshops` ON `boughtworkshops`.`paymentID` = `tbl1`.`paymentID` AND `boughtworkshops`.`username` = '".$USER->username."'");
			$pom = array();
			$i = 0;
			while($help_arr = mysqli_fetch_object_wrapper($sql))	$pom[$i++] = $help_arr;


		
			$to = ($PAYMENT_INFO->printButton ? $USER->email : $banking );
			$subject = $lang->payment." - Handmade Fantasy World";
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
											<td style="text-align:left; padding-left:5px"> '.$USER->username.' </td>
											<td style="text-align:left;padding-left:5px"> '.$USER->name.' </td>
											<td style="text-align:left;padding-left:5px"> '.$USER->surname.' </td>
											<td style="text-align:left;padding-left:5px"> '.$USER->email.' </td>
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
			break;

			case "newUser":
			global $FILE,$REGISTRATION,$lang;
			$to = $antonis;
			$subject = $lang->newUser." - Handmade Fantasy World";
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
			break;

}; // Switch

	if (!isset($send) || isset($send) && $send == false )
	{
		$IS_SENT = mail($to, $subject, $message, $headers);
	}

	return $IS_SENT;
}
?>
