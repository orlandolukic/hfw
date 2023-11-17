
$(document).ready(function(e) {
	toggled  = animation = toggledSubmenu = false;

	$(".mobile-menu").on("click", function(e) {
		if (!animation)
		{
			if (!toggled) _msubmenu(1, this); else _msubmenu(0, this);
		}		
	});

	$("[data-toggle='tooltip']").tooltip();
	
	$(".mobile-sub-submenu").on("click", function(e) {
		if (!toggledSubmenu) 
		{
			toggledSubmenu = true;
			$(this).find("i").removeClass("fa-plus").addClass("fa-minus"); 
			$(this).parent().find(".submenu").show();
		} else
		{
			toggledSubmenu = !true;
			$(this).find("i").addClass("fa-plus").removeClass("fa-minus");
		}
	});

	$(".up-btn").on("click", function() {
		$("body, html").animate({scrollTop: 0}, 1300);
		$(this).tooltip("hide");
	});
});

$(window).on("load", function() {
	$("[data-toggle='progress-bar']").each(function(i,v) {
		var subobj = $(v).find(".bar");
		subobj.css({opacity: 0}).css({width: subobj.attr("data-percentage")+"%", opacity: 1});		
	});
	/*
	$(".welcome-note .content .heading").addClass("load-heading").addClass("md-load-heading");
	*/
});

$(window).on("scroll", function(e) {
	if ($(this).scrollTop() > 650)
	{
		$(".menu-fixed").removeClass("hide-fixed-menu").addClass("load-fixed-menu");
		$(".up-btn").addClass("show").removeClass("hide");
		if ($(".social-fixed").length > 0) 
		{
			if (!$(".social-fixed").hasClass("hide-social-fixed"))
			{
				$(".social-fixed").addClass("load-social-fixed");
			} else
			{
				$(".social-fixed").removeClass("hide-social-fixed").addClass("load-social-fixed");
			}
		};
	} else
	{
		$(".up-btn").addClass("hide");
		if ($(".social-fixed").length > 0) if ($(".social-fixed").hasClass("load-social-fixed")) $(".social-fixed").addClass("hide-social-fixed");
		if ($(".menu-fixed").hasClass("load-fixed-menu"))
		{
			$(".menu-fixed").addClass("hide-fixed-menu").removeClass("load-fixed-menu");
		}
	}
});

function _SD_DB(pg, obj, povrSucc = function(a) {}, povrErr = function(a) {}, async = false)
{
	$.ajax({
		url: window.location.origin + "/ajax/"+pg,
		method: "POST",
		crossDomain: false,
		data: obj,
		cache: true,
		async: async,
		success: function(d) { povrSucc(d); },
		error: function(exept) { povrErr(exept); }
	});
}

function _UMNG_DB(pg, obj, povrSucc = function(a) {}, povrErr = function(a) {}, async = false)
{
	var x;
	$.ajax({
		url: window.location.origin + "/ajax/"+pg,
		method: "POST",
		crossDomain: false,
		data: obj,
		cache: false,
		async: async,
		success: function(d) { x = povrSucc(d); },
		error: function(exept) { povrErr(exept); }
	});
	return x;
};

function _UUPL_DB(obj, povrSucc = function(a) {}, povrErr = function(a) {}, async = false)
{
	var x;
	$.ajax({
		url: window.location.origin + "/ajax/upload",
		type: "POST",             
		data: obj,
		contentType: false,     
		cache: false,            
		processData: false,
		async: async,
		success: function(d) { x = povrSucc(d); },
		error: function(exept) { povrErr(exept); }
	});
	return x;
};

function _msubmenu(a, elem)
{
	animation = true;
	switch(a)
	{
	case 0:
		$(".mobile-main-menu").show();
		$(".mobile-submenu").animate({height: 0}, function(e) {
			toggled = false;
			animation = false;
			$(".mobile-main-menu").hide();
		});
		break;
	case 1:
		$(".mobile-main-menu").hide();
		$(".mobile-submenu").animate({height: "350px"}, function(e) {
			toggled = true;
			animation = false;			
		});
		break;
	};
}

function checkScroll()
{
	if ($(this).scrollTop() > 450)
	{
		$(".menu-fixed").removeClass("hide-fixed-menu").addClass("load-fixed-menu");
	} else
	{
		$(".menu-fixed").addClass("hide-fixed-menu").removeClass("load-fixed-menu");
	}
}

function actTooltip()
{
	$("[data-toggle='tooltip']").tooltip();
}

function actTooltip_ident(a, c=0, b=[])
{
	var th = a;
	for (var i=0; (c===0 ? i<=0 : i<b.length); i++) {
		if (c === 1) { a = th.find(".allComments .comment-user[data-comment-id='"+b[i]+"']"); }
		a.find("[data-toggle='tooltip']").tooltip();
	};
}

function focusContEdit(elem)
{
	var p = elem[0],
		s = window.getSelection(),
		r = document.createRange();
		p.innerHTML = '';
		r.setStart(p, 0);
		r.setEnd(p, 0);
		s.removeAllRanges();
		s.addRange(r);
}

function determine_rating_stars(a,i)
{
	$(i).find("span").each(function(i,o) {
		$(o).attr("class", "fa "+a[i]+" fa-2x");
	});
}

function validEmail(a) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(a)
}