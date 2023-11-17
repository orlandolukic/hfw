

$(document).ready(function(e) {
	$("#copy").bind("click", function() {
		$("#passwordVal").select();
		var obj = $(this);
		try
		{
			if (!document.execCommand("copy")) throw "Copy execCommand is not supported.";
			document.execCommand("copy");
			$.notify({
				message: obj.attr("data-success-mssg"),
				icon: "fa fa-clone"
			},{
				timer: 2000,
				delay: 6000,
				type: "success",
				offset: {x: 20, y: 40},
				placement: {from: "bottom", align: "left"},
				z_index: 9999
			});

		} catch (err)
		{
			console.error(err);
		};
	});

	$("#question").on("keyup",function() {
		if(this.value.trim().length > 9) {	$("#sendQuestion").removeClass("disabled"); } else { $("#sendQuestion").addClass("disabled"); }
	});

	$("#sendQuestion").on("click",function() {
		var object = this;
		// _UMNG_DB("control", { HttpRequest: JSON.stringify({type: btoa("sendQuestionFromVideo")}), data: JSON.stringify({ workshopID : $(this).attr("data-workshop-id"),
		// 	questionHeading : $("#question_title").val(), questionMessage : $("#question").val() }) }, function(e) {
		// 		e = JSON.parse(e);
		// 		if (e.info.response)
		// 		{
		// 			$.notify({
		// 				message: $(object).attr("data-success-sent"),
		// 				icon: "fa fa-envelope"
		// 			},{
		// 				timer: 2000,
		// 				delay: 6000,
		// 				type: "success",
		// 				offset: {x: 20, y: 40},
		// 				placement: {from: "bottom", align: "left"},
		// 				z_index: 9999
		// 			});
		// 			$("#question, #question_title").val("");
		// 			$(object).addClass("disabled");
		// 		} else window.location.reload();
		// });
		setTimeout(() => {
			$.notify({
				message: $(object).attr("data-success-sent"),
				icon: "fa fa-envelope"
			},{
				timer: 2000,
				delay: 6000,
				type: "success",
				offset: {x: 20, y: 40},
				placement: {from: "bottom", align: "left"},
				z_index: 9999
			});
			$("#question, #question_title").val("");
			$(object).addClass("disabled");
		}, 2500);
	})

	$("#likeVideo").bind("click", function() {
		_UMNG_DB("shopping", {type: btoa("likeVideo"), data: JSON.stringify({wid: $("#iframe").attr("data-wid")})}, function (p) {
			p = JSON.parse(p);
			if (p.info.response)
			{
				$("#likePreview").html(p.data.message)
				if (p.data.liked)
				{
					$("#likeVideo").removeClass("link-not-selected");
				} else
				{
					$("#likeVideo").addClass("link-not-selected");
				}
			};
		});
	});

	$(".gallery").each(function(e,v) {
		$(this).magnificPopup({delegate: 'a', type: 'image', 
		     gallery: {
				enabled: true
			 },
		     mainClass: 'mfp-with-zoom',
		     zoom: {
		       enabled: true,

		       duration: 300,
		       easing: 'ease-in-out',
		       opener: function(openerElement) {			     
		         return openerElement.is('img') ? openerElement : openerElement.find('img');
		       }
		     }
		});
	});
});