
// SANDBOX ACCOUNT
// sb-idpso28198045@personal.example.com
// }r+b/3W+

//	Start PayPal Express Checkout
	paypal.Button.render({	    
	    env: 'sandbox',

	    client: {
	        sandbox:    'ASSFb9oA5RuleCJBJsWv73j1Cs0nZeXCXx34no5OfRodHVWwD8_KSXmSK56-5NxMuk0FTrFnjnkJjfcG'
	    },

	    locale: "en_US",

	   style: {
	        size: 'medium',
	        color: 'gold',
	        shape: 'pill'
	    },

	    payment: function() {
	    
	        var env    = this.props.env;
	        var client = this.props.client;
	        var resp   = _UMNG_DB("pull", {HttpRequest: JSON.stringify({type: btoa("getPaymentAmount")}), 
	                              data: JSON.stringify({})}, function(f) {
	                              	 f = JSON.parse(f);
	                              	 if (f.info.response) 
	                              	 {
	                              	 	return {pa: f.data.pa, pc: f.data.pc};
	                              	 } else
	                              	 {
	                              	 	return null;
	                              	 }
	                              });
	        if (resp===null) { window.location = "/user/"; }
	    
	        return paypal.rest.payment.create(env, client, {
	            transactions: [
	                {
	                    amount: { total: resp.pa, currency: resp.pc}
	                }
	            ]
	        });
	    },

	    commit: false,

	    onAuthorize: function(data, actions) {
	        return actions.payment.execute().then(function() {
	           actions.payment.get().then(function(payment) {
	           	 console.log(payment);
	             _UMNG_DB("payment", {type: btoa("addNewPayment"), data: JSON.stringify(payment)}, function(e) {
	             	e = JSON.parse(e);
	             	if (e.info.response)
	             	{
	             		if (e.info.verify)
	             		{
	             			window.location = e.data.redirectURI+payment.id;
	             		} else window.location.reload();	                 		
	             	};
	             });
	           });
	        });
	    },

	    onError: function(err) {
			console.log(err);
	        alert("An error occured during the PayPal transaction. Please try again.");
	    },

	    onCancel: function() {

	    }

	}, '#PayPalBtn');