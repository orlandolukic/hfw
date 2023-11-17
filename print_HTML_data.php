<?php 

function print_HTML_data($type,$file)
{
	global $FILE, $lang, $year, $domain;
	$wb_title = "Handmade Fantasy World";
	$meta     = '<meta name="description" content="'.$lang->wb_description.'">
				 <meta name="keywords" content="handmade fantasy world, fantasy world workshops, handmade workshops, handmade fantasy workshops, workshops fantasy, online radionice, antonis tzanidakis artist, antonis tzanidakis, handmade radionice, handmade fantasy radionice">
				 ';

	if($type==="script")
	{
		switch($file)
		{
			case "about":
				echo '
				<!-- Page Scripts -->
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/about.js"></script>
				<!-- /Page Scripts -->
				'; break;

			case "gallery":
				global $userActive;
				echo '
				<!-- Page Scripts -->
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/gallery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/gallery-pg.js" data-catch="gallery" data-user-active="'.($userActive ? "true" : "false").'"></script>
				<!-- /Page Scripts -->
				'; break;

			case "calendar":
				global $setEvent1, $setEvent2, $EVENT;
				echo '
				<!-- Page Scripts -->
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/responsive-calendar.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/about.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/calendar.js" data-calendar="true" data-months="[';
				for ($i=0, $len = count($lang->c_months); $i<$len; $i++) echo "'".$lang->c_months[$i]."'".($i<$len-1 ? "," : "");
				echo ']" data-days="[';
				for ($i=0, $len = count($lang->c_days_acr); $i<$len; $i++) echo "'".$lang->c_days_acr[$i]."'".($i<$len-1 ? "," : "");
				echo ']" data-year="'.$year.'" data-set-event="'.($setEvent1 ? "true\" data-event-id=\"".$_GET['eventID']."\" data-event-month=\"".$EVENT->month."\" data-event-year=\"".$EVENT->year : "false").'"';
				if ($setEvent2)
				{
					echo 'data-set-month="true" data-month="'.DEC($EVENT->month).'"  data-selected-year="'.$EVENT->year.'"';
				}
				echo '></script>
				<!-- /Page Scripts -->
				'; break;

			case "news":
				echo '
				<!-- Page Scripts -->
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>	
				<script type="text/javascript" src="'.$FILE.'js/about.js"></script>			
				<!-- /Page Scripts -->
				'; break;

			case "contact":
				echo '
				<!-- Page Scripts -->
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/notify.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/contact.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/about.js"></script>
				<!-- /Page Scripts -->
				'; break;

			case "index":
				echo '
				<!-- Page Scripts -->
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/index.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/packages.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/select.js"></script>
				<!-- /Page Scripts -->
				'; break;

			case "404":
				echo '
				<!-- Page Scripts -->
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<!-- /Page Scripts -->
				'; break;

			case "500":
				echo '
				<!-- Page Scripts -->
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<!-- /Page Scripts -->
				'; break;

			case "403":
				echo '
				<!-- Page Scripts -->
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<!-- /Page Scripts -->
				'; break;

			case "login":
				echo '
				<!-- Page Scripts -->
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/notify.js"></script>
				<!-- /Page Scripts -->
				'; break;

			case "register":
				echo '
				<!-- Page Scripts -->
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/register.js"></script>
				<!-- /Page Scripts -->
				'; break;

			case "workshop":
				echo '
				<!-- Page Scripts -->
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/notify.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/gallery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/workshop.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/select.js"></script>
				<!-- /Page Scripts -->
				'; break;

			case "workshops":
				echo '
				<!-- Page Scripts -->
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/select.js"></script>
				<!-- /Page Scripts -->
				'; break;

			case "cart":
				global $hasPackages, $USER;
				echo '
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/notify.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/switch.min.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/select.js"></script>';

				if ($hasPackages)
				{
					$d = get_month_days($year);
					echo '<script type="text/javascript" data-script="packages" data-month-format="'.get_date_format().'" data-month-days="';
					for ($i=0, $count = count($d); $i < $count; $i++) echo $d[$i].($i<$count-1 ? "," : "");
					echo '" src="'.$FILE.'user/js/packages-cart.js"></script>';
				}
				echo '
				<script>
					$(window).on(\'resize\', function () {
					  if ($(window).width() > 768) $(\'#sidebar-collapse\').collapse(\'show\')
					})
					$(window).on(\'resize\', function () {
					  if ($(window).width() <= 767) $(\'#sidebar-collapse\').collapse(\'hide\')
					})
				</script>	
				'; break;

			case "user/index":
				echo '
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/notify.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'user/js/index.js"></script>
				<script type="text/javascript" src="'.$FILE.'user/js/cart.js"></script>

				<script>
					$(window).on(\'resize\', function () {
					  if ($(window).width() > 768) $(\'#sidebar-collapse\').collapse(\'show\')
					})
					$(window).on(\'resize\', function () {
					  if ($(window).width() <= 767) $(\'#sidebar-collapse\').collapse(\'hide\')
					})
				</script>	
				'; break;

			case "receipt":
				echo '
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/notify.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'user/js/index.js"></script>
				<script>
					$(window).on(\'resize\', function () {
					  if ($(window).width() > 768) $(\'#sidebar-collapse\').collapse(\'show\')
					})
					$(window).on(\'resize\', function () {
					  if ($(window).width() <= 767) $(\'#sidebar-collapse\').collapse(\'hide\')
					})
				</script>	
				'; break;

			case "settings":
				echo '
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/notify.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/switch.min.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/overlay.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/select.js"></script>
				<script type="text/javascript" src="'.$FILE.'user/js/settings.js"></script>
				<script>
					$(window).on(\'resize\', function () {
					  if ($(window).width() > 768) $(\'#sidebar-collapse\').collapse(\'show\')
					})
					$(window).on(\'resize\', function () {
					  if ($(window).width() <= 767) $(\'#sidebar-collapse\').collapse(\'hide\')
					})
				</script>	
				'; break;

			case "subscriptions":
				echo '
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/notify.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'user/js/index.js"></script>
				<script type="text/javascript" src="'.$FILE.'user/js/subscriptions.js"></script>
				<script>
					$(window).on(\'resize\', function () {
					  if ($(window).width() > 768) $(\'#sidebar-collapse\').collapse(\'show\')
					})
					$(window).on(\'resize\', function () {
					  if ($(window).width() <= 767) $(\'#sidebar-collapse\').collapse(\'hide\')
					})
				</script>	
				'; break;

			case "wishlist":
				echo '
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/notify.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/select.js"></script>
				<script src="'.$FILE.'user/js/wishlist.js"></script>
				<script>
					$(window).on(\'resize\', function () {
					  if ($(window).width() > 768) $(\'#sidebar-collapse\').collapse(\'show\')
					})
					$(window).on(\'resize\', function () {
					  if ($(window).width() <= 767) $(\'#sidebar-collapse\').collapse(\'hide\')
					})
				</script>	
				'; break;

			case "user/workshops":
				echo '
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/notify.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/select.js"></script>
				<script type="text/javascript" src="'.$FILE.'user/js/cart.js"></script>
				<script>
					$(window).on(\'resize\', function () {
					  if ($(window).width() > 768) $(\'#sidebar-collapse\').collapse(\'show\')
					})
					$(window).on(\'resize\', function () {
					  if ($(window).width() <= 767) $(\'#sidebar-collapse\').collapse(\'hide\')
					})
				</script>	
				'; break;

			case "video":
				echo '
				<script type="text/javascript" src="'.$FILE.'js/jquery.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/bootstrap.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/notify.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/global.js"></script>
				<script type="text/javascript" src="'.$FILE.'js/gallery.js"></script>
				<script type="text/javascript" src="'.$FILE.'user/js/workshop.js"></script>
				<script type="text/javascript" src="'.$FILE.'user/js/index.js"></script>
				<script type="text/javascript" src="'.$FILE.'user/js/video.js"></script>				

				<script>
					$(window).on(\'resize\', function () {
					  if ($(window).width() > 768) $(\'#sidebar-collapse\').collapse(\'show\')
					})
					$(window).on(\'resize\', function () {
					  if ($(window).width() <= 767) $(\'#sidebar-collapse\').collapse(\'hide\')
					})
				</script>	
				'; break;
		}
	} elseif($type === "head")
	{
		switch($file)
		{
			case "about":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>'.$wb_title.' | '.$lang->about.'</title>
				'.$meta.'
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">

				<meta property="og:url"         content="'.$domain.'about" />
				<meta property="og:type"        content="website" />
				<meta property="og:title"       content="'.$wb_title.' | '.$lang->about.'" />
				<meta property="og:description" content="'.$lang->wb_description.'" />
				<meta property="og:image"       content="'.$FILE.'img/homepage.jpg" />
				'; break;

			case "gallery":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>Handmade Fantasy World | '.$lang->gallery.'</title>	
				'.$meta.'
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/magnific-popup.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/gallery.css">

				<meta property="og:url"         content="'.$domain.'gallery" />
				<meta property="og:type"        content="website" />
				<meta property="og:title"       content="'.$wb_title.' | '.$lang->gallery.'" />
				<meta property="og:description" content="'.$lang->wb_description.'" />
				<meta property="og:image"       content="'.$FILE.'img/homepage.jpg" />
				'; break;

			case "calendar":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>Handmade Fantasy World | '.$lang->calendar.'</title>	
				'.$meta.'
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/magnific-popup.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/responsive-calendar.css">

				<meta property="og:url"         content="'.$domain.'calendar" />
				<meta property="og:type"        content="website" />
				<meta property="og:title"       content="'.$wb_title.' | '.$lang->calendar.'" />
				<meta property="og:description" content="'.$lang->wb_description.'" />
				<meta property="og:image"       content="'.$FILE.'img/homepage.jpg" />
				'; break;

			case "news":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>Handmade Fantasy World | '.$lang->news.'</title>	
				'.$meta.'
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/magnific-popup.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">

				<meta property="og:url"         content="'.$domain.'news" />
				<meta property="og:type"        content="website" />
				<meta property="og:title"       content="'.$wb_title.' | '.$lang->news.'" />
				<meta property="og:description" content="'.$lang->wb_description.'" />
				<meta property="og:image"       content="'.$FILE.'img/homepage.jpg" />
				'; break;

			case "contact":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>Handmade Fantasy World | '.$lang->contact.'</title>	
				'.$meta.'
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/animate.css">

				<meta property="og:url"         content="'.$domain.'contact" />
				<meta property="og:type"        content="website" />
				<meta property="og:title"       content="'.$wb_title.' | '.$lang->contact.'" />
				<meta property="og:description" content="'.$lang->wb_description.'" />
				<meta property="og:image"       content="'.$FILE.'img/homepage.jpg" />
				'; break;

			case "index":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>Handmade Fantasy World | '.$lang->home.'</title>	
				'.$meta.'
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">

				<meta property="og:url"         content="'.$domain.'" />
				<meta property="og:type"        content="website" />
				<meta property="og:title"       content="'.$wb_title.' | '.$lang->home.'" />
				<meta property="og:description" content="'.$lang->wb_description.'" />
				<meta property="og:image"       content="'.$FILE.'img/homepage.jpg" />
				'; break;

			case "404":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>Handmade Fantasy World | '.$lang->_404.'</title>	
				'.$meta.'
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">
				'; break;

			case "500":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>Handmade Fantasy World | '.$lang->_500.'</title>
				'.$meta.'	
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">
				'; break;

			case "403":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>Handmade Fantasy World | '.$lang->_403.'</title>	
				'.$meta.'
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">
				'; break;

			case "login":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>Handmade Fantasy World | '.$lang->login.'</title>
				'.$meta.'	
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/animate.css">

				<meta property="og:url"         content="'.$domain.'login" />
				<meta property="og:type"        content="website" />
				<meta property="og:title"       content="'.$wb_title.' | '.$lang->login.'" />
				<meta property="og:description" content="'.$lang->wb_description.'" />
				<meta property="og:image"       content="'.$FILE.'img/homepage.jpg" />
				'; break;

			case "register":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>Handmade Fantasy World | '.$lang->register.'</title>	
				'.$meta.'
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/animate.css">

				<meta property="og:url"         content="'.$domain.'register" />
				<meta property="og:type"        content="website" />
				<meta property="og:title"       content="'.$wb_title.' | '.$lang->register.'" />
				<meta property="og:description" content="'.$lang->wb_description.'" />
				<meta property="og:image"       content="'.$FILE.'img/homepage.jpg" />
				'; break;

			case "workshop":
				global $domain,$images,$workshop;
				echo '
					<meta charset="utf-8">
					<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
					<title>Handmade Fantasy World | '.$workshop->heading.'</title>	
					'.$meta.'
					<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
					<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
					<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
					<link rel="stylesheet" type="text/css" href="'.$FILE.'css/magnific-popup.css">
					<link rel="stylesheet" type="text/css" href="'.$FILE.'css/workshop.css">
					<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">
					<link rel="stylesheet" type="text/css" href="'.$FILE.'css/animate.css">

					<meta property="og:url"         content="'.$domain.'workshop/'.$workshop->workshopID.'" />
					<meta property="og:type"        content="article" />
					<meta property="og:title"       content="'.$workshop->heading.' | '.$lang->workshops.'" />
					<meta property="og:description" content="'.substr($workshop->subheading,0,70).'..." />';
					if (count($images)) {
						echo '<meta property="og:image" content="'.$FILE.'img/content/'.$images[0]->imageID.'.'.$images[0]->extension.'" />';
					};
				break;

			case "workshops":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>Handmade Fantasy World | '.$lang->workshops.'</title>
				'.$meta.'	
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/products.css">

				<meta property="og:url"         content="'.$domain.'workshops" />
				<meta property="og:type"        content="website" />
				<meta property="og:title"       content="'.$wb_title.' | '.$lang->workshops.'" />
				<meta property="og:description" content="'.$lang->wb_description.'" />
				<meta property="og:image"       content="'.$FILE.'img/homepage.jpg" />
				'; break;

			case "cart":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>Handmade Fantasy World | '.$lang->cart.'</title>
				'.$meta.'
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">	
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/animate.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/switch.min.css">
				<link href="'.$FILE.'user/css/datepicker3.css" rel="stylesheet">
				<link href="'.$FILE.'user/css/styles.css" rel="stylesheet" type="text/css">
				<link href="'.$FILE.'user/css/style.css" rel="stylesheet" type="text/css">
				'; break;

			case "user/index":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>Handmade Fantasy World | '.$lang->user_pannel.'</title>	
				'.$meta.'
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/animate.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">
				<link href="'.$FILE.'user/css/styles.css" rel="stylesheet" type="text/css">
				<link href="'.$FILE.'user/css/style.css" rel="stylesheet" type="text/css">
				'; break;

			case "receipt":
				echo '
					<meta charset="utf-8">
					<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
					<title>Handmade Fantasy World | '.$lang->invoice_view.'</title>	
					'.$meta.'
					<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
					<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
					<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
					<link rel="stylesheet" type="text/css" href="'.$FILE.'css/animate.css">
					<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">
					<link href="'.$FILE.'user/css/datepicker3.css" rel="stylesheet">
					<link href="'.$FILE.'user/css/styles.css" rel="stylesheet" type="text/css">
					<link href="'.$FILE.'user/css/style.css" rel="stylesheet" type="text/css">
				'; break;

			case "settings":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>Handmade Fantasy World | '.$lang->acc_settings.'</title>	
				'.$meta.'
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/animate.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/switch.min.css">
				<link href="'.$FILE.'user/css/datepicker3.css" rel="stylesheet">
				<link href="'.$FILE.'user/css/styles.css" rel="stylesheet" type="text/css">
				<link href="'.$FILE.'user/css/style.css" rel="stylesheet" type="text/css">
				'; break;

			case "subscriptions":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>Handmade Fantasy World | '.$lang->subs.'</title>	
				'.$meta.'
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/animate.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">
				<link href="'.$FILE.'user/css/datepicker3.css" rel="stylesheet">
				<link href="'.$FILE.'user/css/styles.css" rel="stylesheet" type="text/css">
				<link href="'.$FILE.'user/css/style.css" rel="stylesheet" type="text/css">
				'; break;

			case "wishlist":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>Handmade Fantasy World | '.$lang->my_wishlist.'</title>
				'.$meta.'	
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/animate.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">
				<link href="'.$FILE.'user/css/datepicker3.css" rel="stylesheet">
				<link href="'.$FILE.'user/css/styles.css" rel="stylesheet" type="text/css">
				<link href="'.$FILE.'user/css/style.css" rel="stylesheet" type="text/css">
				'; break;

			case "user/workshops":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>Handmade Fantasy World | '.$lang->my_workshops.'</title>	
				'.$meta.'
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/animate.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">
				<link href="'.$FILE.'user/css/datepicker3.css" rel="stylesheet">
				<link href="'.$FILE.'user/css/styles.css" rel="stylesheet" type="text/css">
				<link href="'.$FILE.'user/css/style.css" rel="stylesheet" type="text/css">
				'; break;

			case "video":
				echo '
				<meta charset="utf-8">
				<meta name="viewport" content="initial-scale=1, maximum-scale=1, width=device-width">
				<title>Handmade Fantasy World | '.$lang->video_mat.'</title>
				'.$meta.'
				<link rel="icon" type="image/png" href="'.$FILE.'img/favicon.png">	
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/bootstrap.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/font-awesome.min.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/animate.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/style.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/workshop.css">
				<link rel="stylesheet" type="text/css" href="'.$FILE.'css/magnific-popup.css">
				<link href="'.$FILE.'user/css/datepicker3.css" rel="stylesheet">
				<link href="'.$FILE.'user/css/styles.css" rel="stylesheet" type="text/css">
				<link href="'.$FILE.'user/css/style.css" rel="stylesheet" type="text/css">
				'; break;
		}
	}

}


?>