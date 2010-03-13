$(document).ready(function(){

		$("#register").validate({
				rules: {
					firstname: {
						required: true,
						minlength: 2,
						maxlenght: 40
					}, 
					lastname: {
						required: true,
						minlength: 2,
						maxlenght: 40
					}, 
					user: {
						required: true,
						minlength: 2,
						maxlenght: 40
					},
					mail: {
						required: true,
						email: true
					},
					mail_repeat: {
						required: true,
						email: true,
						equalTo: "#mail"
					},
					password: {
						required: true,
						url: true //eigene prüfung für passwort
					},
					password_repeat: {
						required: true,
						equalTo: "#password",
						url: true //eigene prüfung für passwort
					},
					checker: {
						required: true
					}
				}, 
				messages: {
					firstname: {
						required: "Pflichtfeld",
						minlength: "Mindestlänge 2 Zeichen",
						maxlenght: "Maximallänge 40 Zeichen"
					},
					lastname: {
						required: "Pflichtfeld",
						minlength: "Mindestlänge 2 Zeichen",
						maxlenght: "Maximallänge 40 Zeichen"
					},
					user: {
						required: "Pflichtfeld",
						minlength: "Mindestlänge 2 Zeichen",
						maxlenght: "Maximallänge 40 Zeichen"
					},
					mail: {
						required: "Pflichtfeld",
						email: "Bitte geben Sie eine gültige Emailaddresse ein"
					},
					mail_repeat: {
						required: "Pflichtfeld",
						email: "Bitte geben Sie eine gültige Emailaddresse ein",
						equalTo: "Die beiden Emailaddressen stimmen nicht überein"
					},
					password: {
						required: "Pflichtfeld",
						url: "Mindestends fünf Zeichen (Buchstaben und Zahlen)."
					},
					password_repeat: {
						required: "Pflichtfeld",
						equalTo: "Die beiden Passwörter stimmen nicht überein",
						url: "Mindestends fünf Zeichen (Buchstaben und Zahlen)."
					},
					checker: {
						required: "Bitte geben Sie den Captcha ein"
					}
				
				}
		});
});
