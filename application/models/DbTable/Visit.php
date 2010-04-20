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
	 * @return array $result
	 */
	public function getVisits() {
		$orderby = array('name ASC');
		$result  = $this->fetchAll('1', $orderby);
		return $result->toArray();
	}


	/**
	 * returns the visits for a visitid
	 * 
	 * @param string $visitid
	 * @return string
	 */
	public function getVisit($visitid) {
		$result = $this->fetchRow('vid = ' . $visitid);
		if ($result === null) {
			return '';
		}
		$result = $result->toArray();
		return $result['name'];
	}
}
