$(document).ready(function() {

	 elem = $(".js-switch");
	 if (elem.length>0) {
		 cred_card = new Switchery(elem[0], { disabled: true, size: "small" });
		 check     = new Switchery(elem[1], { disabled: true, size: "small" });
		 terms     = new Switchery(elem[2], { disabled: true, size: "small" });
	 };
	 us_err    = window.setTimeout(function() {},0);
	 it        = false;

	$(".cart-remove-item").on("click", function() {
		var obj = $(this);
		$("#loader").show();
		$("#paymentWrapper").css({opacity: 0.2});
		_UMNG_DB("shopping", {type: btoa("removeFromShoppingCart"), data: JSON.stringify({cartItemID: $(this).attr("data-cart-id")})}, function(f) {
			f = JSON.parse(f);
			if (f.info.response)
			{
				if (!f.data.morePackages)
				{
					$("#morePackages").fadeOut("medium");
				}
				$(".tr-cart-item[data-cart-id='"+obj.attr("data-cart-id")+"']").fadeOut("medium",function() {
					$(this).remove();
				});
				if (f.data.items===0) _proceed(0,1); else
				{
					_proceed(1);
					$("#cartItems").html(f.data.items);
					$("#p_pwt").html(f.data.price.pwt);
					$("#p_tax").html(f.data.price.tax);
					$("#pform_amount").val(f.data.price.total_noformat);
					if ($("#totalForeignCurrency").length>0) $("#totalForeignCurrency").html(f.data.price.totalForeignCurrency);
					$("#p_total").html(f.data.price.total);
					$("#loader").fadeOut(function() {});
					$("#paymentWrapper").css({opacity: 1});
				};
			}
		})
	});

	$("#emptyCart").on("click", function() {
		var obj = $(this);
		_UMNG_DB("shopping", {type: btoa("removeAllFromShoppingCart"), data: JSON.stringify({cartItemID: $(this).attr("data-cart-id")})}, function(f) {
			f = JSON.parse(f);
			if (f.info.response) _proceed(0,1);
		});
	});
});

