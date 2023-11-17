
<?php 

//	SET @PRINT_MESSAGE@
	//if (!isset($PRINT_MESSAGE) || isset($PRINT_MESSAGE) && $PRINT_MESSAGE->open === false) header("location: ".$domain);

	$PRINT_MESSAGE->final = '<div class="row margin-t-20">
		<div class="col-md-8 col-md-push-2 col-xs-12 col-sm-12">
			<div id="panelMessage" class="panel panel-body dn bg-'.$PRINT_MESSAGE->messageType.' text-center">
				<i class="fa '.$PRINT_MESSAGE->messageIcon.'"></i> '.$PRINT_MESSAGE->messageText.'
			</div>
		</div>
	</div>';

?>