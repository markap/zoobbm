<?php

/**
 * neccessery for user authentification
 * @package models
 */
class Model_AuthAdapter implements Zend_Auth_Adapter_Interface {

	/**
	 * @var string
	 */
	protected $username;

	/**
	 * @var string
	 */
	protected $password;
	
	/**
	 * @var Model_DbTable_User
	 */
	protected $userDb;


	/**
	 * constructor
	 * @author Martin Kapfhammer
	 * @param string $username
	 * @param string $password
	 */
	public function __construct($username, $password) {
		$this->username = $username;
		$this->password = $password;
		$this->userDb 	= new Model_DbTable_User();
	}


	/**
	 * user authentification 
	 *
	 * @author Martin Kapfhammer
	 * @return Zend_Auth_Result $result
	 */
	public function authenticate() {
		$match = $this->userDb->findCredentials($this->username, $this->password);

 		if (!$match) {
            $result = new Zend_Auth_Result(
                            Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,
                            null);
        } else {
        	$user = current($match);
        	$result = new Zend_Auth_Result(
                        Zend_Auth_Result::SUCCESS,
                        $user);
		}

        return $result;

	}


	/**
	 * returns the user
	 *
 	 * @author Martin Kapfhammer
 	 * @return array user
	 */
	public function getUser() {
		return $this->userDb->getUser();
	}

}
