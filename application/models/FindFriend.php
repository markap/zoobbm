<?php

/**
 * find friends 
 * @package models
 */
class Model_FindFriend {
	
	
	/**
	 * @var string
	 */
	protected $name = null;

	/**
 	 * @var array
 	 */
	protected $aninal = array();

	/**
	 * @var array
 	 */
	protected $visit = array();

	/**
	 * @var array
	 */
	protected $type = array();
	

	/**
	 * constructor
	 *
	 * @author Martin Kapfhammer	
	 * @param array $searchData
 	 */
	public function __construct(array $searchData) {
		$this->name = $searchData['name'];
		$this->animal = isset($searchData['animal']) ? $searchData['animal'] : array();
		$this->vist   = isset($searchData['visit']) ? $searchData['visit'] : array();
		$this->type = isset($searchData['type']) ? $searchData['type'] : array();
	}


	/**
	 * splits the name and searches it in db
	 *
	 * @return $result
	 */
	public function checkName() {
		if (!$this->name) {
			return null;
		}
		$partOne = substr($this->name, strpos($this->name, ' ')); 
		$partTwo = substr($this->name, 0, strpos($this->name, ' ')); 
		$user    = new Model_DbTable_User();
		$result  = $user->findUser($partOne, $partTwo);
		return $result;
	}
}

