<?php			

/**
 * Main class for register validation
 * @package models/Validation
 */
class Model_Validation_RegisterValidation {


	/**
	 * errors of validation
	 * @var array
	 */
	protected $errors = array();

	/**
	 * Main method for the validation of register data
	 * when you need to validate all register data
	 * executes the other methods of this class
	 *
	 * @author Martin Kapfhammer
	 * @param array $data registration data
	 * @return array $this->errors
	 *
	 */
	public function validate(array $data) {
		$this->validateNames(array('firstname' 	=> $data['firstname'],
								   'lastname' 	=> $data['lastname']
								));
		$this->validateUser(array('user' => $data['user']));
		$this->validateMail(array('mail' 		=> $data['mail'],
								  'mail_repeat' => $data['mail_repeat']
								));
		$this->existsMail(array('mail' => $data['mail']));
		$this->validatePassword(array('password'		=> $data['password'],
									  'password_repeat' => $data['password_repeat']
								));

		return $this->errors;

	}


	/**
	 * validates the lastname and the firstname
	 * 
	 * @author Martin Kapfhammer
	 * @param array $data
	 * @return Model_Validation_RegisterValidation $this to use fluent interfaces
	 */
	public function validateNames(array $data) {
		$errors = array();

		// Names Validation
		$lengthValidation  = new Zend_Validate_StringLength(array('max' => 40));

		$isFirstNameShort  = $lengthValidation->isValid($data['firstname']);
		if ($isFirstNameShort === false) {
			$errors[] = 'Zu langer Vorname';
		}

		$isLastNameShort   = $lengthValidation->isValid($data['lastname']);
		if ($isLastNameShort === false) {
			$errors[] = 'Zu langer Nachname';
		}

		$this->mergeErrors($errors);
		return $this;
	}


	/**
	 * validates the username 
	 * 
	 * @author Martin Kapfhammer
	 * @param array $data
	 * @return Model_Validation_RegisterValidation $this to use fluent interfaces
	 */
	public function validateUser(array $data) {

		$errors = array();
	
		// User Validation
		$lengthValidation  = new Zend_Validate_StringLength(array('max' => 40));

		$isUserShort = $lengthValidation->isValid($data['user']);		
		if ($isUserShort === false) {
			$errors[] = 'Zu langer Username';
		}
		
		$spaces 	= strpos($data['user'], ' ');
		if ($spaces !== false) {
			$errors[] = 'Kein gültiger Username';
		}

		$uniqueUserValidate = new Zend_Validate_Db_RecordExists('user', 'username');
		$existsUser = $uniqueUserValidate->isValid($data['user']);
		if ($existsUser === true) {
			$errors[] = 'Username existiert bereits';
		}

		$this->mergeErrors($errors);
		return $this;
	}


	/**
	 * validates email 
	 * 
	 * @author Martin Kapfhammer
	 * @param array $data
	 * @return Model_Validation_RegisterValidation $this to use fluent interfaces
	 */
	public function validateMail(array $data) {

		$errors = array();		

		// Mail validation
		$mailValidate = new Zend_Validate_EmailAddress();

		$isMailValid  = $mailValidate->isValid($data['mail']);
		if ($isMailValid === false) {
			$errors[] = 'Keine gültige Emailadresse';
		}

		$isMailRepeatValid = $mailValidate->isValid($data['mail_repeat']);
		if ($isMailRepeatValid === false) {
			$errors[] = 'Falsche Emailwiederholung';
		}

		if ($data['mail'] !== $data['mail_repeat']) {
			$errors[] = 'Falsche Emailwiederholung';
		}

		$this->mergeErrors($errors);
		return $this;
	}


	/**
	 * checks if a mail already exists 
	 * 
	 * @author Martin Kapfhammer
	 * @param array $data
	 * @return Model_Validation_RegisterValidation $this to use fluent interfaces
	 */
	public function existsMail(array $data) {
		$errors = array();

		$uniqueMailValidate = new Zend_Validate_Db_RecordExists('user', 'mail');
		$existsMail = $uniqueMailValidate->isValid($data['mail']);
		if ($existsMail === true) {
			$errors[] = 'Email existiert bereits';
		}

		$this->mergeErrors($errors);
		return $this;
	}


	/**
	 * validates password
	 *
	 * @author Martin Kapfhammer
	 * @param array $data
	 * @return Model_Validation_RegisterValidation $this to use fluent interfaces
	 */
	public function validatePassword(array $data) {
		$errors = array();

		// Password Validation
		// 1 letter
		// 1 number
		// 5 letters long
		$validPasswordPattern =  "/(?=^.{5,35}$)(?![.\n])(?=.*[A-Z]).*$/i";
		$isValidPassword = preg_match($validPasswordPattern, $data['password']);
		if ($isValidPassword === 0) {
			$errors[] = 'Passwort ist nicht gültig';
		}

		if ($data['password'] !== $data['password_repeat']) {
			$errors[] = 'Passwortwhd falsch';
		}

		$this->mergeErrors($errors);
		return $this;
	}


	/**
 	 * merge the errors arrays
	 * 
	 * @author Martin Kapfhammer
	 * @param array $errors
	 */
	protected function mergeErrors(array $errors) {
		$this->errors = array_merge($this->errors, $errors);
	}


	/**
	 * return the errors
	 * 
	 * @author Martin Kapfhammer
	 * @return array $this->errors
	 */
	public function getErrors() {
		return $this->errors;
	}
}

