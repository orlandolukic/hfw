$(document).ready(function(e) {
	
	if (typeof endDate === typeof undefined) endDate = new Date(2017, 1, 1);

	updateTime(new Date());
	window.setInterval(function() {
		updateTime(new Date());
	},1000);

});

function updateTime(a)
{
	var days = Math.floor(Math.abs((endDate.getTime() - a.getTime())/(24*60*60*1000)));
	var hours = Math.floor(Math.abs((endDate.getTime() - a.getTime())/(60*60*1000))) - days*24;
	var minutes = Math.floor(Math.abs((endDate.getTime() - a.getTime())/(60*1000))) - hours*60 - days*24*60;
	var seconds = Math.floor(Math.abs((endDate.getTime() - a.getTime()))/1000) - hours*60*60 - days*24*60*60 - minutes*60; 
	$("#d").html(days >= 10 ? days : '0'+days);
	$("#h").html(hours >= 10 ? hours : '0'+hours);
	$("#m").html(minutes >= 10 ? minutes : '0'+minutes);
	$("#s").html(seconds >= 10 ? seconds : '0'+seconds);
}