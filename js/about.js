
$(document).ready(function() {
	$("#select_div").css({ height: ($(window).width() / 2.71)+"px" });
})

$(window).resize(function() {
	var elem = $(".welcome-note");
	if (($("#select_div").width() / 2.71) < 350) $("#select_div").css({ height: ($(window).width() / 2.71)+"px" });
})