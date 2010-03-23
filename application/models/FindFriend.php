<?php

/**
 * find friends 
 * @package models
 */
class Model_FindFriend {
	
	
	/**
	 * @var string
	 */
	protected $name = null;

	/**
 	 * @var string
	 */
	protected $age = null;

	/**
 	 * @var array
 	 */
	protected $aninal = array();

	/**
	 * @var array
 	 */
	protected $visit = array();

	/**
	 * @var array
	 */
	protected $type = array();
	

	/**
	 * constructor
	 *
	 * @author Martin Kapfhammer	
	 * @param array $searchData
 	 */
	public function __construct(array $searchData) {
		$this->name = $searchData['name'];
		$this->age  = $searchData['age'];
		$this->animal = isset($searchData['animal']) ? $searchData['animal'] : array();
		$this->vist   = isset($searchData['visit']) ? $searchData['visit'] : array();
		$this->type = isset($searchData['type']) ? $searchData['type'] : array();
	}


	/**
	 * @return array $result
	 */
	public function fiendFriends() {
		$result = array();
		$result[] = $this->checkName();
		$result[] = $this->checkAge();
		$result[] = $this->checkAnimal();
		$result[] = $this->checkVisit();
		$result[] = $this->checkType();
		var_dump($result);
	}

	protected function checkName() {
		if (!$this->name) {
			return null;
		}
		// hat string leerzeichen?
		// trennen
		// sowohl nachnamen und vorname suchen
		//return $result;
	}

	protected function checkAge() {
		if ((int)$this->age === 0) {
			return null;
		}
		// alter auf datum umrechnen
		// sql abfrage vorbereiten ...
	}

	protected function checkAnimal() {
		if (empty($this->animal)) {
			return null;
		}	
		// einfaches auslesen ...
	}

}
