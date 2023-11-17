<?php 
	
	if (!isset($_GET['wid']))
	{
		header("location: index.php");
	}
	$wid = $_GET['wid'];

// Include Important Scripts
	session_start();
	$prepath = '../';
	include $prepath."connect.php";
	include "global.php";
	include "getDATA.php";
	include $prepath."pages.php";
	include $prepath."functions.php";

	// Include Language
	include $prepath."lang/func.php";
	include $prepath."lang/write_".strtolower($lang_acr).".php";
	include $prepath."workshopAnalytics.php";

// 	SQL STATEMENTS
	$sql = mysqli_query($CONNECTION, "SELECT workshopID, heading_".$lang_acr." AS heading, subheading_".$lang_acr." AS subheading, forsale, active, date_publish, date_end, text_".$lang_acr." AS text, sale, sale_date_start, sale_date_end, price_".$curr." AS price, price_RSD as priceRSD, views FROM workshops WHERE (BINARY workshopID = '$wid' OR BINARY heading_".$lang_acr." = '$wid') AND active = 1 AND (date_end = '0000-00-00' OR (date_end >= CURDATE() AND date_end != '0000-00-00')) LIMIT 1");
	if (!mysqli_num_rows_wrapper($sql)) header("location: ".$domain."workshops/"); else
	{
		while($t = mysqli_fetch_object_wrapper($sql)) $workshop = $t;
		$n = 0; $images = array();
		$sql2 = mysqli_query($CONNECTION, "SELECT * FROM images WHERE workshopID = '$wid' ORDER BY im_index ASC");
		while($t = mysqli_fetch_object_wrapper($sql2)) $images[$n++] = $t;
	};

	$sql_packages = mysqli_query($CONNECTION, "SELECT text_".$lang_acr." AS name, price_".$curr." AS price, price_RSD AS priceRSD, packageID, flag FROM packages");
	if (!$adminActive) 
	{
		if ($userActive)
		{
			$sql = mysqli_query($CONNECTION, "SELECT * FROM reviews WHERE BINARY workshopID='$wid' AND username='$user' AND status=-1");
			$sql_reviews = mysqli_query($CONNECTION, "SELECT * FROM reviews WHERE BINARY workshopID = '$wid' ORDER BY (`username`='".$user."') DESC, date DESC LIMIT 5");
			$revPerUser = mysqli_num_rows_wrapper(mysqli_query($CONNECTION, "SELECT username FROM reviews WHERE username='$user' AND workshopID='$wid'"));
			$sql_wishlist = mysqli_query($CONNECTION, "SELECT * FROM wishlist WHERE BINARY workshopID='$wid' AND username='$user'");
		} else
		{
			$sql_reviews = mysqli_query($CONNECTION, "SELECT * FROM reviews WHERE BINARY workshopID = '$wid' AND status=1 AND username IN (SELECT username FROM users WHERE active=1) ORDER BY date DESC LIMIT 5");
			$revPerUser = 0;
		};
	} else
	{
		$sql_reviews = mysqli_query($CONNECTION, "SELECT * FROM reviews WHERE workshopID = '$wid' AND username IN (SELECT username FROM users WHERE active=1)  ORDER BY status ASC, date DESC LIMIT 5");
		$revPerUser = 1;
	}
	$sql_reviews_all = mysqli_query($CONNECTION, "SELECT * FROM reviews WHERE workshopID = '$wid' AND username IN (SELECT username FROM users WHERE active=1) ");
	$sql_reviews_rat = mysqli_query($CONNECTION, "SELECT SUM(rating) AS sumRating, COUNT(*) AS count FROM reviews WHERE status=1 AND workshopID = '$wid' AND username IN (SELECT username FROM users WHERE active=1)");
	$p = 0; while($t = mysqli_fetch_object_wrapper($sql_reviews)) $reviews[$p++] = $t;
	$obj = mysqli_fetch_object_wrapper($sql_reviews_rat); $rating_sum = $obj->sumRating; $total_rating_sum = $obj->count; unset($obj);
	if ($userActive) $sql_subscription = mysqli_query($CONNECTION, "SELECT * FROM subscriptions WHERE username = '$user' AND active = 1 AND date_end>=CURDATE() AND date_start<=CURDATE() LIMIT 1");

//	Purchased or subscribed to workshops
if ($userActive) {
	$passCheck = (object) array("bought" => false, "subscriptions" => false);
	$sql = mysqli_query($CONNECTION, "SELECT * FROM boughtworkshops WHERE BINARY workshopID = '".$workshop->workshopID."' AND BINARY username='".$USER->username."'");
	$passCheck->bought = (bool) mysqli_num_rows_wrapper($sql);

	$sql = mysqli_query($CONNECTION, "SELECT * FROM subscriptions WHERE CURDATE()>=date_start AND CURDATE()<=date_end AND active=1 AND username='".$USER->username."'");
	$passCheck->subscriptions = (bool) mysqli_num_rows_wrapper($sql) && !$workshop->forsale;

} else $passCheck = NULL;


?>

<!DOCTYPE html>
<html>
<head>
	<?php print_HTML_data("head","workshop") ?>
	<?php print_HTML_data("script","workshop") ?>
