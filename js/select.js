$(document).ready(function(e) {
	clicked = false;
	$(".sort-options").on("click", function() {
		if ($(this).hasClass("fold")) { $(this).find("ul").show(); $(this).removeClass("fold").addClass("unfolded"); } else
		{
			$(this).find("ul").hide(); $(this).addClass("fold").removeClass("unfolded");
		}
		clicked = true;		
	});
	$(document).on("click", function(e) {
		if (!clicked && $(".sort-options").hasClass("unfolded")) { 
			$(".sort-options ul").hide(); 
			$(".sort-options").addClass("fold").removeClass("unfolded"); 
		};
		clicked = false;
	});
})