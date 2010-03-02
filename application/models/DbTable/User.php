<?php

/**
 * Table-Data-Gateway-Pattern Implementation
 * used to write and select user data
 *
 * @package models/DbTable
 */
class Model_DbTable_User extends Zend_Db_Table_Abstract {

	/**
	 * Table name 
	 * @var string 
	 */
	protected $_name = 'user';
	protected $user = null;


	/**
	 * inserts the new user in the table users
	 * @author Martin Kapfhammer
	 * @params array $user
	 */
	public function saveUser($user) {
	  	$data = array('firstname' 	=> $user['firstname'],
                      'lastname' 	=> $user['lastname'],
                      'username' 	=> $user['user'],
                      'password' 	=> md5($user['password']),
                      'mail' 		=> $user['mail'],
					  'newsletter'  => ($user['newsletter'] === '0') ? 'N' : 'Y'
                    );

       	$this->insert($data);
	}


	/**
	 * searches username and password for authentification
	 *
	 * @author Martin Kapfhammer
	 * @param string $username
	 * @param string $password
	 * @return array|boolean
	 */
	public function findCredentials($username, $password) {
		$stmt =  $this->select()
						->where('username = ?', $username)
						->where('password = ?', md5($password));
		$row  =  $this->fetchRow($stmt);
		$this->user = $row;

		return ($row) ? $row : false;
	}

	/** 
	 * returns the user 
	 * 
 	 * @author Martin Kapfhammer
	 * @return array $user
	 */
	public function getUser() {
		return $this->user->toArray();
	}

}
