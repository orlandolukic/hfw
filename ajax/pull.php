<?php
	// Begin with..
	if (!isset($_POST['HttpRequest']) || !isset($_POST['data'])) exit();
	session_start();
	$prepath  = "../";
	$REDIRECT = false;
	include "../functions.php";
	include "../connect.php";
	if ($_SESSION['lang'] !== "EN")
	{
		include "../".strtolower($_SESSION['lang'])."/global.php";
		include "../".strtolower($_SESSION['lang'])."/getDATA.php";
	} else
	{
		include "../global.php";
		include "../getDATA.php";
	};

//	Prepare for data conditions check
	$arr_info = array(); $arr_data = array();
	$DETAIL   = json_decode($_POST['HttpRequest']);
	$DATA     = json_decode($_POST['data']);
	$RESPONSE = array("info" => &$arr_info, "data" => &$arr_data);
	$dat      = '';

	switch($DETAIL->type)
	{
	//	 Leave review section
	case base64_encode("fetchReviews"):	
		$DATA->SQL_statement = base64_decode($DATA->SQL_statement);
		if ($DATA->SQL_statement==="recent")
		{	
			if ($adminActive)
			{
				$sql_main = mysqli_query($CONNECTION, "SELECT * FROM reviews WHERE workshopID = '".$DATA->workshopID."' AND username IN (SELECT username FROM users WHERE active=1)  ORDER BY status ASC, date DESC LIMIT 5 OFFSET ".( (intval($DATA->page)-1) * 5 )."");
			} elseif ($userActive)
			{
				$sql_main = mysqli_query($CONNECTION, "SELECT * FROM reviews WHERE workshopID = '".$DATA->workshopID."' AND username IN (SELECT username FROM users WHERE active=1) AND ((username='$user' AND status=-1) OR status=1) ORDER BY (`username`='".$user."') DESC, date DESC LIMIT 5 OFFSET ".( (intval($DATA->page)-1) * 5 )."");
			} else
			{
				$sql_main = mysqli_query($CONNECTION, "SELECT * FROM reviews WHERE workshopID = '".$DATA->workshopID."' AND status=1 AND username IN (SELECT username FROM users WHERE active=1)  ORDER BY date DESC LIMIT 5 OFFSET ".( (intval($DATA->page)-1) * 5 )." ");
			};
		} elseif ($DATA->SQL_statement==="top")
		{
			if ($adminActive)
			{
				  $sql_main = mysqli_query($CONNECTION, "SELECT * FROM reviews WHERE workshopID = '".$DATA->workshopID."' AND username IN (SELECT username FROM users WHERE active=1)  ORDER BY rating DESC, status ASC, date DESC LIMIT 5 OFFSET ".( (intval($DATA->page)-1) * 5 )."");
			} elseif ($userActive)
			{
				$sql_main = mysqli_query($CONNECTION, "SELECT * FROM reviews WHERE workshopID = '".$DATA->workshopID."' AND username IN (SELECT username FROM users WHERE active=1) AND ((username='$user' AND status=-1) OR status=1) ORDER BY (`username`='".$user."') DESC, rating DESC LIMIT 5 OFFSET ".( (intval($DATA->page)-1) * 5 )."");
			} else
			{
				$sql_main = mysqli_query($CONNECTION, "SELECT * FROM reviews WHERE workshopID = '".$DATA->workshopID."' AND username IN (SELECT username FROM users WHERE active=1) AND status=1 ORDER BY rating DESC, date DESC LIMIT 5 OFFSET ".( (intval($DATA->page)-1) * 5 )." ");
			};
		};

		if (!$sql_main) { $arr_info = array("response" => false, "error_code" => "0x0194"); $arr_data = NULL; break; };

		$dat = '';
		while($t = mysqli_fetch_object_wrapper($sql_main))
		{
			$dat .= '<!-- .user-comment -->	<div class="user-comment col-md-12 col-xs-12 col-sm-12 padding-b-40 margin-t-20" data-comment-id="'.$t->reviewID.'" data-rating="'.$t->rating.'">';
			//	Specific username
			//	Options: [DELETE, INFO]
    		if ($userActive && $t->username===$user || $adminActive) {
				$dat .= '<div class="user-comment-notactive smaller '.(intval($t->status)===1 ? "dn" : "").'"><i class="fa fa-refresh"></i> <span>'.$lang->onCheck.'</span>
				</div>
				<div class="user-comment-actions div-inl">';

				if (!$adminActive) {

					$dat.='<div data-toggle="tooltip" title="'.$lang->delete_Review.'" data-placement="bottom" class="rUINI_del btn-theme-fix btn-rounded-danger btn-padding-x-small" data-id="'.pop_obj($t,"reviewID").'" style="border-radius: 0"><i class="fa fa-trash"></i>
					</div>
					<div data-toggle="tooltip" title="'.$lang->change_Review.'" data-placement="bottom" class="rUINI_ed btn-theme-fix btn-rounded-danger btn-padding-x-small" data-id="'.pop_obj($t,"reviewID").'" style="border-radius: 0; margin: 0"><i class="fa fa-edit"></i></div>';
				} else {	
	    						
					$dat .= '<div data-toggle="tooltip" title="'.$lang->delete_Review.'" data-placement="bottom" class="rUINI_del btn-theme-fix btn-rounded-danger btn-padding-x-small" data-id="'.pop_obj($t,"reviewID").'" style="border-radius: 0"><i class="fa fa-trash"></i>
					</div>';
					if ($t->status<1 && $ADMIN->access_allow_commenting) {
					$dat .= '<div data-toggle="tooltip" title="'.$lang->allow_Review.'" data-placement="bottom" class="rAINI_allow btn-theme-fix btn-rounded-success btn-padding-x-small" data-id="'.pop_obj($t,"reviewID").'" style="border-radius: 0"><i class="fa fa-check"></i>
					</div>';
					}; //  if ($reviews[$i]->status<1 && $ADMIN->access_allow_commenting) { ... } else { ... }
				}; //  if (!$adminActive) { ... } else { ... }
				$dat .= '</div>';
				
				if ($t->status<1 && $adminActive && $ADMIN->access_allow_commenting) {
					$dat .= '<div class="admin-property check-circle color-success"><i class="fa fa-check-circle fa-3x"></i></div>';
				 };
				
				if (!$adminActive) {
					$dat .= '<div class="user-comment-property smaller">'.$lang->myComment.'</div>';
				};
				
				$dat .= '<div class="user-comment-delete dn">
					<div class="container">
						<div class="col-md-push-1 col-md-5 col-xs-12 col-sm-12">
							<div class="margin-t-40 margin-md-t-10 strong" style="font-size: 120%"><i class="fa fa-trash"></i> '.$lang->review_delete.'</div>
							<div class="margin-t-10 text-left">
								'.$lang->areYouSureReview.'
							</div>
						</div>
						<div class="col-md-6 col-xs-12 col-sm-12 margin-t-70 margin-md-t-10 md-text-center div-inl">
							<div class="rUINI_delConf btn-theme-fix btn-rounded-danger btn-full btn-padding-x-small small" style="border-radius: 0" data-id="'.pop_obj($t,"reviewID").'"><i class="fa fa-trash"></i> '.$lang->delete.'</div>

							<div class="rUINI_decl btn-theme-fix btn-rounded-danger margin-md-t-10  btn-padding-x-small small" data-id="'.pop_obj($t,"reviewID").'" style="border-radius: 0;"><i class="fa fa-times"></i> '.$lang->cancel.'</div>
						</div>
					</div>
				</div>';

			    }; // END OF USER'S OPTIONS


			// Searching for review's user
			$pom_sql = mysqli_query($CONNECTION, "SELECT name, surname, image, username FROM users WHERE username='".$t->username."' LIMIT 1");
			while($m = mysqli_fetch_object_wrapper($pom_sql)) $userOBJ = $m;

			// Making profile image
			$imgsrc = make_image($userOBJ->image, $FILE);

			// Making time and date [DISPLAY]
			$tm = make_time($t->time);
			$dt = make_date(!$lang_cond, $t->date);
			$stars = determine_rating($t->rating);
			
			$dat .= '<img class="user-comment-image" src="'.$imgsrc.'" width="50" /> 
			<div class="user-comment-info">
				<span class="strong">'.$userOBJ->name." ".$userOBJ->surname.'</span>
				<div class="smaller text-faded"><span class="review-date">'.$dt.'</span>, '.$lang->in.' <span class="review-time">'.$tm.'</span></div>
				<div>	
					<span class="stars star-color">		    						
	    				<span class="fa '.$stars[0].'"></span>
		    			<span class="fa '.$stars[1].'"></span>
		    			<span class="fa '.$stars[2].'"></span>
		    			<span class="fa '.$stars[3].'"></span>
		    			<span class="fa '.$stars[4].'"></span>	
	    			</span>
	    			<span class="stars-num padding-l-5 small strong">'.$t->rating.'</span>    
				</div>
			</div>							    				
			<div class="margin-t-20 margin-l-60">
				<div class="review-title strong">'.pop_obj($t, "title").'</div>
				<div class="review-text">'.$t->text.'</div>
			</div>'; 

			// Discussion \/ comments \/ replies

			$sql_comments = mysqli_query($CONNECTION, "SELECT * FROM comments WHERE reviewID='".$t->reviewID."' ORDER BY time DESC, date DESC LIMIT 5");
			// bg: 144
			if (mysqli_num_rows_wrapper($sql_comments) || $adminActive) {

			$dat .= '<div class="row margin-md-t-20 margin-t-10 small">
				<div class="col-md-5 col-xs-12 col-sm-12 padding-l-70 padding-md-l-10">
					<div class="btn-fix user-comment-all-comments div-inl">';
						if (($adminActive && $ADMIN->access_workshop_comments) || ($userActive && mysqli_num_rows_wrapper($sql_comments) && $t->username===$user)) {
					 	$dat .= '<div>
							<a class="comment_answer" data-review-id="'.$t->reviewID.'" href="javascript: void(0)">
								<div><i class="fa fa-reply"></i> '.$lang->reply.'</div>
							</a>
						</div>';
					    };				    							
						// Subquery
						$sql_pom = mysqli_query($CONNECTION, "SELECT * FROM comments WHERE reviewID='".$t->reviewID."'");
						
						$dat .= '<div>
							<a data-review-id="'.$t->reviewID.'" class="comment_showComments '.(!mysqli_num_rows_wrapper($sql_pom) ? "dn" : "").'" href="javascript: void(0)">
								<div><i class="fa fa-chevron-down"></i> '.$lang->check_comment.'</div>
							</a>
						</div>					    							
					</div>
				</div>
			</div>';
			};
			// bg: 144, en: 169
			$dat .= '<div class="comments-placeholder dn" data-review-id="'.$t->reviewID.'">
			<div class="row margin-t-10">
				<div class="col-md-12 col-xs-12 col-sm-12" style="padding: 0">
					<div class="divider width-100" style="margin-bottom: 0"></div>
				</div>
			</div>
			<div class="row">';
				// bg: 177
				if ($adminActive || ($userActive && $userOBJ->username===$user)) {
				$dat .= '<div class="col-md-6 col-xs-12 col-sm-12" style="padding: 0">
					<div class="user-answer padding-b-20">
						<div class="div-inl">    								
							<div class="initiator-comment-image pointer">';
							    if ($adminActive) 
									{ $img = $ADMIN->image; $name = $ADMIN->name." ".$ADMIN->surname; } 
									else { $img = $userOBJ->image; $name = $userOBJ->name." ".$userOBJ->surname; };

								$dat .= '<img class="user-comment-image" src="'.$FILE.'img/users/'.$img.'" width="45" height="45" title="'.$name.'" data-toggle="tooltip" data-placement="top" />
							</div>	    								
							<div style="width: 100%" class="padding-l-10">
								<div class="initiator-comment" placeholder="'.$lang->comment_site.'" contenteditable="true" data-review-id="'.$t->reviewID.'"></div>
							</div>				    								
						</div>
						<div class="text-right">
							<div class="btn-initiator-send smaller" data-review-id="'.$t->reviewID.'">'.$lang->send_comment.'</div>
						</div>
					</div>
				</div>';
				};
				// bg: 177, en: 199
				$dat .= '<div class="col-md-6 col-xs-12 col-sm-12 border-r-shadow-eff border-b-shadow-eff" style="padding: 0">
					<div class="pre-allComments padding-t-10 padding-b-10 l-sp-1 strong  uppercase padding-l-20">'.$lang->comment_i.'</div>
					<div class="allComments" data-comments-num="'.mysqli_num_rows_wrapper($sql_comments).'">';

				$op = 1;
				$DATA->reviewID = $t->reviewID;
				$DATA->displayedComments = 0;
				$clss = 'margin-t-10 margin-md-t-20';
				require "printComment.php";
				
			   $gr = mysqli_num_rows_wrapper(mysqli_query($CONNECTION, "SELECT * FROM comments WHERE reviewID='".$t->reviewID."'"))>5;
				// bg: 204, en: 244;
			 $dat .= '</div><div class="fetch-more-comments small margin-t-10 margin-md-t-20 btn-ord '.(!$gr ? "dn" : "").'" data-fetch="0" data-review-id="'.$t->reviewID.'"><i class="fa fa-comments"></i>  '.$lang->more_comment.'</div>
				</div>
			</div>	
			</div>
		</div>';
		}; // While

	//	Successfully made query
		$arr_info = array("response" => true); $arr_data = $dat;
		break; // Main switch



	case base64_encode("deleteUserReview"):
		$sql = mysqli_query($CONNECTION, "DELETE FROM reviews WHERE username='$user' AND reviewID='".$DATA->reviewID."'");
		if ($sql)
		{
			$arr_info = array("response" => true); $arr_data = NULL;
		} else
		{
			$arr_info = array("response" => false); $arr_data = NULL;
		}
		break;

	case base64_encode("fetchMoreComments"):
		if (isset($_POST['retrieve_cURL']))
		{
			$sql_comments = $_POST['retrieve_cURL']["sql"];
		} else
		{
			$sql_comments = mysqli_query($CONNECTION, "SELECT * FROM comments WHERE reviewID='".$DATA->reviewID."' ORDER BY time DESC, date DESC LIMIT 5 OFFSET ".$DATA->displayedComments);
		}
		$dat = '';
		$op  = 1;
		$offset = array(0 => "margin-t-10 margin-md-t-10");
		require "printComment.php";

		$arr_info = array("response" => true);
		$arr_data = array("more_comments"      => $more, 
		                  "data_comments"      => intval($DATA->displayedComments)+mysqli_num_rows_wrapper($sql_comments),
		                  "sql_query_comments" => mysqli_num_rows_wrapper($sql_comments),    
		                  "htmlProperty"       => $dat,
		                  "commentID"          => ((bool) $numberSQL ? ($numberSQL===1 ? $commID : $comments) : -1),
		                  "hasData"            => (bool) $numberSQL
		                  );				
		break;

	case base64_encode("registerUserFetch"):
		$arr_data = NULL;
		$sql  = mysqli_query($CONNECTION, "SELECT * FROM users WHERE username='".$DATA->username."'");
		$sql1 = mysqli_query($CONNECTION, "SELECT * FROM users WHERE email = '".$DATA->email."'");

		if ($sql && $sql1)
		{
			$arr_info = array("response" => true, "foundUser" => (trim($DATA->email)!=="" ? (bool) mysqli_num_rows_wrapper($sql) : (bool) 0), "foundEmail" => (bool) mysqli_num_rows_wrapper($sql1));
		} else
		{
			$arr_info = array("false" => true, "errorCode" => 404);
		}
		break;

	case base64_encode("getPaymentAmount"):
		$REDIRECT = false;
		include "../requests/shopping.php";
		if ($CURR_SUM !== 0)
		{
			$arr_info["response"] = true;
			$arr_data["pc"] = $USER->currencyID;
			$arr_data["pa"] = number_format($CURR_SUM,2,".",",");
		};
		break;

	case base64_encode("checkPassword"):
		checkUser();
		$sql = mysqli_query($CONNECTION, "SELECT password FROM users WHERE BINARY username = '".$USER->username."'");
		if (mysqli_num_rows_wrapper($sql)) $t = mysqli_fetch_object_wrapper($sql);
		$arr_info["response"] = true;
		if ($DATA->password === $t->password) $arr_data["match"] = true; else $arr_data["match"] = false;
		break;

	case base64_encode("GET_Gallery_images"):
		$i = 0; $y = 0; $m = array();
		$offset = intval($DATA->items_per_page) * intval($DATA->page);
		if (!$userActive)
		{
			$string = "SELECT `tbl`.*, COUNT(`likes`.imageID) AS likes FROM (SELECT CONCAT(`images`.imageID,'.',`images`.extension) AS image, `images`.imageID, `images`.im_index, `images`.g_index, `workshops`.date_publish, `workshops`.date_end, `workshops`.heading_".$lang_acr." AS heading, `workshops`.workshopID FROM images LEFT OUTER JOIN workshops ON `workshops`.workshopID = `images`.workshopID AND `workshops`.active = 1 AND (`workshops`.`date_end` = '0000-00-00' OR (`workshops`.`date_end` != '0000-00-00' AND `workshops`.date_end >= CURDATE())) ) tbl LEFT OUTER JOIN likes ON `likes`.imageID = `tbl`.imageID GROUP BY `tbl`.imageID ORDER BY `tbl`.workshopID IS NULL DESC, `tbl`.date_publish DESC, `tbl`.g_index ASC ";
			$sql_gl = mysqli_query($CONNECTION, $string);
			$sql = mysqli_query($CONNECTION, $string. "LIMIT ".$DATA->items_per_page." OFFSET ".$offset);
			while($t = mysqli_fetch_object_wrapper($sql)) 
			{
				if ($t->workshopID)
				{
					$m[$i++] = (object) array("hasWS" => true, "imageID" => $t->imageID, "heading" => $t->heading, "image" => make_image_content($t->image, $FILE), "index" => $offset+($y++), "workshopID" => $t->workshopID, "likes" => intval($t->likes), "link" => $domain."workshop/".$t->workshopID);
				} else
				{
					$m[$i++] = (object) array("hasWS" => false, "imageID" => $t->imageID, "image" => make_image_content($t->image, $FILE, "", "gallery"), "index" => $offset+($y++));
				}
			};
		} else
		{
			$string = "SELECT `tbl2`.*, COUNT(`likes`.imageID) AS likes FROM (SELECT `tbl`.*, COUNT(`likes`.imageID) AS liked FROM (SELECT CONCAT(`images`.imageID,'.',`images`.extension) AS image, `images`.imageID, `images`.im_index, `images`.g_index ,`workshops`.date_publish, `workshops`.date_end, `workshops`.heading_".$USER->lang." AS heading, `workshops`.workshopID FROM images LEFT OUTER JOIN workshops ON `workshops`.workshopID = `images`.workshopID AND `workshops`.active = 1 AND (`workshops`.`date_end` = '0000-00-00' OR (`workshops`.`date_end` != '0000-00-00' AND `workshops`.date_end >= CURDATE())) ) tbl LEFT OUTER JOIN likes ON `tbl`.imageID = `likes`.imageID AND `likes`.username = '".$USER->username."' GROUP BY `tbl`.imageID) tbl2 LEFT OUTER JOIN likes ON `tbl2`.imageID = `likes`.imageID GROUP BY `tbl2`.imageID ORDER BY `tbl2`.workshopID IS NULL DESC, `tbl2`.date_publish DESC, `tbl2`.g_index ASC ";
			$sql_gl = mysqli_query($CONNECTION, $string);
			$sql = mysqli_query($CONNECTION, $string."LIMIT ".$DATA->items_per_page." OFFSET ".$offset);
			while($t = mysqli_fetch_object_wrapper($sql)) { 
				if ($t->workshopID)
				{
					$m[$i++] = (object) array("hasWS" => true, "imageID" => $t->imageID, "heading" => $t->heading, "liked" => (bool) $t->liked, "image" => make_image_content($t->image, $FILE), "index" => $offset+($y++), "workshopID" => $t->workshopID, "likes" => intval($t->likes), "link" => $domain."workshop/".$t->workshopID);
				} else
				{
					$m[$i++] = (object) array("hasWS" => false, "imageID" => $t->imageID, "image" => make_image_content($t->image, $FILE, "", "gallery"), "index" => $offset+($y++));
				}	
			};
		};

		$arr_info["response"]    = true;
		$arr_data["fetch"]       = $m;
		$arr_data["user_active"] = (bool) $userActive;
		$arr_data["words"]       = (object) array("onslide" => $lang->onSlide, "like" => $lang->likeItImage, "dislike" => $lang->dislikeItImage);
		$arr_data["has_more"]    = (mysqli_num_rows_wrapper($sql_gl) - ($offset + mysqli_num_rows_wrapper($sql))) > 0;
		$arr_data["images"]      = mysqli_num_rows_wrapper($sql_gl);
		$arr_data["pages"]       = ceil(mysqli_num_rows_wrapper($sql_gl) / intval($DATA->items_per_page));
		$arr_data["rows"]        = mysqli_num_rows_wrapper($sql);

		break;

	case base64_encode("GET_Gallery_image_info"):
		if (!$userActive)
		{
			$string = "SELECT `tbl`.*, COUNT(`likes`.imageID) AS likes FROM (SELECT CONCAT(`images`.imageID,'.',`images`.extension) AS image, `images`.imageID, `images`.im_index, `workshops`.heading_".$lang_acr." AS heading, `workshops`.workshopID FROM images LEFT OUTER JOIN `workshops` ON `workshops`.workshopID = `images`.workshopID WHERE `images`.imageID = '".$DATA->imageID."' ) tbl LEFT OUTER JOIN likes ON `likes`.imageID = `tbl`.imageID GROUP BY `tbl`.imageID";
			$sql = mysqli_query($CONNECTION, $string);
			while($t = mysqli_fetch_object_wrapper($sql))
			{
				if ($t->workshopID)
				{
					$m = (object) array("hasWS" => true, "imageID" => $t->imageID, "heading" => $t->heading, "image" => make_image_content($t->image, $FILE), "workshopID" => $t->workshopID, "likes" => intval($t->likes), "link" => $domain."workshop/".$t->workshopID);
				} else
				{
					$m = (object) array("hasWS" => false, "imageID" => $t->imageID, "heading" => $t->heading, "image" => make_image_content($t->image, $FILE, "", "gallery"), "workshopID" => $t->workshopID);
				};
			}
		} else
		{
			$string = "SELECT `tbl2`.*, COUNT(`likes`.imageID) AS likes FROM (SELECT `tbl`.*, COUNT(`likes`.imageID) AS liked FROM (SELECT CONCAT(`images`.imageID,'.',`images`.extension) AS image, `images`.imageID, `images`.im_index, `workshops`.heading_".$USER->lang." AS heading, `workshops`.workshopID FROM images LEFT OUTER JOIN workshops ON `workshops`.workshopID = `images`.workshopID WHERE `images`.imageID = '".$DATA->imageID."' ) tbl LEFT OUTER JOIN likes ON `tbl`.imageID = `likes`.imageID AND `likes`.username = '".$USER->username."' GROUP BY `tbl`.imageID) tbl2 LEFT OUTER JOIN likes ON `likes`.imageID = `tbl2`.imageID GROUP BY `tbl2`.imageID";
			$sql = mysqli_query($CONNECTION, $string);
			while($t = mysqli_fetch_object_wrapper($sql))
			{
				if ($t->workshopID)
				{
					$m = (object) array("hasWS" => true, "imageID" => $t->imageID, "heading" => $t->heading, "liked" => (bool) $t->liked, "image" => make_image_content($t->image, $FILE), "workshopID" => $t->workshopID, "likes" => intval($t->likes), "link" => $domain."workshop/".$t->workshopID);
				} else
				{
					$m = (object) array("hasWS" => false, "imageID" => $t->imageID, "heading" => $t->heading, "image" => make_image_content($t->image, $FILE, "", "gallery"), "workshopID" => $t->workshopID);
				};
			}
		};

		if ($m->hasWS)
		{
			$arr_data["heading"]     = $m->heading;
			$arr_data["workshopID"]  = $m->workshopID;
			$arr_data["likes"]       = $m->likes;
			$arr_data["liked"]       = $userActive ? (bool) $m->liked : -1;
			$arr_data["link"]        = $m->link;
		}
		$arr_data["hasWS"]       = $m->hasWS;
		$arr_info["response"]    = true;
		$arr_data["imageID"]     = $m->imageID;				
		$arr_data["user_active"] = (bool) $userActive;
		$arr_data["words"]       = (object) array("onslide" => $lang->onSlide, "like" => $lang->likeItImage, "dislike" => $lang->dislikeItImage);
		break;

	case base64_encode("getCalendarEvents"):
		$today = (object) array("month" => intval(date("m")), "day" => intval(date("d")), "year" => intval(date("Y")));
		if ($DATA->month === -1) 
		{
			// Query all events
			$query = "SELECT eventID, heading_".$lang_acr." AS heading, date_start, date_end, text_".$lang_acr." AS text, location,  MONTH(date_start) AS startMonth, MONTH(date_end) AS endMonth, DAY(date_start) AS startDay, DAY(date_end) AS endDay, YEAR(date_start) AS year FROM events WHERE active = 1 ORDER BY date_start ASC";			
		} else
		{			
			$query = "SELECT eventID, heading_".$lang_acr." AS heading, date_start, date_end, text_".$lang_acr." AS text, location, MONTH(date_start) AS startMonth, MONTH(date_end) AS endMonth, DAY(date_start) AS startDay, DAY(date_end) AS endDay, YEAR(date_start) AS year FROM events WHERE MONTH(date_start) = ".$DATA->month." AND YEAR(date_start) = ".$DATA->year." AND active = 1 ORDER BY date_start ASC";
		};
		$sql = mysqli_query($CONNECTION, $query);
		if ($sql)
		{
			$arr_info["response"] = true;
			if ($num = mysqli_num_rows_wrapper($sql))
			{
				$m = ""; $i = 0; $g = 0; $arr = array();
				while($t = mysqli_fetch_object_wrapper($sql)) 
				{
					$arr[$i] = array("date_start" => $t->date_start,"date_end" => $t->date_end, "eventID" => $t->eventID, "inMonth" => intval(part_date('m', $t->date_start)) === $today->month, "has_text" => (bool) $t->text,
					    "dates" => array("month" => array("start" => $t->startMonth, "end" => $t->endMonth), 
					                     "day"   => array("start" => $t->startDay, "end" => $t->endDay),
					                     "year"  => $t->year
					                     )
					 );
					if ($DATA->month === -1 && $arr[$i]["inMonth"] || $DATA->month>-1 || (isset($DATA->eset) && $DATA->eset)) {
					$m .= '<div class="event">';
						if ($t->text)
						{
					$m .= '<div class="pull-right">
								<div class="btn-green background-theme btn-ord btn-open-event small" data-event-id="'.$t->eventID.'" data-set-listeners="'.($t->text ? "true" : "false").'"><i class="fa fa-search"></i> '.$lang->details.'</div>
							</div>';
						};
					$m .= '<a class="link-ord" href="javascript:void(0)">
							<h3 class="btn-open-event margin-b-none" data-event-id="'.$t->eventID.'" data-set-listeners="'.($t->text ? "true" : "false").'">'.$t->heading.'</h3>
							</a>
							<div class="padding-t-5">								
								<div class="smaller">'.$lang->location.': <span class="strong">'.$t->location.'</span></div>';
								if ($t->date_start !== $t->date_end)
								{
									$m .= '<div class="smaller padding-t-5"><i class="fa fa-calendar"></i> '.make_date(-1,$t->date_start).' - '.make_date(-1,$t->date_end).'</div>
								';
								} else {
								$m .= '<div class="smaller padding-t-5"><i class="fa fa-calendar"></i> '.make_date(-1,$t->date_start).'</div>
								';
								};
								if ($i<$num-1-$g) {
								$m .= '
								<div class="margin-t-20">
									<div class="divider width-100"></div>							
								</div>
								';
							};
							$m .= '</div>
						</div>				
					';
					$i++;
					} else { $g++; };
				};
				$arr_data["arr"]    = $arr;
				$arr_data["text"]   = $m;
				$arr_data["events"] = $num-$g;
				if (isset($DATA->eset))
				{
					$arr_data["events"]   = $num;					
				} else
				{
					if ($num-$g === 0)  $arr_data["text"] = '<div class="margin-t-20"><div class="strong small"><i class="fa fa-ban"></i> '.$lang->no_events.'</div></div>'; 
				}
				
			} else
			{
				$arr_data["arr"]          = null;
				$arr_data["events"]       = $num;
				$arr_data["text"]         = '<div class="margin-t-20"><div class="strong small"><i class="fa fa-ban"></i> '.$lang->no_events.'</div></div>';
				$arr_info["responseCode"] = 404;
			}
		} else
		{
			$arr_info["response"] = false; $arr_data = NULL;
		}
		break;

	case base64_encode("getCalendarEventData"):
		$sql = mysqli_query($CONNECTION, "SELECT heading_".$lang_acr." AS heading, date_start, date_end, location, text_".$lang_acr." AS text FROM events WHERE BINARY eventID = '".$DATA->eventID."'");
		if ($sql)
		{
			$arr_info["response"] = true;
			if (mysqli_num_rows_wrapper($sql))
			{
				while($t = mysqli_fetch_object_wrapper($sql)) $el = $t;
				$arr_data["heading"]  = $el->heading;
				$arr_data["date"]     = make_date(-1, $el->date);
				$arr_data["location"] = $el->location;
				$arr_data["text"]     = $el->text;
			} else
			{
				$arr_info["errorCode"] = 404; $arr_data = NULL;
			}
			
		} else
		{
			$arr_info["response"] = false; $arr_info["errorCode"] = 403;
		}
		break;
	};

	echo json_encode($RESPONSE);

?>