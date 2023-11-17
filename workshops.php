<?php 
	
	session_start();
	$prepath = '';
	$page 	 = "workshops";
	$show_footer = true;
	include $prepath."functions.php";
	include $prepath."connect.php";
	include "global.php";
	include "getDATA.php";
	include $prepath."pages.php";
	include $prepath."lang/func.php";
	include $prepath."lang/write_".strtolower($lang_acr).".php";

	if (isset($_SESSION['user_view']) && isset($_SESSION['user_view']->from) && $_SESSION['user_view']->from->status) $_SESSION['user_view']->from->status = false;

	$show = isset($_GET['month']) && isset($_GET['year']);
	if ($show)
	{
		$month = $_GET['month']; $year = $_GET['year'];
		if (!(is_numeric($month) && is_finite($month) && is_numeric($year) && is_finite($year))) $show = false; else
		{
			$month = intval($month); $year = intval($year);
			if ($month > 12 || $month < 1) $show = false;
			if ($year < 2017) $show = false; 
		};
	};

//  Set @REDIRECT@ parameter to ensure `page closing` on `user open attempt`
	$REDIRECT = false;
	include $prepath."requests/workshops.php";
	if (mysqli_num_rows_wrapper($sql_DATA))
	{
		$i     = 0;
		$query = array();
		if ($DATA_SQL->addDATA && $DATA_SQL->first)
		{
			for ($p=0; $p<count($O); $p++) $query[$i++] = $O[$p];
		};
		if ($DATA_SQL->read) while($t = mysqli_fetch_object_wrapper($sql_DATA)) $query[$i++] = $t; else { $query = $O; }
		if ($DATA_SQL->addDATA && !$DATA_SQL->first && $DATA_SQL->first !== NULL)
		{
			for ($p=0; $p<count($O); $p++) $query[$i++] = $O[$p];
		};
	};

	$sql = mysqli_query($CONNECTION, "SELECT packageID, name_".$lang_acr." AS text, price_".$curr." AS pprice, price_RSD AS ppriceRSD FROM packages");
	$i = 0; $PACKAGES = array();
	while($t = mysqli_fetch_object_wrapper($sql)) $PACKAGES[$i++] = $t;
	
	if ($userActive && $show)
	{
		$sql = mysqli_query($CONNECTION, "SELECT * FROM subscriptions WHERE start_month = '".$_GET['month']."' AND BINARY username = '".$USER->username."'");
		if (mysqli_num_rows_wrapper($sql)==1) $subscribed = true; else $subscribed = false;
	} else $subscribed = false;
?>

