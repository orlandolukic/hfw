$(document).ready(function(e) {
	valid_email_tm = window.setTimeout(function() {},0);
	if ($("script[has-data='true']").length)
	{
		eval($("script[has-data='true']").attr("data-set"));
	};
//  Proceed to check on login result
	if (login === 1)
	{
		if (found === 0)
		{
			$("#loginError").show();
			window.setTimeout(function() {
				$("#loginError").fadeOut();
			}, timeout);
		}
	}

	$("#username").on("keydown", function(e) {
		if (e.keyCode === 13)
		{
			e.preventDefault();
			$("#password").focus();
		}
	});
	
	$("#resetEmailLink").on("click", function() {
		$("#login").removeClass("showDivRight").addClass("hideDivLeft");
		$("#email").val('');
		$("#emailError").hide();
		window.setTimeout(function() {
			$("#login").hide().removeClass("hideDivLeft");
			$("#emailReset").show(function() {
				$("#email").focus();
			}).addClass("showDivRight");			
		}, 200);
	});

	$("#btnBack").on("click", function(e) {
		$("#emailReset").removeClass("showDivRight").addClass("hideDivLeft");
		$("#username, #password").val('');
		$("#loginError").hide();
		window.setTimeout(function() {
			$("#emailReset").hide();
			$("#login").show(function() {
				$("#username").focus();				
			}).addClass("showDivRight");			
		}, 200);
	});

	$("#submitEmailRequest").on("click", function() {
		var em = $("#email").val();
		if (!validEmail(em))
		{
			$("#email").val('').focus();
			$("#emailError").show().find("span span:eq(0)").show().parent().find("span:eq(1)").hide();
			window.clearTimeout(valid_email_tm);
			valid_email_tm = window.setTimeout(function() {
				$("#emailError").fadeOut(function() {});
			},6000);
		} else
		{
			$("#emailError").hide().find("span span:eq(0)").hide();
			var p  = $(this).attr("data-success-mssg")+" "+em+".",
			    er = $(this).attr("data-error-mssg");
			_SD_DB("email", {type: btoa("sendResetPasswordEmail"), data: JSON.stringify({email: em})}, function(v) {
				v = JSON.parse(v);
				if (v.info.response)
				{
					$("#btnBack").click();
					$.notify({
						message: p,
						icon: "fa fa-envelope"
					},{
						timer: 2000,
						delay: 6000,
						type: "success",
						offset: {x: 20, y: 40},
						placement: {from: "bottom", align: "left"}
					});
				} else
				{
					if (v.info.errorCode === 404)
					{
						$("#emailMistake").html(em);
						$("#emailError").show().find("span span:eq(1)").show();
					} else if (v.info.errorCode === 409)
					{
						$.notify({
							message: er,
							icon: "fa fa-times"
						},{
							timer: 2000,
							delay: 6000,
							type: "danger",
							offset: {x: 20, y: 40},
							placement: {from: "bottom", align: "left"}
						});
					};
				}
			});
		};
	});
});