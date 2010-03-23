<?php

/**
 * question object without multiple choice
 *
 * @package models
 */
class Model_NoMultipleChoiceQuestion extends Model_Question {

	
	/**
	 * returns no choice instead of an array with answers
	 * because there is no multiple choice
	 * 
	 * @author Martin Kapfhammer
 	 * @return string nochoice
	 */
	public function getAnswers() {
		return 'nochoice';
	}

	/**
	 * checks the given answer
	 *
	 * @author Martin Kapfhammer
	 * @param string $answer
	 * @return boolean
	 */
	public function checkAnswer($answer) {
		if ($answer === $this->answers['answer']) {
			return true;
		}		
		$myAnswer 	 = $this->modifyAnswer($answer);
		$rightAnswer = $this->modifyAnswer($this->answers['answer']);
		if ($myAnswer === $rightAnswer) {
			error_log('modify_answer|' . $answer . '|' . $this->answers['answer'] . '|' . $myAnswer . '|' . $rightAnswer);
			return true;
		}
		return false;
	}

	/**
	 * modifies the given answer string for better comparison
	 * TODO observe this piece of code
	 * TODO it could become tricky when
	 * TODO you have a question which should 
	 * TODO exactly proof e.g. ä, ü etc. 
	 *
	 * @author Martin Kapfhammer	
 	 * @param string $answer
	 * @return string $answer modified answer
	 */
	protected function modifyAnswer($answer) {
		$answer = strtolower($answer);
		$answer = trim($answer);

		$chars 		 = array(" ", "-", "_", "ä", "ö", "ü", "ß");
		$replaceWith = array("", "", "", "ae", "oe", "ue", "ss");
		$answer = str_replace($chars, $replaceWith, $answer);	

		return $answer;
	}


	/**
 	 * return just the given string 
	 * overrides inherit method 
	 *
	 * @author Martin Kapfhammer
	 * @param string $answer
	 * @return string $answer
 	 */	
	public function getAnswer($answerString) {
		return $answerString;
	}
}
