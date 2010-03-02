<?php

/**
 * Table-Data-Gateway-Pattern Implementation
 * used to write and select category data
 *
 * @package models/DbTable
 */
class Model_DbTable_Category extends Zend_Db_Table_Abstract {

	/**
	 * Table name 
	 * @var string 
	 */
	protected $_name = 'category';


	/**
	 * returns all categories 
	 * @author Martin Kapfhammer
	 */
	public function getCategories() {
		$orderby = array('name ASC');
		$result  = $this->fetchAll('1', $orderby);
		return $result->toArray();
	}


	/**
	 * searches username and password for authentification
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

		return ($row) ? $row : false;
	}
	
}
