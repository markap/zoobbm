<?php

/**
 * score object
 * the collector of all your game data
 * 
 * @package models
 */
class Model_Score {

	/**
	 * counts the played questions
	 * @var integer
	 */
	protected $questions	= 0;

	/**
	 * counts the right answers
	 * @var integer
	 */
	protected $rightAnswers = 0;

	/**
	 * counts the wrong answers
	 * @var integer
	 */
	protected $wrongAnswers = 0;
	

	/**
 	 * increments the right answers
	 * and the played questions
	 *
	 * @author Martin Kapfhammer
	 */
	public function addRightAnswer() {
		$this->questions++;
		$this->rightAnswers++;
	}	
	
	
	/**
	 * increments the wrong answers
	 * and the played questions
	 *
	 * @author Martin Kapfhammer
	 */
	public function addWrongAnswer() {
		$this->questions++;
		$this->wrongAnswers++;
	}


	/**
	 * getter for the right answers
	 *
	 * @author Martin Kapfhammer
	 * @return integer $this->rightAnswers
	 */
	public function getRightAnswers() {
		return $this->rightAnswers;
	}


	/**
	 * getter for the wrong  answers
	 *
	 * @author Martin Kapfhammer
	 * @return integer $this->wrongAnswers
	 */
	public function getWrongAnswers() {
		return $this->wrongAnswers;
	}


	/**
	 * getter for the played questions
	 *
	 * @author Martin Kapfhammer
	 * @return integer $this->questions
	 */
	public function getPlayedQuestions() {
		return $this->questions;
	}

}
