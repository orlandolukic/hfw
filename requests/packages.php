<?php
	$niz = array(0,0,0,0,0,0,0,0,0,0,0,0);
	if($userActive) 
	{
		$sql = mysqli_query($CONNECTION,"SELECT type,start_month FROM `subscriptions` WHERE BINARY username='".$USER->username."'");
		if( mysqli_num_rows_wrapper($sql) ) {
			while($pom = mysqli_fetch_object_wrapper($sql)) 
			{
				if($pom->type == 1) 
				{
					$niz[$pom->start_month-1] = 1;

				} else if($pom->type == 2) {
					for ($i=$pom->start_month; $i < ($pom->start_month + 3); $i++) { 
						$niz[$i-1] = 1;
					}
				} else if($pom->type == 3) {
					for ($i=$pom->start_month; $i < ($pom->start_month + 12); $i++) { 
						$niz[$i-1] = 1;
					}
				}
			} 
		}
	};

	//	Get info for packages
    $PACKAGES = array(); $i = 0;
    $sql = mysqli_query($CONNECTION,"SELECT packageID, name_".$lang_acr." AS ptext, price_".$curr." AS pprice, price_RSD AS ppriceRSD, flag, sort FROM packages WHERE active=1 ORDER BY packageID ASC");
    while($t = mysqli_fetch_object_wrapper($sql)) $PACKAGES[$i++] = $t;
    ?>	   

    	<div class="col-md-8 col-lg-8 col-xs-12 col-sm-12 margin-md-t-50 bordered-theme">

    		<?php for($i=0,$temp = count($PACKAGES);$i<$temp;$i++) { ?>
    		<div class="margin-t-10 margin-b-20">
	    		<div class="text-left color-theme padding-t-10 padding-b-10 padding-l-20">
						<h2 class="strong" style="margin: 0"> <?= print_duration($PACKAGES[$i]->flag) ?> - online workshops  </h2>
				</div>
			
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
						<div class="text-left padding-l-20">
							<?php echo $PACKAGES[$i]->ptext ?>							
						</div>
					</div>


					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
						<div class="text-left padding-l-20 padding-t-20">
							<a href="javascript: void(0)" class="select-btn" data-month-flag="<?= $PACKAGES[$i]->flag ?>">
								<div class="btn-buy btn-ord uppercase"><i class="fa fa-shopping-cart"></i> Buy</div>
							</a>
						</div>
					</div>

				</div>


				<?php if($i<$temp-1) { ?>
				<div class="margin-t-20 margin-b-20">
					<div class="divider width-100"></div>
				</div>
				<?php } ?>	
			</div>
			<?php }; ?>
    	</div>

    <div class="margin-md-t-30">
	<?php for($i=0;$i<count($PACKAGES);$i++) { ?>
		<div class="month-show col-md-4 col-lg-4 col-xs-12 col-sm-12 margin-md-t-30 <?= ($i>0 ? "margin-md-t-50 dn" : ""); ?>" data-month-flag="<?= $PACKAGES[$i]->flag ?>" 
	data-base-url="<?= ($userActive ? $FILE."user/addCart/subscription/".$PACKAGES[$i]->packageID."?" : 
	$domain."login.php?action=addCart&type=subscription&cid=".$PACKAGES[$i]->packageID."&") ?>">
			<div class="pricing-table <?= ($PACKAGES[$i]->sort === "2" ? "table-extra" : "table-ordinary") ?> bg-white bordered-theme">
				<div class="main background-theme">
					<h2 class="strong color-white" style="margin: 0"><?= print_duration($PACKAGES[$i]->flag) ?></h2>
				</div>
				<div class="text-center money relative">
					<div class="container margin-t-10 margin-l-10">
						<div class="text-left small">Choose month</div>
						<div class="padding-t-5">
						    <div class="month-selection sort-options fold " style="padding-right: 30px; z-index: 203; max-width: 200px; margin-left: 0px;">
								<div class="text-left change-month default-option"><?= $lang->c_months[$startMonth-1] ?></div>							
								<ul class="options text-left">
                                    <?php
                                    ?>
									<?php for ($j=$startMonth-1; $j<count($lang->c_months); $j++) { if($niz[$j] != 1) { ?>
									<li class="text-left <?php if($j==$startMonth-1) echo 'strong' ?>" data-value="<?= ($j+1) ?>" data-html-month="<?= $lang->c_months[$j] ?>"><a href="javascript: void(0)"><div><?= $lang->c_months[$j] ?></div></a></li>
									<?php } else { ?>
									<li class="disabled text-left" data-placement="right" data-toggle="tooltip" data-original-title="Subscribed" data-value="<?= ($j+1) ?>" data-html-month="<?= $lang->c_months[$j] ?>">
										<div><?= $lang->c_months[$j] ?></div> 
									</li>
									<?php }}; ?>
								</ul>
								<i class="fold fa fa-chevron-down"></i>
								<i class="unfolded fa fa-chevron-up"></i>
							</div> 
						</div>
					</div>

						<span class="money-currency">RSD</span><span class="large strong"><?= print_money_PLAINTXT($PACKAGES[$i]->ppriceRSD,0) ?></span>
						<span class="after-money">/ <?= ($PACKAGES[$i]->flag == 1 ? pop("oneMonth") : ($PACKAGES[$i]->flag == 3 ? pop("threeMonths") : pop("oneYear") )) ?></span>
						<?php if ($curr !== "RSD") { ?><div class="foreign-currency-price styled strong"><?= $currName." ".print_money_PLAINTXT($PACKAGES[$i]->pprice,2); ?></div> <?php }; ?>
				</div>

				<div class="margin-t-20 margin-b-20 text-center">
					<?php if ($userActive) { ?>
					<a class="subs" href="<?= $FILE ?>user/addCart/subscription/<?= $PACKAGES[$i]->packageID; ?>?month=<?= ($startMonth)?>">
						<div class="btn-buy btn-ord uppercase"><i class="fa fa-shopping-cart"></i> <?php echo $lang->subscribe_now ?></div>
					</a>
					<?php } else { ?>
					<a class="subs" href="<?= $domain ?>login.php?action=addCart&type=subscription&cid=<?= $PACKAGES[$i]->packageID; ?>&month=<?= ($startMonth)?>" >
						<div class="btn-buy btn-ord uppercase"><i class="fa fa-shopping-cart"></i> <?php echo $lang->subscribe_now ?></div>
					</a>
					<?php }; ?>
				</div>
			</div>					
		</div>
		<?php } ?>
		</div>

	
