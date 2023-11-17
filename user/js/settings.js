$(window).on("load", function() {

	 err       = window.setTimeout(function() {},0);
	 uploadTim = window.setTimeout(function() {},0);
	 elem = $(".js-switch");
	 newsletter = new Switchery(elem[0], { disabled: true, size: "small" });

	 $("#initiate_deactivation").on("click", function() {
	 	$(".white-overlay[data-value='deactivation']").show("medium");
	 });

	 $("#deactivateAccount").on("click", function() {
	 	_UMNG_DB("control", {HttpRequest: JSON.stringify({type: btoa("deactivateAccount")}), data: JSON.stringify({})}, function(e) {
	 		e = JSON.parse(e);
	 		if (e.info.response)
	 		{
	 			$(".deactivation-buttons").hide(function() {
	 				$(".message-success-deactivation").fadeIn(function() {
	 					var x = 5;
 						timer = setInterval(function() {
 							$(".timer-seconds").html(--x);
 							if (x===0) { window.clearInterval(timer); window.location = window.location.origin+"/user/logout/"; }
 						}, 1000);	 					
	 				});
	 			})
	 		};
	 	})
	 });

	 $("#newPassword").on("keyup", function(e) {
	 	checkPass.call(this);
	 });

	 $("#newPasswordConfirm").on("keyup", function() {
	 	if ($("#newPassword").val().trim() !== "")
	 	{
	 		if ($("#newPasswordConfirm").val() === $("#newPassword").val()) $(".status-mssg").show("medium"); else $(".status-mssg").hide("medium");
	 	}
	 });

	 $("#changePassword").on("click", function() {
	 	var int = { old: false, new: false, newConf: false };
	 	if ( $("#newPassword").val().trim().length === 0 ) int.new = true;
	 	if ( $("#newPasswordConfirm").val().trim().length === 0 ) int.newConf = true;
	 	if (int.new || int.newConf)
	 	{
	 		clear(); if (int.newConf) $("#mistakes ol li:eq(1)").show(); else $("#mistakes ol li:eq(1)").hide(); 
	 		if (int.new) $("#mistakes ol li:eq(3)").show(); else $("#mistakes ol li:eq(3)").hide(); 
	 		window.clearTimeout(err);
	 		err = window.setTimeout(function() {
	 			$("#mistakes").hide("medium");
	 		}, 5000);
	 		$("#mistakes").show("medium");
	 		return false;
	 	};
	 	int = null;

	 	if ( $("#newPassword").val().trim() === $("#newPasswordConfirm").val().trim() && $("#newPassword").val().trim() === "" )
	 	{
	 		clear();
	 		$("#mistakes ol li:eq(1)").show();
	 		$("#mistakes").show("medium");
	 		window.clearTimeout(err);
	 		err = window.setTimeout(function() {
	 			$("#mistakes").hide("medium");
	 		}, 5000);
	 		return false;
	 	}

		_UMNG_DB("control", { HttpRequest: JSON.stringify({type: btoa("changePassword")}), data: JSON.stringify({
			value: $("#newPassword").val()
		})}, function(f) {
			f = JSON.parse(f);
			if (f.info.response) window.location = f.data.redirect; else window.location.reload();
		});
	 			
	 });

	 $("#removePicture").on("click", function() {
	 	_UMNG_DB("control", {HttpRequest: JSON.stringify({type: btoa("removeProfilePicture")}), data: JSON.stringify({})}, function(f) {
	 		f = JSON.parse(f);
	 		if (f.info.response)
	 		{
	 			$("#profilePictureMain, #profilePictureImage").attr("src", f.data.image);
	 			$("#removePictureParent").hide();
	 		}
	 	});
	 });

	 $("#emailResetBtn").on("click", function() {
	 	if (validEmail($("#emailReset").val())) 
	 	{
	 		_UMNG_DB("control", {HttpRequest: JSON.stringify({type: btoa("resetEmailAddress")}), 
	 		         data: JSON.stringify({sendValue: $("#emailReset").val()})}, function(f) {
	 			f = JSON.parse(f);
	 			if (f.info.response)
	 			{
	 				window.location = f.data.redirect;
	 			} else window.location.reload();
	 		});
	 	} else
	 	{
	 		$("#emailReset").val('').focus();
	 		$(".mistake").show("medium");
	 		err = window.setTimeout(function() {
	 			window.clearTimeout(err);
	 			$(".mistake").hide("medium");
	 		}, 5000);
	 	}
	 });

	 $("#usernameResetBtn").on("click", function() {
	 	if ($("#usernameReset").val().trim().length > 0)
	 	{
			_UMNG_DB("control", {HttpRequest: JSON.stringify({type: btoa("resetUsername")}), 
			         data: JSON.stringify({sendValue: $("#usernameReset").val()})}, function(j) {
	 		   j = JSON.parse(j);
	 		   if (j.info.response)
	 		   {
	 		   	   if (j.data.equal_param && !j.data.other_params)
	 		   	   {
		 		   	   	var parent = $(".mistake ol");
							for (var i=0; i<parent.find("li").length; i++) parent.find("li:eq("+i+")").hide();
					 		parent.find("li:eq(2)").removeClass("dn").show();
					 		$(".mistake").removeClass("dn");
					 		err = window.setTimeout(function() {
					 			for (var i=0; i<parent.find("li").length; i++) parent.find("li:eq("+i+")").hide();
					 			$(".mistake").hide("medium");
					 		}, 6000);
	 		   	   } else if (j.data.free)
	 		   	   {
   					  $("#usernameResetBtn").addClass("disabled");
   					  $("#usernameResetBtn i").removeClass("fa-check").addClass("fa-spinner").addClass("fa-spin");
   					  $("#usernameResetBtn span").html(j.data.loading);
	 		   	   	  window.location = j.data.redirect; 
	 		   	   } else
	 		   	   {
	 		   	   		var parent = $(".mistake ol");
							for (var i=0; i<parent.find("li").length; i++) parent.find("li:eq("+i+")").hide();
				 		parent.find("li:eq(0)").removeClass("dn").show();
				 		$(".mistake").removeClass("dn");
				 		err = window.setTimeout(function() {
				 			for (var i=0; i<parent.find("li").length; i++) parent.find("li:eq("+i+")").hide();
				 			$(".mistake").hide("medium");
				 		}, 6000);
	 		   	   };
	 		   };
	 		});
	 				
	 	} else
	 	{
	 		var parent = $(".mistake ol");	 		
	 		for (var i=0; i<parent.find("li").length; i++) parent.find("li:eq("+i+")").hide();
	 		parent.find("li:eq(1)").removeClass("dn").show();
	 		$(".mistake").removeClass("dn").show();
	 		err = window.setTimeout(function() {
	 			for (var i=0; i<parent.find("li").length; i++) parent.find("li:eq("+i+")").hide();
	 			$(".mistake").hide("medium");
	 		}, 6000);
	 		
	 	}
	 });

	 $("#inputPicture").on("change", function() {
	 	if (this.value !== "")
	 	{
	 		$(".profile-picture-loading").show();
	 		$("#profilePictureFORM").submit();	 		
	 	}
	 });

	 $("#languageSelect li").click(function() {
	 	_UMNG_DB("control", {HttpRequest: JSON.stringify({type: btoa("changeLanguage")}), data: JSON.stringify({
	 		language: $(this).attr("data-value")
	 	})}, function(e) {
	 		e = JSON.parse(e);
	 		if (e.info.redirect) window.location.reload();
	 	})
	 })

	 $("#currencySelect li").click(function() {
	 	_UMNG_DB("control", {HttpRequest: JSON.stringify({type: btoa("changeCurrency")}), data: JSON.stringify({
	 		currency: $(this).attr("data-value")
	 	})}, function(e) {
	 		e = JSON.parse(e);
	 		if (e.info.redirect) window.location.reload();
	 	});
	 });

	 $("#profilePictureFORM").on("submit", function(e) {
	 	e.preventDefault();
	 	console.log(this);

	 	uploadTim = window.setTimeout(function() {
 			window.location.reload();
 		}, 15000);	

 		_UUPL_DB(new FormData(this), function(r) {
 			r = JSON.parse(r);	
 			window.clearTimeout(uploadTim); 			
 			if (r.info.response)
 			{
 				$(".profile-picture-loading").hide();
 				if (r.info.uploaded) 
 				{ 					
 					$("#profilePictureMain, #profilePictureImage").attr("src", r.data.image);
 					$("#removePictureParent").show();
 					$("#inputPicture").val('');
 					if (r.info.hasMessage)
 					{
 						if (typeof tim1 !== typeof undefined) window.clearTimeout(tim1);
 						$(".message-placeholder").append(r.data.message);
 						$("#panelMessage").show("medium");
 						tim1 = window.setTimeout(function() {
							$("#panelMessage").hide("medium", function(e) {
								$(this).parent().parent().remove();
							});
						}, (r.data.timeout > -1 ? r.data.timeout : 5000));
 					};
 				} else
 				{
 					if (r.info.hasMessage)
 					{
 						if (typeof tim1 !== typeof undefined) window.clearTimeout(tim1);
 						$(".message-placeholder").append(r.data.message);
 						$("#panelMessage").show("medium");
 						tim1 = window.setTimeout(function() {
							$("#panelMessage").hide("medium", function(e) {
								$(this).parent().parent().remove();
							});
						}, (r.data.timeout > -1 ? r.data.timeout : 5000));
 					}
 				}
 			} else window.location.reload();
 		}, function(f) { console.log(f); }); 	 	
	 });

	 if (document.getElementById("newsletterCheck")) {
		 document.getElementById("newsletterCheck").addEventListener("click", function(e) {
		 	_UMNG_DB("control", {HttpRequest: JSON.stringify({type: btoa("updateNewsletterSubscription")}), 
		 		data: JSON.stringify({value: document.getElementById("newsletterCheck").checked})}, function(f) {
		 			f = JSON.parse(f);
		 			if (!f.info.response) window.location.reload();
		 		});
		 });
	};
});

