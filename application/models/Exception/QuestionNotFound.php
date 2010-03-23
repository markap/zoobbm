<?php

/**
 * throw this exception if 
 * question does not exists
 *
 * @package models
 * @subpackage Exception
 */
class Model_Exception_QuestionNotFound extends Exception {

	/**
	 * name of the class which throws the error
	 * @var string
	 */
	protected $className = null;

	/**
	 * id of the question which does not exist
	 * @var string
	 */
	protected $id = null;


	/**
	 * constructor
	 * 
	 * @author Martin Kapfhammer
	 * @param string $className
	 * @param string $id
	 */
	public function __construct($className, $id) {
		$this->className = $className;
		$this->id		 = $id;
	}

	
	/**
	 * Getter for the classname
	 *
	 * @author Martin Kapfhammer
	 * @return $className
	 */
	public function getClassName() {
		return $this->className;
	}


	/**
	 * Getter for the id
	 *
	 * @author Martin Kapfhammer
	 * @return $id
	 */
	public function getId() {
		return $this->id;
	}

}
