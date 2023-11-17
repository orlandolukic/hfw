$(document).ready(function() {
	$(".btn-deactivate-subscription").bind("click", function(e) {
		var main = $(this).parent().parent().parent().parent(),
		    attr = main.attr("sid"),
		    fade = main.find(".deactivate-subscription");
		_UMNG_DB("control", {HttpRequest: JSON.stringify({type: btoa("deactivateSubscription")}), data: JSON.stringify({
			sid: attr
		})}, function(f) {
			f = JSON.parse(f);
			if (f.info.response)
			{
				fade.fadeOut("medium");

				switch(main.find(".sub-status").attr("data-status"))
				{
				case "active":
					main.find(".sub-status").removeClass("text-success")
										    .removeClass("text-error")
								            .addClass("text-warning")

								            .find("i").removeClass("fa-circle").css({fontSize: "100%", top: "0px"})
								            .addClass(main.find(".sub-status").attr("data-icon-nact"))

								            .parent()
								            .find("span").html(main.find(".sub-status").attr("data-exp-value"))
								            .parent().parent().parent()
								            .find("div:first")
								            .addClass("subscription-disabled");
					main.find(".deactivate-subscription-init").parent().hide();
					main.find(".subscription-receipt").removeClass("margin-t-10");
					main.find(".renewSub").parent().removeClass("dn").show();
					break;
				default: window.location.reload();
				};
			}
		})
	});

	$(".renewSub").bind("click", function(ex) {
		var lnk  = $(this);
		var main = lnk.parents(".subscription");
		_UMNG_DB("control", { HttpRequest: JSON.stringify({type: btoa("renewSubscription")}), data: JSON.stringify({
			sid: main.attr("sid")
		}) }, function(e) {
			e = JSON.parse(e);
			if (e.info.response)
			{
				lnk.parent().hide();
				main.find(".sub-status").removeClass("text-warning")
									    .removeClass("text-error")
							            .addClass("text-success")

							            .find("i").addClass("fa-circle").css({fontSize: "60%", top: "-3px"})
							            .addClass(main.find(".sub-status").attr("data-icon-act"))

							            .parent()
							            .find("span").html(main.find(".sub-status").attr("data-activated"))
							            .parent().parent().parent()
							            .find("div:first")
							            .removeClass("subscription-disabled");
				main.find(".deactivate-subscription-init").parent().removeClass("dn").fadeIn();
				main.find(".subscription-receipt").addClass("margin-t-10");
			};
		});
	});

	$(".deactivate-subscription-init").bind("click", function(e) {
		$(this).parents(".subscription").find(".deactivate-subscription").fadeIn("medium");
	});

	document.getElementsByClassName("btn_deactivate_subscription_decl")[0].addEventListener("click", function() {
		$(this).parents(".deactivate-subscription").fadeOut("medium");
	});
});