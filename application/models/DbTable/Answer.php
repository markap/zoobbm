<?php

/**
 * Table-Data-Gateway-Pattern Implementation
 * used to write and select answer data 
 *
 * @package models
 * @subpackage DbTable
 */
class Model_DbTable_Answer extends Zend_Db_Table_Abstract {

	/**
	 * Table name 
	 * @var string 
	 */
	protected $_name = 'answer';


	/**
	 * returns a answer for a special id
	 * 
	 * @author Martin Kapfhammer
 	 *
	 * @param string $answerId
	 * @throws Model_Exception_QuestionNotFound
	 * @return array $answer
	 */
	public function getAnswer($answerId) {
		$answer = $this->fetchRow('answerid = '. $answerId);
		if (!$answer) {
			throw new Model_Exception_QuestionNotFound('answer', $answerId);
		}
		return $answer->toArray();
	}

}
