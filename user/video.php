<?php 
	if (!isset($_GET['wid']) || (isset($_GET['wid']) && trim($_GET['wid'])==="")) header("location: index.php?access=denied&fr=video.php&err=403");
//	Prep for include
	session_start();
	$prepath     = '../';
	$ALLOW_LOGIN = true;
	$INCLUDE     = (object) array("getDATA" => false);
    include $prepath."connect.php";
	include $prepath."functions.php";
	include $prepath."requests/userManagementCheck.php";
	
	if ($_SESSION['lang'] !== "EN")
	{
		include $prepath.strtolower($_SESSION['lang'])."/global.php";
		include $prepath.strtolower($_SESSION['lang'])."/getDATA.php";
	} else
	{
		include $prepath."global.php";
		include $prepath."getDATA.php";
	};
	include $prepath."pages.php";
	include $prepath."lang/func.php";
	include $prepath."lang/user_".strtolower($lang_acr).".php";
	include $prepath."requests/userManagement.php";
	include $prepath."code/ingredients.php";
	include $prepath."code/cookies.php"; // set --> $GRANT_ACCESS

	if ($GRANT_ACCESS) {

	$WORKSHOP = (object) array();
	$sql = mysqli_query($CONNECTION, "SELECT `tbl1`.*, `coupons`.couponID FROM (SELECT heading_".$USER->lang." AS heading, subheading_".$USER->lang." AS subheading, MONTH(date_publish) AS start_month, video_id, video_password, forsale, workshopID, f_index, date_publish, views FROM workshops WHERE BINARY workshopID = '".$_GET['wid']."' AND date_publish<=CURDATE() AND active = 1) tbl1 LEFT OUTER JOIN `coupons` ON `coupons`.workshopID = `tbl1`.`workshopID` AND `coupons`.`username` = '".$USER->username."' LIMIT 1");
	if (!mysqli_num_rows_wrapper($sql))
	{
		header("location: ".$FILE."user/");
	} else
	{
		while($t = mysqli_fetch_object_wrapper($sql)) $WORKSHOP = $t;
		if(!isset($ACCESS) && !$WORKSHOP->couponID || isset($ACCESS) && !$ACCESS)
		{
			if (!$USER->grant_access) 
			{			
			//	Check if user has access to this video
			//	Check boughtworkshops
				$sql1 = mysqli_query($CONNECTION,"SELECT * FROM boughtworkshops WHERE BINARY username = '".$USER->username."' AND BINARY workshopID = '".$_GET['wid']."'");
				if (!mysqli_num_rows_wrapper($sql1))
				{
					$sql2 = mysqli_query($CONNECTION,"SELECT MONTH(date_start) AS smonth, MONTH(date_end) AS emonth FROM (SELECT subscriptions.*, MONTH(`subscriptions`.date_start) AS smonth, MONTH(date_end) AS emonth FROM subscriptions WHERE BINARY username = '".$USER->username."' AND active = 1 AND CURDATE()>=date_start) tbl WHERE smonth <= ".$WORKSHOP->start_month." AND emonth >= ".$WORKSHOP->start_month);
					if (!mysqli_num_rows_wrapper($sql2) || (mysqli_num_rows_wrapper($sql2) && $WORKSHOP->forsale))
					{
						header("location: ".$FILE.".index.php?access=denied&fr=video.php&err=401");
					};
				};	
			};
		};
	};


	$sql_likes_GL = mysqli_query($CONNECTION,"SELECT * FROM likes WHERE BINARY workshopID = '".$_GET['wid']."' AND username != '".$USER->username."'");
	$likes = mysqli_num_rows_wrapper($sql_likes_GL);

	$sql = mysqli_query($CONNECTION,"SELECT * FROM likes WHERE BINARY username = '".$USER->username."' AND BINARY workshopID = '".$_GET['wid']."'");
	$liked = mysqli_num_rows_wrapper($sql) ? true : false;


//	Fetch Review
	$sql = mysqli_query($CONNECTION,"SELECT * FROM reviews WHERE BINARY workshopID = '".$_GET['wid']."' AND BINARY username = '".$USER->username."' LIMIT 1");
	$REVIEW = NULL;
	$i = 0;
	$hasReview = false;
	$sql_comments = $sql_comments_all = NULL;
	if (mysqli_num_rows_wrapper($sql))
	{
		$hasReview = true;
		while($t = mysqli_fetch_object_wrapper($sql)) $REVIEW = $t;
	//	Fetch Comments
		$sql_comments = mysqli_query($CONNECTION,"SELECT * FROM comments WHERE BINARY reviewID = '".$REVIEW->reviewID."' ORDER BY time DESC LIMIT 5");
		$sql_comments_all = mysqli_query($CONNECTION,"SELECT * FROM comments WHERE BINARY reviewID = '".$REVIEW->reviewID."'");
	};

//	Fetch Links
//	$sql_links = mysqli_query($CONNECTION,"SELECT `tbl`.linkID,`tbl`.website, `tbl`.`website_user`, `tbl`.country FROM (SELECT `links`.*, `countries`.country_".$USER->lang." AS country, `countries`.sort FROM links INNER JOIN `countries` ON `countries`.`countryID` = `links`.`countryID` AND `countries`.active = 1 WHERE BINARY workshopID = '".$_GET['wid']."') tbl ORDER BY `sort` ASC");
	$has_links = false;

	};