<!DOCTYPE html>
<html>
<head>
<?php print_HTML_data("head","workshops") ?>
</head>
<body class="<?= $bodyClass ?>">

	<?php echo $mobileMenu; $mobileMenu = NULL; ?>

	<!-- Fixed Menu -->
	<?php printMainMenu(1,1); ?>

	<!-- Header container -->
	<div class="header-container md-dn">
		<?php echo $headerInfo; $headerInfo = NULL; ?>
		<?php printMainMenu(0,3); ?>		
	</div>
	<!-- /Header container -->

	<div class="container margin-t-20 margin-md-t-90">
		<div class="row margin-b-20">
			<div class="col-md-12 col-xs-12 col-sm-12 text-center">
				<h1 style="margin: 0;" class="strong"><?php echo $lang->online_workshops ?></h1>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-xs-12 col-sm-12 relative">
				<?php if ($show) { ?>
				<div class="div-inl">					
					<div class="small">
						<div id="sort" class="sort-options fold" style="margin-left: 0">
							<div class="default-option"> <?= $lang->c_months[$month-1]; ?></div>
							<ul class="options text-left">
								<?php for ($i=$startMonth-1; $i<count($lang->c_months); $i++) { ?>
								<li><a href="<?= $domain ?>workshops/<?= $i+1 ?>/<?= $year ?>"> <span class="strong"><?= ($i>8 ? ($i+1) : "0".($i+1)) ?></span> - <?= $lang->c_months[$i] ?></a></li>
								<?php }; ?>								
							</ul>
							<i class="fold fa fa-chevron-down"></i>
							<i class="unfolded fa fa-chevron-up"></i>
						</div>						
					</div>
					<div class="padding-l-10 strong"><?= $year ?></div>
					
				</div>
				<?php }; ?>
			</div>
			<?php if ($show) { ?>
			<div class="col-md-6 col-xs-12 col-sm-12 margin-md-t-20 small">
				<div class="text-right div-inl">
				<?php echo $lang->sort ?>:	
				<div id="sort" class="sort-options fold">
					<div class="default-option"><?= $DATA_SQL->sortName; ?></div>
					<ul class="options text-left">
						<li><a href="<?= $domain ?>workshops/<?= $month ?>/<?= $year ?>/latest"><i class="icon-ord fa fa-plus-square"></i> <?php echo $lang->newest ?></a></li>
						<li><a href="<?= $domain ?>workshops/<?= $month ?>/<?= $year ?>/oldest"><i class="icon-ord fa fa-clock-o"></i> <?php echo $lang->oldest ?></a></li>
						<li><a href="<?= $domain ?>workshops/<?= $month ?>/<?= $year ?>/topRated"><i class="icon-ord fa fa-star"></i> <?php echo $lang->best_rated ?></a></li>
						<li><a href="<?= $domain ?>workshops/<?= $month ?>/<?= $year ?>/alpha/asc"><i class="icon-ord fa fa-sort-alpha-asc"></i> <?php echo $lang->a_toZ ?></a></li>
						<li><a href="<?= $domain ?>workshops/<?= $month ?>/<?= $year ?>/alpha/desc"><i class="icon-ord fa fa-sort-alpha-desc"></i> <?php echo $lang->z_toa ?></a></li>
					</ul>
					<i class="fold fa fa-chevron-down"></i>
					<i class="unfolded fa fa-chevron-up"></i>
				</div>		
				</div>					
			</div>
			<?php }; ?>
		</div>
	</div>

	<div class="container">
		<?php if ($show) { ?>

		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12 text-right margin-t-20">
				<div class="div-inl">
					<div class="pay-placeholder small">
						<div class="placeholder">
							<div class="main-price strong"><?= $PACKAGES[0]->pprice ?> <?= $curr ?></div>
							<?php if ($curr !== "RSD") { ?><div class="small"><?= print_money_PLAINTXT($PACKAGES[0]->ppriceRSD,2); ?> RSD</span></div><?php }; ?>
						</div>
						<?php if ($userActive) { ?>
							<?php if ($subscribed) { ?>
							<a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="Subscribed">
								<div class="btn-green disabled btn-ord btn-block">
									<?php echo $lang->subbed_For ?> <span class="strong"><?= $lang->c_months[$month-1] ?></span>
								</div>	
							</a>	
							<?php } else { ?>					
							<a href="<?= $FILE ?>user/addCart/subscription/<?= $PACKAGES[0]->packageID ?>?month=<?= $month ?>">
								<div class="btn-green btn-ord btn-block">
									<?php echo $lang->subb_for ?> <span class="strong"><?= $lang->c_months[$month-1] ?></span>
								</div>	
							</a>	
							<?php }; ?>
						<?php } else { ?>
						<a href="<?= $FILE ?>login.php?action=addCart&type=subscription&cid=<?= $PACKAGES[0]->packageID ?>&month=<?= $month ?>">
							<div class="btn-green btn-ord btn-block">
								<?php echo $lang->subb_for ?> <span class="strong"><?= $lang->c_months[$month-1] ?></span>
							</div>	
						</a>
						<?php }; ?>
					</div>					
				</div>				
			</div>
		</div>
		<div class="row">
			<?php if (mysqli_num_rows_wrapper($sql_DATA)) { ?>
			<?php for ($i = 0; $i < count($query); $i++) { ?>
			
			<div class="col-md-3 col-xs-12 col-sm-12 relative margin-t-30">
				<div class="product-item">
					<?php $dat = strtotime($query[$i]->date_publish); $dat += 5*60*60*24; if ($dat >= time()) { ?>
					<div class="eticket eticket-opt"><span class="strong"><?php echo $lang->newUP ?></span></div>
					<?php }; ?>
					<a href="<?php echo $domain ?>workshop/<?= $query[$i]->workshopID; ?>">
					<div class="top-content">
						<div class="image-optimised">												
							<img src="<?= $FILE ?>img/content/<?= $query[$i]->image ?>" />	
						</div>
						<div class="paint-brush">
							<i class="fa fa-paint-brush fa-2x"></i>
						</div>
					</div>
					</a>
					<div class="padding-l-10 padding-r-10">
					<a href="<?= $domain ?>workshop/<?= $query[$i]->workshopID ?>">
						<div class="padding-t-10 strong text-center"><?= pop_obj($query[$i], "heading"); ?></div>
					</a>
					<div class="star-color text-center">
						<?php $stars = determine_rating($query[$i]->result) ?>
		    			<span class="fa <?= $stars[0] ?>"></span>
		    			<span class="fa <?= $stars[1] ?>"></span>
		    			<span class="fa <?= $stars[2] ?>"></span>
		    			<span class="fa <?= $stars[3] ?>"></span>
		    			<span class="fa <?= $stars[4] ?>"></span>					    			
		    		</div>
		    		<div class="text-center">
		    			<span class="text-center text-faded"><i class="fa fa-eye"></i> <?= $query[$i]->views ?></span>
		    			<span class="text-center text-faded padding-l-10"><i class="fa fa-heart"></i> <?= $query[$i]->num_wish ?></span>
		    			<span class="text-center text-faded padding-l-10"><i class="fa fa-comment"></i> <?= $query[$i]->comments ?></span>
		    		</div>
		    		</div>
				</div>
			</div>
			<?php }; // /for ?>	
			<?php }; // count($query) ?>				
		</div>
		<?php if (!mysqli_num_rows_wrapper($sql_DATA)) { $show_footer = false; ?>
		<div class="margin-t-20">
			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12">
					<div class="large strong color-theme"><i class="fa fa-ban"></i> <?= $lang->no_WS ?></div>
					<div class=""><?= $lang->unableToFindWS ?> <span class="strong"><?= $lang->c_months[$month-1]; ?></span></div>
				</div>
			</div>
		</div>
		<?php }; // if (!mysqli_num_rows_wrapper($sql_DATA)) { .. } ?>
		<?php }; // if ($show) { ... } ?>
	</div>

	<?php if (!$show) { ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12 text-center">
				<h2><?= $lang->searchForWS ?></h2>
				<div class="small"><?= $lang->sfw_selectedP ?></div>
			</div>
		</div>
		<div class="row margin-t-60">			
			<?php for ($i=$startMonth-1; $i<count($lang->c_months); $i++) { ?>
			<div class="col-md-2 col-xs-6 col-sm-6 <?= ($i>5 ? "margin-t-30" : "") ?> <?= ($i>1 ? "margin-md-t-10" : "") ?>">
				<a class="month-choose-link" href="<?= $domain ?>workshops/<?= $i+1 ?>/<?= $year ?>">
					<div class="workshops-month text-center">
						<div class="large strong"><?= ($i<9 ? "0".($i+1) : $i+1) ?></div>
						<div><?= $lang->c_months[$i] ?></div>
					</div>				
				</a>
			</div>
			<?php }; ?>			
		</div>
	</div>

	<?php }; ?>

	<?php if ($show && mysqli_num_rows_wrapper($sql_DATA)) { ?>
	<div class="container margin-t-30">
		<div class="row">
			<div class="col-md-6 col-xs-12 col-sm-12">
				<div class="div-inl">
					<?php echo $lang->workshop_perPage ?>:
					<div id="sort" class="sort-options fold small" style="padding-right: 30px">
						<div class="default-option"><?= ($ip ? $ipp : 16) ?></div>
						<ul class="options text-left">
							<li><a href="<?= $domain.$URL_to_redirect; ?>&ipp=16">16</a></li>
							<li><a href="<?= $domain.$URL_to_redirect; ?>&ipp=32">32</a></li>
							<li><a href="<?= $domain.$URL_to_redirect; ?>&ipp=48">48</a></li>
							<li><a href="<?= $domain.$URL_to_redirect; ?>&ipp=64">64</a></li>
							<li><a href="<?= $domain.$URL_to_redirect; ?>&ipp=80">80</a></li>						
							<li><a href="<?= $domain.$URL_to_redirect; ?>&ipp=96">96</a></li>
						</ul>
						<i class="fold fa fa-chevron-down"></i>
						<i class="unfolded fa fa-chevron-up"></i>
					</div>	
				</div>
			</div>
		</div>
		<div class="row"><!-- <?= $URL_to_redirect; ?>&page=<?= $i ?> -->
			<div class="col-md-12 col-xs-12 col-sm-12 text-center">
				<ul class="pagination">					
					<?php $REDIRECT = false; include $prepath."requests/pagePagination.php"; ?>					
				</ul>
			</div>
		</div>
	</div>
	<?php }; ?>

	<div class="container margin-t-20">
		<div class="divider width-100"></div>
		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12 margin-t-10 bordered-theme padding-l-30 padding-r-30 padding-t-20 padding-b-20">
				<div class="text-left color-theme">
					<h2 class="strong" style="margin: 0"> <i class="fa fa-info-circle"></i> <?php echo $WS_header1 ?>  </h2>
				</div>
				<div class="text-left margin-t-10 margin-b-10">
				 	&emsp;<?php echo $WS_para1 ?>
				</div>
				<div class="text-left margin-b-10">
					&emsp;<?php echo $WS_para2 ?>
				</div>
				<div class="text-left margin-b-10">
					&emsp;<?php echo $WS_para3 ?>
				</div>
				<div class="text-left margin-b-10">
					&emsp;<?php echo $WS_para4 ?>
				</div>
				<div class="text-left margin-b-10">
					&emsp;<?php echo $WS_para5 ?>
				</div>
			</div>
		</div>
	</div>

	<?php printFixedMenuSidebar(); ?>
	<?php if ($show_footer) echo $footer; $footer = NULL; ?>

	<?php echo $upBtn; $upBtn = NULL; ?>

	<?php print_HTML_data("script","workshops") ?>

</body>
</html>