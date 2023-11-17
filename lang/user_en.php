<?php 
//      Class of Languages
//	=========================
// 	Serbian language

	class LanguageUserManagement extends Language_EN {}
	$lang = new LanguageUserManagement;

	function printMainMenu($act=1)
	{
		global $cart_number, $PAGES, $lang, $domain, $FILE, $USER;
		echo '
		<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar padding-md-t-20">
			<ul class="nav menu margin-t-60 margin-md-t-0">
				<li class="padding-l-10 padding-b-10 uppercase smaller">'.$lang->basics.'</li>
				<li '.($act===1 ? "class=\"active\"" : "").'>
					<a href="'.$FILE.'user/"><i class="fa fa-dashboard"></i> '.$lang->user_pannel.'</a>
				</li>
				<li '.($act===2 ? "class=\"active\"" : "").'>
					<a href="'.$FILE.'user/workshops"><i class="fa fa-video-camera"></i> '.$lang->my_workshops.'</a>
				</li>
				';

				if (!$USER->grant_access) {
				echo '<li '.($act===3 ? "class=\"active\"" : "").'>
					<a href="'.$FILE.'user/wishlist"><i class="fa fa-heart"></i> '.$lang->my_wishlist.'</a>
				</li>';
				};

				if (!$USER->grant_access) {
				echo '<li role="representation" class="divider"></li>

				<li class="padding-l-10 padding-b-10 uppercase smaller">' . $lang->online_services . '</li>
				<li '.($act===4 ? "class=\"active\"" : "").'>
					<a href="'.$FILE.'user/cart"><i class="fa fa-shopping-cart"></i> '.$lang->cart.' <span id="cartItems" class="badge">'.($cart_number>0 ? $cart_number : "").'</a>
				</li>
				<li '.($act===5 ? "class=\"active\"" : "").'>
					<a href="'.$FILE.'user/subscriptions"><i class="fa fa-credit-card-alt"></i> '.$lang->all_subs.'</a>
				</li>';
				};			
			echo '</ul>

		</div><!--/.sidebar-->
		';
	};

	function printTopMenu() 
	{
		global $USER, $PAGES, $prepath, $domain, $FILE, $lang;
		echo '
		<nav class="navbar navbar-theme navbar-fixed-top" role="navigation" style="z-index: 9999">
			<div class="container-fluid">
				<div class="navbar-header relative">
					<button type="button" class="navbar-toggle collapsed background-theme" data-toggle="collapse" data-target="#sidebar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar" style="background: white"></span>
						<span class="icon-bar" style="background: white"></span>
						<span class="icon-bar" style="background: white"></span>
					</button>
					<a class="navbar-brand" href="'.$domain.'" style="position: absolute;  top: -15px; left: -10px">
						<div>
							<img src="'.$FILE.'/img/logo.png" width="160">
							<div class="strong color-theme font-eminenz md-dn" style="width: 220%; text-transform: none; font-size: 160%; position: absolute; top: 30px; left: 180px">Handmade Fantasy World</div>
						</div>
					</a>
					<ul class="user-menu">
						<li class="dropdown pull-right">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<img id="profilePictureImage" class="user-image md-dn" src="'.make_image($USER->image, $FILE).'" width="30" />
							<div>
								<table class="small relative">
									<tr>
										<td style="width: auto; padding: 0; ';
										if ($USER->grant_access)
										{
											echo 'position:relative; top: -5px;';
										}
										echo '"><span>'.$USER->name." ".$USER->surname.'</span></td>
										<td><div class="caret"></div></td>
									</tr>';
								if ($USER->grant_access) 
								{
									echo '<tr><td style="position: relative; top: -8px; padding: 0; text-align:left"><div class="label label-success color-white">'.$lang->privileged.'</div></td></tr>';
								};
								echo '
								</table>
							</div>
							</a>
							<ul class="dropdown-menu" role="menu" style="z-index: 6002">							
								<li><a href="'.$FILE.'user/settings/"><i class="fa fa-cog"></i> '.$lang->acc_settOpt.'</a></li>
								<li><a href="'.$FILE.'user/logout/"><i class="fa fa-sign-out"></i> '.$lang->acc_logout.'</a></li>
							</ul>
						</li>
					</ul>
				</div>
								
			</div><!-- /.container-fluid -->
		</nav>
		';
	};

	function printNavigation($num=0, $arrN = array(), $arrP = array())
	{
		global $FILE;
		if (count($arrN) !== $num) trigger_error("Cannot find offset ".($num-1)." in array", E_USER_ERROR);
		echo '
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="'.$FILE.'user/"><i class="fa fa-home"></i></a></li>';
				for ($i=0; $i<$num; $i++) echo '<li>'.($i===$num-1 ? $arrN[$i] : '<a href="'.$arrP[$i].'">'.$arrN[$i].'</a>').'</li>';
		echo '</ol>
		</div><!--/.row-->
		';
	}

?>