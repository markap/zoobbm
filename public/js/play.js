$(document).ready(function() {

	// multiple choice buttons
	$('.answer').click(function() {
		sendRequest('answerrequest', this.id);
	});

	// no multiple choice button
	$('#send_answer').click(function() {
		if ($('#input_answer').val() === '') return;
		sendRequest('answerrequest', $('#input_answer').val());
	});

	// send textfield with button click
	$('#input_answer').keydown(function(e) {
		if (e.keyCode === 13) {		// Enter
			if ($('#input_answer').val() === '') return; 
			sendRequest('answerrequest', $('#input_answer').val());
		}
	});

	// ajax-call for checking the answer
	var sendRequest = function(action, getParam) {
		$.post("/game/" + action
			+ "/answer/" + getParam
		,
		function(response) {
			$("#answer_score").html(response);	
		},
		"text"
		);
	}

	// countdown
	var seconds = new Date(); 
	seconds.setSeconds(seconds.getSeconds() + 30);
	$('#countdown').countdown({
		until: seconds,
		format: 'S',
		onExpiry: timeOverRequest
	});

	function timeOverRequest() {
		sendRequest('timeover', 1);
	}
});
