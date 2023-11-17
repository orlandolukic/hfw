
// Init Gallery and other elements
$(function() {
	var i=0;
	$(".gallery-placeholder").each(function(e,v) {
		$(this).magnificPopup({delegate: 'a.image-gallery', type: 'image', 
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

	$(".like-image").on("click", function() {
		if (user_active) likeImage();
	});
});

	const script         = $(document).find("script[data-catch='gallery']");
	const elemsPerPage   = Math.floor(($(window).width()-80) / 180);
	const pictureTimeout = 6000;
	imageTimeout         = window.setTimeout(function() {},0);
	counter              = window.setInterval(function() {}, 1000);
	loaded               = false;
	imagesOnPage         = 10;
	pages                = 1;
	slide                = 0;
	page                 = 0;
	timeout              = pictureTimeout;
	const user_active    = eval(script.attr("data-user-active"));
	_hasMore             = false;
	timeout              = pictureTimeout;
	fetchData            = true;
	show_description     = true;

$(window).on("load", function() {

	window.dispatchEvent(new Event('resize'));

	loaded = true; 
	_SD_DB("pull", { HttpRequest: JSON.stringify({ type: btoa("GET_Gallery_images") }), 
	       data: JSON.stringify({ page: 0, items_per_page: elemsPerPage }) }, function(f) {
	       f = JSON.parse(f);
	       if (f.info.response)
	       {	       	   
	       	   if (f.data.rows > 0) 
	       	   {
	       	   	   imagesOnPage = f.data.rows;
		       	   _hasMore     = f.data.has_more;
		       	   pages        = f.data.pages;
	       	   	   appendData(f.data.fetch, f.data.words);	       	   
		       	   $(".list-of-images").attr("data-pages", f.data.pages);
		       	   if (f.data.has_more) $(".list-of-images .right-navigation").show(); else $(".list-of-images .right-navigation").hide();	
		       	   $(".gallery-wrapper .right-navigation").show();
		       	   if (f.data.fetch[0].hasWS)
		       	   {
		       	   	  $("#workshopName").attr("href", f.data.fetch[0].link);
		       	   	  if (f.data.fetch[0].likes > 0) 
		       	      { 
		       	    	  $(".gallery-wrapper .image-likes").show();
		       	    	  $(".gallery-wrapper .image-likes span").html(f.data.fetch[0].likes); 
		       	      } else $(".gallery-wrapper .image-likes").hide();
		       	      $(".like-image").attr("data-image-id", f.data.fetch[0].imageID);
		       	      if (f.data.user_active) 
			       	  { 
			       	     $("#gallery-wrapper .like-image").attr("data-image-id", f.data.fetch[0].imageID);
			       	   	 if (f.data.fetch[0].liked) $(".gallery-wrapper .like-image").html("<i class=\"fa fa-heart\"></i> <span>"+f.data.words.dislike+"</span>"); else
			       	   	 $(".gallery-wrapper .like-image").html("<i class=\"fa fa-heart-o\"></i> <span>"+f.data.words.like+"</span>").attr("data-workshop-id", f.data.fetch[0].workshopID);
			       	   };	
		       	   } else
		       	   {
		       	   	  show_description = false;
		       	   	  $(".gallery-wrapper .description-placeholder").removeClass("show-description");
		       	   }		       	   
		       	   
		       	   window.setTimeout(function() {startSlideshow(0, f.data.fetch[0].heading, f.data.fetch[0].likes);}, 400);   
		       	   $(".list-of-images").attr("data-images", f.data.images).attr("has-more", f.data.has_more);		       	   	       	   

		       	   if (f.data.rows > 1)
		       	   {
			       	   $(document).on("keydown", function(e) {
							if (e.keyCode === 39) navigation(1);
							if (e.keyCode === 37) navigation(0);
							if (e.keyCode === 76) likeImage();
						});

			       	   $(".gallery-wrapper").on("mouseenter", function() {
							if (typeof counter !== typeof undefined) { window.clearInterval(counter); window.clearInterval(counter); }
						});

						$(".gallery-wrapper").on("mouseleave", function() {
							if (typeof counter !== typeof undefined) 
							{
								window.clearInterval(counter); window.clearInterval(counter);
							    counter = window.setInterval(function() { timeout -= 1000; if (timeout === 0) { window.clearInterval(counter); nextSlide(); } }, 1000);
							}
						});
					} else
					{				
						 fetchData = false;	
						 $(".gallery-wrapper .right-navigation").hide();	
						 window.clearInterval(counter);
					}
					$(".no-images-placeholder").remove();

				  setTimeout(() => {
					  window.dispatchEvent(new Event('resize'));
				  }, 1000);
	       	   } else
	       	   {
	       	   	  window.clearInterval(counter);
	       	   	  $(".gallery-wrapper .gallery-loader").fadeOut(300, function() {
	       	   	  	 $(".no-images-placeholder").fadeIn(400);
	       	   	  });
	       	   }	       	  
	       } else window.location.reload();
	});

	$(".gallery-wrapper .left-navigation").on("click", function() {
		navigation(0);
	});

	$(".gallery-wrapper .right-navigation").on("click", function() {
		navigation(1);
	});

	$(".list-of-images .right-navigation").on("click", function() {
		window.clearInterval(counter);
		fetchNewData(++page);
	});

});

function navigation(a)
{
	if (a===1) 	// Right Navigation - Gallery Wrapper
	{
		window.clearInterval(counter);
		++slide;
		if (slide===imagesOnPage) 
		{ 
			if (!_hasMore) 
			{ 				
				var obj = $(".list-of-images");
				var currPage = parseInt(obj.attr("data-page"));
				if (currPage === pages-1 && currPage > 0)
				{
					page = 0;		
					$(".list-of-images .left-navigation").hide();
					fetchNewData(page);
				} else
				{
					slide = 0; 
					$(".gallery-wrapper .left-navigation").hide();
					nextSlide(slide, false); 
				}
			} else
			{
				var elem = $(".list-of-images");
				if (parseInt(elem.attr("data-page")) === parseInt(elem.attr("data-pages"))-1) { page = 0; fetchNewData(page); return; }
				fetchNewData(++page);
			}
		} else
		{
			nextSlide(slide, false);
		};	
	} else if (a === 0)	// Left Navigation - Gallery Wrapper
	{
		window.clearInterval(counter); --slide;
		if (slide === -1 && page === 0) { slide = 0; return; }
		if (slide === -1 && pages === 1) { slide = 0; return; };
		if (slide === -1 && pages > 1)
		{				
			page--;
			fetchNewData(page, imagesOnPage-1);
			return true;			
		} else if (slide === 0)
		{
			if (page === 0) { $(".gallery-wrapper .left-navigation").hide(); }
		}
		nextSlide(slide, false);
	};
};

function do_job() {
	$(".list-of-images .list-item-placeholder:eq("+slide+") .on-slide").addClass("selected");
	$("#slideImage").attr("src", $(".list-of-images .list-item-placeholder:eq("+slide+")").attr("data-image") ).fadeIn(400);
	_SD_DB("pull", { HttpRequest: JSON.stringify({ type: btoa("GET_Gallery_image_info") }), 
       data: JSON.stringify({ imageID: $(".list-of-images .list-item-placeholder:eq("+slide+")").attr("data-image-id") }) }, function(e) {
		e = JSON.parse(e);
		if (e.info.response)
		{
       	    if (e.data.hasWS)
       	    {
       	    	$("#workshopName span").html(e.data.heading);
				if (e.data.user_active) 
	       	    {
	       	    	$("#gallery-wrapper .like-image").attr("data-image-id", e.data.imageID);
	       	   	     if (e.data.liked) $(".gallery-wrapper .like-image").html("<i class=\"fa fa-heart\"></i> <span>"+e.data.words.dislike+"</span>"); else
	       	   	  	 $(".gallery-wrapper .like-image").html("<i class=\"fa fa-heart-o\"></i> <span>"+e.data.words.like+"</span>");       	   	  	         	   	  	  
	       	    };
	       	    $("#workshopName").attr("href", e.data.link);
       	    	$(".like-image").attr("data-image-id", e.data.imageID);
       	    	if (e.data.likes > 0) 
	       	    { 
	       	    	$(".gallery-wrapper .image-likes").show();
	       	    	$(".gallery-wrapper .image-likes span").html(e.data.likes); 
	       		} else $(".gallery-wrapper .image-likes").hide();
	       		$(".gallery-wrapper .description-placeholder").addClass("show-description");
       	    } else
       	    {
       	    	$(".gallery-wrapper .description-placeholder").removeClass("show-description");
       	    }
			if (slide>0 || page > 0)
			{
				$(".gallery-wrapper .left-navigation").show();
			};
		}
	});	
};

function nextSlide(i=-1, setTimer=true)
{
	if (!fetchData) { window.clearInterval(counter); return; }
	window.clearInterval(counter);
	//if (slide > imageInRow && loaded) { alert(); window.location.reload(); }
	$("#slideImage").fadeOut(300, function() {
		$(".gallery-wrapper .description-placeholder").removeClass("show-description");
		$(".list-of-images .list-item-placeholder .on-slide").removeClass("selected");
		if (i===-1) slide++;
		if (slide === imagesOnPage) // Fetch new package of data or go to the start if !_hasMore
		{
			if (!_hasMore)
			{
				var obj = $(".list-of-images");
				var currPage = parseInt(obj.attr("data-page"));
				if (currPage === pages-1 && currPage > 0)
				{
					page = 0;
					$(".list-of-images .left-navigation").hide();
					fetchNewData(page);
				} else
				{
					slide = 0; do_job(); $(".gallery-wrapper .left-navigation").hide();
				};
			} else
			{
				fetchNewData(++page);
			}
		} else
		{
			do_job();		
		};

		timeout = pictureTimeout;
		if (setTimer)
		{
			window.clearInterval(counter);
			counter = window.setInterval(function() { timeout -= 1000; if (timeout === 0) { window.clearInterval(counter); nextSlide(); } }, 1000);	
		};
	});
}

function fetchNewData(p,s=0,start=true)
{
	if (!fetchData) return;
	_SD_DB("pull", { HttpRequest: JSON.stringify({ type: btoa("GET_Gallery_images") }), 
	       data: JSON.stringify({ page: p, items_per_page: elemsPerPage }) }, function(f) {
	       f = JSON.parse(f);
	       if (f.info.response)
	       {		
	       	   	slide = s;
	       	   	imagesOnPage = f.data.rows;
	       	    $(".list-of-images").attr("data-page", page);
	       	   _hasMore = f.data.has_more;
	       	   appendData(f.data.fetch, f.data.words);
	       	   if (f.data.has_more) $(".list-of-images .right-navigation").show(); else $(".list-of-images .right-navigation").hide();	
	       	   if (p === 0) $(".list-of-images .left-navigation, .gallery-wrapper .left-navigation").hide(); else $(".list-of-images .left-navigation").show();
	       	   if (!f.data.hasWS) $(".description-placeholder").removeClass("show-description"); else $(".description-placeholder").addClass("show-description");
	       	   if (start)
	       	   {
		       	   window.setTimeout(function() { startSlideshow(s, f.data.fetch[s].heading, f.data.fetch[s].likes)}, 400);  
		       	   $(".list-of-images").attr("data-images", f.data.images).attr("has-more", f.data.has_more);
		       	   if (f.data.user_active) 
		       	   { 
		       	   	  $("#gallery-wrapper .like-image").attr("data-image-id", f.data.fetch[s].imageID);
		       	   	  if (f.data.fetch[s].liked) $(".gallery-wrapper .like-image").html("<i class=\"fa fa-heart\"></i> <span>"+f.data.words.dislike+"</span>"); else
		       	   	  	$(".gallery-wrapper .like-image").html("<i class=\"fa fa-heart-o\"></i> <span>"+f.data.words.like+"</span>");
		       	   	  $(".image-likes span").html(f.data.fetch[s].likes);
		       	   };
		       };
	       } else window.location.reload();
	});
}

function startSlideshow(p,h,likes)
{
	var obj = $("#images .list-item-placeholder:eq("+p+")");
	obj.find(".on-slide").addClass("selected");
	$("#slideImage").hide().attr("src", obj.attr("data-image")).fadeIn(400);
	$("#workshopName span").html(h);
	if (show_description) $(".description-placeholder").addClass("show-description");
	$(".image-likes span").html(likes);

	window.clearInterval(counter);
	counter = window.setInterval(function() { timeout -= 1000; if (timeout === 0) {  window.clearInterval(counter); nextSlide(); } }, 1000);
}

function appendData(d,w)
{
	$("#images").fadeOut(300, function() {
		$(this).html('');
		for (var i=0; i<d.length; i++)
		{
			$(this).append('<div class="list-item-placeholder margin-md-l-none margin-l-20" data-image="'+d[i].image+'" data-ind="'+i+'" data-index="'+d[i].index+'" '+
			               ' data-image-id="'+d[i].imageID+'">'+
				'<div class="overlay"></div>'+
				'<div class="on-slide uppercase smaller">'+w.onslide+'</div>'+
				'<img src="'+d[i].image+'">'+
			'</div>');
		};
		$(this).fadeIn(900);
		$(".list-item-placeholder").each(function(i,v) {
			$(v).on("click", function() {					
				slide = parseInt($(this).attr("data-ind"));				
				if (typeof counter !== typeof undefined) window.clearInterval(counter);
				if (slide === 0 && page === 0) { $(".gallery-wrapper .left-navigation").hide(); }			
				nextSlide(0);
			});		
		});
	});
}

function likeImage()
{
	if (user_active)
	{
		window.clearInterval(counter);
		var self = document.getElementsByClassName("like-image")[0];
		_SD_DB("control", {HttpRequest: JSON.stringify({ type: btoa("likeImage") }), data: JSON.stringify({imageID: $(self).attr("data-image-id")})}, function(e) {
			e = JSON.parse(e);
			if (e.info.response)
			{
				if (e.data.action === "dislike") $(self).find("i").removeClass("fa-heart").addClass("fa-heart-o"); else 
				if (e.data.action === "like")    $(self).find("i").removeClass("fa-heart-o").addClass("fa-heart");
				if (e.data.num === 0) $(".gallery-wrapper .image-likes").hide(); else  $(".gallery-wrapper .image-likes").show();
				$(self).find("span").html(e.data.self);
				$(".gallery-wrapper .image-likes span").html(e.data.num);
			} else console.error(e);
		});
	}
}

$(window).resize(function() {
	$("#select_div").css({ height: ($("#select_div").width() / 1.875)+"px" });
})
