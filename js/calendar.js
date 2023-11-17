$(window).on("load",function() {
	const elem = $(document).find("script[data-calendar='true']");
	const year = $(document).find("script[data-calendar='true']").attr("data-year");
	const eset = eval(elem.attr("data-set-event"));
	const mset = eval(elem.attr("data-set-month"));
	if (!eset && !mset) {
		$('.responsive-calendar').responsiveCalendar({
			translateMonths: eval($("body").find("script[data-calendar='true']").attr("data-months")),
			allRows : false,
			 onActiveDayClick: function(a) {
			 	var g = function(t) { return t<10 ? "0"+t : t;	};
			 	key = $(this).data('year')+'-'+ g( $(this).data('month') )+'-'+ g( $(this).data('day') );
			 	if (eval(a[key].details)) getCalendarEventData(a[key].eventID, true);
			 },
			 onMonthChange: function() {
			 	_SD_DB("pull", { HttpRequest: JSON.stringify({ type: btoa("getCalendarEvents") }), data: JSON.stringify({ year: this.currentYear, month: this.currentMonth+1})}, function(e) {
					e = JSON.parse(e);
					var g = function(t) { return t<10 ? "0"+t : t;	};
					$(".event-list").html('');
					if (e.info.response)
					{
						$(".event-list").prepend(e.data.text);
						if (e.data.events)
						{
							var str = "";
							for (var i=0, len = e.data.arr.length; i<len; i++)
							{
								if (i>0) str += ",";
								for (var k = parseInt(e.data.arr[i].dates.month.start), months = parseInt(e.data.arr[i].dates.month.end); k <= months; k++)
								{
									for (var j = parseInt(e.data.arr[i].dates.day.start), days = parseInt(e.data.arr[i].dates.day.end); j <= days; j++)
									{
										if (j===days)
										{										
											str += '"'+e.data.arr[i].dates.year+'-'+g(k)+'-'+g(j)+'": {"number": 1, "badgeClass": "calendar-notif", "url": "javascript: void(0)", "eventID": "'+e.data.arr[i].eventID+'", "details": '+e.data.arr[i].has_text+'}';
										} else
										{
											str += '"'+e.data.arr[i].dates.year+'-'+g(k)+'-'+g(j)+'": { "badgeClass": "", "url": "javascript: void(0)", "eventID": "'+e.data.arr[i].eventID+'", "details": '+e.data.arr[i].has_text+'}'+(j<days ? "," : "");
										}
									}
								}													
							}
							eval('$(".responsive-calendar").responsiveCalendar("edit", {'+str+'})');	
							activateBtns();		
						}						    

					} else console.error("Error fetching data");
				});
			 }
		}).removeClass("op-0");

		_SD_DB("pull", { HttpRequest: JSON.stringify({ type: btoa("getCalendarEvents") }), data: JSON.stringify({ year: year, month: -1})}, function(e) {
			e = JSON.parse(e);
			var g = function(t) { return t<10 ? "0"+t : t;	};
			if (e.info.response)
			{
				$(".event-list").prepend(e.data.text);
				var str = "";
				if (e.data.events)
				{
					var str = "";
					for (var i=0, len = e.data.arr.length; i<len; i++)
					{
						if (i>0) str += ",";
						for (var k = parseInt(e.data.arr[i].dates.month.start), months = parseInt(e.data.arr[i].dates.month.end); k <= months; k++)
						{
							for (var j = parseInt(e.data.arr[i].dates.day.start), days = parseInt(e.data.arr[i].dates.day.end); j <= days; j++)
							{
								if (j===days)
								{										
									str += '"'+e.data.arr[i].dates.year+'-'+g(k)+'-'+g(j)+'": {"number": 1, "badgeClass": "calendar-notif", "url": "javascript: void(0)", "eventID": "'+e.data.arr[i].eventID+'", "details": '+e.data.arr[i].has_text+'}';
								} else
								{
									str += '"'+e.data.arr[i].dates.year+'-'+g(k)+'-'+g(j)+'": { "badgeClass": "", "url": "javascript: void(0)", "eventID": "'+e.data.arr[i].eventID+'", "details": '+e.data.arr[i].has_text+'}'+(j<days ? "," : "");
								}
							}
						}
					 };			
					 eval('$(".responsive-calendar").responsiveCalendar("edit", {'+str+'})');	
					 activateBtns();
				};			
			} else {
				console.error("Could not fetch data from the server");
			}
		});
	} else // eset is true --->
	{
		$('.responsive-calendar').responsiveCalendar({
			translateMonths: eval($("body").find("script[data-calendar='true']").attr("data-months")),
			allRows : false,
			time: !mset ? elem.attr("data-event-year")+"-"+elem.attr("data-event-month") : elem.attr("data-selected-year")+"-"+elem.attr("data-month"),
			 onActiveDayClick: function(a) {
			 	var g = function(t) { return t<10 ? "0"+t : t;	};
			 	key = $(this).data('year')+'-'+ g( $(this).data('month') )+'-'+ g( $(this).data('day') );
			 	if (eval(a[key].details)) getCalendarEventData(a[key].eventID, true);
			 },
			 onMonthChange: function() {
			 	_SD_DB("pull", { HttpRequest: JSON.stringify({ type: btoa("getCalendarEvents") }), data: JSON.stringify({ year: this.currentYear, month: this.currentMonth+1})}, function(e) {
					e = JSON.parse(e);
					var g = function(t) { return t<10 ? "0"+t : t;	};
					$(".event-list").html('');
					if (e.info.response)
					{
						$(".event-list").prepend(e.data.text);
						if (e.data.events)
						{
							var str = "";
							for (var i=0, len = e.data.arr.length; i<len; i++)
							{
								if (i>0) str += ",";
								for (var k = parseInt(e.data.arr[i].dates.month.start), months = parseInt(e.data.arr[i].dates.month.end); k <= months; k++)
								{
									for (var j = parseInt(e.data.arr[i].dates.day.start), days = parseInt(e.data.arr[i].dates.day.end); j <= days; j++)
									{
										if (j===days)
										{										
											str += '"'+e.data.arr[i].dates.year+'-'+g(k)+'-'+g(j)+'": {"number": 1, "badgeClass": "calendar-notif", "url": "javascript: void(0)", "eventID": "'+e.data.arr[i].eventID+'", "details": '+e.data.arr[i].has_text+'}';
										} else
										{
											str += '"'+e.data.arr[i].dates.year+'-'+g(k)+'-'+g(j)+'": { "badgeClass": "", "url": "javascript: void(0)", "eventID": "'+e.data.arr[i].eventID+'", "details": '+e.data.arr[i].has_text+'}'+(j<days ? "," : "");
										}
									}
								}
							}
							eval('$(".responsive-calendar").responsiveCalendar("edit", {'+str+'})');	
							activateBtns();		
						}						    

					} else window.location.reload();
				});
			 }
		}).removeClass("op-0");

		_SD_DB("pull", { HttpRequest: JSON.stringify({ type: btoa("getCalendarEvents") }), 
		       data: JSON.stringify({ year: elem.attr("data-event-year"), month: (!mset ? elem.attr("data-event-month") : elem.attr("data-month")), eset: true, 
		                            year: (!mset ? elem.attr("data-event-year") : elem.attr("data-selected-year")) })}, function(e) {
			e = JSON.parse(e);
			var g = function(t) { return t<10 ? "0"+t : t;	};
			if (e.info.response)
			{
				$(".event-list").prepend(e.data.text);
				if (e.data.events>0)
				{					
					var str = "";
					for (var i=0, len = e.data.arr.length; i<len; i++)
					{
						if (i>0) str += ",";
						for (var k = parseInt(e.data.arr[i].dates.month.start), months = parseInt(e.data.arr[i].dates.month.end); k <= months; k++)
						{
							for (var j = parseInt(e.data.arr[i].dates.day.start), days = parseInt(e.data.arr[i].dates.day.end); j <= days; j++)
							{
								if (j===days)
								{										
									str += '"'+e.data.arr[i].dates.year+'-'+g(k)+'-'+g(j)+'": {"number": 1, "badgeClass": "calendar-notif", "url": "javascript: void(0)", "eventID": "'+e.data.arr[i].eventID+'", "details": '+e.data.arr[i].has_text+'}';
								} else
								{
									str += '"'+e.data.arr[i].dates.year+'-'+g(k)+'-'+g(j)+'": { "badgeClass": "", "url": "javascript: void(0)", "eventID": "'+e.data.arr[i].eventID+'", "details": '+e.data.arr[i].has_text+'}'+(j<days ? "," : "");
								}
							}
						};
					}
					eval('$(".responsive-calendar").responsiveCalendar("edit", {'+str+'})');	
					activateBtns();	
					if (eset)
					{
						for (var i=0; i < e.data.arr.length; i++)
						{
							if (e.data.arr[i].eventID == elem.attr("data-event-id") && e.data.arr[i].has_text) { getCalendarEventData(elem.attr("data-event-id"), true); break; };
						};
					}
				};    
			} else window.location.reload();
		});		
	}	

	$("#evBackLst").on("click", function() {
		$(".event-wrapper").fadeOut(300, function() {
			$(".event-list").fadeIn(400);
		});
	});
});

