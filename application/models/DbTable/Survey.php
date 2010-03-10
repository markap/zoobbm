<?php

/**
 * Table-Data-Gateway-Pattern Implementation
 * used to write and select survey data
 *
 * @package models/DbTable
 */
class Model_DbTable_Survey extends Zend_Db_Table_Abstract {

	/**
	 * Table name 
	 * @var string 
	 */
	protected $_name = 'survey';


	/**
	 * updates survey data
* 
	 * @author Martin Kapfhammer
	 * @param string $key 
	 * @param string $value
	 */
	public function updateSurvey($key, $value) {
	  	$data  = array('number' 	=> $value); 
		$where = 'sid = "' . $key . '"'; 

       	$this->update($data, $where);
	}

	public function getSurveyData() {
		$orderBy = array('sid DESC');
		$results = $this->fetchAll('1', $orderBy);
		$formattedResults = $results->toArray();
		$ret = array();
		foreach ($formattedResults as $formattedResult) {
			$ret[$formattedResult['sid']] = $formattedResult['number'];
		}
		return $ret;
	}

}
