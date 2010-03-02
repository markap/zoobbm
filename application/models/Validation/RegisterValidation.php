<?php			

class Model_Validation_RegisterValidation {

	public static function validate($data) {
		$errors = array();

		// Names Validation
		$lengthValidation  = new Zend_Validate_StringLength(array('max' => 40));

		$isFirstNameShort  = $lengthValidation->isValid($data['firstname']);
		if ($isFirstNameShort === false) {
			$errors[] = 'long_first';
		}

		$isLastNameShort   = $lengthValidation->isValid($data['lastname']);
		if ($isLastNameShort === false) {
			$errors[] = 'long_last';
		}

		// User Validation
		$isUserShort = $lengthValidation->isValid($data['user']);		
		if ($isUserShort === false) {
			$errors[] = 'long_user';
		}
		
		$spaces 	= strpos($data['user'], ' ');
		if ($spaces !== false) {
			$errors[] = 'wrong_user';
		}

		$uniqueUserValidate = new Zend_Validate_Db_RecordExists('user', 'username');
		$existsUser = $uniqueUserValidate->isValid($data['user']);
		if ($existsUser === true) {
			$errors[] = 'user_exists';
		}
			

		// Mail validation
		$mailValidate = new Zend_Validate_EmailAddress();

		$isMailValid  = $mailValidate->isValid($data['mail']);
		if ($isMailValid === false) {
			$errors[] = 'wrong_mail';
		}

		$isMailRepeatValid = $mailValidate->isValid($data['mail_repeat']);
		if ($isMailRepeatValid === false) {
			$errors[] = 'wrong_mailrepeat';
		}

		$uniqueMailValidate = new Zend_Validate_Db_RecordExists('user', 'mail');
		$existsMail = $uniqueMailValidate->isValid($data['mail']);
		if ($existsMail === true) {
			$errors[] = 'mail_exists';
		}
		
		if ($data['mail'] !== $data['mail_repeat']) {
			$errors[] = 'different_mail';
		}

		// Password Validation
		// 1 uppercase letter
		// 1 lowercase letter
		// 1 number
		// 8 letters long
		$validPasswordPattern = "/(?=^.{8,35}$)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/";
		$isValidPassword = preg_match($validPasswordPattern, $data['password']);
		if ($isValidPassword === 0) {
			$errors[] = 'wrong_pass';
		}

		return $errors;
	}
}

