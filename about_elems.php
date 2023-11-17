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

?>
<!DOCTYPE html>
<html>
<head>
	<?php print_HTML_data("head","about") ?>
</head>
<body>

	<?php echo $mobileMenu; $mobileMenu = NULL; ?>

	<!-- Fixed Menu -->
	<?php printMainMenu(1,2); ?>

	<!-- Header container -->
	<div class="header-container md-dn">
		<?php echo $headerInfo; $headerInfo = NULL; ?>
		<?php printMainMenu(0,2); ?>		
	</div>
	<!-- /Header container -->

	<!-- Main Container -->
	<div class="main-container margin-md-t-60">
		<div class="welcome-note welcome-note-min welcome-about">
			<div class="overlay"></div>
			<div class="content text-center">
				<div class="heading strong"><?php echo $lang->about ?></div>
				<div class="sub-heading">
					Pertinax invenire eos, ut vis eius maiestatis temporibus
				</div>
			</div>
		</div>
	</div>
	<!-- /Main Container -->

	<div class="container-fluid padding-t-40 padding-b-40">		
		<div class="row text-center margin-t-10">
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
				Graeco et quo aeterno, solet <span class="strong">laboramus</span> Laoreet praesent sed cu.				
			</div>
		</div>	
		<div class="row text-center margin-t-10">
			<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
				Qui ex melius consulatu, ad <span class="strong">pertinax invenire</span> eos, ut vis eius maiestatis temporibus
			</div>
		</div>	
	</div>

	<div class="container-fluid gray-bg padding-t-40 padding-b-40 about-detail-container">
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-xs-12 col-sm-12 text-center">
					<i class="fa fa-paint-brush fa-2x color-theme"></i>
					<div class="margin-t-10 l-sp-1 strong"><?php echo $lang->new_WorkshopsUP ?></div>
					<div class="margin-t-10 smaller">
						Morbi ac placerat nibh, in vestibulum odio cras vel orci non dolor sagittis sollicitudin et eu jeriu ntesque.
						Curabitur vitae augue
					</div>
				</div>

				<div class="col-md-4 col-xs-12 col-sm-12 text-center margin-md-t-20">
					<i class="fa fa-rocket fa-2x color-theme"></i>
					<div class="margin-t-10 l-sp-1 strong"><?php echo $lang->new_opportunitiesUP ?></div>
					<div class="margin-t-10 smaller">
						Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id mi dolor, vel rutrum nulla lobortis vel etiam mauris sem.
					</div>
				</div>

				<div class="col-md-4 col-xs-12 col-sm-12 text-center margin-md-t-20">
					<i class="fa fa-lightbulb-o fa-2x color-theme"></i>
					<div class="margin-t-10 l-sp-1 strong"><?php echo $lang->new_ideasUP ?></div>
					<div class="margin-t-10 smaller">
						Mel an cibo dolore persequeris, eu justo lorem epicuri duo. Mea eleifend principes te. Qui iusto homero. Sed ut perspiciatis unde omnis iste natus errata.
					</div>
				</div>
			</div>
		</div>		
	</div>

	<div class="parallax container-fluid">
		<div class="container margin-t-80 margin-b-80">
			<div class="row text-center">
				<div class="col-md-3 col-xs-12 col-sm-12">
					<i class="fa fa-star fa-3x"></i>
					<div class="margin-t-20 large">18</div>
					<div class="margin-t-10 text-faded l-sp-1 strong"><?php echo $lang->highest_ratesUP ?></div>
				</div>
				<div class="col-md-3 col-xs-12 col-sm-12 margin-md-t-50">
					<i class="fa fa-paint-brush fa-3x"></i>
					<div class="margin-t-20 large">27</div>
					<div class="margin-t-10 text-faded l-sp-1 strong"><?php echo $lang->workshopUP ?></div>
				</div>
				<div class="col-md-3 col-xs-12 col-sm-12 margin-md-t-50">
					<i class="fa fa-play fa-3x"></i>
					<div class="margin-t-20 large">74</div>
					<div class="margin-t-10 text-faded l-sp-1 strong"><?php echo $lang->active_usersUP ?></div>
				</div>
				<div class="col-md-3 col-xs-12 col-sm-12 margin-md-t-50">
					<i class="fa fa-lightbulb-o fa-3x"></i>
					<div class="margin-t-20 large">351</div>
					<div class="margin-t-10 text-faded l-sp-1 strong"><?php echo $lang->new_ideasUP ?></div>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid padding-t-20">
		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12 text-center">
				<h3 class="strong underlined padding-b-20"><?php echo $lang->meet_ourTeamUP ?></h3>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-xs-12 col-sm-12 text-center padding-t-10">
				Sit sale vocibus ad, qui ne dictas menandri reprehendunt.
				
				<div class="padding-t-5">
					Pro ei nemore saperet. Et <span class="strong">denique voluptaria</span> conclusionemque sed, sonet sensibus an sed. 
				</div>
			</div>
		</div>
		<div class="container margin-t-30">
			<div class="row">
				<div class="row">
					<div class="col-md-3 col-xs-12 col-sm-12">
						<div class="personnel">
							<img src="<?php echo $FILE ?>img/users/test1.jpg" />
							<div class="hovercard">
								<div class="smaller strong"><?php echo $lang->artistUP ?></div>
								<div>Teodora Milanović</div>
								<div class="padding-t-5 padding-b-10 text-center smaller">
									<div class="round"><i class="fa fa-facebook"></i></div>
									<div class="round"><i class="fa fa-twitter"></i></div>
									<div class="round"><i class="fa fa-google-plus"></i></div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-3 col-xs-12 col-sm-12 margin-md-t-30">
						<div class="personnel">
							<img src="<?php echo $FILE ?>img/users/test2.jpg" />
							<div class="hovercard">
								<div class="smaller strong"><?php echo $lang->koordinatorUP ?></div>
								<div>Bojan Ristić</div>
								<div class="padding-t-5 padding-b-10 text-center smaller">
									<div class="round"><i class="fa fa-facebook"></i></div>
									<div class="round"><i class="fa fa-twitter"></i></div>
									<div class="round"><i class="fa fa-google-plus"></i></div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-3 col-xs-12 col-sm-12 margin-md-t-30">
						<div class="personnel">
							<img src="<?php echo $FILE ?>img/users/test3.jpg" />
							<div class="hovercard">
								<div class="smaller strong"><?php echo $lang->contributorUP ?></div>
								<div>Violeta Marković</div>
								<div class="padding-t-5 padding-b-10 text-center smaller">
									<div class="round"><i class="fa fa-facebook"></i></div>
									<div class="round"><i class="fa fa-twitter"></i></div>
									<div class="round"><i class="fa fa-google-plus"></i></div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-3 col-xs-12 col-sm-12 margin-md-t-30">
						<div class="personnel">
							<img src="<?php echo $FILE ?>img/users/test4.jpg" />
							<div class="hovercard">
								<div class="smaller strong"><?php echo $lang->finance_directorUP ?></div>
								<div>Milan Petrović</div>
								<div class="padding-t-5 padding-b-10 text-center smaller">
									<div class="round"><i class="fa fa-facebook"></i></div>
									<div class="round"><i class="fa fa-twitter"></i></div>
									<div class="round"><i class="fa fa-google-plus"></i></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid gray-bg margin-t-50 padding-t-60 padding-b-90">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12 text-center">
					<h3 class="strong underlined padding-b-20"><?php echo $lang->what_peopleSayUP ?></h3>
				</div>
			</div>
			<div class="row margin-md-t-10">
				<div class="col-md-12 col-xs-12 col-sm-12 text-center small">
					<div>Phasellus vitae ipsum ex. Etiam eu vestibulum ante. </div>
					<div>
						Lorem <span class="strong">ipsum dolor</span> sit amet, consectetur adipiscing elit. Morbi libero libero, imperdiet fringilla
					</div>
				</div>
			</div>
			<div class="row margin-t-30">
				<div class="col-md-6 col-xs-12 col-sm-12">
					<div class="testimonial">
						<img class="margin-t-20" src="<?php echo $FILE ?>img/users/test1.jpg" />
						<div class="margin-t-10">
							<div class="small">
							Posse libris everti pri no, eum alienum mandamus ex. Ei vide omnes omnesque ius. Ea facilisis gloriatur mei, id his atqui tollit imperdiet. Timeam discere qui te. Cu eum labitur volumus mnesarchum.
							</div>
							<br>
							<span class="margin-t-20 strong">Marina Stojanović</span>
							<span style="display: block;"><?php echo $lang->graduate_economics ?></span>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-xs-12 col-sm-12 margin-md-t-30">
					<div class="testimonial">
						<img class="margin-t-20" src="<?php echo $FILE ?>img/users/test2.jpg" />
						<div class="margin-t-10">
							<div class="small">
							Mel an cibo dolore persequeris, eu justo lorem epicuri duo. Mea eleifend principes te. Qui iusto homero at, nec verear meliore complectitur ne, ipsum scripta duo ad.
							</div>
							<br>
							<span class="margin-t-20 strong">Marko Simonović</span>
							<span style="display: block;"><?php echo $lang->graduate_law ?></span>
						</div>
					</div>
				</div>
			</div>

			<div class="row margin-t-30">
				<div class="col-md-6 col-xs-12 col-sm-12">
					<div class="testimonial">
						<img class="margin-t-20" src="<?php echo $FILE ?>img/users/test4.jpg" />
						<div class="margin-t-10">
							<div class="small">
							Posse libris everti pri no, eum alienum mandamus ex. Ei vide omnes omnesque ius. Ea facilisis gloriatur mei, id his atqui tollit imperdiet. Timeam discere qui te. Cu eum labitur volumus mnesarchum.
							</div>
							<br>
							<span class="margin-t-20 strong">Vasilije Mitrović</span>
							<span style="display: block;"><?php echo $lang->physiotherapist ?></span>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-xs-12 col-sm-12 margin-md-t-30">
					<div class="testimonial">
						<img class="margin-t-20" src="<?php echo $FILE ?>img/users/test3.jpg" />
						<div class="margin-t-10">
							<div class="small">
							Feugiat graecis ei his, mel id illum augue soleat, elit nonumy omnium per et. Modo choro sanctus ut duo, viderer scriptorem in his. Malorum ullamcorper consectetuer.
							</div>
							<br>
							<span class="margin-t-20 strong">Irena Filipović</span>
							<span style="display: block;"><?php echo $lang->director_newBusiness ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php  echo $footer; $footer = NULL; ?>
	<?php echo $upBtn; $upBtn = NULL; ?>

	<?php print_HTML_data("script","about") ?>

</body>
</html>