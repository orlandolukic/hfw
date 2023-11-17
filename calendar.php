<?php 
	session_start();
	$prepath = '';
	$ft_mg   = '';
	include $prepath."functions.php";
	include $prepath."connect.php";
	include "global.php";
	include "getDATA.php";
	include $prepath."pages.php";
	include $prepath."lang/func.php";
	include $prepath."lang/write_".strtolower($lang_acr).".php";

	$setEvent1 = isset($_GET['eventID']) && trim($_GET['eventID']) !== "";
	$setEvent2 = isset($_GET['month']) && is_numeric($_GET['month']) && intval($_GET['month']) > 0 && intval($_GET['month']) < 13 && isset($_GET['year']) && is_numeric($_GET['year']);
	if ($setEvent1)
	{
		$sql = mysqli_query($CONNECTION, "SELECT date_start, location, heading_".$lang_acr." AS heading, text_".$lang_acr." AS text, MONTH(date_start) AS month, YEAR(	date_start) AS year FROM events WHERE BINARY eventID = '".$_GET['eventID']."' AND active = 1");
		if (!mysqli_num_rows_wrapper($sql)) $setEvent = false; else
		{
			$EVENT = mysqli_fetch_object_wrapper($sql);
		}	
	} elseif ($setEvent2)
	{
		$EVENT = (object) array("month" => $_GET['month'], "year" => $_GET['year']);
	};
?>
<!DOCTYPE html>
<html>
<head>
	<?php print_HTML_data("head","calendar") ?>
</head>
<body class="<?= $bodyClass ?>">

	<?php echo $mobileMenu; $mobileMenu = NULL; ?>

	<!-- Fixed Menu -->
	<?php printMainMenu(1,-1); ?>

	<!-- Header container -->
	<div class="header-container md-dn">
		<?php echo $headerInfo; $headerInfo = NULL; ?>
		<?php printMainMenu(0,-1); ?>		
	</div>
	<!-- /Header container -->

	<!-- Main Container -->
	<div class="main-container margin-md-t-60">
		<div class="welcome-note welcome-note-minified">
			<div class="overlay"></div>			
			<div id="select_div" class="welcome-optimised-background welcome-optimised-background-calendar"></div>
		</div>
	</div>
	<!-- /Main Container -->

	<div class="container margin-t-40 margin-md-t-none margin-b-80">		
		<div class="row">			
			<div class="col-md-6 col-xs-12 col-sm-12 margin-md-t-30">
				<div class="responsive-calendar op-0">
				  <div class="controls">
				      <a class="pull-left" data-go="prev" data-toggle="tooltip" data-placement="right" title="<?= $lang->prevMonth ?>"><div class="btn-initiator-send background-theme color-white"><i class="fa fa-chevron-left"></i></div></a>
				      <h4><span data-head-year></span> <span data-head-month></span> <i class="fa fa-calendar"></i></h4>
				      <a class="pull-right" data-go="next" data-toggle="tooltip" data-placement="left" title="<?= $lang->nextMonth ?>"><div class="btn-initiator-send background-theme color-white"><i class="fa fa-chevron-right"></i></div></a>
				  </div><hr/>
				  <div class="day-headers">
				    <div class="day header"><?= $lang->c_days_acr[1] ?></div>
				    <div class="day header"><?= $lang->c_days_acr[2] ?></div>
				    <div class="day header"><?= $lang->c_days_acr[3] ?></div>
				    <div class="day header"><?= $lang->c_days_acr[4] ?></div>
				    <div class="day header"><?= $lang->c_days_acr[5] ?></div>
				    <div class="day header"><?= $lang->c_days_acr[6] ?></div>
				    <div class="day header"><?= $lang->c_days_acr[7] ?></div>
				  </div>
				  <div class="days" data-group="days">
				   
				  </div>
				</div>
			</div>
			<div class="col-md-6 col-xs-12 col-sm-12 margin-md-t-20">
				<h4><div class="strong"><?= $lang->welcomeCalendar ?></div></h4>
				<div class="small"><?= $lang->wc_details ?></div>
				<div class="divider width-100" style="margin-bottom: 0"></div>				

				<div class="calendar-event-wrapper padding-t-10">
					<div class="event-wrapper margin-t-10">
						<div class="large strong color-theme" id="evHeading"></div>
						<div>
							<div class="pull-right smaller"><?= $lang->location ?>: <span id="evLoc" class="strong"></span></div>
							<div class="smaller"><i class="fa fa-calendar"></i> <span id="evDate"></span></div>
						</div>
						<div id="evDetails" class="margin-t-20">
							
						</div>
						<div class="margin-t-20">
							<div id="evBackLst" class="btn-initiator-send background-theme small"><i class="fa fa-chevron-left"></i> <?= $lang->backEvents ?></div>
						</div>
					</div>
					<div class="event-list">

					</div>
				</div>
			</div>
		</div>
	</div>


	<?php echo $footer; $footer = NULL; ?>
	<?php echo $upBtn; $upBtn = NULL; ?>

	<?php print_HTML_data("script","calendar") ?>

</body>
</html>