<?php 
	session_start();
	$prepath = '../';
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
<body class="<?= $bodyClass ?>">

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
		<div class="welcome-note welcome-note-min welcome-artist">
			<div class="overlay"></div>
			<div class="content text-center">
				<div class="heading strong heading-special heading-special-rest md-heading-special padding-l-60 padding-r-60" style="display: inline-block;"> <?php echo $lang->about ?> </div>							
			</div>
		</div>
	</div>
	<!-- /Main Container -->

	<div class="container-fluid padding-t-40 padding-b-40">		
		<div class="container">
			<div class="row text-center margin-t-10">
				<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
					<h2 class="margin-t-none">Hi, my name is <span class="strong color-theme">Antonis Tzanidakis</span></h2>			
				</div>
			</div>	
			<div class="row margin-t-10">				
				<div class="col-md-12 col-xs-12 col-sm-12 margin-t-20">					
					<div class="margin-t-10">
						I'm from Greece and I'm mixed media artist. Three years ago this art accidentally came to my life. I started it as hobby and gave it a lot of time.
						I felt so relaxed, so I went further and further into it. Somehow, this became something big in my mind, like obsession and I saw very soon that I am very good at this. It  led me to make  page on Facebook <span class="strong">"Handmade Fantasy"</span> and from that moment I knew I want more...  to make someday Handmade Fantasy Around the World, project that will be global and recognized world-wide.
					</div>
				</div>
			</div>	
			<div class="row margin-t-20">					
				<div class="col-md-4 col-xs-12 col-sm-12 image-optimised">
					<div><img src="<?= $FILE ?>img/antonis.jpg"></div>
				</div>
				<div class="col-md-8 col-xs-12 col-sm-12 margin-md-t-20">
					<div>
						One day a friend of mine asked me if I want to teach people, to teach people of my way to create and that was my beginning doing workshops under name <span class="strong color-theme">Handmade Fantasy</span>. 
					</div>
					<div class="margin-t-20">					
						 I started from Lefkada Island,the place I was living at that moment ,and day by day, month by month,people around Greece started asking me to organize workshops at their shops. Very soon messages came from other countries,so my dream almost came true..					
					</div>

					<div class="margin-t-20">
						From the beginning I had idea to start website like this, so that people from every corner of the World can see how I work, without losing time in travel and spending much money. That’s how I got to this and now all of you can visit my place called  <a class="link-ord color-theme strong" href="http://www.handmadefantasyworld.com" target="_blank">handmadefantasyworld.com</a> .
					</div>
				</div>			
			</div>

			<div class="margin-t-60">
				<div class="divider width-100"></div>
			</div>

			<div class="row margin-t-30">
				<div class="col-md-12 col-xs-12 col-sm-12">
					<div class="text-center"><i class="fa fa-lightbulb-o fa-3x color-red"></i></div>
					<div class="text-center"><h3 class="strong color-theme margin-t-none margin-md-t-10">So a lot of things gonna happen inside here, I have many ideas.</h3></div>					
				</div>
			</div>

			<div class="row margin-t-20">
				<div class="col-md-12 col-xs-12 col-sm-12 text-center">
					<a href="<?= $domain ?>packages">
						<div id="subscribeBtn" class="btn-welcome color-theme btn-ord small"><i class="fa fa-video-camera"></i> <span class="padding-l-5">Subscribe to my online workshops</span></div>
					</a>
				</div>
			</div>

			<div class="margin-t-40">
				<div class="divider width-100"></div>
			</div>

			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12">
					<div class="margin-t-20">Be in touch!</div>
					<div>Thank you</div>
				</div>
			</div>			
		</div>
	</div>

	<?php  echo $footer; $footer = NULL; ?>
	<?php echo $upBtn; $upBtn = NULL; ?>

	<?php print_HTML_data("script","about") ?>

</body>
</html>