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

	/**
	 * @var string
	 */
	protected $user = null;


	/**
	 * creates/inserts the new user in the table users
	 * @author Martin Kapfhammer
	 * @param array $user
	 * @return string insert ID
	 */
	public function saveUser($user) {
	  	$data = array('firstname' 	=> $user['firstname'],
                      'lastname' 	=> $user['lastname'],
                      'username' 	=> $user['user'],
                      'password' 	=> md5($user['password']),
                      'mail' 		=> $user['mail'],
					  'newsletter'  => ($user['newsletter'] === '0') ? 'N' : 'Y'
                    );

       	return $this->insert($data);
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


	/**
	 * returns user from db for a special id
	 * 
	 * @author Martin Kapfhammer
	 * @param string $userId
	 * @return array $user
	 */
	public function fetchUser($userId) {
		$user = $this->fetchRow('userid = '. $userId);
		if (!$user) {
			throw new Exception('Userid nicht gefunden');
		}
		return $user->toArray();
	}


	/**
	 * updates user data
	 * @author Martin Kapfhammer
	 * @param string $userId
	 * @param string $userData
	 */
	public function updateUser($userId, $userData) {
		$data = array('firstname' 	=> $userData['firstname'],
			  		  'lastname' 	=> $userData['lastname'],
			  		  'mail' 		=> $userData['mail'],
			  		  'newsletter'  => ($userData['newsletter'] === '0') ? 'N' : 'Y'
				);
		$where = 'userid = ' . $userId;
		
		$this->update($data, $where);
	}


	/**
	 * updates user password
	 * @author Martin Kapfhammer
	 * @param string $userId
	 * @param string $password
	 */
	public function updatePassword($userId, $password) {
		$data = array('password' => md5($password));
		$where = 'userid = ' . $userId;

		$this->update($data, $where);
	}


	/**
	 * return all users
	 * @author Martin Kapfhammer
	 * @return array $result
	 */
	public function getUsers() {
		$orderBy = array('userid DESC');
		$result  = $this->fetchAll('1', $orderBy);
		return $result->toArray();
	}


	/**
	 * finds a user
	 * searches for name parts in first-, last-, und username
	 *
	 * @author Martin Kapfhammer
 	 * @param string $namePartOne 
 	 * @param string $namePartTwo 
	 * @return array $result
 	 */ 
	public function findUser($namePartOne, $namePartTwo) {
		$orderBy = array('userid DESC');
		$where = 'firstname like "%' . $namePartOne . '%"
					or lastname like "%' . $namePartOne . '%"
					or username like "%' . $namePartOne . '%"';
		if ($namePartTwo) {
			$where = ' or firstname like "%' . $namePartTwo . '%"
					or lastname like "%' . $namePartTwo . '%"
					or username like "%' . $namePartTwo . '%"';	
		}
		$result = $this->fetchAll($where, $orderBy);
		return $result->toArray();
	}

}
