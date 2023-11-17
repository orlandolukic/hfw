$(document).ready(function(e){

	$(".select-btn").on("click", function() {
		$(".month-show").addClass("dn");
		$(".month-show[data-month-flag='" +$(this).attr('data-month-flag')+ "']").removeClass("dn");	
		$(".month-hide").addClass("dn");
	});

	$("ul.options li").on("click", function() {
		if($(this).hasClass("disabled")) {
			return false;
		} 
		else {
		$(this).addClass("strong");
		$(this).siblings().removeClass("strong");	
		var months = $(this).attr("data-value");
		var data_URL = $(this).parents(".month-show").attr("data-base-url");
		var link = $(this).parents(".month-show").find(".subs");
		link.attr("href", data_URL + "month=" + months);
		$(this).parents(".month-show").find(".change-month").html($(this).attr("data-html-month"));
	}
	});

});