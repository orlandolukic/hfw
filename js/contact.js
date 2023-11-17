
timeout_mssg_send = window.setTimeout(function() {}, 0);

$(window).on("load",function(e) {
	$("#sendEmail").on("click", function(e) {
		var mssg     = validEmail($("#email").val()),
	        name     = $("#name").val(),
		    mssgText = $("#message").val(),
		    subject  = $("#subject").val(),
		    email    = $("#email").val();

		if(!mssg) $("#errors .ordered-list-errors li:eq(0)").show(); else $("#errors .ordered-list-errors li:eq(0)").hide();
		if (name.trim().length < 3) $("#errors .ordered-list-errors li:eq(1)").show(); else $("#errors .ordered-list-errors li:eq(1)").hide();
		if (mssgText.trim().length < 10) $("#errors .ordered-list-errors li:eq(2)").show(); else $("#errors .ordered-list-errors li:eq(2)").hide();
		// Condition FALSE
		if (!mssg || name.trim().length < 3 || mssgText.trim().length < 10) 
		{
			$("#errors").show();
			window.clearTimeout(timeout_mssg_send);
			timeout_mssg_send = window.setTimeout(function() {
				$("#errors").hide();
			}, 7000);
		} else
		{
			$("#errors").hide();
			var p = $(this).attr("data-success-send");
			// _SD_DB("email", {type: btoa("sendLetter"), data: JSON.stringify({subject: subject, name: name, text: mssgText, email: email})}, function(r) {
			// 	var obj = JSON.parse(r);
			// 	if (obj.info.response)
			// 	{
			// 		$("#name, #email, #subject, #message").val('');
			// 		$.notify({
			// 			message: p,
			// 			icon: "fa fa-check"
			// 		},{
			// 			timer: 2000,
			// 			delay: 6000,
			// 			type: "success",
			// 			offset: {x: 60, y: 80},
			// 			placement: {from: "top", align: "right"}
			// 		});
			// 	}
			// });
			setTimeout(() => {
				$("#name, #email, #subject, #message").val('');
				$.notify({
					message: p,
					icon: "fa fa-check"
				},{
					timer: 2000,
					delay: 6000,
					type: "success",
					offset: {x: 60, y: 80},
					placement: {from: "top", align: "right"}
				});
			}, 2500);
		}
	});
});