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
	 * returns all animals for one user
	 * @author Martin Kapfhammer
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
				$this->deleteAnimals($catId);
			}
		}
		if (!empty($insert)) {
			foreach ($insert as $catId) {
				$this->insertAnimals($userId, $catId);
			}
		}
	}

	protected function deleteAnimals($catId) {
		$where = array('catid = ' . $catId);
		$this->delete($where);
	}

	protected function insertAnimals($userId, $catId) {
		$data = array('userid' => $userId,
					  'catid'  => $catId);

		$this->insert($data);
	}

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
