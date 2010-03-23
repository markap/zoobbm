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
	 * @return array $result
	 */
	public function getCategories() {
		$orderby = array('name ASC');
		$result  = $this->fetchAll('1', $orderby);
		return $result->toArray();
	}


	/**
	 * return one special category
	 *
	 * @author Martin Kapfhammer
	 * @param string $catId
	 * @return array $result
	 */	
	public function getCategory($catId) {
		$where  = array('catid = ' . $catId);
		$result = $this->fetchAll($where);
		return $result->toArray();
	}

}

