$(function() {
	$(".delete-wishitem").on("click", function() {
		var obj = $(this);
		_UMNG_DB("control", {HttpRequest: JSON.stringify({type: btoa("deleteWishItem")}), data: JSON.stringify({
			itemID: $(this).parents(".wishitem").attr("data-wish-item")
		})}, function(e) {
			e = JSON.parse(e);
			if (e.info.response)
			{
				obj.parents(".wishitem").fadeOut("medium", function() {
					$(this).remove();
					if (e.data.wishlist_items_count === 0) $(".empty-wishlist").show();
				});
			};
		});
	});

	$(".subscription-options li").on("click", function() {
		$(this).parents(".sort-options").find(".default-option").html($(this).html().trim()).attr("data-selected-id", $(this).attr("data-value"));
		$(this).parents(".wishitem").find(".subscription-button")
				.attr("href", $(this).parents(".wishitem").find(".subscription-button").attr("data-base-uri")+$(this).attr("data-value"));
	});
});