function activateBtns()
{
	$(".btn-open-event[data-set-listeners='true']").on("click", function() {
		getCalendarEventData($(this).attr("data-event-id"));
		$(".event-list").fadeOut(300, function() {
			$(".event-wrapper").fadeIn(400);
		});
	})
}

function getCalendarEventData(f,sh=false)
{
	if (sh)
	{
		$(".event-list").fadeOut(300, function() {
			_SD_DB("pull", { HttpRequest: JSON.stringify({ type: btoa("getCalendarEventData") }), data: JSON.stringify({ eventID: f }) }, function(g) {
				g = JSON.parse(g);
				if (g.info.response)
				{
					$("#evHeading").html(g.data.heading);
					$("#evLoc").html(g.data.location);
					$("#evDetails").html(g.data.text);
					$("#evDate").html(g.data.date);
				} else window.location.reload();
				$(".event-wrapper").fadeIn(400);
			});			
		});
	} else
	{
		_SD_DB("pull", { HttpRequest: JSON.stringify({ type: btoa("getCalendarEventData") }), data: JSON.stringify({ eventID: f }) }, function(g) {
			g = JSON.parse(g);
			if (g.info.response)
			{
				$("#evHeading").html(g.data.heading);
				$("#evLoc").html(g.data.location);
				$("#evDetails").html(g.data.text);
				$("#evDate").html(g.data.date);
			} else window.location.reload();
		});
	}
	
}