?>

<!DOCTYPE html>
<html>
<head>
	<?php print_HTML_data("head","video") ?>
</head>

<body class="<?= $bodyClass ?>">

	<?php printTopMenu(); ?>
	<?php printMainMenu(-1); ?>
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main margin-b-60">			
		<?php if ($GRANT_ACCESS) { ?>
		<?php printNavigation(2,[$lang->my_workshops,$WORKSHOP->heading],[$FILE."user/workshops"]); ?>		
		<div class="row">
			<div class="col-lg-12">	
				<?php if ($WORKSHOP->couponID) { ?>
				<div class="pull-right margin-t-40 md-dn">
					<div class="padding-l-10 padding-r-10">
						<div class="coupon-placeholder text-center">
							<div class="scissors"><img src="<?= $FILE ?>img/scissors.png"></div>
							<div class="strong smaller"><i class="fa fa-bell-o"></i> <?= $lang->have_coupon ?></div>
						</div>
					</div>
				</div>			
				<?php }; ?>

				<h1 class="page-header heading margin-md-b-30" style="margin-bottom: 0px"><?= $lang->online_workshop ?></h1>
				<div>
					<span class="strong"><a href="<?= $domain ?>workshop/<?= $WORKSHOP->workshopID ?>"><?= $WORKSHOP->heading ?></a></span>
					<span class="smaller"><i class="fa fa-calendar"></i> <?= make_date(-1, $WORKSHOP->date_publish) ?></span>
					<span class="smaller"><i class="fa fa-eye"></i> <?= $WORKSHOP->views ?></span>
				</div>
				<?php if ($WORKSHOP->couponID) { ?>
				<div class="pull-right margin-t-30 margin-md-t-20 dn md-db">
					<div class="padding-l-10 padding-r-10">
						<div class="coupon-placeholder text-center">
							<div class="scissors"><img src="<?= $FILE ?>img/scissors.png"></div>
							<div class="strong smaller"><i class="fa fa-bell-o"></i> <?= $lang->have_coupon ?></div>
						</div>
					</div>
				</div>
				<?php }; ?>
				
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="col-md-12 margin-t-20">
				<div class="panel bg-info">
					<div class="panel-body">
						<i class="fa fa-info"></i> Currently, video has only subtitles in English and Serbian. Other languages will be provided in couple of days. 
					</div>
				</div>
			</div>
		</div>

		<div class="row margin-t-20">
			<div class="col-md-8 col-xs-12 col-sm-12">
				<div class="">
					<div class="panel bg-default">
						<div class="panel-body" style="padding: 0px 0 15px 0">
							<iframe id="iframe" data-wid="<?= $WORKSHOP->workshopID; ?>" src="https://player.vimeo.com/video/<?= $WORKSHOP->video_id; ?>" style="width: 100%" height="500" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
							<div class="padding-l-20 padding-r-20 margin-t-10 small">
								<a id="likeVideo" class="link-ord strong <?= (!$liked ? "link-not-selected" : "") ?>" href="javascript: void(0)"><span><i class="fa fa-thumbs-o-up"></i> <?= $lang->likeIt ?></span></a>
								<span id="likePreview" class="padding-l-10">
									<?php if ($likes>0) { ?>
										<?php if ($liked) { ?>
											<?php if ($USER->lang === "SR") { ?>
												<?= $lang->like_yand ?> <?= $likes.($likes>=2 && $likes<=4 ? " ".$lang->persons : " ".$lang->person) ?> volite ovo.
											<?php } elseif($USER->lang === "EN") { ?>
												<?= $lang->like_yand ?> <?= $likes.($likes>=2 ? " ".$lang->persons : " ".$lang->person) ?> <?= $lang->like_this ?>
											<?php }; ?>
											
										<?php } else { ?>

											<?php if ($USER->lang === "SR") { ?>
												<?= $likes.($likes>=2 && $likes<=4 ? " ".$lang->persons : " ".$lang->person) ?> <?= $lang->like_this ?>
											<?php } elseif($USER->lang === "EN") { ?>
												<?= $likes.($likes>=2 ? " ".$lang->persons : " ".$lang->person) ?> <?= ($likes==1 ? $lang->likes_this : $lang->like_this) ?>
											<?php }; ?>

										<?php }; ?>
									<?php } else { ?>
										<?php if ($liked) { ?>
											<?= $lang->youLikeThis ?>
										<?php } else { ?>
											<?= $lang->beFirstToLike ?>
										<?php }; ?>
									<?php }; ?>
								</span>
							</div>
						</div>						
					</div>
				</div>
			</div>

			<div class="col-md-4 col-xs-12 col-sm-12">
				<div class="panel bg-default">
					<div class="panel-body">
						<div class="color-theme strong" style="font-size: 140%"> <i class="fa fa-question-circle"></i> <?php echo $lang->ask_aQuestion ?></div>
						<div class="small"><?php echo $lang->here_postQuestion ?></div>
						<div class="margin-t-30" >
							<div class="form-group">
							    <input type="text" class="input-theme" id="question_title" placeholder="<?= $lang->qHeading ?>">
						    </div>
						    <div class="form-group">
							    <textarea type="text" class="input-theme" id="question" placeholder="<?= $lang->qText ?>" style="max-height: 250px; min-height: 50px;"></textarea>
						    </div>
						    <div class="form-group">
							    <div id="sendQuestion" class="btn-green btn-ord small disabled" data-workshop-id="<?php echo $_GET['wid'] ?>" data-success-sent="<?= $lang->succSendQuestion ?>">
				    				<i class="fa fa-envelope"></i> <?php echo $lang->send_question ?>
				    			</div>
							</div>
					    </div>

					    <div>
					    	<p class="text-info small"><?php echo $lang->respond_asSoonAs ?>.</p>
					    </div>
					</div>
				</div>
			</div>
		</div>

		<?php 

		$sql = mysqli_query($CONNECTION,"SELECT CONCAT(`images`.imageID,'.',`images`.extension) AS image FROM `images` WHERE BINARY workshopID='".$_GET['wid']."' AND im_index = '".$WORKSHOP->f_index."' LIMIT 1");
		if (mysqli_num_rows_wrapper($sql)) $pom = mysqli_fetch_object_wrapper($sql);

		?>

		<div class="row">
			<div class="col-md-6 col-xs-12 col-sm-12">
				<div class="panel bg-default">
					<div class="panel-body">
						<div class="color-theme strong" style="font-size: 120%;"><i class="fa fa-picture-o"></i> <?php echo $lang->finished_product ?>
						</div>
						<div><?= $lang->finishedProduct ?></div>
						<div class="image-optimised margin-t-20 gallery">
							<a href="<?= make_image_content($pom->image, $FILE); ?>" title="<?= $lang->finishedProductAcr ?>">
								<img src="<?= make_image_content($pom->image, $FILE); ?>" >
							</a>
						</div>
					</div>
				</div>
			</div>

			<!-- Ingredients -->
			<div class="col-md-6 col-xs-12 col-sm-12">
					<div class="panel bg-default">
						<div class="panel-body">
							<div class="color-theme strong" style="font-size: 140%"> <i class="fa fa-archive"></i> <?php echo $lang->ingredients ?></div>
							<div class="small"><?php echo $lang->for_thisWSYouNeed ?>:</div>
							
							<?php if ($has_ingr) { ?>							
							<div class="margin-t-10">											
			<?php for ($i=0, $tempID = -1, $margins = false, $print_free = true; $i<count($ingredients); $i++, $margins = true) { // Print ingredients ?>
							<?php if ($ingredients[$i]->manufacturerID && $tempID != $ingredients[$i]->manufacturerID || !$ingredients[$i]->manufacturerID && $print_free) { 
								if (!$ingredients[$i]->manufacturerID && $print_free) $print_free = false;
								$tempID = $ingredients[$i]->manufacturerID;
								$margins = false;
							?>
							<?php if ($i>0 && !$ingredients[$i]->manufacturerID) { ?>
							<div class="margin-t-10">
								<div class="divider" style="opacity: 0.4"></div>
							</div>
							<?php }; ?>
							<div class="<?= ($i>0 ? "margin-t-20" : "") ?>">
								<?php if ($ingredients[$i]->m_website && $ingredients[$i]->manufacturerID) { ?>
								<a href="<?= $FILE ?>l.php?type=<?= base64_encode("manufacturer"); ?>&manufacturerID=<?= $ingredients[$i]->m_id ?>&redirect_url=<?= $ingredients[$i]->m_website ?>" target="_blank" data-toggle="tooltip" data-placement="right" title="Visit <?= $ingredients[$i]->m_name ?> website"><span class="smaller bg-info padding-l-10 manufacturer"><?= $ingredients[$i]->m_name ?></span></a>
								<?php } elseif (!$ingredients[$i]->m_website && $ingredients[$i]->manufacturerID) { ?>
								<a href="javascript: void(0)" target="_blank" data-toggle="tooltip" data-placement="right"><span class="smaller bg-info padding-l-10 manufacturer" style="cursor: not-allowed; opacity: 0.6"><?= $ingredients[$i]->m_name ?></span></a>
								<?php }; ?>
							</div>
							<?php }; ?>
							<div>
								<div class="strong text-larger"><?= $ingredients[$i]->text ?></div>								
							<?php if ($ingredients[$i]->quantity) { ?>
								<div>									
									<span class="smaller"><?= $lang->quantity.": ".$ingredients[$i]->quantity." ".$ingredients[$i]->measure_acr ?></span>	
								</div>
							<?php }; ?>

							</div>
				<?php }; ?>	
							<div class="divider width-100"></div>

							<div class="margin-b-20">
								<div class="strong text-large margin-t-10"><i class="fa fa-trademark"></i> <?= $lang->suppliers ?></div>
								<div class="smaller"><?php echo $lang->take_aLookSup ?></div>	
							</div>													

							<?php for ($i=0; $i<count($manufacturers_list); $i++) { ?>
							<div class="row margin-t-10">
								<div class="col-md-12 col-xs-12 col-sm-12 <?= ($i>0 ? "margin-t-20" : "") ?>">
									<?php if ($manufacturers_list[$i]["m_logo"]) { ?>
									<a href="<?= $FILE ?>l.php?type=<?= base64_encode("manufacturer"); ?>&manufacturerID=<?= $manufacturers_list[$i]["id"] ?>&redirect_url=<?= $manufacturers_list[$i]["m_website"] ?>" target="_blank">
										<div class="manufacturer-image" <?= ($manufacturers_list[$i]["dimensions"]["hasDimensions"] ? "style=\"width: ".$manufacturers_list[$i]["dimensions"]["width"]."px; height: ".$manufacturers_list[$i]["dimensions"]["height"]."px;\"" : "") ?>>
											<img src="<?= $FILE ?>img/manufacturers/<?= $manufacturers_list[$i]["m_logo"]; ?>" >
										</div>
									</a>
									<?php }; ?>
									<div>
										<a class="link-ord" href="<?= ($manufacturers_list[$i]["m_website"] ? $FILE."l.php?type=".base64_encode("manufacturer")."&manufacturerID=".$manufacturers_list[$i]["id"]."&redirect_url=".$manufacturers_list[$i]["m_website"] : "javascript: void(0)") ?>" target="_blank" <?= (!$manufacturers_list[$i]["m_website"] ? "data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Currently no link\" style=\"cursor: not-allowed;\"" : "") ?>><span class="bg-info manufacturer color-white" <?= (!$manufacturers_list[$i]["m_website"] ? "style=\"opacity: 0.6; cursor: not-allowed; pointer-events: none\"" : "") ?>><?= $manufacturers_list[$i]["m_name"]; ?></span></a>
										<span class="padding-l-5">
											<span><?php echo $lang->product ?> <span class="strong badge bg-danger"><?= $count_manufacturers[$manufacturers_list[$i]["id"]]; ?></span></span>
										</span>
										<?php if ($manufacturers_list[$i]["m_website"]) { ?>
										<div class="smaller padding-t-5">
											<i class="fa fa-globe"></i> <?php echo $lang->website ?>:
											<a class="link-ord" href="<?= $FILE ?>l.php?type=<?= base64_encode("manufacturer"); ?>&manufacturerID=<?= $manufacturers_list[$i]["id"] ?>&redirect_url=<?= $manufacturers_list[$i]["m_website"] ?>" target="_blank"><?= $manufacturers_list[$i]["m_website_show"] ?></a>
										</div>
										<?php }; ?>
									</div>
								</div>
							</div>
							<?php }; ?>

							<?php if ($has_links) { ?>
							<div class="margin-t-20">
								<div class="divider width-100"></div>
							</div>
							<div class="margin-b-20">
								<div class="strong text-large margin-t-10"><i class="fa fa-external-link"></i> <?= $lang->links ?></div>	
								<div class="smaller"><?= $lang->shoppingMaterials ?></div>
							</div>
				
							<?php while($t = mysqli_fetch_object_wrapper($sql_links)) { ?>
							<div class="row country-line">
								<div class="col-md-4 col-xs-12 col-sm-12">
									<span class="strong small"><?= $t->country ?></span>
								</div>
								<div class="col-md-6 col-xs-12 col-sm-12">
									<a href="<?= $FILE ?>l.php?type=<?= base64_encode("link") ?>&lid=<?= $t->linkID ?>&redirect_url=<?= $t->website ?>" target="_blank"><span class="smaller"><i class="fa fa-external-link"></i> <?= $t->website_user ?></span></a>
								</div>
							</div>						
							<?php }; ?>
					
							<?php }; ?>
						
							<?php } else { ?>
							<div class="small padding-t-20 strong"><?= $lang->currNoIngr ?>
							<?php }; ?>
							</div>
						</div>
					</div>
				</div>
			</div>


		<div class="row">
			<div class="col-md-6 col-xs-12 col-sm-12">
				<div>
					<h2><i class="fa fa-comments"></i> <?= $lang->review ?></h2>
				</div>
			</div>
		</div>
		<div id="leaveReview" class="row <?= ($hasReview ? "dn" : "") ?>" data-review-id="<?= ($REVIEW ? $REVIEW->reviewID : ""); ?>" data-action="<?= (!$hasReview ? "new" : "change") ?>" data-wid="<?= $_GET['wid']; ?>">
			<div class="col-md-6 col-xs-12 col-sm-12">
				<div class="panel bg-default">
					<div class="panel-body" style="padding: 0">
						<div class="eticket-ui-user-data-pi smaller bg-info" style="right: 40px; z-index: auto">
							<i class="fa fa-cloud"></i> <?= $lang->wantToHearOp ?>
						</div>
						<!-- .workshop-text -->
				    	<div class="workshop-text" style="border: none">
				    		<div id="rMistake" class="margin-t-20 margin-b-20">
				    			<div class="mistake small dn">
				    				<i class="fa fa-exclamation-triangle"></i> <?php echo $lang->didNotFill_RightWay ?>
				    			</div>
				    		</div>
				    		<div class="margin-t-20 margin-b-20">
				    			<div class="row">
				    				<div class="col-md-7 col-xs-12 col-sm-12">
				    					<div id="rMaxCharsTitle" class="fl-right small">(<span>40</span>)</div>
				    					<div class="strong"><?php echo $lang->title_head ?></div>
						    			<div class="margin-t-5">
						    				<input id="rTitle" type="text" name="" placeholder="<?php echo $lang->enter_titleReview ?>" class="input-theme" />
					    				</div>
				    				</div>
				    			</div>
				    			<!-- .row -->
				    			<div class="row margin-t-20">
				    				<div class="col-md-12 col-xs-12 col-sm-12">
				    					<div class="strong"><?php echo $lang->grade ?> *</div>
						    			<div class="margin-t-5 div-inl">
						    				<input id="rRating" type="hidden" value="0" />
						    				<span id="rRatingOut" class="margin-l-20 large strong op-0"><span>0</span> <i class="fa fa-star star-color"></i></span>
						    				<div id="st-rating" class="margin-t-10 padding-l-20 pointer star-color">
						    					<span class="main">
					    							<i data-id="1" class="fa fa-star-o fa-2x"></i>
					    							<span>
				    									<i data-id="2" class="fa fa-star-o fa-2x"></i>
				    									<span>
			    											<i data-id="3" class="fa fa-star-o fa-2x"></i>
			    											<span>
			    												<i data-id="4" class="fa fa-star-o fa-2x"></i>
			    												<span>
			    													<i data-id="5" class="fa fa-star-o fa-2x"></i>
			    												</span>
			    											</span>
				    									</span>
					    							</span>
						    					</span>
						    				</div>							    				
					    				</div>
				    				</div>
				    			</div>
				    			<!-- /.row -->
				    			<div class="row margin-t-20">
				    				<div class="col-md-12 col-xs-12 col-sm-12">
				    					<div>
				    						<span class="strong"><?php echo $lang->review ?> *</span>
				    						<div id="rMaxChars" class="fl-right small">(<span>1600</span>)</div>
				    					</div>
				    					<div class="margin-t-10">
				    					<textarea id="rTextarea" class="input-theme" placeholder="<?php echo $lang->enter_textReview ?>"></textarea>
				    					</div>
				    				</div>
				    			</div>
				    			<div class="row margin-t-20">
				    				<div class="col-md-12 col-xs-12 col-sm-12">
				    					<div id="rSubmit" class="btn-theme-fix btn-rounded-success btn-padding-small smaller" data-message="<?php echo $lang->review_sent ?>">
				    						<i class="fa fa-envelope"></i> <?php echo $lang->send_Review ?>
				   						</div>
				   						<span class="<?= (!$hasReview ? "dn" : "") ?>">	
					   						<div id="rDecline" class="btn-theme-fix btn-rounded-danger btn-padding-small smaller margin-l-10">
					    						<i class="fa fa-times"></i> <?php echo $lang->cancel ?>
					   						</div>
				   						</span>	   										   						
				    				</div>
				    			</div>
				    			<div class="row margin-t-10">
				    				<div class="col-md-12 col-xs-12 col-sm-12">
				    					<span class="smaller"><?php echo $lang->necessary_field ?> *</span>			
				    				</div>
				    			</div>
				    		</div>
				    	</div>
				    	<!-- /.workshop-text -->
					</div>
				</div>
			</div>

					

			<div class="col-md-6 col-xs-12 col-sm-12">
				<div class="panel bg-default">
					<div class="panel-body">
						<div class="strong small">
							<span class="large color-theme"><i class="fa fa-tags"></i> <?= $lang->submitReview ?></span>
						</div>
						<div class="margin-t-10 small">
							<?= $lang->text_reviews ?>
						</div>
						<a href="<?= $domain ?>terms" style="display: inline-block;">
							<div class="margin-t-20">
								<div class="btn-green btn-ord small"><i class="fa fa-shield"></i> <?= $lang->details; ?></div>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
		<!-- #leaveReview -->



		<!-- Show Review -->
		<div id="review" class="row <?= ($hasReview ? "" : "dn") ?>">
			<div class="col-md-6 col-xs-12 col-sm-12">
				<div class="panel bg-default">
					<div class="panel-body relative user-comment" data-rating="<?= ($REVIEW ? $REVIEW->rating : "") ?>" data-delete-mssg="<?php echo $lang->delete_impressionSucc ?>.">
						<div class="fl-left">
							<img src="<?= make_image($USER->image, $FILE) ?>" class="user-comment-image" width="45" height="45" />
						</div>
						<div class="user-comment-notactive smaller <?php if (intval(($REVIEW ? $REVIEW->status : -2))===1) echo "dn"; ?>" style="right: 2px; top: 2px; bottom: auto; left: auto">
							<i class="fa fa-refresh"></i> <span><?php echo $lang->onCheck ?></span>
						</div>
						<div class="user-comment-delete dn">							
							<div class="container">
								<div class="col-md-12 col-xs-12 col-sm-12">
									<div class="margin-t-40 margin-md-t-10 strong" style="font-size: 120%"><i class="fa fa-trash"></i> <?php echo $lang->review_delete ?></div>
									<div class="margin-t-10 text-left">
										<?php echo $lang->areYouSureReview ?>
									</div>
								</div>
								<div class="col-md-6 col-xs-12 col-sm-12 margin-t-20 margin-md-t-10 text-center div-inl">
									<div class="rUINI_delConf btn-theme-fix btn-rounded-danger btn-full btn-padding-x-small small" style="border-radius: 0" data-id="<?= ($REVIEW ? $REVIEW->reviewID : "") ?>"><i class="fa fa-trash"></i> <?php echo $lang->delete ?></div>

									<div class="rUINI_decl btn-theme-fix btn-rounded-danger margin-md-t-10  btn-padding-x-small small" data-id="<?= ($REVIEW ? $REVIEW->reviewID : "") ?>" style="border-radius: 0;"><i class="fa fa-times"></i><?php echo $lang->cancel ?></div>
								</div>
							</div>
						</div>
						<div class="padding-l-60">
							<div class="strong"><?= $USER->name." ".$USER->surname ?></div>
							<?php $stars = determine_rating(($REVIEW ? $REVIEW->rating : 0)); ?>
							<div class="small">
								<span class="stars-num strong"><?= ($REVIEW ? $REVIEW->rating : "") ?></span>			
								<span class="stars star-color padding-l-10">
					    			<span class="fa <?= $stars[0] ?>"></span>
					    			<span class="fa <?= $stars[1] ?>"></span>
					    			<span class="fa <?= $stars[2] ?>"></span>
					    			<span class="fa <?= $stars[3] ?>"></span>
					    			<span class="fa <?= $stars[4] ?>"></span>	
				    			</span>				    				    			
				    		</div>
				    		<div class="smaller">
				    			<i class="fa fa-clock-o"></i> <span class="review-date"><?= make_date(-1,($REVIEW ? $REVIEW->date : date("Y-m-d"))) ?></span>, <span id="review-time"><?= make_time(($REVIEW ? $REVIEW->time : time())) ?></span> 
				    		</div>
				    		<div class="<?= ($REVIEW && $REVIEW->title === "" ? "dn" : "") ?> margin-t-10 strong review-title"><?= ($REVIEW ? $REVIEW->title : "") ?></div>
				    		<div class="review-text margin-t-10"><?= ($REVIEW ? $REVIEW->text : "") ?></div>
				    		<div class="margin-t-20 margin-b-10 div-inl">
				    			<div class="rUINI_ed btn-green btn-ord small">
				    				<i class="fa fa-edit"></i> <?= $lang->change_Review ?>
				    			</div>
				    			<div class="rUINI_del btn-danger btn-padding-x-small btn-ord small">
				    				<i class="fa fa-trash"></i> <?= $lang->delete_Review ?>
				    			</div>
				    		</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Show Review -->




		<div class="row <?= ($REVIEW && $REVIEW->status==1 ? "" : "dn")  ?> all-comments">
			<div class="col-md-6 col-xs-12 col-sm-12">
				<div class="panel panel-body">	
					<div class="padding-b-20">
						<div class="div-inl">    								
							<div class="initiator-comment-image pointer">
								<?php if ($adminActive) 
									{ $img = $ADMIN->image; $name = $ADMIN->name." ".$ADMIN->surname; } 
									else { $img = $USER->image; $name = $USER->name." ".$USER->surname; }; ?>
								<img class="user-comment-image margin-t-10" src="<?= make_image($USER->image, $FILE); ?>" width="45" height="45" title="<?= $name; ?>" data-toggle="tooltip" data-placement="top" />
							</div>	    								
							<div style="width: 100%" class="padding-l-10">
								<div class="initiator-comment" placeholder="<?= $lang->comment_site ?>" contenteditable="true" data-review-id="<?= ($REVIEW ? $REVIEW->reviewID : 0); ?>"></div>
							</div>				    								
						</div>
						<div class="text-right margin-t-10">
							<div class="btn-initiator-send smaller" data-review-id="<?= ($REVIEW ? $REVIEW->reviewID : 0) ?>"><?php echo $lang->send_comment ?></div>
						</div>
					</div>
					
				</div>
			</div>

			<div class="col-md-6 col-xs-12 col-sm-12">
				<div class="panel bg-default">
					<div class="panel-body comments-placeholder" style="padding: 0" data-review-id="<?= $REVIEW->reviewID ?>">
						<div class="pre-allComments padding-b-10" style="background: white; border-bottom: none;">
							<div class="pre-allComments padding-t-10 padding-b-10 l-sp-1 strong  uppercase padding-l-20"><?php echo $lang->comment_i ?> <span id="comments_num" class="badge bg-danger <?= ($sql_comments_all ? (mysqli_num_rows_wrapper($sql_comments_all)===0 ? "dn" : "") : "") ?>"><?= ($sql_comments_all ? (mysqli_num_rows_wrapper($sql_comments_all)>0 ? mysqli_num_rows_wrapper($sql_comments_all) : 0) : "") ?></span></div>

							<div class="no-more-comments small margin-t-10 padding-l-30 padding-b-30 <?= mysqli_num_rows_wrapper($sql_comments) ? "dn" : "" ?>" style="background: white;">
							<i class="fa fa-comment-o"></i> <?php echo $lang->no_commentsATM ?>
							</div>

							<div class="allComments" data-comments-num="<?= mysqli_num_rows_wrapper($sql_comments) ?>" style="background: white">
								<?php if ($sql_comments && mysqli_num_rows_wrapper($sql_comments)) {
									$go = 0;
									while($t = mysqli_fetch_object_wrapper($sql_comments)) {

									$pom1_sql = mysqli_query($CONNECTION,"SELECT * FROM users WHERE username='".$t->username."' LIMIT 1");
									while($j = mysqli_fetch_object_wrapper($pom1_sql)) $US_COMM = $j;
									$comm_ad = !!!true;

									if ($US_COMM->account_type)			
									{
										$pom1_sql = mysqli_query($CONNECTION,"SELECT * FROM administrators WHERE username='".$t->username."'");
										while($j = mysqli_fetch_object_wrapper($pom1_sql)) $US_COMM = $j;
										$comm_ad = true;
									}
								
								?>
								<div class="comment-user <?php if ($go>0) echo "margin-t-10  margin-md-t-20" ?>" data-comment-id="<?= $t->commentID; ?>">   								
									<div class="small fl-left">							
										<img src="<?= make_image($US_COMM->image, $FILE) ?>" class="user-comment-image" width="45" height="45" />	
									</div>
									<div class="small padding-l-60">
										<?php if ($comm_ad) { ?>
										<span class="admin-ticket">
											<?= $US_COMM->name." ".$US_COMM->surname; ?>
											<div class="verified pointer" data-toggle="tooltip" data-placement="top" title="<?php echo $lang->verifiedUser ?>"><i class="fa fa-check"></i></div>
										</span>
										<?php } else { ?>
										<span class="user-ticket strong">
											<?= $US_COMM->name." ".$US_COMM->surname; ?>
										</span>
										<?php }; ?>
										<?= html_entity_decode($t->text); ?>
										<div class="smaller padding-t-5">
											<i class="fa fa-calendar"></i> <?= make_date(!$lang_cond, $t->date); ?>,
											<i class="fa fa-clock-o"></i> <?= make_time($t->time); ?>
											<?php if ($userActive && $US_COMM->username===$user || $adminActive) {  ?>
											<span class="padding-l-10">
												<a class="rUINI_delThisComment text-error strong-hover" href="javascript: void(0)" data-review-id="<?= $REVIEW->reviewID ?>" data-comment-id="<?= $t->commentID ?>"><i class="fa fa-trash"></i> <?php echo $lang->delete ?></a>

												<span class="message-bef-del dn">
												<?php echo $lang->comment_delete ?>
												<span>
													<span class="padding-l-10">
														<a class="rUINI_delThisCommentProceed strong" href="javascript: void(0)"
														data-review-id="<?= $REVIEW->reviewID ?>" data-comment-id="<?= $t->commentID ?>"><?php echo $lang->yes ?></a>
													</span>
													<span class="padding-l-10">
														<a class="rUINI_delThisCommentDecline strong" href="javascript: void(0)"><?php echo $lang->no ?></a>
													</span>
												</span>
												</span>
											</span>
											<?php }; ?>
										</div>
									</div>
								</div>
								<?php $go++; }; }; 
								if (!$REVIEW) { $gr = false; } else {
								$gr = mysqli_num_rows_wrapper(mysqli_query($CONNECTION,"SELECT * FROM comments WHERE reviewID='".$REVIEW->reviewID."'"))>5; }; ?>
							</div>
							<div class="fetch-more-comments small margin-b-10 margin-md-t-20 btn-ord <?= (!$gr ? "dn" : "") ?>" data-fetch="0" data-review-id="<?= $REVIEW->reviewID ?>"><i class="fa fa-comments"></i> <?php echo $lang->more_comment ?></div>			    							
						</div>
					</div>
					<!-- .panel-body -->
				</div>
			</div>
		</div>

		<?php } else { // GRANT_ACCESS ?>
		<?php printNavigation(1,[$lang->video]); ?>		
		<div class="row margin-t-20">
			<div class="col-md-12 col-xs-12 col-sm-12">
				<div class="panel bg-default">
					<div class="panel-heading">
						<i class="fa fa-ban"></i> You don't have access to watch video from this device.
						<div class="divider width-100" style="opacity: 0.3"></div>
					</div>
					<div class="panel-body">
						<div>You have registered on maximum amount of devices. Please log in on device where you always watched videos.</div>
						<div class="margin-t-20 small">
							For more information contact us at <a href="mailto: <?= $COMPANY_INFO->e_office ?>" class="link-ord"><?= $COMPANY_INFO->e_office ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php }; ?>


			
	</div>	<!--/.main-->
	

	<?php print_HTML_data("script","video") ?>
</body>

</html>
