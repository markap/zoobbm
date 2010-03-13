<?php

/**
 * Table-Data-Gateway-Pattern Implementation
 * used to write and select user-animal relation data
 *
 * @package models/DbTable
 */
class Model_DbTable_HasAnimal extends Zend_Db_Table_Abstract {

	/**
	 * Table name 
	 * @var string 
	 */
	protected $_name = 'hasAnimal';


	/**
	 * returns all favourite animals IDS for one user
	 *
	 * @author Martin Kapfhammer
	 * @param string $userId
	 * @return array $ret
	 */
	public function getAnimals($userId) {
		$orderby = array('catid ASC');
		$where   = array('userid = ' . $userId);
		$result  = $this->fetchAll($userId, $orderby);
	
		$ret = array();
		foreach ($result->toArray() as $animal) {
			$ret[] = $animal['catid'];
		}
		return $ret;
	}


	/**
	 * updates the user-animal relation:
	 * compares the old relation with the new relation
 	 * inserts new and deletes old
	 *	
	 * @author Martin Kapfhammer
	 * @param string $userId
	 * @param array $newAnimals
	 */	
	public function updateAnimals($userId, $newAnimals) {
		$newVals = array(); // array with animals ids
		foreach ($newAnimals as $animal) {
			$newVals[] = $animal;
		}

		$oldVals = $this->getAnimals($userId);
		
		$delete = array();
		$insert = array();
		$delete = array_diff($oldVals, $newVals);
		$insert = array_diff($newVals, $oldVals);

		if (!empty($delete)) {
			foreach ($delete as $catId) {
				$this->deleteAnimals($userId, $catId);
			}
		}
		if (!empty($insert)) {
			foreach ($insert as $catId) {
				$this->insertAnimals($userId, $catId);
			}
		}
	}


	/**
 	 * delete a user - animal relation 
	 *
	 * @author Martin Kapfhammer
	 * @param string $userId
	 * @param string $catId
	 */
	protected function deleteAnimals($userId, $catId) {
		$where = array('userid' => $userId,
					   'catid'	=> $catId);
		$this->delete($where);
	}


	/**
 	 * inserts a user - animal relation 
	 *
	 * @author Martin Kapfhammer
	 * @param string $userId
	 * @param string $catId
	 */
	protected function insertAnimals($userId, $catId) {
		$data = array('userid' => $userId,
					  'catid'  => $catId);

		$this->insert($data);
	}


	/**
	 * return all animals NAMES for one user
	 *
	 * @author Martin Kapfhammer
	 * @param string $userId
	 * @return array $animal
	 */
	public function getAnimalNames($userId) {
		$animalIds  = $this->getAnimals($userId);
		$categoryDb = new Model_DbTable_Category();
		$animals 	= array();
		foreach ($animalIds as $animalId) {
			$animal	   = $categoryDb->getCategory($animalId);
			$animals[] = $animal[0]['name']; 
		}
		return $animals;
	}

}
