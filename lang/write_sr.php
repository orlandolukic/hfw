<?php 

	$mobileMenu = '
		<!-- Mobile Menu -->
		<div class="main-menu mobile-main-menu md-db dn padding-t-20 padding-b-20">
			<div class="container margin-b-10">
				<div class="col-sm-10 col-xs-10 relative">
					<a href="'.$domain.'">
					<div class="strong color-theme font-eminenz" style="font-size: 140%; position: absolute; top: 5px; left: 0">Handmade Fantasy World</div>
					</a>
				</div>
				<div class="col-sm-2 col-xs-2 padding-r-40 color-theme" style="padding-top: 5px">
					<div id="mobileMenu"><i class="fa fa-bars fa-2x"></i></div>
				</div>
			</div>
			<div class="mobile-submenu">
				<div class="content margin-t-20">
					<div class="container margin-md-t-20">
						<div class="row">
							<div class="col-xs-12 col-sm-12 div-inl text-right small">
							';
							$str = '';
							if ($userActive) {
								if ($USER->grant_access) 
								{
									$str .= '<span class="margin-r-10"><div class="label label-success">'.$lang->privileged.'</div></span>
									<div><a href="'.$FILE.'user/"><i class="fa fa-user-circle"></i> '.$USER->username.'</a></div>
									<div class="padding-l-5 padding-r-5">&bull;</div>								
									<div><a href="'.$FILE.'user/workshops"><i class="fa fa-video-camera"></i> '.$lang->online_workshops.'</a></div>
									';								
								} else 
								{
									$str .= '<div><a href="'.$FILE.'user/"><i class="fa fa-user-circle"></i> '.$USER->username.'</a></div>
									<div class="padding-l-5 padding-r-5">&bull;</div>
									<div><a href="'.$FILE.'user/cart"><i class="fa fa-shopping-cart"></i> '.$lang->cart.'</a></div>
									<div class="padding-l-5 padding-r-5">&bull;</div>
									<div><a href="'.$FILE.'user/workshops"><i class="fa fa-video-camera"></i> '.$lang->online_workshops.'</a></div>
									';		
								}						
							} elseif ($adminActive)
							{
								$str .= '
								<span class="margin-r-10"><div class="label label-success">'.$lang->welcomeAdmin.'</div></span>
								<div><i class="fa fa-user-circle"></i> '.$ADMIN->username.'</div>
								<div class="padding-l-5 padding-r-5">&bull;</div>															
								<div><a href="'.$FILE.'user/"><i class="fa fa-cog"></i> '.$lang->controlPanel.'</a></div>
								<div><a href="'.$FILE.'user/logout"><i class="fa fa-power-off"></i> '.$lang->acc_logout.'</a></div>
								';
							} else
							{
								$str .= '<div><a href="'.$domain.'login"><i class="fa fa-sign-in"></i> '.$lang->please_login.'</a></div>
								<div class="padding-l-5 padding-r-5">&bull;</div>
								<div><a href="'.$domain.'register"><i class="fa fa-edit"></i> '.$lang->register_please.'</a></div>
								';
							};

							$mobileMenu .= $str.'								
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-12">
								<div class="row">
									<div class="col-xs-6 col-sm-6">
										<div class="mobile-menu-item"><a href="'.$domain.'"><div>'.$lang->home.'</div></a></div>
									</div>
									<div class="col-xs-6 col-sm-6">
										<div class="mobile-menu-item"><a href="'.$domain.$PAGES->about.'"><div>'.$lang->about.'</div></a></div>
									</div>
								</div>

								<div class="row">
									<div class="col-xs-6 col-sm-6">
										<div class="mobile-menu-item"><a href="'.$domain.$PAGES->workshops.'"><div>'.$lang->online_workshops.'</div></a></div>
									</div>
									<div class="col-xs-6 col-sm-6">
										<div class="mobile-menu-item"><a href="'.$domain.$PAGES->gallery.'"><div>'.$lang->gallery.'</div></a></div>
									</div>
								</div>	

								<div class="row">
									<div class="col-xs-6 col-sm-6">
										<div class="mobile-menu-item"><a href="'.$domain.$PAGES->contact.'"><div>'.$lang->contact.'</div></a></div>
									</div>
									<div class="col-xs-6 col-sm-6">
										<div class="mobile-menu-item"><a href="'.$domain.$PAGES->news.'"><div>'.$lang->news.'</div></a>';
										if ($news>0) $mobileMenu .= '<div class="news-alert" style="top: 0; border-radius: 0; line-height: inherit">'.$news.'</div>';
									$mobileMenu.= '</div>
								</div>													
								</div>

								<div class="margin-t-20">
									<div class="strong">'.$lang->rest_links.'</div>
									<div class="row margin-t-10">
										<div class="col-xs-6 col-sm-6">
											<a href="'.$domain.'calendar">'.$lang->calendar.'</a>
										</div>
									</div>
								</div>

								<div class="margin-t-20 text-center">
									<ul class="lang">
										<li><a href="'.$FILE.'">'.$lang->english.'</a></li>
										<li><a href="'.$FILE.'">'.$lang->serbian.'</a></li>
										<!--<li><a href="'.$FILE.'pl/">'.$lang->polish.'</a></li>-->
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	';

	$headerInfo = '
		<!-- Header Info -->
		<div class="header-info">
			<div class="container">
				<div class="col-md-5 col-xs-12 col-sm-12">
					<table>
						<tr>						
							<td><a href="mailto:'.$COMPANY_INFO->email.'"><i class="fa fa-envelope"></i> '.$COMPANY_INFO->email.'</a></td>
						</tr>
					</table>					
				</div>	
				<div class="col-md-7 col-md-push-'.($userActive || $adminActive ? 1 : 2).'">
					<table>
						<tr>';
			//	Print for logged and not-logged users
						$str = "";
						if ($userActive) {
							if ($USER->grant_access) 
							{
								$str .= '<td class="strong"> 
											<span class="margin-r-10"><div class="label label-success">'.$lang->privileged.'</div></span>		
											<a href="'.$FILE.'user/workshops">'.$lang->online_workshops.' <i class="fa fa-video-camera"></i> </a>
											<span class="padding-l-5 padding-r-5">&bull;</span>											
											<a href="'.$FILE.'user/">'.$user.' <i class="fa fa-user-circle"></i></a>
										</td>
										';				
							} else
							{
								$str .= '<td class="strong"> 
										<a href="'.$FILE.'user/workshops">'.$lang->online_workshops.' <i class="fa fa-video-camera"></i> </a>
										<span class="padding-l-5 padding-r-5">&bull;</span>
										<a href="'.$FILE.'user/cart"> '.$lang->cart.' <i class="fa fa-shopping-cart"></i></a>
										<span class="padding-l-5 padding-r-5">&bull;</span>
										<a href="'.$FILE.'user/">'.$user.' <i class="fa fa-user-circle"></i></a>
									</td>
									';
							}									
						} elseif ($adminActive)
						{
							$str .= '<td class="strong">
										<span class="margin-r-10"><div class="label label-success">'.$lang->welcomeAdmin.'</div></span>						
										<a href="'.$FILE.'user/"> '.$lang->controlPanel.' <i class="fa fa-cog"></i></a>
										<span class="padding-l-5 padding-r-5">&bull;</span>
										<span>'.$ADMIN->username.' <i class="fa fa-user-circle"></i></span>
										<span class="padding-l-5 padding-r-5">&bull;</span>
										<a href="'.$FILE.'user/logout">'.$lang->acc_logout.' <i class="fa fa-power-off"></i></a>
									</td>';
						} else
						{
							$str .= '<td><a href="'.$domain.$PAGES->register.'"><i class="fa fa-edit"></i> '.$lang->register.'</a></td>
							<td><a href="'.$domain.$PAGES->login.'"><i class="fa fa-sign-in"></i> '.$lang->login.'</a></td>';
						};
			$headerInfo .= $str.'<td>
								<table>
									<tr>
										<td class="social-icon-transform">
											<a href="http://www.facebook.com" target="_blank" title="'.$lang->findUsOnFace.'">
												<i class="view fa fa-facebook"></i><i class="no-view fa fa-facebook"></i>
											</a>
										</td>
										<td class="social-icon-transform">
											<a href="http://www.twitter.com" target="_blank" title="'.$lang->findUsOnTwitter.'">
												<i class="view fa fa-twitter"></i><i class="no-view fa fa-twitter"></i>
											</a>
										</td>
										<td class="social-icon-transform">
											<a href="http://www.googleplus.com" target="_blank" title="'.$lang->findUsOnGoogle.'">
												<i class="view fa fa-google-plus"></i><i class="no-view fa fa-google-plus"></i>
											</a>
										</td>
										<td class="social-icon-transform">
											<a href="http://www.dribbble.com" target="_blank" title="'.$lang->findUsOnDribbble.'">
												<i class="view fa fa-dribbble"></i><i class="no-view fa fa-dribbble"></i>
											</a>
										</td>
										<td class="social-icon-transform">
											<a href="http://www.pinterest.com" target="_blank" title="'.$lang->findUsOnPinterest.'">
												<i class="view fa fa-pinterest"></i><i class="no-view fa fa-pinterest"></i>
											</a>
										</td>
									</tr>
								</table>
							</td>
							';
							if (!$userActive && !$adminActive) {
							$headerInfo .= '
							<td class="language menu pointer">
								<i class="fa fa-globe"></i> '.$lang->serbian.'
								<div class="submenu">
									<div class="strong margin-b-10 uppercase">'.$lang->lang.'</div>									
									<a href="'.$FILE.'"><div>'.$lang->english.'</div></a>
									<!--<a href="'.$FILE.'pl/"><div class="padding-t-5">'.$lang->polish.'</div></a>-->
									<!--<a href="'.$FILE.'ru/"><div class="padding-t-5">'.$lang->russian.'</div></a>-->
								</div>
							</td>
							';
							}
							$headerInfo .= '						
						</tr>
					</table>
				</div>			
			</div>
		</div>
		<!-- /Header Info -->
	';

	$footer = '
	<!-- Footer -->
	<div class="container-fluid footer'.(isset($ft_mg) ? " ".$ft_mg : " margin-t-60").'">
		<div class="pre-footer">
			<div class="container padding-t-30 padding-b-30">
				<div class="row">
					<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
						<h4 class="strong uppercase">'.$lang->contactinfo.'</h4>

						<div class="margin-t-20">														
							<div class="margin-t-10">'.$lang->email.': <a href="mailto:'.$COMPANY_INFO->email.'">'.$COMPANY_INFO->email.'</a></div>
							<div class="margin-t-10 strong">'.$lang->cooperation.'?</div>
							<div>'.$lang->emailUS.' <a class="link-ord color-theme" href="mailto: office@handmadefantasyworld.com">office@handmadefantasyworld.com</a></div>
						</div>
					</div>
					<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12 margin-md-t-10">
						<h4 class="strong uppercase">'.$lang->recent_Workshops.'</h4>';

						if (count($DATA_FOOTER)) {
						$strA = '';
						for ($i=0; $i < count($DATA_FOOTER) && $i<2; $i++) {
						$strA .= '
						<div class="margin-t-20">
							<table>
								<tr>
									<td>
										<a href="'.$domain.'workshop/'.$DATA_FOOTER[$i]->workshopID.'">
											<img src="'.$FILE.'img/content/'.$DATA_FOOTER[$i]->image.'" width="45" height="45" />
										</a>
									</td>
									<td>
										<div class="strong">
											<a href="'.$domain.'workshop/'.$DATA_FOOTER[$i]->workshopID.'">'.$DATA_FOOTER[0]->heading.'</a>
										</div>
										<div class="margin-t-10 text-faded">
											<i class="fa fa-calendar"></i> '.make_date(!$lang_cond, $DATA_FOOTER[$i]->date_publish).' / '.$DATA_FOOTER[$i]->comments;
											if ($DATA_FOOTER[$i]->comments===1)
											{
												$strA .= " ".mb_strtolower($lang->comment);
											} else
											{
												$strA .=  " ".mb_strtolower($lang->comment_a);
											};
					
										$strA .= '
										</div>
									</td>
								</tr>
							</table>
						</div>';
						};
					} else
					{
						$strA = $lang->currNoWS;
					};
					$footer .= $strA.'	
					</div>
					<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12 margin-md-t-10">
						<h4 class="strong uppercase">'.$lang->popular_workshops.'</h4>';

						if (count($DATA_FOOTER)) {
						$strA = '';
						for ($i=0; $i < count($DATA_FOOTER) && $i<2; $i++) {
						$strA .= '
						<div class="margin-t-20">
							<table>
								<tr>
									<td>
										<a href="'.$domain.'workshop/'.$DATA_HEADER[0]->workshopID.'">
											<img src="'.$FILE.'img/content/'.$DATA_HEADER[0]->image.'" width="45" height="45" />
										</a>
									</td>
									<td>
										<div class="strong">
											<a href="'.$domain.'workshop/'.$DATA_HEADER[0]->workshopID.'">'.$DATA_HEADER[0]->heading.'</a>
										</div>
										<div class="margin-t-10 text-faded">
											<i class="fa fa-calendar"></i> '.make_date(-1, $DATA_HEADER[0]->date_publish).' / '.$DATA_HEADER[0]->reviews;
											if (intval($DATA_HEADER[0]->reviews)===1)
											{
												$strA .= " ".mb_strtolower($lang->comment);
											} else
											{
												$strA .=  " ".mb_strtolower($lang->comment_a);
											};
					
										$strA .= '
										</div>
									</td>
								</tr>
							</table>
						</div>';
						};
					} else
					{
						$strA = $lang->currNoWS;
					}				

					$footer .= $strA.'						
					</div>					
				</div>				
			</div>
		</div>
		<div class="container-fluid post-footer padding-t-30 padding-b-30">
			<div class="col-md-push-1 col-md-6 col-lg-push-1 col-lg-6 col-xs-12 col-sm-12">
				Copyright © 2016. Handmade Fantasy World. '.$lang->allRightsReserved.'.
			</div>
			<div class="col-md-5 col-lg-5 col-xs-12 col-sm-12 follow-footer text-right margin-md-t-30">
				'.$lang->followUs.' 
				<table>
					<tr>
						<td class="social-icon-transform">
							<a href="http://www.facebook.com" target="_blank" title="'.$lang->findUsOnFace.'">
								<i class="view fa fa-facebook"></i><i class="no-view fa fa-facebook"></i>
							</a>
						</td>
						<td class="social-icon-transform">
							<a href="http://www.twitter.com" target="_blank" title="'.$lang->findUsOnTwitter.'">
								<i class="view fa fa-twitter"></i><i class="no-view fa fa-twitter"></i>
							</a>
						</td>
						<td class="social-icon-transform">
							<a href="http://www.googleplus.com" target="_blank" title="'.$lang->findUsOnGoogle.'">
								<i class="view fa fa-google-plus"></i><i class="no-view fa fa-google-plus"></i>
							</a>
						</td>
						<td class="social-icon-transform">
							<a href="http://www.dribbble.com" target="_blank" title="'.$lang->findUsOnDribbble.'">
								<i class="view fa fa-dribbble"></i><i class="no-view fa fa-dribbble"></i>
							</a>
						</td>
						<td class="social-icon-transform">
							<a href="http://www.pinterest.com" target="_blank" title="'.$lang->findUsOnPinterest.'">
								<i class="view fa fa-pinterest"></i><i class="no-view fa fa-pinterest"></i>
							</a>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<!-- /Footer -->
	';

	$upBtn = '<div class="up-btn" data-toggle="tooltip" data-placement="left" title="'.$lang->backToTop.'"><i class="fa fa-chevron-up"></i></div>';

	function printMainMenu($a,$b)
	{
		global $lang, $PAGES, $DATA_HEADER, $prepath, $domain, $FILE, $news, $year, $startMonth;
		echo '
		<div class="main-menu md-dn '.($a ? 'menu-fixed' : '').'">
			<div class="container content">
				<div class="col-md-5 logo relative font-eminenz">
					<a href="'.$domain.'">
						<img src="'.$FILE.'img/logo.png" width="180" style="position: absolute; top: 7px; left: -60px" />
						<div class="strong color-theme" style="font-size: 150%; position: absolute; top: 23px; left: 150px">Handmade Fantasy World</div>	
					</a>
				</div>
				<div class="col-md-7">
					<ul class="menu-items">';

						if ($b===1) echo '<li class="active">'.pop("home").'</li>'; else echo '<li><a href="'.$domain.'">'.pop("home").'</a></li>';
						if ($b===2) echo '<li class="active">'.pop("about").'</li>'; else echo '<li><a href="'.$domain.$PAGES->about.'">'.pop("about").'</a></li>';

						if ($b===3) echo '<li class="active has-submenu relative">'.pop("online_workshops"); else echo '<li class="has-submenu relative"><a href="'.$domain.$PAGES->workshops.'">'.pop("online_workshops").'</a>';

						echo '												
							<div class="sub-menu container-fluid" style="left: 0px;">
								<div class="row margin-t-10">
									<div class="col-md-12 padding-t-10 padding-b-10 col-xs-12 col-sm-12">
										<div class="strong color-theme" style="line-height: normal"><i class="fa fa-video-camera"></i> '.pop("online_workshops").'</div>
										<div class="small padding-t-5" style="line-height: normal">'.$lang->searchByMonth.'</div>
									</div>			
								</div>
								<div class="divider width-100" style="margin-bottom: 0"></div>	
								<div class="row">

								<div class="menu-choices">';									
									for ($i=$startMonth-1; $i<count($lang->c_months); $i++)
									{
										echo '<a href="'.$domain.'workshops/'.($i+1).'/'.$year.'"><div class="item">'.$year.' - '.$lang->c_months[$i].'</div></a>';
									};									
								echo '</div>								
							   </div>														
							</div>
						</li>
						';						

						if ($b===5) echo '<li class="active">'.pop("gallery").'</li>'; else echo '<li><a href="'.$domain.$PAGES->gallery.'">'.pop("gallery").'</a></li>';

						if ($b===6) echo '<li class="active relative">'.pop("news"); else echo '<li class="relative"><a href="'.$domain.$PAGES->news.'">'.pop("news");
						if ($news>0) echo '<div class="news-alert" '.($a===1 ? "style=\"top: 0px\"" : "").'>'.$news.'</div>';
						echo '</a></li>';
											
						echo '
						<li class="right-side has-submenu relative">
							<a href="javascript: void(0)"><i class="fa fa-caret-down anim-icon fa-2x color-theme" style="position: relative; top: 5px"></i> '.$lang->more.'</a>
							<div class="sub-menu" style="left: -20px; width: 260px">
								<div class="menu-choices">
									<a href="'.$domain.'calendar"><div class="item">'.$lang->contact.'</div></a>
								</div>
								<div class="menu-choices">
									<a href="'.$domain.'calendar"><div class="item">'.$lang->calendar.'</div></a>
								</div>
							</div>
						</li>
					</ul>					
				</div>
			</div>
		</div>
		';
	};

?>