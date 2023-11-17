

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