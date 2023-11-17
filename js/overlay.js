$(window).on("load", function() {
	$(".decline-white-overlay").on("click", function() {
		$(this).parents(".white-overlay").hide("medium");
	});
});