</head>
<body class="<?= $bodyClass ?>">

	<?php echo $mobileMenu; $mobileMenu = NULL; ?>

	<!-- Fixed Menu -->
	<?php printMainMenu(1,3); ?>

	<!-- Header container -->
	<div class="header-container md-dn">
		<?php echo $headerInfo; $headerInfo = NULL; ?>
		<?php printMainMenu(0,-1); ?>		
	</div>
	<!-- /Header container -->

	<div class="container margin-t-20 margin-md-t-90">
		<div class="container">			
			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12">
					<ul class="breadcrumb small">
						<li><a href="<?php echo $domain ?>"><?php echo $lang->home ?></a></li>
						<li><a href="<?php echo $domain.pop_obj($PAGES, "workshops") ?>"><?php echo pop("workshops") ?></a></li>
						<li class="strong"><?php echo $workshop->heading; ?></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container margin-t-10">
		<div class="container">
			<div class="row">
				<div class="gallery col-md-5 col-xs-12 col-sm-12">
					<a id="workshopGallery" class="gallery-images" href="<?php echo $FILE ?>img/content/<?php echo pop_obj($images[0], "imageID") ?>.<?php echo pop_obj($images[0], "extension") ?>" data-image-name="<?php echo pop_obj($images[0], "imageID") ?>.<?php echo pop_obj($images[0], "extension") ?>" data-base="<?= $FILE ?>img/content/">
						<div style="height: 360px">
							<div class="image-container">
								<img id="workshopGalleryImage" class="workshop-main-image" src="<?php echo $FILE ?>img/content/<?php echo pop_obj($images[0], "imageID") ?>.<?php echo pop_obj($images[0], "extension") ?>" data-image="<?php echo pop_obj($images[0], "imageID") ?>.<?php echo pop_obj($images[0], "extension") ?>" >
								<div class="overlay"></div>
								<div class="i"><i class="fa fa-picture-o fa-2x"></i></div>
							</div>
						</div>
					</a>

					<div class="image-optimised">
						<?php for ($i=0; $i<$n; $i++) { ?>						
						<div class="col-md-4 col-xs-6 col-sm-6 margin-t-10" style="padding-left: 0;">
							<img class="addon-picture data-image" data-source="<?php echo pop_obj($images[$i], "imageID") ?>.<?php echo pop_obj($images[$i], "extension") ?>" src="<?php echo $FILE ?>img/content/<?php echo pop_obj($images[$i], "imageID") ?>.<?php echo pop_obj($images[$i], "extension") ?>" />
							<?php if ($i>0) { ?>
							<a class="gallery-images" data-image-name="<?php echo pop_obj($images[$i], "imageID") ?>.<?php echo pop_obj($images[$i], "extension") ?>" href="<?php echo $FILE ?>img/content/<?php echo pop_obj($images[$i], "imageID") ?>.<?php echo pop_obj($images[$i], "extension") ?>"></a>
							<?php }; ?>
						</div>
						<?php } ?>	
					</div>
				</div>

				<!-- Workshop details -->
				<div class="col-md-7 col-xs-12 col-sm-12 margin-md-t-30">
					<h1 style="margin: 0" class="strong">
						<?php echo $workshop->heading; ?>						
					</h1>					
					<div class="small margin-t-10"><?php echo $workshop->subheading; ?></div>
					<?php if ($workshop->forsale || $workshop->sale) { ?>
					<div class="padding-t-5 text-success">
						<span class="" style="font-size: 70%"><i class="fa fa-circle"></i></span>
						<span class="padding-l-5 smaller"><?php echo $lang->workshop_forSale1 ?></span>
					</div>
					<?php }; ?>

					<div class="div-inl padding-t-10 small">
						<div class="strong"><i class="fa fa-calendar"></i>  <?php echo pop("publishDate") ?>:</div>
						<div><?php echo make_date(($lang_cond ? 0 : 1), $workshop->date_publish); ?></div>
					</div>

					<?php 
					//	Sale
					if ($workshop->sale)
					{
						$date = pop_obj($workshop, "sale_date_end");
					?>
					<div class="workshop-sale margin-t-20 margin-b-30">
						<div class="eticket strong uppercase"><?php echo $lang->sale ?></div>
						<div class="padding-l-0">
							<div class="row">
								<div class="col-md-5 col-xs-10 col-sm-10"> 
									<h2 class="strong padding-t-10 uppercase" style="margin: 0"><?php echo $lang->sale ?></h2>
									<div class="small padding-t-5">										
								   		<?php echo $buyWarranty ?>
									</div>
								</div>
								<div class="div-inl col-md-7 text-center margin-md-t-20 col-xs-12 col-sm-12 countdown countdown-elems margin-t-10">
									<div class="number-placeholder col-xs-6">
										<div id="d"></div>
										<div class="smaller"><?php echo $lang->days ?></div>
									</div>		
									<div class="number-placeholder col-xs-6">
										<div id="h"></div>
										<div class="smaller"><?php echo $lang->hours ?></div>
									</div>	
									<div class="number-placeholder col-xs-6">
										<div id="m"></div>
										<div class="smaller"><?php echo $lang->minutes ?></div>
									</div>	
									<div class="number-placeholder col-xs-6">
										<div id="s"></div>
										<div class="smaller"><?php echo $lang->seconds ?></div>
									</div>														
								</div>
							</div>
							<div class="margin-t-30 margin-b-10 text-center">
								<div class="btn-buy-workshop-white uppercase l-sp-1"><i class="fa fa-shopping-cart"></i><?php echo $lang->subscribe ?></div>
							</div>
						</div>
						<script type="text/javascript">endDate = new Date(<?php echo part_date('Y',$date); ?>, <?php echo intval(part_date('M',$date))-1; ?>, <?php echo part_date('d',$date); ?>);</script>
						<script type="text/javascript" src="<?php echo $FILE ?>js/csscript.js"></script>
					</div>
					<?php
					}
					// End of section Sale
					?>

					<div class="divider margin-20 width-100"></div>

					<div class="margin-b-20">
						<div class="small"><?php echo $lang->share_workshop ?></div>
						<div class="large div-inl">
							<?php if ($userActive) { ?>
							<?php if (!$passCheck->bought && !$passCheck->subscriptions && !$USER->grant_access) { ?>
							<div id="addToWishList" data-workshop-id="<?= $wid ?>" data-after-mssg="<?= $lang->alreadyOnList ?>" data-success-mssg="<?= $lang->succAddWishLst ?>" class="pointer" data-set="<?= (mysqli_num_rows_wrapper($sql_wishlist) ? "true" : "false"); ?>">
								<i class="fa fa-heart icon icon-heart<?= (mysqli_num_rows_wrapper($sql_wishlist) ? " icon-heart-selected" : ""); ?>" data-toggle="tooltip" title="<?= (mysqli_num_rows_wrapper($sql_wishlist) ? $lang->alreadyOnList : $lang->addToWish); ?>" data-placement="top"></i>
							</div>
							<?php }; ?>

							<div class="pointer padding-l-10">
								<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fhandmadefantasyworld.com/%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" data-toggle="tooltip" title="<?php echo $lang->share_facebook ?>" data-placement="top">
								<i class="fa fa-facebook-official icon icon-facebook"></i>
								</a>
							</div>	
							<?php } else { ?>

							<div class="pointer">
								<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fhandmadefantasyworld.com/%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse" data-toggle="tooltip" title="<?php echo $lang->share_facebook ?>" data-placement="top">
								<i class="fa fa-facebook-official icon icon-facebook"></i>
								</a>
							</div>						

							<?php }; ?>							

							<div class="pointer padding-l-10"><i data-toggle="tooltip" title="<?php echo $lang->share_twitter ?>" data-placement="top" class="fa fa-twitter icon icon-twitter"></i></div>
						</div>
					</div>

					<div class="divider margin-20 width-100"></div>

					<?php 
					  $f = false;
					  $printed = false;
					  $subscrSection = true;
					  if ($userActive) { 						
						if ($f = (bool) mysqli_num_rows_wrapper($sql_subscription)) {
							while($t = mysqli_fetch_object_wrapper($sql_subscription)) $subscription = $t;
						};

						if ($passCheck->bought || $passCheck->subscriptions || $USER->grant_access) 
						{  // Subscribed for workshop
						   $printed = true;
						   $subscrSection = false;
						?>
						<div class="margin-t-20">
							<div class="smaller">
								<div class="fl-right"><?php echo $lang->access_workValid ?><?= (!$passCheck->bought && isset($subscription) && $subscription->date_end != "0000-00-00") ? 
								" ".pop("till").": ".make_date($lang_cond, pop_obj($subscription, "date_end")) : ": <span class=\"strong\">".mb_strtolower(pop("unlimited"),"UTF-8")."</span>"; ?></div>	

								<?php if ($USER->grant_access) { ?>
								<div class="margin-b-10">
									<div class="label label-success"><?= $lang->privileged ?></div>									
									<div class="padding-t-5"><?php echo $lang->accessAllVideos ?></div>
								</div>									
								<?php } else { ?>
								<div><?php echo $lang->subscription_seccess ?></div>
								<?php }; ?>														
							</div>
							<a href="<?= $FILE ?>user/video/<?= $workshop->workshopID ?>">
								<div class="btn-green btn-ord">
									<i class="fa fa-video-camera"></i> <?php echo $lang->watch_video ?>
								</div>
							</a>
						</div>
						<?php }; 
					}; // $userActive ...
					// Handling forsale request
					if ($workshop->forsale && !$printed)
					{
						if ($userActive)
						{
							$sql = mysqli_query($CONNECTION, "SELECT * FROM cart WHERE workshopID = '$wid' AND username='".$USER->username."'");
							$data_set = (mysqli_num_rows_wrapper($sql) ? 1 : 0);
						} else $data_set = 0;
					
					?>
						<div class="text-faded"><i class="fa fa-shopping-cart"></i> <?php echo $lang->buy_thisWS ?></div>
						<div class="strong large div-inl">
							<div>RSD <?= print_money_PLAINTXT($workshop->priceRSD,2); ?></div>
							<?php if ($curr !== "RSD" || $userActive && $USER->currencyID !== "RSD") { ?>
							<div class="foreign-currency-price smaller margin-l-10" style="opacity: 0.8; border-radius: 3px">
								<?= $currName ?> <?= print_money_PLAINTXT($workshop->price,2); ?>
							</div>
							<?php }; ?>
						</div>
						<div class="smaller margin-t-10">
							<?php echo $lang->when_youBuyWS ?>
						</div>
						<div class="margin-t-10">
							<?php if ($userActive) { ?>
							<div id="buyBtn" data-workshop-id="<?= $workshop->workshopID ?>" data-set="<?= $data_set ?>" class="btn-green btn-ord <?= ($data_set ? "disabled" : "") ?>" <?= ($data_set ? "data-placement=\"bottom\" data-toggle=\"tooltip\" title=\"".$lang->inCart."\"" : "") ?> style="pointer-events: auto;" data-added="<?= $lang->inCart ?>" data-success-mssg="<?= $lang->succAddedInCart ?>"><i class="fa fa-shopping-cart"></i> <?php echo $lang->add_toCart ?></div>
							<?php } else { ?>
							<a href="<?= $domain ?>login.php?action=addCart&type=workshop&cid=<?= $workshop->workshopID ?>">
								<div class="btn-green btn-ord" style="pointer-events: auto;" data-added="<?= $lang->inCart ?>" data-success-mssg="<?= $lang->succAddedInCart ?>"><i class="fa fa-shopping-cart"></i> <?php echo $lang->add_toCart ?></div>
							</a>
							<?php }; ?>
						</div>
			<?php   } elseif ($subscrSection) {					
						$i = 0;
						while($t = mysqli_fetch_object_wrapper($sql_packages)) $packages[$i++] = $t;
						?>

					<div class="div-inl">
						<div class="small"><?php echo $lang->package_type ?>: </div>
						<div class="padding-l-10">
							<div class="sort-options fold small" style=" padding-right: 60px; margin-left: 0">
								<div id="defaultOption" class="default-option" data-selected-id="<?= $packages[0]->packageID ?>"> <?= print_duration($packages[0]->flag) ?></div>
								<ul class="packages-select-options options text-left" style="z-index: 202; width: 100%">
									<?php 
									for ($p=0; $p<count($packages); ) echo "<li data-value=\"".$packages[$p]->packageID."\">".print_duration($packages[$p++]->flag)."</li>";
									?>														
								</ul>
								<i class="fold fa fa-chevron-down"></i>
								<i class="unfolded fa fa-chevron-up"></i>
							</div>
						</div>						
					</div>
					<div class="smaller margin-t-10"><?php echo $lang->package_detailHere ?> <a class="underline" href="<?php echo $domain ?>packages"><?php echo $lang->here ?></a>.</div>
					<div class="div-inl margin-t-10">
						<div class="small"><?php echo $lang->package_price ?>: </div>
						<div id="price" class="padding-l-10 strong div-inl">
							RSD <span class="pm-value"><?= print_money_PLAINTXT($packages[0]->priceRSD,2); ?></span>
							<?php if ($curr !== "RSD") { ?>
							<div class="padding-l-10">
								<div class="foreign-currency-price"><?= $currName ?> <span class="value"><?= print_money_PLAINTXT($packages[0]->price,2); ?></span></div>
							</div>
							<?php }; ?>
						</div>
					</div>					

					<div class="margin-t-10 md-text-center">
						<?php if ($userActive) { 							
						?>
							<a id="subscribeLink">
								<div id="subscribe" class="btn-green btn-ord" style="pointer-events: auto;" data-base-URI="<?= $FILE; ?>user/addCart/subscription/"><i class="fa fa-shopping-cart"></i> <?php echo $lang->subscribe ?></div>
							</a>
							<?php } else { ?>
							<a id="subscribeLink">
								<div id="subscribe" class="btn-green btn-ord" style="pointer-events: auto;" data-base-URI="<?= $domain; ?>login.php?action=addCart&type=subscription&cid="><i class="fa fa-shopping-cart"></i> <?php echo $lang->subscribe ?></div>
							</a>
							<?php }; ?>						
					</div>
					<script type="text/javascript">
						"use strict"; 
						$(function(){
							var arr = [<?php for ($p=0; $p<count($packages); $p++) echo '"'.print_money_PLAINTXT($packages[$p]->priceRSD, 2).'"'.($p<$i-1 ? ',' : ''); ?>];
							<?php if ($curr !== "RSD") { ?>
							var arr_frgn = [<?php for ($p=0; $p<count($packages); $p++) echo '"'.print_money_PLAINTXT($packages[$p]->price, 2).'"'.($p<$i-1 ? ',' : ''); ?>]
							<?php }; ?>
							var ids = [<?php for ($p=0; $p<count($packages); $p++) echo '"'.$packages[$p]->packageID.'"'.($p<$i-1 ? ',' : ''); ?>];
							$(".packages-select-options li").on("click", function(e) {
								$("#defaultOption").html($(this).html());							
								$("#price .pm-value").html(arr[$(this).index()]);
								$("#price .pm-value").html(arr[$(this).index()]);
								<?php if ($curr !== "RSD") { ?>
								$(".foreign-currency-price .value").html(arr_frgn[$(this).index()]);
								<?php }; ?>
								$("#subscribe").attr("data-option", $(this).index());
								$("#subscribeLink").attr("href", $("#subscribe").attr("data-base-URI")+ids[$(this).index()]);			
							});
							$("#subscribeLink").attr("href", $("#subscribe").attr("data-base-URI")+ids[0]);
						});						
					</script>

					<?php }; ?>

		

				</div>
				<!-- /Workshop details -->
			</div>
			<!-- /.row -->
			<div class="row margin-t-30 margin-md-t-60">
				 <div class="col-md-12 col-xs-12 col-sm-12">
				 	<!-- Nav tabs -->
					  <ul class="nav nav-tabs" role="tablist">
					    <li role="presentation" class="active"><a href="#description" role="tab" data-toggle="tab">
					    	<?php echo $lang->workshop_description ?>
					    </a></li>
					    <li role="presentation"><a href="#reviews" role="tab" data-toggle="tab">
					    	<?php echo $lang->impressions ?>
					    	<?php if ($total_rating_sum) { ?> <span id="totalWorkshopComments" class="badge"><?= $total_rating_sum; ?></span> <?php } ?>
					    </a></li>				   
					  </ul>

					  <!-- Tab panes -->
					  <div class="tab-content">
					    <div role="tabpanel" class="tab-pane active" id="description">
					    	<div class="workshop-text">
					    		<h4 style="margin: 0"><div class="strong uppercase l-sp-1"><?php echo $lang->workshop_description ?></div></h4>
								<?php echo $workshop->text; ?>
					    	</div>
					    </div>
					    <div role="tabpanel" class="tab-pane" id="reviews" data-sql-statement="recent">
					    	<div id="reviewsTab" class="workshop-text">
					    		<h4 style="margin: 0"><div class="strong uppercase l-sp-1"><?php echo $lang->user_impression ?></div></h4>	
				    			<?php if ( !mysqli_num_rows_wrapper($sql_reviews) ) { ?>
				    			<div class="margin-t-20 margin-md-t-40" style="height: auto; ">
					    			<div class=""><?php echo $lang->no_impressionsATM ?>
					    				<div class="fl-right star-color">
							    			<span class="fa fa-star-o fa-2x"></span>
							    			<span class="fa fa-star-o fa-2x"></span>
							    			<span class="fa fa-star-o fa-2x"></span>
							    			<span class="fa fa-star-o fa-2x"></span>
							    			<span class="fa fa-star-o fa-2x"></span>					    			
							    		</div>						    				
					    			</div>					    								    		
						    	</div>
						    	<div class="text-center">	
						    	<?php if ($userActive && $revPerUser<1) { 
					    				if (!$USER->verified) { ?>
						    			<div class="margin-t-10 info-data relative text-left">
						    				<i class="fa fa-check-circle fa-2x"></i> 
						    				<span class="text-lifted-up"> <?php echo $lang->verify_Accout ?></span>
						    				<div class="small padding-t-5 padding-b-5"><?php echo $lang->verify_toComment ?></div>
						    			</div>
						    				<?php } elseif ($passCheck) { ?>			    			
						    			<div class="padding-r-10 text-center">
						    				<div id="leaveReviewBtn" class="bth-theme-fix btn-padding-small btn-rounded-success small margin-md-t-10"><i class="fa fa-edit"></i> <?php echo $lang->leave_impression ?></div>
						    			</div>
						    			<?php }; }; ?>		
						    	</div>		    	
					    		<?php } else {    						    						    			
					    			$average   = ( intVal($total_rating_sum) !== 0 ? ($rating_sum / $total_rating_sum) : 0 );		
					    			$stars = determine_rating($average);				    				
					    		?>  
					    		<div class="margin-t-20">
					    			<ul class="breadcrumb small" style="margin-bottom: 15px">
					    				<li><a id="recentReviews" data-selected="true" class="strong" href="javascript: void(0)"><?php echo $lang->recent_impressions ?></a></li>						    					
					    				<li><a id="topReviews" class="underline" data-selected="false" href="javascript: void(0)"><?php echo $lang->top_impressions ?></a></li>		
					    			</ul>
					    		</div>	  						    							    		
					    		<div class="text-right margin-md-t-20">					    			
					    			<span id="workshopRating" class="large strong padding-r-10"><?= ($average !== 0 ?round($average,2) : ""); ?></span>
					    			<span id="workshopRatingStars" class="star-color">
				    				<span class="fa <?= $stars[0] ?> fa-2x"></span>
					    			<span class="fa <?= $stars[1] ?> fa-2x"></span>
					    			<span class="fa <?= $stars[2] ?> fa-2x"></span>
					    			<span class="fa <?= $stars[3] ?> fa-2x"></span>
					    			<span class="fa <?= $stars[4] ?> fa-2x"></span>	
					    			</span>		
					    			<?php if ($userActive && $revPerUser<1) { 
					    				if (!$USER->verified) { ?>
					    				<div></div>
					    				<?php } else { ?>			    			
					    			<div class="padding-r-10 text-center">
					    				<div id="leaveReviewBtn" class="bth-theme-fix btn-padding-small btn-rounded-success small margin-md-t-10"><i class="fa fa-edit"></i> <?php echo $lang->leave_impression ?></div>
					    			</div>
					    			<?php }; }; ?>
					    		</div>					    						    		
					    		<div>
					    			<div class="container-fluid">
					    				<div id="allReviews" class="row" data-delete-mssg="<?php echo $lang->delete_impressionSucc ?>" data-edit-mssg="<?php echo $lang->change_impressionSucc ?>" data-error-mssg="<?php echo $lang->error_whileProcess ?>">
						 	<?php for ($i=0; $i<count($reviews); $i++) { ?>	
						 					<!-- .user-comment -->						    
						    				<div class="user-comment col-md-12 col-xs-12 col-sm-12 padding-b-30 margin-t-20" data-comment-id="<?= $reviews[$i]->reviewID; ?>" data-rating="<?= $reviews[$i]->rating; ?>">
						    	    <?php 
			    					//	Specific username
			    					//	Options: [DELETE, INFO]
						    		if ($userActive && $reviews[$i]->username===$user || $adminActive) { ?>
						    						<div class="user-comment-notactive smaller <?php if (intval($reviews[$i]->status)===1) echo "dn"; ?>"><i class="fa fa-refresh"></i> <span><?php echo $lang->onCheck ?></span>
						    						</div>
						    						<div class="user-comment-actions div-inl">						
						    							<?php if (!$adminActive) { ?>
						    							<div data-toggle="tooltip" title="<?php echo $lang->delete_Review ?>" data-placement="bottom" class="rUINI_del btn-theme-fix btn-rounded-danger btn-padding-x-small" data-id="<?= pop_obj($reviews[$i],"reviewID"); ?>" style="border-radius: 0"><i class="fa fa-trash"></i>
						    							</div>
						    							<div data-toggle="tooltip" title="<?php echo $lang->change_Review ?>" data-placement="bottom" class="rUINI_ed btn-theme-fix btn-rounded-danger btn-padding-x-small" data-id="<?= pop_obj($reviews[$i],"reviewID"); ?>" style="border-radius: 0; margin: 0"><i class="fa fa-edit"></i></div>		
						    							<?php } else { ?>	    						
						    							<div data-toggle="tooltip" title="<?php echo $lang->delete_Review ?>" data-placement="bottom" class="rUINI_del btn-theme-fix btn-rounded-danger btn-padding-x-small" data-id="<?= pop_obj($reviews[$i],"reviewID"); ?>" style="border-radius: 0"><i class="fa fa-trash"></i>
						    							</div>
						    							<?php if ($reviews[$i]->status<1 && $ADMIN->access_allow_commenting) { ?>
						    							<div data-toggle="tooltip" title="<?php echo $lang->allow_Review ?>" data-placement="bottom" class="rAINI_allow btn-theme-fix btn-rounded-success btn-padding-x-small" data-id="<?= pop_obj($reviews[$i],"reviewID"); ?>" style="border-radius: 0"><i class="fa fa-check"></i>
						    							</div>
						    							<?php }; }; ?>
						    						</div>
						    						<?php if ($reviews[$i]->status<1 && $adminActive && $ADMIN->access_allow_commenting) { ?>
						    						<div class="admin-property check-circle color-success"><i class="fa fa-check-circle fa-3x"></i></div>
						    						<?php }; ?>
						    						<?php if (!$adminActive) { ?>
						    						<div class="user-comment-property smaller"><?php echo $lang->myComment ?></div>	
						    						<?php }; ?>
						    						<div class="user-comment-delete dn">
						    							<div class="container">
						    								<div class="col-md-push-1 col-md-5 col-xs-12 col-sm-12">
						    									<div class="margin-t-40 margin-md-t-10 strong" style="font-size: 120%"><i class="fa fa-trash"></i> <?php echo $lang->review_delete ?></div>
						    									<div class="margin-t-10 text-left">
						    										<?php echo $lang->areYouSureReview ?>
						    									</div>
						    								</div>
						    								<div class="col-md-6 col-xs-12 col-sm-12 margin-t-70 margin-md-t-10 md-text-center div-inl">
						    									<div class="rUINI_delConf btn-theme-fix btn-rounded-danger btn-full btn-padding-x-small small" style="border-radius: 0" data-id="<?= pop_obj($reviews[$i],"reviewID"); ?>"><i class="fa fa-trash"></i> <?php echo $lang->delete ?></div>

						    									<div class="rUINI_decl btn-theme-fix btn-rounded-danger margin-md-t-10  btn-padding-x-small small" data-id="<?= pop_obj($reviews[$i],"reviewID"); ?>" style="border-radius: 0;"><i class="fa fa-times"></i><?php echo $lang->cancel ?></div>
						    								</div>
						    							</div>
						    						</div>

						    					<?php };
						    						// END OF USER'S OPTIONS


						    						// Searching for review's user
						    						$pom_sql = mysqli_query($CONNECTION, "SELECT name, surname, image, username FROM users WHERE username='".($reviews[$i]->username)."' LIMIT 1");
						    						while($t = mysqli_fetch_object_wrapper($pom_sql)) $userOBJ = $t;

						    						$pom_sql = mysqli_query("SELECT * FROM reviews WHERE ");

						    						// Making profile image
						    						if (!$userOBJ->image) $imgsrc = $FILE."img/user.png"; else $imgsrc = $FILE."img/users/".$userOBJ->image;

						    						// Making time and date [DISPLAY]
						    						$tm = make_time($reviews[$i]->time);
						    						$dt = make_date(($lang_cond ? 0 : 1), $reviews[$i]->date);
						    						$stars = determine_rating($reviews[$i]->rating);
						    					?>
							    				<img class="user-comment-image" src="<?= $imgsrc; ?>" width="50" /> 
							    				<div class="user-comment-info">
							    					<span class="strong"><?= $userOBJ->name." ".$userOBJ->surname; ?></span>
							    					<div class="smaller text-faded"><span class="review-date"><?= $dt; ?></span>, <?= $lang->in ?> <span class="review-time"><?= $tm; ?></span></div>
							    					<div>	
							    						<span class="stars star-color">		    						
										    				<span class="fa <?= $stars[0] ?>"></span>
											    			<span class="fa <?= $stars[1] ?>"></span>
											    			<span class="fa <?= $stars[2] ?>"></span>
											    			<span class="fa <?= $stars[3] ?>"></span>
											    			<span class="fa <?= $stars[4] ?>"></span>	
										    			</span>
										    			<span class="stars-num padding-l-5 small strong"><?= $reviews[$i]->rating; ?></span>    
							    					</div>
							    				</div>							    				
							    				<div class="margin-t-20 margin-l-60">
							    					<div class="review-title strong"><?= pop_obj($reviews[$i], "title") ?></div>
							    					<div class="review-text"><?= $reviews[$i]->text; ?></div>
							    				</div>	
							    				<?php 

							    				// Discussion \/ comments \/ replies

						    					$sql_comments = mysqli_query("SELECT * FROM comments WHERE reviewID='".$reviews[$i]->reviewID."' ORDER BY time DESC, date DESC LIMIT 5");
							    				if (mysqli_num_rows_wrapper($sql_comments) || $adminActive) {
							    				?> 
							    				<div class="row margin-md-t-20 margin-t-10 small">
							    					<div class="col-md-5 col-xs-12 col-sm-12 padding-l-70 padding-md-l-10">
							    						<div class="btn-fix user-comment-all-comments div-inl">
							    							<?php if (($adminActive && $ADMIN->access_workshop_comments) || ($userActive && mysqli_num_rows_wrapper($sql_comments) && $reviews[$i]->username===$user)) { ?>
							    						 	<div>
							    								<a class="comment_answer" data-review-id="<?= $reviews[$i]->reviewID; ?>" href="javascript: void(0)">
							    									<div><i class="fa fa-reply"></i> <?php echo $lang->reply ?></div>
							    								</a>
							    							</div>
							    							<?php };				    							
							    							// Subquery
							    							$sql = mysqli_query("SELECT * FROM comments WHERE reviewID='".$reviews[$i]->reviewID."'");
							    							?>
							    							<div>
							    								<a data-review-id="<?= $reviews[$i]->reviewID; ?>" class="comment_showComments <?php if (!mysqli_num_rows_wrapper($sql)) echo "dn"; ?>" href="javascript: void(0)">
							    									<div><i class="fa fa-chevron-down"></i> <?php echo $lang->check_comment ?></div>
							    								</a>
							    							</div>					    							
							    						</div>
							    					</div>
							    				</div>
							    				<?php }; ?>	
							    				<div class="comments-placeholder dn" data-review-id="<?= $reviews[$i]->reviewID; ?>">
							    				<div class="row margin-t-10">
							    					<div class="col-md-12 col-xs-12 col-sm-12" style="padding: 0">
							    						<div class="divider width-100" style="margin-bottom: 0"></div>
							    					</div>
							    				</div>
							    				<div class="row">
							    					<?php if ($adminActive || ($userActive && $userOBJ->username===$user)) { ?>
							    					<div class="col-md-6 col-xs-12 col-sm-12" style="padding: 0">
							    						<div class="user-answer padding-b-20">
							    							<div class="div-inl">    								
							    								<div class="initiator-comment-image pointer">
							    									<?php if ($adminActive) 
							    										{ $img = $ADMIN->image; $name = $ADMIN->name." ".$ADMIN->surname; } 
							    										else { $img = $userOBJ->image; $name = $userOBJ->name." ".$userOBJ->surname; }; ?>
							    									<img class="user-comment-image" src="<?= make_image($img,$FILE) ?>" width="45" height="45" title="<?= $name; ?>" data-toggle="tooltip" data-placement="top" />
							    								</div>	    								
							    								<div style="width: 100%" class="padding-l-10">
							    									<div class="initiator-comment" placeholder="<?= $lang->comment_site ?>" contenteditable="true" data-review-id="<?= $reviews[$i]->reviewID; ?>"></div>
							    								</div>				    								
							    							</div>
							    							<div class="text-right">
							    								<div class="btn-initiator-send smaller" data-review-id="<?= $reviews[$i]->reviewID; ?>"><?php echo $lang->send_comment ?></div>
							    							</div>
							    						</div>
							    					</div>
							    					<?php }; ?>
							    					<div class="col-md-6 col-xs-12 col-sm-12 border-r-shadow-eff border-b-shadow-eff pre-allComments" style="padding: 0">
							    						<div class="pre-allComments padding-t-10 padding-b-10 l-sp-1 strong  uppercase padding-l-20"><?php echo $lang->comment_i ?></div>

							    						<div class="no-more-comments small margin-t-10 padding-l-30 padding-b-30 <?= mysqli_num_rows_wrapper($sql_comments) ? "dn" : "" ?>">
							    						<i class="fa fa-comment-o"></i> <?php echo $lang->no_commentsATM ?>
							    						</div>

							    						<div class="allComments" data-comments-num="<?= mysqli_num_rows_wrapper($sql_comments) ?>">
							    							<?php if (mysqli_num_rows_wrapper($sql_comments)) {
							    								$go = 0;
							    								while($t = mysqli_fetch_object_wrapper($sql_comments)) {

						    									$pom1_sql = mysqli_query("SELECT * FROM users WHERE username='".$t->username."' LIMIT 1");
						    									while($j = mysqli_fetch_object_wrapper($pom1_sql)) $US_COMM = $j;
						    									$comm_ad = !!!true;

							    								if ($US_COMM->account_type)			
							    								{
							    									$pom1_sql = mysqli_query("SELECT * FROM administrators WHERE username='".$t->username."'");
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
							    											<a class="rUINI_delThisComment text-error strong-hover" href="javascript: void(0)" data-review-id="<?= $reviews[$i]->reviewID ?>" data-comment-id="<?= $t->commentID ?>"><i class="fa fa-trash"></i> <?php echo $lang->delete ?></a>

							    											<span class="message-bef-del dn">
							    											<?php echo $lang->comment_delete ?>
							    											<span>
							    												<span class="padding-l-10">
							    													<a class="rUINI_delThisCommentProceed strong" href="javascript: void(0)"
							    													data-review-id="<?= $reviews[$i]->reviewID ?>" data-comment-id="<?= $t->commentID ?>"><?php echo $lang->yes ?></a>
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
							    							$gr = mysqli_num_rows_wrapper(mysqli_query("SELECT * FROM comments WHERE reviewID='".$reviews[$i]->reviewID."'"))>5; ?>
							    						</div>
							    						<div class="fetch-more-comments small margin-b-10 margin-md-t-20 btn-ord <?= (!$gr ? "dn" : "") ?>" data-fetch="0" data-review-id="<?= $reviews[$i]->reviewID; ?>"><i class="fa fa-comments"></i> <?php echo $lang->more_comment ?></div>			    							
							    					</div>
							    				</div>	
							    				</div>
							    			</div>
							    			<!-- /.user-comment -->
						    				<?php }; // Main while([$reviews]) { ... } ?>				    			
							    		</div>
							    		<div class="row text-center margin-t-10">
							    			<ul id="reviewsPagination" class="pagination" data-current-page="1">
											    <li class="previous"><a href="javascript: void(0)" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
											    <li data-number="1" class="active"><a href="javascript: void(0)"><span>1</span></a></li>
											    <?php 
											    	$pages = ceil(mysqli_num_rows_wrapper($sql_reviews_all) / 5);		   
											    	for ($i=2; $i<=$pages; $i++) { ?>
										    	<li data-number="<?= $i; ?>"><a href="javascript: void(0)"><span><?= $i; ?></span></a></li>	
											    <?php }; ?>
											   	<li class="next"><a href="javascript: void(0)">&raquo;</a></li>
											</ul>
							    		</div>	
					    			</div>				    			
					    		</div>	
					    		<?php }; ?>
					    	</div>
					    	<!-- /#reviewsTab -->
					    	<!-- #leaveReview -->
					    	<div id="leaveReview" class="workshop-text dn" data-review-id="" data-action="" data-wid="<?= $wid; ?>">
					    		<h4 style="margin: 0"><div class="strong uppercase l-sp-1"><?php echo $lang->leave_impression ?></div></h4>
					    		<div id="rMistake" class="margin-t-20 margin-b-20">
					    			<div class="mistake small dn">
					    				<i class="fa fa-exclamation-triangle"></i> <?php echo $lang->didNotFill_RightWay ?>
					    			</div>
					    		</div>
					    		<div class="margin-t-20 margin-b-20">
					    			<div class="row">
					    				<div class="col-md-3 col-xs-12 col-sm-12">
					    					<div class="strong"><?php echo $lang->title_head ?></div>
							    			<div class="margin-t-5">
							    				<input id="rTitle" type="text" name="" placeholder="<?php echo $lang->enter_titleReview ?>" class="input-theme" />
						    				</div>
					    				</div>
					    			</div>
					    			<!-- .row -->
					    			<div class="row margin-t-20">
					    				<div class="col-md-4 col-xs-12 col-sm-12">
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
					    				<div class="col-md-6 col-xs-12 col-sm-12">
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
					    				<div class="col-md-6 col-xs-12 col-sm-12">
					    					<div id="rSubmit" class="btn-theme-fix btn-rounded-success btn-padding-small smaller" data-message="<?php echo $lang->review_sent ?>">
					    						<i class="fa fa-envelope"></i> <?php echo $lang->send_Review ?>
					   						</div>	
					   						<div id="rDecline" class="btn-theme-fix btn-rounded-danger btn-padding-small smaller margin-l-10">
					    						<i class="fa fa-times"></i> <?php echo $lang->cancel ?>
					   						</div>					   						
					    				</div>
					    			</div>
					    			<div class="row margin-t-10">
					    				<div class="col-md-6 col-xs-12 col-sm-12">
					    					<span class="smaller"><?php echo $lang->necessary_field ?> *</span>
					    				</div>
					    			</div>
					    		</div>
					    	</div>
					    </div>				    
					  </div>
				 </div>
			</div>
		</div>
	</div>
	

	<?php echo $footer; $footer = NULL; ?>
	<?php echo $upBtn; $upBtn = NULL; ?>

	<?php 
	// Delete all objects
		unset($PAGES);
		unset($lang);
	?>

</body>
</html>