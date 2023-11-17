$(document).ready(function(e) {

	errors_timeout = window.setTimeout(function() {}, 0);

	for (var i=0, arr = ["name","surname","email", "username", "password"]; i<arr.length-1; i++)
	{
		(function(i) {
			$("#"+arr[i]).on("keydown", function(e) {		
				if (e.keyCode===13)
				{			
					e.preventDefault();
					$("#"+arr[i+1]).focus();
				}
			});
		})(i);
	}

	$("#password").bind("keydown", function (e) {
	    checkPass.call(this);
	    if (e.keyCode === 13)
	    {
	    	e.preventDefault();
	    	$("#passwordConfirm").focus();
	    }
	});

	$("#passwordConfirm").on("keyup", function() {
		if ($("#password").val() === $(this).val() && $(this).val() !== "")
		{
			$(".status-mssg").show("medium");
		} else
		{
			$(".status-mssg").hide("medium");
		}
	});

	$("#register").on("click", function(e) {
		e.preventDefault();
		var name     =  $("#name"),
		    surname  =  $("#surname"),
		    email    =  $("#email"),
		    username = $("#username"),
		    list     = $(".mistake ol.ordered-list-errors"),
		    city     = $("#city"),
		    state    = $("#state");
		    shown    = false,
		    ret      = false;

		if (name.length==0 || surname.length==0 || email.length==0 || username.length==0 || list.length==0 || city.length==0 || state.length==0) 
			window.location.reload();

		_SD_DB("pull", {HttpRequest: JSON.stringify({type: btoa("registerUserFetch")}), 
			data: JSON.stringify({username: username.val(), email: email.val()})}, function(z) {
			z = JSON.parse(z);
			if (z.info.response)
			{
				if (z.info.foundUser)
				{
					list.find("li:eq(3)").show();
					shown = true;
				};

				if (z.info.foundEmail)
				{
					$(".info-placeholder").show("medium");
					$("#emailForgotEmailLink").attr("href","login.php?action=emailForgot&data="+email.val());
					ret = true;
				}
			}
		});
		if (ret) return !ret;
//		Username check
		if (username.val().trim().length < 3)
		{
			list.find("li:eq(4)").show(); shown = true;
		};

		if (name.val().trim().length < 3) { shown = true; list.find("li:eq(0)").show() };
		if (surname.val().trim().length < 3) { shown = true; list.find("li:eq(1)").show() };
		if (!validEmail(email.val().trim())) { shown = true; list.find("li:eq(2)").show(); }
		if (city.val().trim().length === 0) { shown = true; list.find("li:eq(9)").show(); }
		if (state.val().trim().length === 0) { shown = true; list.find("li:eq(10)").show(); }
		if (document.getElementById("password").value.trim().length < 3) { shown = true; list.find("li:eq(8)").show() }
		if ($("#password").val() !== $("#passwordConfirm").val()) { shown = true; list.find("li:eq(6)").show() }

		if (shown)
		{
			$(".mistake").show("medium");
			window.clearTimeout(errors_timeout);
			errors_timeout = window.setTimeout(function() {	
				$(".mistake").hide("medium");
				for (var i=0, b = $(".mistake ol.ordered-list-errors li").length; i<b; i++) $(".mistake ol.ordered-list-errors li").hide();
			}, 6000);
		} else
		{
			$("#registerForm").submit();
		}
	});
});

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