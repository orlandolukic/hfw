
$(document).ready(function() {
	$(".welcome-note").css({height: ($(".welcome-note").width() / 1.6) + "px"})
});

$(window).on("load", function() {
	/*
	$("#backgroundImage").removeClass("op-0").addClass("welcome-zoom-note");
	$(".compass-placeholder").removeClass("op-0").addClass("load-compass");
	$(".main-page-image").removeClass("op-0").addClass("load-main-page-image");

	var el = $("#elements");
	el.find("#headingRight").removeClass("op-0").addClass("load-heading-t1");
	el.find(".subheading").removeClass("op-0").addClass("load-subheading-t1");
	el.find(".marked-text").removeClass("op-0").addClass("load-marked-text");
	el.find("#subscribeBtn").removeClass("op-0").addClass("load-subheading-t2");
	$(".welcome-note .banners-right-placeholder").addClass("load-banners-right");
	*/
	$(window).resize(function() {
		$(".welcome-note").css({height: ($(".welcome-note").width() / 1.6) + "px"});
		//$(".welcome-note .content").css({height: ($(".welcome-note").width() / 1.6) + "px"});
		//$(".heading .text").css({fontSize: ($(".heading").width() * 0.8 * 40) + "px" })
	});
});