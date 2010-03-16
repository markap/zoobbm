$(document).ready(function() {
	
	$('.delete').click(function() {
		$(this).parents(".ticket").animate({opacity: "hide"}, "slow");
		$.post("/booking/stornoajax"
			+ "/id/" + this.id
		, function (response) {
			$("#storno").html(response);
		},
		"text"
		);	
	});


});

