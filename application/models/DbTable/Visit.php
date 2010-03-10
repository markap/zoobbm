<?php

/**
 * Table-Data-Gateway-Pattern Implementation
 * used to write and select visits data
 *
 * @package models/DbTable
 */
class Model_DbTable_Visit extends Zend_Db_Table_Abstract {

	/**
	 * Table name 
	 * @var string 
	 */
	protected $_name = 'visit';


	/**
	 * returns all visits 
	 * @author Martin Kapfhammer
	 */
	public function getVisits() {
		$orderby = array('name ASC');
		$result  = $this->fetchAll('1', $orderby);
		return $result->toArray();
	}

}