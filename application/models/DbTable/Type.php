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


	/**
	 * returns the type for a typeid
	 * 
	 * @param string $typeid
	 * @return string
	 */
	public function getType($typeid) {
		$result = $this->fetchRow('tid = ' . $typeid);
		if ($result === null) {
			return '';
		}
		$result = $result->toArray();
		return $result['name'];
	}

}
