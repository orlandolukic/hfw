$(document).ready(function(e) {
	animationHide = mssgDisp = false;
	slide         = 0;

	$(".gallery").each(function(e,v) {
		$(this).magnificPopup({delegate: 'a', type: 'image', 
		     gallery: {
				enabled: true
			 },
		     mainClass: 'mfp-with-zoom',
		     zoom: {
		       enabled: true,

		       duration: 300,
		       easing: 'ease-in-out',
		       opener: function(openerElement) {			     
		         return openerElement.is('img') ? openerElement : openerElement.find('img');
		       }
		     }
		});
	});

	$(".data-image").each(function(i,v) {
		$(v).on("click", function() {			
			if (animationHide || slide===i) return false;			
			animationHide = true;
			$("#workshopGalleryImage").addClass("toggleImage");
			var p = $(this);
			window.setTimeout(function() {				
				// Image link reset
				var newImage = p.attr("data-source");
				$("a.gallery-images[data-image-name='"+newImage+"']").attr("href", $("#workshopGallery").attr("href"))
																	 .attr("data-image-name", $("#workshopGallery").attr("data-image-name"));
				$("#workshopGallery").attr("href", $("#workshopGallery").attr("data-base")+newImage)
									 .attr("data-image-name", newImage);
				$("#workshopGalleryImage").removeClass("toggleImage")
									      .addClass("showImage")
									      .attr("src", $("#workshopGallery").attr("data-base")+newImage);
					
				window.setTimeout(function() {
					$("#workshopGalleryImage").removeClass("showImage");
					slide = i;
					animationHide = false;
				}, 500);							
			}, 500);
		});
	});

	$("#reviewsPagination li:not(.previous,.next)").on("click", function() {
		var f;
		if ( !(f = parseInt($(this).parent().attr("data-current-page"))) || f===$(this).index() ) return false;
		$(this).addClass("active").siblings().removeClass("active").parent().attr("data-current-page", $(this).index());
		$("#allReviews").html('');
		_SD_DB("pull", {HttpRequest: JSON.stringify({type: btoa("fetchReviews")}), 
		       			data: JSON.stringify({page:          $(this).attr("data-number"), 
		       			                      SQL_statement: btoa($("#reviews").attr("data-sql-statement")),
		       			                      workshopID:    $("#leaveReview").attr("data-wid")  
		       			                  }
		 	  )}, function(j) {
		      var obj = JSON.parse(j);
		      if (obj.info.response)
		      {
		      	 $("#allReviews").html('').append(obj.data);
		      	 actTooltip();
		      	 actComments();
		      }
		});
	});

	$("#reviewsPagination li.next").on("click", function() {
		if ( !(f = parseInt($(this).parent().attr("data-current-page"))) || f===$(this).index()-1 ) return false;
		$(this).parent().find("li:eq("+(parseInt($(this).parent().attr("data-current-page"))+1)+")").click();
	});

	$("#reviewsPagination li.previous").on("click", function() {
		if ( !(f = parseInt($(this).parent().attr("data-current-page"))) || f===1 ) return false;
		$(this).parent().find("li:eq("+(parseInt($(this).parent().attr("data-current-page"))-1)+")").click();
	});

	$("#recentReviews").on("click", function() {
		var f;
		if ( typeof $(this).attr("data-selected") === typeof undefined || typeof eval($(this).attr("data-selected")) !== typeof true) window.location.reload();
		if ( eval($(this).attr("data-selected")) === true ) return false;

		$(this).attr("data-selected", "true");
		$(this).attr("class", "strong").parent().parent().find("li:eq(1) a").attr("data-selected", false).removeClass("strong").addClass("underline");
		$("#reviewsPagination li:eq(1)").addClass("active").siblings().removeClass("active");
		$("#reviews").attr("data-sql-statement", "recent");
		_SD_DB("pull", {HttpRequest: JSON.stringify({type: btoa("fetchReviews")}), 
		       			data: JSON.stringify({page:          1, 
		       			                      SQL_statement: btoa("recent"),
		       			                      workshopID:    $("#leaveReview").attr("data-wid")  
		       			                  }
		 	  )}, function(j) {
		      var obj = JSON.parse(j);
		      if (obj.info.response)
		      {
		      	 $("#allReviews").html('').append(obj.data);
		      	 actTooltip();
		      	 actComments();
		      }
		});
	});

	$("#topReviews").on("click", function() {
		var f;
		if ( typeof $(this).attr("data-selected") === typeof undefined || typeof eval($(this).attr("data-selected")) !== typeof true) window.location.reload();
		if ( eval($(this).attr("data-selected")) === true ) return false;

		$(this).attr("data-selected", "true");
		$(this).attr("class", "strong").parent().parent().find("li:eq(0) a").attr("data-selected", false).removeClass("strong").addClass("underline");
		$("#reviewsPagination li:eq(1)").addClass("active").siblings().removeClass("active");
		$("#reviews").attr("data-sql-statement", "top");
		_SD_DB("pull", {HttpRequest: JSON.stringify({type: btoa("fetchReviews")}), 
		       			data: JSON.stringify({page:          1, 
		       			                      SQL_statement: btoa("top"),
		       			                      workshopID:    $("#leaveReview").attr("data-wid")  
		       			                  }
		 	  )}, function(j) {
		      var obj = JSON.parse(j);
		      if (obj.info.response)
		      {
		      	 $("#allReviews").html('').append(obj.data);
		      	 actTooltip();
		      	 actComments();
		      }
		});
	});

	$("#st-rating span i").each(function(i,v) {		
		var parent = $("#st-rating");
		$(v).on("mouseover", function() {			
			if (parent.hasClass("selected"))
			{		
				if (i+1<parseInt($("#rRating").val())) return true;	
				for (var p=4; p>i; p--) parent.find("span i:eq("+p+")").removeClass("fa-star").addClass("fa-star-o");		
				for (var p=i; p>parseInt($("#rRating").val())-1; p--) parent.find("span i:eq("+p+")").addClass("fa-star").removeClass("fa-star-o");
				$(this).addClass("fa-star").removeClass("fa-star-o");
				return true;
			}
			for (var p=i+1; p<5; p++) $("#st-rating span i:eq("+p+")").removeClass("fa-star").addClass("fa-star-o");						
			for (var p=i-1; p>=0; p--) $("#st-rating span i:eq("+p+")").addClass("fa-star").removeClass("fa-star-o");
			$(this).addClass("fa-star").removeClass("fa-star-o");
		}).on("click", function() {
			for (var p=i; p>=0; p--) parent.find("span i:eq("+p+")").css({opacity: 1}).addClass("fa-star").removeClass("fa-star-o");
			for (var p=i+1; p<5; p++) parent.find("span i:eq("+p+")").css({opacity: 0.6}).removeClass("fa-star").addClass("fa-star-o");
			parent.addClass("selected");
			$("#rRating").val($(this).attr("data-id"));	
			$("#rRatingOut").css("opacity", 1).find("span").html($(this).attr("data-id"));
		});
	});

	$("#st-rating").on("mouseleave", function() {
		$(this).find("span i").removeClass("fa-star").addClass("fa-star-o");
		if ($(this).hasClass("selected"))
		{
			for (var p=0; p<parseInt($("#rRating").val()); p++) $(this).find("span i:eq("+p+")").addClass("fa-star").removeClass("fa-star-o");			
			for (var p=parseInt($("#rRating").val()); p<5; p++) $(this).find("span i:eq("+p+")").removeClass("fa-star").addClass("fa-star-o");				
		}		
	});

	$("#rTextarea").on("keyup", function() {
		$("#rMaxChars span").html(1600-$(this).val().length);
		if (1600-$(this).val().length < 0) 
		{
			if ( !$("#rMaxChars").hasClass("text-error") ) $("#rMaxChars").addClass("text-error"); 
		} else
		{
			if ( $("#rMaxChars").hasClass("text-error") ) $("#rMaxChars").removeClass("text-error"); 
		}
	});

	$("#rSubmit").on("click", function() {
		if (mssgDisp) return false;
		if ($.trim($("#rTextarea").val()).length === 0 || parseInt($("#rRating").val()) === 0 || 1600-$("#rTextarea").val().length<0) return actErr();
		$(this).addClass("disabled");
		$("#rDecline").addClass("disabled");
		var p = $(this).attr("data-message");
		$("#leaveReview").fadeOut();
		$("#reviewsTab").fadeIn();
		var act = $("#leaveReview").attr("data-action");		
		_SD_DB("control",{
			HttpRequest: JSON.stringify({
			   type:   btoa("leaveReview"),
			   action: btoa(act)
			}), 
	        data: JSON.stringify({
	       		title:      ($.trim($("#rTitle").val()).length===0 ? null : $("#rTitle").val()), 
	       		rating:     $("#rRating").val(), 
	       		review:     $("#rTextarea").val(),
	       		workshopID: $("#leaveReview").attr("data-wid"),
	       		reviewID:   (act==="change" ? $("#leaveReview").attr("data-review-id") : -1)
	       	})}, function(d) {
			var obj = JSON.parse(d);
			if (obj.info.response)
			{
				window.setTimeout(function() {
					$.notify({
						message: p,
						icon: "fa fa-check"
					},{
						timer: 2000,
						delay: 6000,
						type: "success",
						offset: {x: 20, y: 100},
						placement: {from: "top", align: "right"}
					});
				}, 500);
				var m = $(".user-comment[data-comment-id='"+$("#leaveReview").attr("data-review-id")+"']");
				for (var l=0; l<5; l++) m.find(".stars span:eq("+l+")").removeClass("fa-star").removeClass("fa-star-o").addClass(obj.data.stars.each[l]);
				m.find(".stars-num").html(obj.data.stars.num);
				m.find(".review-text").text($("#rTextarea").val());
				m.find(".review-date").html(obj.data.date);
				m.find(".review-time").html(obj.data.time);
				m.find(".user-comment-notactive").show();
			}			
		});
	});

	$("#rDecline").on("click", function() {
		$("#leaveReview").fadeOut();
		$("#reviewsTab").fadeIn();
		$("#rRating").val(0);
		$("#rRatingOut").css("opacity", 0);
		$("#st-rating span i").removeClass("fa-star").addClass("fa-star-o").css({opacity: 0.6});
		$("#st-rating").removeClass("selected");
		$("#leaveReview").attr("data-review-id", "");
	});

	$("#leaveReviewBtn").on("click", function() {
		$("#reviewsTab").fadeOut();
		$("#leaveReview").fadeIn(function() {
			$("#rTitle").focus();
		}).attr("data-action", "new");		
		$("#rSubmit, #rDecline").removeClass("disabled");
		$("#rTextarea, #rTitle").val('');
		$("#rRating").val(0);
		$("#rRatingOut").css("opacity", 0);
		$("#st-rating span i").removeClass("fa-star").addClass("fa-star-o").css({opacity: 0.6});
		$("#st-rating").removeClass("selected");
		$("#leaveReview").attr("data-review-id", "");
		$("#rMaxChars span").html(1600);
	});

	actComments();

	$("#addToWishList").on("click", function() {
		if ($(this).attr("data-set")==="false")
		{	
			var t = $(this);
			_SD_DB("control", {HttpRequest: JSON.stringify({type: btoa("addWishList")}), 
			       data: JSON.stringify({workshopID: $(this).attr("data-workshop-id")})}, function(b) {
			       var obj = JSON.parse(b);
			       if (obj.info.response)
			       {
			       	   $.notify({
					   	 message: t.attr("data-success-mssg"),
					   	 icon: "fa fa-heart"
					   },{
					   	 timer: 2000,
					   	 delay: 6000,
					   	 type: "success",
					   	 offset: {x: 30, y: 100},
					   	 placement: {from: "top", align: "right"}
					   });
					   t.attr("data-set","true");
					   t.find("i").addClass("icon-heart-selected")
					   			  .tooltip("hide")
					   			  .attr("data-original-title", t.attr("data-after-mssg"))
					   			  .tooltip("fixTitle");					   			  
			       } else
			       {
			       	  if (obj.info.reload) window.location.reload();
			       };
			});
		} else if ($(this).attr("data-set")==="true")
		{
			return false;
		} else window.location.reload();
	});

	$("#buyBtn").on("click", function() {
		if ($(this).attr("data-set")!=="0" && $(this).attr("data-set")!=="1") window.location.reload();
		if ($(this).attr("data-set") === "1") return false;
		var obj = $(this);		
		obj.attr("data-set", 1);
		_SD_DB("shopping", {type: btoa("addToShoppingCart"), data: JSON.stringify({workshopID: $(this).attr("data-workshop-id")})}, function(d) {
			d = JSON.parse(d);
			if (d.info.response)
			{
				obj.addClass("disabled").attr("data-placement", "bottom").attr("data-toggle", "tooltip").attr("title", obj.attr("data-added")).tooltip("show");
				 $.notify({
				   	 message: obj.attr("data-success-mssg"),
				   	 icon: "fa fa-shopping-cart"
				 },{
				   	 timer: 2000,
				   	 delay: 6000,
				   	 type: "success",
				   	 offset: {x: 30, y: 40},
				   	 placement: {from: "bottom", align: "left"}
				 });					  
			}
		});
	});

});

