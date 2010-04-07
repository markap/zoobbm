<?php

/**
 * Table-Data-Gateway-Pattern Implementation
 * used to write and select profile data 
 *
 * @package models/DbTable
 */
class Model_DbTable_Profile extends Zend_Db_Table_Abstract {

	/**
	 * Table name 
	 * @var string 
	 */
	protected $_name = 'profile';

	
	/**
	 * creates/inserts empty profile 
	 * in relation to user id
	 * 
	 * @author Martin Kapfhammer
	 * @param string $userId 
	 */
	public function createProfile($userId) {
		$data = array('userid' => (int)$userId);
		$this->insert($data);
	}


	/**
	 * updates the profile Image 
	 * @author Martin Kapfhammer
	 * @params string $userId
	 * @params string $imagePath
	 */
	public function updateImage($userId, $imagePath) {
		$data = array('image' 	=> $imagePath);
		$where = 'userid = '. $userId;

		$this->update($data, $where);
	}


	/**
	 * updates the users address data, phone etc.
	 * 
	 * @author Martin Kapfhammer
	 * @param string $userId
	 * @param array $data
	 */
	public function updateAddress($userId, $data) {
		$data = array('phone' 	=> $data['phone'], 
                      'street' 	=> $data['street'],
                      'plz' 	=> $data['plz'],
                      'number' 	=> $data['number'],
                      'city' 	=> $data['city'],
					  'birth'   => $data['birth']
                    );
		$where = 'userid = '. $userId;

		$this->update($data, $where);
	}


	/**
	 * updates the users personal data
	 *
	 * @author Martin Kapfhammer
	 * @param string $userId
	 * @param array $data
	 */
	public function updatePrivate($userId, $data) {
		$data = array('type'  		=> $data['type'],
					  'visit' 		=> $data['visit'],
					  'description' => $data['description']
					);
		$where = 'userid = ' . $userId;
		
		$this->update($data, $where);
	}


	/**
	 * return user profile for given id
	 * @author Martin Kapfhammer
	 * @param string id
	 * @throw Exception
	 * @return array profile
	 */
	public function getProfile($userId) {
		$profile = $this->fetchRow('userid = '. $userId);
		if (!$profile) {
			throw new Exception('Userid nicht gefunden');
		}
		return $profile->toArray();
	}


	public function findBirth($startDate, $endDate) {
		$orderBy = array('userid DESC');
		$where = 'str_to_date(birth, "%d.%m.%Y") >= ' . $startDate;
//				   . ' and str_to_date(birth, "%Y.%m.%d") >= ' . $endDate;
		$result = $this->fetchAll($where, $orderBy);
		return $result->toArray(); 
		

	}

}
