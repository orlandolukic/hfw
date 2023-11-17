<?php 

/*  PRINT COMMENT - UI
	Before starting, make sure you initialized variables:
	=====================================================
	1. $sql_comments [mysqli_query]
	2. $dat          [string]
	3. $op           [integer]
	4. $offset       [array STRING] { top: [.class] ; bottom: [.class]; left: [.class]; right: [.class]	 }
	5. $clss         [string] // direct class name that should be included on second+ comment
*/
//  Begin With Code
	$sql = mysqli_query($CONNECTION, "SELECT * FROM comments WHERE reviewID='".$DATA->reviewID."' ORDER BY time DESC, date DESC");

	if ($numberSQL = mysqli_num_rows_wrapper($sql_comments)) {
		$go = 0;
		if ($numberSQL>1) $comments = array();
		while($qqq = mysqli_fetch_object_wrapper($sql_comments)) {

		if ($numberSQL>1) { $comments[$go] = $qqq->commentID; } else $commID = $qqq->commentID;

		$pom1_sql = mysqli_query($CONNECTION, "SELECT * FROM users WHERE username='".$qqq->username."' LIMIT 1");
		while($j = mysqli_fetch_object_wrapper($pom1_sql)) $US_COMM = $j;
		$comm_ad = !!!true;

		if ($US_COMM->account_type)			
		{
			$pom1_sql = mysqli_query($CONNECTION, "SELECT * FROM administrators WHERE username='".$qqq->username."'");
			while($j = mysqli_fetch_object_wrapper($pom1_sql)) $US_COMM = $j;
			$comm_ad = true;
		}
	
	$dat .= '<div class="comment-user ';

	if (isset($clss))
	{
		if ($go>0) $dat .= $clss;
	} else
	{
		for ($g=0; $g<count($offset); $g++)
		{
			$dat .= $offset[$g].' ';
		};
	}

	$dat .= '" data-comment-id="'.$qqq->commentID.'" style="opacity: '.$op.'">			
				<div class="small fl-left">							
					<img src="'.make_image($US_COMM->image, $domain).'" class="user-comment-image" width="45" height="45" />	
				</div>
				<div class="small padding-l-60">';

				if ($comm_ad) {
			$dat.= '<span class="admin-ticket">
						'.$US_COMM->name." ".$US_COMM->surname.'
						<div class="verified pointer" data-toggle="tooltip" data-placement="top" title="'.$lang->verifiedUser.'"><i class="fa fa-check"></i></div>
					</span>
					'; 
				} else {
			$dat .='<span class="user-ticket strong">
						'.$US_COMM->name." ".$US_COMM->surname.'
					</span>
					';
				};
				$dat .= html_entity_decode($qqq->text).'
					<div class="smaller padding-t-5">
						<i class="fa fa-calendar"></i> '.make_date(!$lang_cond, $qqq->date).',
						<i class="fa fa-clock-o"></i> '.make_time($qqq->time);

						if (($userActive && $US_COMM->username===$user) || $adminActive) {
						$dat .= '<span class="padding-l-10">
							<a class="rUINI_delThisComment text-error strong-hover" href="javascript: void(0)" data-review-id="'.$qqq->reviewID.'" data-comment-id="'.$qqq->commentID.'"><i class="fa fa-trash"></i> '.$lang->delete.'</a>

							<span class="message-bef-del dn">
							'.$lang->comment_delete.'
							<span>
								<span class="padding-l-10">
									<a class="rUINI_delThisCommentProceed strong" href="javascript: void(0)"
									data-review-id="'.$qqq->reviewID.'" data-comment-id="'.$qqq->commentID.'">'.$lang->yes.'</a>
								</span>
								<span class="padding-l-10">
									<a class="rUINI_delThisCommentDecline strong" href="javascript: void(0)">'.$lang->no.'</a>
								</span>
							</span>
							</span>
						</span>';
						};
					$dat .= '</div>
				</div>
			</div>';
	$go++; }; };

	if (mysqli_num_rows_wrapper($sql) - $DATA->displayedComments - mysqli_num_rows_wrapper($sql_comments) === 0)
	{
		$more = false;
	} else
	{
		$more = $DATA->displayedComments+mysqli_num_rows_wrapper($sql_comments) < mysqli_num_rows_wrapper($sql);
	};

/*	Prints:
	1. $more [boolean]														// more comments
	2. $dat  [string]														// comment in textual form
	3. $commID ? $numberSQL === 1 
	   *** 
	   $comments [array of const char*] ? $numberSQL>1   					// returns one ID of comment or array
*/




?>