$(window).on("load", function() {
	eval($("script[data-attr='true']:eq(0)").attr("data-value"));

	$(".times").on("click", function() {
		$(this).parent().parent().fadeOut(function() {});
	});

	$(".buyBtn").on("click", function() {
		if ($(this).attr("data-set")!=="0" && $(this).attr("data-set")!=="1") window.location.reload();
		if ($(this).attr("data-set") === "1") return false;
		var obj = $(this);		
		obj.attr("data-set", 1);
		_UMNG_DB("shopping", {type: btoa("addToShoppingCart"), data: JSON.stringify({workshopID: $(this).attr("data-workshop-id")})}, function(d) {
			d = JSON.parse(d);
			if (d.info.response)
			{
				obj.find("div").addClass("disabled").parent().attr("data-placement", "bottom").attr("data-toggle", "tooltip").attr("title", obj.find("div").attr("data-added")).tooltip("show");
				$.notify({
					message: obj.attr("data-success-mssg"),
					icon: "fa fa-shopping-cart"
				},{
					timer: 2000,
					delay: 6000,
					type: "success",
					offset: {x: 20, y: 40},
					placement: {from: "bottom", align: "left"},
					z_index: 9999
				});
				if ($("#cartItems").length>0)
				{
					$("#cartItems").html(d.data.items);					
				}
			}
		});
	});

	$("#submitPayment").on("click", function(e) {
		e.preventDefault();
		if (!$("#terms")[0].checked)
		{
			$.notify({
				message: $(this).attr("data-message-terms"),
				icon: "fa fa-exclamation-triangle"
			},{
				timer: 2000,
				delay: 6000,
				type: "danger",
				offset: {x: 20, y: 40},
				placement: {from: "bottom", align: "left"},
				z_index: 9999
			});
		} else if ($("#buyerInfoFinished").attr("data-status") !== "true")
		{
			if ($("#buyerInfoFinished").attr("data-status") !== "false")
			{
				window.location.reload();
			}
			$.notify({
				message: $(this).attr("data-message-user-data"),
				icon: "fa fa-exclamation-triangle"
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
			var ok = false;
			_UMNG_DB("shopping", { type: btoa("checkPaymentAmount"), data: JSON.stringify({  })}, function(e) {
				e = JSON.parse(e);
				if (e.info.response) 
				{
					ok = document.getElementById("pform_amount");
					$("#pform_amount").val(e.data.price.total_noformat);
					// Submit payment if everything is ok
					_UMNG_DB("control", {HttpRequest: JSON.stringify({type: btoa("createPaymentID")}), data: JSON.stringify({
						amount: $("#paymentDataForm input[name='amount']").val()
					})}, function(e) {
				 		e = JSON.parse(e);
				 		if (e.info.submit)
				 		{
				 			var form = $("#paymentDataForm");
				 			form.find("input[name='oid']").val(e.data.paymentID);
				 			form.find("input[name='okUrl']").val(e.data.okUrl);	
				 			form.find("input[name='rnd']").val(e.data.rnd);
				 			form.find("input[name='hash']").val(e.data.hash);
							window.setTimeout(function() { if (!ok) window.location.reload(); else $("#paymentDataForm").submit(); },100);
				 		} else window.location.reload();
				 	});

				} else window.location.reload();
			});		
		};
	});

	$("#changeBuyerInformation").on("click", function() {
		$("#buyerInfoFinished").attr("data-status", false).fadeOut(function() {
			$("#buyerInfoProcessing").fadeIn(function() {
				$("#name").select();
				$("#buyerInfoDeclEdit").removeClass("dn-imp");
			});
		});
	});

	$("#buyerInfoSaveEdit").on("click", function() {
		window.clearTimeout(us_err);
		if (securityCheck()) {
			_UMNG_DB("control", {HttpRequest: JSON.stringify({type: btoa("savePredefinedPaymentDetails")}), 
			         data: JSON.stringify({
			         	name:      $("#name").val(),
			         	address:   $("#address").val(),
			         	city:      $("#city").val(),
			         	telephone: $("#telephone").val()
			     })}, function(e) {
				e = JSON.parse(e);
				if (e.info.response)
				{
					printBI(e.data.paymentInfo);
					$("#buyerInfoProcessing").fadeOut(function() {
						$("#buyerInfoFinished").fadeIn(function() {
							$("#buyerInfoDeclEdit").removeClass("dn-imp");
							$("#buyerInfoFinished").attr("data-status", true);
						});
					});	
					$("#PayPalGroup #PayPalBtn").removeClass("disabled");			
					if (!e.data.row)
					{						
						if (e.data.count_pi > 1)
						{
							$("#showUserDataLst").show();
							printDataLst(e.data.paymentInfoOptions,e.data.words);
						};			
					};
				};
			});
		} else
		{			
			us_err = window.setTimeout(function() {
				$("#buyerInfoProcessing .mistake").fadeOut(function() {});
			}, 6000);
		}
	});

	$("#PayPalGroup").bind("click", function() {
		if (!document.getElementById("terms").checked)
		{
			$.notify({
				message: $("#submitPayment").attr("data-message-terms"),
				icon: "fa fa-exclamation-triangle"
			},{
				timer: 2000,
				delay: 6000,
				type: "danger",
				offset: {x: 20, y: 40},
				placement: {from: "bottom", align: "left"},
				z_index: 9999
			});
			return false;
		} else if ($("#buyerInfoFinished").attr("data-status") !== "true")
		{
			if ($("#buyerInfoFinished").attr("data-status") !== "false")
			{
				window.location.reload();
			}
			$.notify({
				message: $("#submitPayment").attr("data-message-user-data"),
				icon: "fa fa-exclamation-triangle"
			},{
				timer: 2000,
				delay: 6000,
				type: "danger",
				offset: {x: 20, y: 40},
				placement: {from: "bottom", align: "left"},
				z_index: 9999
			});
			return false;
		};		
	});

	$("#terms").on("change", function() {
		if (this.checked)
		{
			if ($("#buyerInfoFinished").attr("data-status") === "true")
			{
				$("#PayPalGroup #PayPalBtn").removeClass("disabled");
			};					
		} else
		{
			$("#PayPalGroup #PayPalBtn").addClass("disabled");
		}
	})

	$("#buyerInfoDeclEdit").on("click", function() {
		$("#buyerInfoProcessing").fadeOut(function() {
			$("#buyerInfoFinished").attr("data-set", true).attr("data-status", true).fadeIn(function() {});
			$("#name").val($("#BIname").html());
			$("#address").val($("#BIaddress").html());
			$("#city").val($("#BIcity").html());
			$("#telephone").val($("#BItelephone").html());
		});
	});

	$("#showUserDataLst").on("click", function() {
		$("#userPaymentInfoData").parent().fadeIn(function() {});
		$(this).hide();
		$("#hideUserDataLst").show();
	});

	$("#hideUserDataLst").on("click", function() {
		$("#userPaymentInfoData").parent().fadeOut(function() {});
		$(this).hide();
		$("#showUserDataLst").show();
	});

	$("#submitPaymentInfo").on("click", function() {
		var ID = $("input[name='usd_radio']:checked").attr("data-value");
		$(".payment-info .badge").hide();
		$(".payment-info[data-value='"+ID+"'] .badge").show();
		_UMNG_DB("control", {HttpRequest: JSON.stringify({type: btoa("selectDefaultPaymentInfo")}), data: JSON.stringify({
			paymentInfoID: ID
		})}, function(d) {
			d = JSON.parse(d);
			if (d.info.response)
			{
				$("#hideUserDataLst").click();
				printBI(d.data.paymentInfo);				
			};
		});
	});

	_appendClickRemovePIO();
});

function securityCheck()
{
//	Have to check name, address, city, telephone
	var name     = $("#name"),
	 	address  = $("#address"),
	 	city     = $("#city"),
	 	phone    = $("#telephone"),
	 	mainElem = $("#buyerInfoProcessing"),
	 	correct  = true;

	 mainElem.find(".mistake").hide();
//	Hide all list items
	 mainElem.find(".mistake ol li").each(function(i,v) {
	 	$(v).hide();
	 });

//	Security check
	 if (name.val().trim().length < 3) { correct = false; mainElem.find(".mistake ol li:eq(0)").show() }
	 if (address.val().trim().length < 5) { correct = false; mainElem.find(".mistake ol li:eq(1)").show() }
	 if (city.val().trim().length < 5) { correct = false; mainElem.find(".mistake ol li:eq(2)").show() }
	 if (phone.val().trim().length<7) { correct = false; mainElem.find(".mistake ol li:eq(3)").show()  } else
	 {
	 	 var p = true;
		 for (var i=0, t = phone.val().trim(); i<t.length; i++)
		 {
		 	p = p && !isNaN(t.charAt(i));
		 };		 
		 correct = correct && p;
		 if (!p) { mainElem.find(".mistake ol li:eq(4)").show(); }
	 };
	 if (!correct)
	 {
	 	mainElem.find(".mistake").fadeIn(function() {});
	 };
	 return correct;
}

function _hide(a)
{
	switch(a)
	{
	case ~0x00+1:
		$("#sw_creditCard")[0].click();
		$("#advice").hide();
		$("#creditCardPayment").fadeOut(function() {});
		break;	
	}
};

function printBI(o)
{
	for (var key in o) { $("#BI"+key).html(o[key]); }
}

function _appendClickRemovePIO()
{
	$(".removePIO").on("click", function() {
		var attr = $(this).attr("data-value");
		_UMNG_DB("control", {HttpRequest: JSON.stringify({type: btoa("deletePaymentInfoOption")}), data: JSON.stringify({
			id: $(this).attr("data-value")
		})}, function(f) {
			f = JSON.parse(f);
			if (f.info.response)
			{				
				$(".payment-info[data-value='"+attr+"']").fadeOut(function() {
					$(this).remove();
					if (f.info.allowAction)
					{
						if (f.data.deleteDefault)
						{
							var p = $("input[name='usd_radio']:eq(0)").prop("checked", true).attr("data-value");
							$(".payment-info .badge").hide();
							$(".payment-info[data-value='"+p+"']").find(".badge").show();
							_UMNG_DB("control", {HttpRequest: JSON.stringify({type: btoa("selectDefaultPaymentInfo")}), data: JSON.stringify({
								paymentInfoID: p
							})}, function(d) {
								d = JSON.parse(d);
								if (d.info.response)
								{					
									printBI(d.data.paymentInfo);				
								};
							});
						}		
					} else
					{
						$("#showUserDataLst, #hideUserDataLst").hide();
						$("#userPaymentInfoData").parent().hide();
						$(".buyer-card").fadeOut(function() {
							$("#buyerInfoProcessing").fadeIn(function() {});
						});
					};

					if (f.info.hideOptions)
					{
						$(".user-data-info").hide();
						$("#showUserDataLst, #hideUserDataLst").hide();
					}			
				});			
			} else window.location.reload();
		});
	});
}

function _proceed(a=0, destroy=0)
{
	if (destroy) {
		$("#cartItems").fadeOut(function() {});
		$("#morePackages").fadeOut("medium");
		$("#alreadySubscribed").fadeOut("medium");
		$("#cartHasContent").fadeOut(function() {
			$("#cartEmpty").fadeIn(function() {});
			$(this).remove();
		});
	};
	$.notify({
		message: (a===0 ? $("#emptyCart").attr("data-delete-all") : $("#emptyCart").attr("data-delete-spec")),
		icon: "fa fa-envelope"
	},{
		timer: 2000,
		delay: 6000,
		type: "success",
		offset: {x: 20, y: 40},
		placement: {from: "bottom", align: "left"},
		z_index: 9999
	});
}

function printDataLst(a,w)
{
	$("#paymentInfoOptions").html("");
	for (var i=0; i<a.length-1; i++)
	{
		$("#paymentInfoOptions").append(
		  "<div class=\"payment-info col-md-6 col-xs-12 col-sm-12 margin-b-10\" data-value=\""+a[i].piID+"\">"+
			"<label class=\"pointer\" style=\"margin: 0\">"+
			"<div>"+
				"<input type=\"radio\" name=\"usd_radio\" value=\""+(i+1)+"\" data-value=\""+a[i].piID+"\" "+(parseInt(a[i].defaultInfo)===1 ? "checked=\"checked\"" : "")+"/> "+a[i].name+
				" <span class=\"badge "+(parseInt(a[i].defaultInfo)===0 ? "dn" : "")+" bg-info\"><span class=\"small\">"+w.default+"</span></span>"+
			"</div>"+
			"<div class=\"padding-l-20 smaller\" style=\"font-weight: normal;\">"+
				"<div>"+a[i].address+"</div>"+
				"<div>"+a[i].city+"</div>"+
				"<div>"+a[i].telephone+"</div>"+
				"<div>"+a[a.length-1]+"</div>"+
				"<div><a class=\"removePIO link-ord\" data-value=\""+a[i].piID+"\" href=\"javascript: void(0)\"><i class=\"fa fa-trash\"></i> "+w.delete+"</a></div>"+
			"</div>"+
			"</label>"+
		"</div>");
	};
	_appendClickRemovePIO();
};