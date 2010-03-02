<?php

/**
 * Validation Class
 * Are all required fields filled?
 * @package Validation
 */
class Model_Validation_NotEmpty {

	/**
     * checks an array, if everything is filled
	 * @author Martin Kapfhammer
	 * @static
 	 * @param array $data
	 * @return boolean
	 */
	static public function notEmpty(array $data) {
		$empty = false;
		foreach ($data as $value) {
			if (!$value) {
				$empty = true;
				break;
			}
		}
		return $empty;
	}

} 
 
