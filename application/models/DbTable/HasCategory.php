<?php

/**
 * Table-Data-Gateway-Pattern Implementation
 * used to write and select question-category relation data 
 *
 * @package models
 * @subpackage DbTable
 */
class Model_DbTable_HasCategory extends Zend_Db_Table_Abstract {

	/**
	 * Table name 
	 * @var string 
	 */
	protected $_name = 'hasCategory';


	/**
	 * returns all categories as string for a questionid
	 * 
	 * @author Martin Kapfhammer
 	 *
	 * @param string $questionId
	 * @return array $categories
	 */
	public function getCategories($questionId) {
		$categoryIds = $this->getCategoryIds($questionId);
		$categoryDb  = new Model_DbTable_Category();
		$categories  = array();	
		foreach ($categoryIds as $categoryId) {
			$category 		= $categoryDb->getCategory($categoryId);
			$categories[] 	= $category['name'];
		}
		return $categories; 
	}


	/**
 	 * return categoryids for a questionid
	 *
	 * @author Martin Kapfhammer
	 * @param string $questionId
	 * @throws Model_Exception_QuestionNotFound
 	 * @return array $ret question ids
	 */
	protected function getCategoryIds($questionId) {
		$where = array('questionid = ' . $questionId);
		$results = $this->fetchAll($where);
		if (!$results) {
			throw new Model_Exception_QuestionNotFound('hascategory', $questionId);
		}

		$ret = array();
		foreach ($results->toArray() as $result) {
			$ret[] = $result['categoryid'];
		}
		return $ret;
	}

}
