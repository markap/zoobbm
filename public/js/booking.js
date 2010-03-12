$(document).ready(function() {
	
	$('#link-label').click(function() {
		var adult = $('#adult').val();
		var child = $('#child').val();
		var student = $('#student').val();
		var startdate = $('#startdate').val();
		var enddate = $('#enddate').val();

		$.post("/booking/priceajax"
			+ "/adult/" + adult
			+ "/child/" + child
			+ "/student/" + student
			+ "/startdate/" + startdate
			+ "/enddate/" + enddate
		, function (response) {
			$("#price-label").html(response);
		},
		"text"
		);	
	});


	$("#startdate").datepicker();
	$("#enddate").datepicker();

});

