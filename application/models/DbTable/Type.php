<?php

/**
 * Table-Data-Gateway-Pattern Implementation
 * used to write and select type data
 *
 * @package models/DbTable
 */
class Model_DbTable_Type extends Zend_Db_Table_Abstract {

	/**
	 * Table name 
	 * @var string 
	 */
	protected $_name = 'type';


	/**
	 * returns all types 
	 * @author Martin Kapfhammer
	 * @return array $result
	 */
	public function getTypes() {
		$orderby = array('name ASC');
		$result  = $this->fetchAll('1', $orderby);
		return $result->toArray();
	}

	public function getType($typeid) {
		$result = $this->fetchRow('tid = ' . $typeid)->toArray();
		return $result['name'];
	}

}
