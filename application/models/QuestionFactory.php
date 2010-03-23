<?php

class Model_QuestionFactory {

	static public function getRandomQuestion($questionId) {
		$randomNumber = rand(1, 2);
		if ($randomNumber === 1) {
			return new Model_Question($questionId);	
		}
		return new Model_NoMultipleChoiceQuestion($questionId);	
	}

}