function actErr()
{
	mssgDisp = true;
	$("#rMistake div").fadeIn();
	return window.setTimeout(function() {
		$("#rMistake div").fadeOut();
		mssgDisp = false;
		return false;
	}, 4000);
};

function actComments()
{
	$(".rUINI_delConf").on("click", function() {	
		var p = $(this).attr("data-id");	
		_SD_DB("control", {
			HttpRequest: JSON.stringify({
				type: btoa("deleteUserReview")
			}), 
		    data: JSON.stringify({
		    	reviewID: $(this).attr("data-id")
		    })}, function(o) {
			var obj = JSON.parse(o);
			if (obj.info.response)
			{
				$("#totalWorkshopComments").html(obj.data.num_comments);
				$("#workshopRating").html(obj.data.rating);	
				determine_rating_stars(obj.data.stars, "#workshopRatingStars");
				$(".user-comment[data-comment-id='"+p+"']").remove();
				$.notify({
					message: $("#allReviews").attr("data-delete-mssg"),
					icon: "fa fa-check"
				},{
					timer: 2000,
					delay: 6000,
					type: "info",
					offset: {x: 20, y: 100},
					placement: {from: "top", align: "right"}
				});
			}
		});
	});

	$(".rUINI_del").on("click", function() {
		$(".user-comment[data-comment-id='"+$(this).attr("data-id")+"'] .user-comment-delete").show();
	});

	$(".rUINI_decl").on("click", function() {
		$(".user-comment[data-comment-id='"+$(this).attr("data-id")+"'] .user-comment-delete").hide();
	});

	$(".rAINI_allow").on("click", function() {
		var p = $(this);
		_SD_DB("control", {HttpRequest: JSON.stringify({type: btoa("allowReview")}), data: JSON.stringify({reviewID: $(this).attr("data-id")})}, function(d) {
			var obj = JSON.parse(d);
			if (obj.info.response)
			{		
				$("#totalWorkshopComments").html(obj.data.num_comments);
				$("#workshopRating").html(obj.data.rating);	
				determine_rating_stars(obj.data.stars, "#workshopRatingStars");
				$(".user-comment[data-comment-id='"+p.attr("data-id")+"']").find(".admin-property.check-circle").show(function() {
					p.addClass("disabled");
					var t = $(this);
					$(this).addClass("expand-icon");
					window.setTimeout(function() {
						t.hide().removeClass("expand-icon");	
						$(".user-comment[data-comment-id='"+p.attr("data-id")+"']").find(".user-comment-notactive").css({transform: "translateY(70px)"});
						p.css({transform: "translateY(-70px)"});
						window.setTimeout(function() {
							p.hide();
						}, 2000);
					}, 1600);
				});
			};
		});		
	});

	$(".rUINI_ed").on("click", function() {
		$("#reviewsTab").fadeOut();
		$("#leaveReview").fadeIn(function() {
			$("#rTitle").focus();
		}).attr("data-action", "change");		
		$("#rSubmit, #rDecline").removeClass("disabled");
		$("#rTextarea").val($(".user-comment[data-comment-id='"+$(this).attr("data-id")+"'] .review-text").html());
		$("#rTitle").val($(".user-comment[data-comment-id='"+$(this).attr("data-id")+"'] .review-title").html());
		$("#st-rating i:eq("+(parseInt($(".user-comment[data-comment-id='"+$(this).attr("data-id")+"']").attr("data-rating"))-1)+")").click();
		$("#leaveReview").attr("data-review-id", $(this).attr("data-id"));
		$("#rMaxChars span").html(1600 - $("#rTextarea").val().length);
	});

	$(".comment_answer").on("click", function() {
		$(".comments-placeholder[data-review-id='"+$(this).attr("data-review-id")+"']").show();
		focusContEdit($(".comments-placeholder[data-review-id='"+$(this).attr("data-review-id")+"']").find(".initiator-comment"));
	});

	$(".comment_showComments").on("click", function() {
		if ($(".comments-placeholder[data-review-id='"+$(this).attr("data-review-id")+"']").css("display") !== "block")
		$(".comments-placeholder[data-review-id='"+$(this).attr("data-review-id")+"']").show(); else
		$(".comments-placeholder[data-review-id='"+$(this).attr("data-review-id")+"']").hide();
	});

	$(".btn-initiator-send").on("click", function() {
		if ( !(currcomm=parseInt($(".comments-placeholder[data-review-id='"+$(this).attr("data-review-id")+"'] .allComments").attr("data-comments-num"))) && currcomm!==0 ) window.location.reload();
		var t = $(this).attr("data-review-id");
		if ($.trim($(".user-comment[data-comment-id='"+t+"']").find(".initiator-comment").text()).length ===0 )
		{
			$(".comments-placeholder[data-review-id='"+$(this).attr("data-review-id")+"']").find(".initiator-comment").text('');
			focusContEdit($(".comments-placeholder[data-review-id='"+$(this).attr("data-review-id")+"']").find(".initiator-comment"));
			return false;
		}
		_SD_DB("control", {HttpRequest: JSON.stringify({type: btoa("submitComment")}), data: JSON.stringify({
			reviewID: t,
			text:     $(".user-comment[data-comment-id='"+t+"']").find(".initiator-comment").html(),
			displayedComments: $(".comments-placeholder[data-review-id='"+$(this).attr("data-review-id")+"'] .allComments").attr("data-comments-num")
		})}, function(get) {
			var obj = JSON.parse(get);
			if (obj.info.response)
			{
				var commBef = parseInt($(".comments-placeholder[data-review-id='"+t+"'] .allComments").attr("data-comments-num"));				
				if (commBef % 5 === 0 && commBef !== 0)
				{
					$(".comments-placeholder[data-review-id='"+t+"'] .allComments .comment-user:eq("+(commBef-1)+")").remove();
					$(".comments-placeholder[data-review-id='"+t+"'] .fetch-more-comments").show();
				} else
				{
					$(".comments-placeholder[data-review-id='"+t+"'] .allComments").attr("data-comments-num", commBef+1);
				}
				$(".user-comment[data-comment-id='"+t+"']").find(".allComments").prepend(obj.data.text);
				$(".user-comment[data-comment-id='"+t+"']").find(".initiator-comment").html('');
				$(".comment-user[data-comment-id='"+obj.data.comment_id+"']").delay(200).animate({opacity: 1}, 800);
				$(".user-comment[data-comment-id='"+t+"']").find(".comment_showComments").show();
				$(".comments-placeholder[data-review-id='"+t+"'] .no-more-comments").hide();
				actTooltip_ident($(".comments-placeholder .comment-user[data-comment-id='"+obj.data.comment_id+"']"));
				actComments_ident($(".comments-placeholder .comment-user[data-comment-id='"+obj.data.comment_id+"']"));
			}			
		});
	});

	$(".fetch-more-comments").on("click", function() {
		var id      = $(this).attr("data-review-id"),
			mainObj = $(".comments-placeholder[data-review-id='"+id+"']");
		_SD_DB("pull", {HttpRequest: JSON.stringify({type: btoa("fetchMoreComments")}), data: JSON.stringify({
			reviewID: id,
			displayedComments: mainObj.find(".allComments").attr("data-comments-num")
		})}, function(json) {
			var obj = JSON.parse(json);
			if (obj.info.response)
			{
				if (!obj.data.more_comments) mainObj.find(".fetch-more-comments").hide();
				mainObj.find(".allComments").attr("data-comments-num", obj.data.data_comments);
				mainObj.find(".allComments").append(obj.data.htmlProperty);
				if (obj.data.hasOwnProperty("commentID") && obj.data.commentID.constructor === Array)
				{
					actTooltip_ident(mainObj.find(".allComments"), 1, obj.data.commentID);
					actComments_ident(mainObj.find(".allComments"), 1, obj.data.commentID);
				} else if (obj.data.hasOwnProperty("commentID") && obj.data.commentID.constructor === String)
				{
					actTooltip_ident(mainObj.find(".allComments .comment-user[data-comment-id='"+obj.data.commentID+"']"));
					actComments_ident(mainObj.find(".allComments .comment-user[data-comment-id='"+obj.data.commentID+"']"));
				};				
			}
		});
	});

	$(".rUINI_delThisComment").on("click", function() {
		$(this).hide();
		$(this).parent().find(".message-bef-del").show();
	});

	$(".rUINI_delThisCommentDecline").on("click", function() {
		$(this).parent().parent().parent().hide();
		$(this).parent().parent().parent().parent().find(".rUINI_delThisComment").show();
	});

	$(".rUINI_delThisCommentProceed").on("click", function() {
		var id        = $(this).attr("data-review-id"),
			mainObj   = $(".comments-placeholder[data-review-id='"+id+"']"),
			commentID = $(this).attr("data-comment-id");
		if (!(currcomm = parseInt(mainObj.find(".allComments").attr("data-comments-num"))) && currcomm!==0) window.location.reload();
		_SD_DB("control", {HttpRequest: JSON.stringify({type: btoa("deleteComment")}), data: JSON.stringify({
			reviewID: id,
			commentID: commentID,
			displayedComments: currcomm-1
		})}, function(v) {
			var obj = JSON.parse(v);
			if (obj.info.response)
			{
				mainObj.find(".comment-user[data-comment-id='"+commentID+"']").fadeOut(400,function() {
					$(this).remove();
				});
				if (obj.data.hasData)
				{
					mainObj.find(".allComments").append(obj.data.htmlProperty);
					actTooltip_ident(mainObj.find(".allComments .comment-user[data-comment-id='"+obj.data.commentID+"']"));
					actComments_ident(mainObj.find(".allComments .comment-user[data-comment-id='"+obj.data.commentID+"']"));
				};
				if (!obj.data.more_comments)
				{
					if (obj.data.data_comments===0) { 
						mainObj.find(".no-more-comments").show(); 
						mainObj.find(".comments-placeholder[data-review-id='"+id+"'] .comment_showComments").hide();
					}
					mainObj.find(".fetch-more-comments").hide();
				};

				if (!obj.data.more_comments && !obj.data.hasData)
				{
					mainObj.find(".allComments").attr("data-comments-num", obj.data.data_comments);
				}
			}
		});
	});
}