function clear()
{
	for (var i=0; i<2; i++) $("#mistakes ol li:eq("+i+")").hide();
}

function checkPass()
{
	//TextBox left blank.
    if ($(this).val().trim().length == 0) {
        $(".progress-bar .bar").css({width: 0});
        $(".status-html").html('');
        return false;
    }

    //Regular Expressions.
    var regex = new Array();
    regex.push("[A-Z]"); // Uppercase Alphabet.     - 0
    regex.push("[a-z]"); // Lowercase Alphabet.     - 1
    regex.push("[0-9]"); // Digit.                  - 2
    regex.push("[$@$!%*#?&]"); //Special Character. - 3

    var passed = 0;

    //Validate for each Regular Expression.
    for (var i = 0; i < regex.length; ) {
        if (new RegExp(regex[i++]).test($(this).val())) passed++;
    };

    //Validate length of Password.
    if (passed > 2 && $(this).val().trim().length > 7) {
        passed++;
    };

    $(".progress-bar .bar").css({width: passed*20+"%"});
    switch (passed) {
        case 0:
        case 1:
            $(".progress-bar .bar").removeClass("hard")
            					   .removeClass("medium")
                                   .addClass("critical");
            $(".status-html").html($(".status-html").attr("data-weak"));
            break;
        case 2:
        case 3:
            $(".progress-bar .bar").removeClass("critical")
            					   .removeClass("hard")
              					   .addClass("medium");
			 $(".status-html").html($(".status-html").attr("data-medium"));	              					
            break;
        case 4:
            $(".progress-bar .bar").removeClass("critical")
            					   .removeClass("hard")
              					   .addClass("hard");
			$(".status-html").html($(".status-html").attr("data-hard"));
			break;
		case 5:
            $(".progress-bar .bar").removeClass("critical")
            					   .removeClass("hard")
              					   .addClass("hard");
			$(".status-html").html($(".status-html").attr("data-extrahard"));	              					   
            break;
    };
    if ($("#passwordConfirm").val() === $(this).val() && $(this).val() !== "")
	{
		$(".status-mssg").show("medium");
	} else
	{
		$(".status-mssg").hide("medium");
	};

	return passed;
}