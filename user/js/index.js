
$(document).ready(function() {
	$("#emailSend").on("click", function(e) {
		var p = $(this).attr("data-success-mssg");
		var obj = $(this);
		_UMNG_DB("email", {type: btoa("sendConfirmationMail"), data: JSON.stringify({})}, function(a) {
			a = JSON.parse(a);
			if (a.info.response)
			{
				// Porukica
				$.notify({
					message: p,
					icon: "fa fa-envelope"
				},{
					timer: 2000,
					delay: 6000,
					type: "success",
					offset: {x: 20, y: 40},
					placement: {from: "bottom", align: "left"},
					z_index: 9999
				});
				obj.addClass("disabled");
			};
		});
	});

	$(".subscribe-btn").on("click", function() {
		if ($(this).hasClass("disabled")) return false;
		 var obj = $(this);
		_UMNG_DB("control", {HttpRequest: JSON.stringify({type: btoa("addToCart")}), data: JSON.stringify({ start_month: $(this).attr("data-start-month") })}, 
				function(r) {
			r = JSON.parse(r);
			if (r.info.response)
			{
				if (r.info.found) 
				{
					$.notify({
					message: obj.attr("data-fail-message") ,
					icon: "fa fa-times"
					},{
						timer: 2000,
						delay: 6000,
						type: "danger",
						offset: {x: 20, y: 40},
						placement: {from: "bottom", align: "left"},
						z_index: 9999
					});
				} else
				{

					$.notify({
						message: obj.attr("data-success-message") ,
						icon: "fa fa-envelope"
					},{
						timer: 2000,
						delay: 6000,
						type: "success",
						offset: {x: 20, y: 40},
						placement: {from: "bottom", align: "left"},
						z_index: 9999
					});
					obj.find(".btn-find").addClass("disabled");
					obj.addClass("disabled");
					obj.find(".btn-find span").html(obj.attr("data-alreadyCart"));
					$("#cartItems").html(r.data.rows);
					obj.attr('title', obj.attr("data-alreadyCart"))
					   .tooltip('fixTitle')
					   .tooltip('show');
				};
			};
		});
	});

	$(".times").on("click", function() {
		$(this).parent().fadeOut(function() {});
	});

	$(".addWishList").on("click", function() {
		if ($(this).attr("data-set") !== "0" && $(this).attr("data-set") !== "1") { window.location.reload(); return false; };
		if ($(this).attr("data-set") === "1") return false;
		var p = $(this);
		_UMNG_DB("control", {HttpRequest: JSON.stringify({type: btoa("addWishList")}), data: JSON.stringify({workshopID: p.attr("data-workshop-id")})}, function(r) {
			r = JSON.parse(r);
			if (r.info.response)
			{
				p.attr("data-set", 1).addClass("addedWishList")
									 .addClass("strong")
						             .find("span")
						             .html(p.attr("data-successfully-added"));
			};
		});
	});

});

if (document.getElementById("actSub")) {
	document.getElementById("actSub").addEventListener("click", function() {
		_UMNG_DB("control", {HttpRequest: JSON.stringify({type: btoa("activateSubscription")}), data: JSON.stringify({
			sid: document.getElementById("actSub").getAttribute("data-subscr-id")
		})}, function(d) {
			d = JSON.parse(d);
			if (d.info.response)
			{
				window.location.reload();
			}
		});
	});
};