function actComments_ident(a, c=0, b = [])
{
	var th = a;
	for (var i=0; (c===0 ? i<=0 : i<b.length); i++) {
		if (c===1) { a = th.find(".comment-user[data-comment-id='"+b[i]+"']"); };


		a.find(".rUINI_delThisComment").on("click", function() {
			$(this).hide();
			$(this).parent().find(".message-bef-del").show();
		});

		a.find(".rUINI_delThisCommentDecline").on("click", function() {
			$(this).parent().parent().parent().hide();
			$(this).parent().parent().parent().parent().find(".rUINI_delThisComment").show();
		});

		a.find(".rUINI_delThisCommentProceed").on("click", function() {
			var id        = $(this).attr("data-review-id"),
				mainObj   = $(".comments-placeholder[data-review-id='"+id+"']"),
				commentID = $(this).attr("data-comment-id");
			if (!(currcomm = parseInt(mainObj.find(".allComments").attr("data-comments-num"))) && currcomm!==0) window.location.reload();
			_SD_DB("control", {HttpRequest: JSON.stringify({type: btoa("deleteComment")}), data: JSON.stringify({
				reviewID: id,
				commentID: commentID,
				displayedComments: currcomm-1
			})}, function(v) {
				var obj = JSON.parse(v);
				if (obj.info.response)
				{
					mainObj.find(".comment-user[data-comment-id='"+commentID+"']").fadeOut(400,function() {
					$(this).remove();
					});
					if (obj.data.hasData)
					{
						mainObj.find(".allComments").append(obj.data.htmlProperty);
						actTooltip_ident(mainObj.find(".allComments .comment-user[data-comment-id='"+obj.data.commentID+"']"));
						actComments_ident(mainObj.find(".allComments .comment-user[data-comment-id='"+obj.data.commentID+"']"));
					};
					if (!obj.data.more_comments)
					{
						if (obj.data.data_comments===0) { 
							mainObj.find(".no-more-comments").show(); 
							mainObj.find(".comments-placeholder[data-review-id='"+id+"'] .comment_showComments").hide();
						}
						mainObj.find(".fetch-more-comments").hide();
					};

					if (!obj.data.more_comments && !obj.data.hasData)
					{
						mainObj.find(".allComments").attr("data-comments-num", obj.data.data_comments);
					}
				}
			});
		});
	}; // for
}