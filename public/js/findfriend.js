$(document).ready(function() {
    $('#name').keyup(function(event){
		var name = $('#name').val();
		$.post("/community/findfriendajax"
			+ "/name/" + name
		, function (response) {
			$("#showfriends").html(response);
		},
		"text"
		);	

    });
});
