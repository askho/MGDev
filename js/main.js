$( document ).ready(function() {
	$(".nav.navbar-nav.navbar-right").click(function(event){
		if($(event.target).is("#gallery")) {
			alert("gallery was pressed");
		} else if($(event.target).is("#blog")) {
			alert("Blog was pressed");
		} else if($(event.target).is("#booking")) {
			alert("Booking was pressed");
		}
	})
});