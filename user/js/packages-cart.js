
$(document).ready(function() {
	script = $(document).find("script[data-script='packages']");
	eval("days = ["+script.attr("data-month-days")+"]");	
	date_format = parseInt(script.attr("data-month-format"));
});

$(window).on("load", function() {
	$(".month-selection .options li").bind("click", function() {
		if ($(this).hasClass("disabled")) return false;
		var parent = $(this).parents(".month-selection"), MAIN = $(this).parents(".tr-cart-item");	
		$(this).addClass("strong").siblings().removeClass("strong");	
		MAIN.find(".theme-spinner").removeClass("dn").show();
		parent.find(".default-option").html($(this).html());
		_UMNG_DB("control", { HttpRequest: JSON.stringify({ type: btoa("changePackageStartMonth") }), 
		         data: JSON.stringify({ month: $(this).attr("data-value"), itemID: btoa(MAIN.attr("data-cart-id")) }) }, function(r) {
			r = JSON.parse(r);
			if (r.info.response)
			{
				MAIN.find(".start-date").html(r.data.start_date);
				MAIN.find(".end-date").html(r.data.end_date);
				MAIN.find(".theme-spinner").hide();
			} else console.error(r.info.errorCode);
		})
	});
})