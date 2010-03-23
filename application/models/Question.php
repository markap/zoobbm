<?php

/**
 * question object
 * creates one question
 *
 * @package models
 */
class Model_Question {

	/**
	 * question array with the data from db
	 * @var array
	 */
	protected $question = array();

	/**
	 * answer array with the data from db
	 * @var array
	 */
	protected $answers = array();


	/**
	 * constructor
	 * creates a new Question-object with given id
	 *
	 * @author Martin Kapfhammer
	 * @param string $questionId
	 */
	public function __construct($questionId) {
		$questionDb 	  = new Model_DbTable_Question();
		$answerDb 		  = new Model_DbTable_Answer();
		$this->question   = $questionDb->getQuestion($questionId);
		$this->answers	  = $answerDb->getAnswer($this->question['answerid']);
	}


	/**
	 * getter for question-array
	 *
	 * @author Martin Kapfhammer
	 * @return $this->question
	 */
	public function getQuestion() {
		return $this->question;
	}


	/**
	 * getter for answers-array
	 *
	 * @author Martin Kapfhammer
	 * @return $this->answers
	 */
	public function getAnswers() {
		$answers = $this->getAnswerStrings();
		$answers = $this->shuffleAnswers($answers);
		$answers = $this->createHashes($answers);
		return $answers;
	}

	
	/**
	 * creates a array with answerstrings only 
	 * from the answers array
	 *
	 * @author Martin Kapfhammer	
	 * @return array $answerStrings
	 */
	protected function getAnswerStrings() {
		$answerStrings = array($this->answers['answer'],
						 	   $this->answers['fake1'],
						 	   $this->answers['fake2'],
						 	   $this->answers['fake3']
							);
		return $answerStrings;
	}


	/**
	 * Wrapper for the php shuffle function
	 * to ensure callback value
	 * and better reading
	 *
	 * @author Martin Kapfhammer
	 * @param array $answers 
	 * @return array $answers shuffled array
	 */
	protected function shuffleAnswers(array $answers) {
		shuffle($answers);
		return $answers;
	}


	/**
	 * creates a array with answer and answer hashes
	 *
	 * @author Martin Kapfhammer
	 * @param array $answers 
	 * @return array $answers answers with hashes
	 */
	protected function createHashes(array $answers) {
		$answerHashes = array();
		$key = 0;
		foreach ($answers as $answer) {
			$answerHashes[$key++] = md5($answer);
			$answerHashes[$key++] = $answer;
		}
		return $answerHashes;
	}


	/**
	 * checks if the given answerhash is right
	 * 
	 * @param string $answerHash
	 * @return boolean $result
	 */
	public function checkAnswer($answerHash) {
		$result = false;
		if ($answerHash === md5($this->answers['answer'])) {
			$result = true;
		}
		return $result;
	}

	
	/**
	 * return readable answer for given hash
	 *
	 * @author Martin Kapfhammer
 	 * @param string $answerHash
	 * @return string $result
	 */
	public function getAnswer($answerHash) {
		$answers = $this->getAnswerStrings();
		$result	 = null;
		foreach ($answers as $answer) {
			$md5Answer = md5($answer);
			if ($answerHash === $md5Answer) {
				$result = $answer;
				break;
			}
		}
		return $result;
	}


	/**
	 * returns the right answer
	 * 
	 * @author Martin Kapfhammer
 	 * @return string $this->answers['answer']
	 */
	public function getRightAnswer() {
		return $this->answers['answer'];
	}


	/**
	 * returns the questionid
	 *
	 * @author Martin Kapfhammer
	 * @return string questionid
	 */
	public function getQuestionId() {
		return $this->question['questionid'];
	}
}
