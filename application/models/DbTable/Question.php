<?php

/**
 * Table-Data-Gateway-Pattern Implementation
 * used to write and select question data 
 *
 * @package models
 * @subpackage DbTable
 */
class Model_DbTable_Question extends Zend_Db_Table_Abstract {

	/**
	 * Table name 
	 * @var string 
	 */
	protected $_name = 'question';


	/**
	 * returns a question for a special id
	 * 
	 * @author Martin Kapfhammer
 	 *
	 * @param string $questionId
	 * @throws Model_Exception_QuestionNotFound
	 * @return array $question
	 */
	public function getQuestion($questionId) {
		$question = $this->fetchRow('questionid = '. $questionId);
		if (!$question) {
			throw new Model_Exception_QuestionNotFound('question', $questionId);
		}
		return $question->toArray();
	}

	
	/**
	 * counts questions
	 * 
	 * @return counted questions
	 */	
	public function countQuestions() {
		$select = $this->select();
		$select->from($this, array('COUNT(*) as question'));
		$result = $this->fetchAll($select);
		$formattedResult = $result->toArray();
		return $formattedResult[0]['question'];
	}
}
