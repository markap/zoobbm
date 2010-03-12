<?php

/**
 * prices factory 
 * @package models
 */
class Model_PricesFactory { 

	/**
	 * Group Tarif -> 15 persons
	 */
	const GROUP = 15;

	/**
	 * Longterm Tarif -> 3 or more days
	 */
	const LONGTERM = 3;

	/**
	 * instance of the price factory
	 * @var Model_PricesFactory
	 */
	protected static $instance = null;

	protected $numbers = array();
	protected $sumVisitor = 0;
	protected $date = array();
	protected $time = 1;

	/**
	 * avoid cloning of this class
	 *
	 * @author Martin Kapfhammer
	 */
	private function __clone() {}


	/**
	 * protected constructor
	 * avoid to create object from outside
	 * 
	 * @author Martin Kapfhammer
	 * @param array $numbers numbers of the visitors of the zoo
	 * @param array $date start and enddate 
	 */
	protected function __construct(array $numbers, array $date) {
		$this->numbers = $numbers;
		$this->date    = $date;
		$this->sumNumbers($numbers);
		$this->setTime($date['startdate'], $date['enddate']);
	}


	/**
	 * sums the numbers from the $numbers array
	 *
	 * @author Martin Kapfhammer
	 * @param array $numbers
	 * @throws Model_NoBookPriceException
	 */
	protected function sumNumbers(array $numbers) {
		$sum = 0;
		foreach ($numbers as $value) {
			$sum = $sum + $value;
		}
		
		if ((int)$sum === 0) {
			throw new Model_NoBookPriceException('Keine Personenanzahl ausgewählt');
		}
		$this->sumNumbers = $sum;
	}


	/**
	 * validates the dates
	 * calculates the time difference between start and endtime
	 * 
	 * @author Martin Kapfhammer
	 * @params string $startdate
	 * @params string $enddate
	 *
	 * @throws Model_NoStartPriceException
	 * @throws Model_NoDateException
	 * @throws Model_StartBiggerEndPriceException
	 * @throws Model_OldDateException
	 */
	protected function setTime($startdate, $enddate) {
		if (!$startdate) {
			throw new Model_NoStartPriceException('Bitte wählen Sie ein gültiges Startdatum');
		}
		if (!Zend_Date::isDate($startdate) || 
			($enddate && !Zend_Date::isDate($enddate))) {
			throw new Model_NoDateException('Kein gültiges Datumsformat gewählt');
		}
		if ($enddate && strtotime($startdate) > strtotime($enddate)) {
			throw new Model_StartBiggerEndPriceException('Startdatum muss vor Enddatum liegen');
		}
		$startdateZ = new Zend_Date($startdate);
		$enddateZ   = ($enddate) ? new Zend_Date($enddate) : null;
		if ($startdateZ->isEarlier(Zend_Date::now()) ||
			($enddate && $enddateZ->isEarlier(Zend_Date::now()))) {
			throw new Model_OldDateException('Zeit liegt in der Gegenwart');
		}
		if ($enddate) {
			$this->time = $enddateZ->sub($startdateZ)/86400 + 1; 
		}
	}

	static public function getInstance(array $numbers, array $date) {
		if (self::$instance === null) {
			self:: $instance = new Model_PricesFactory($numbers, $date);
		}
		return self::$instance;
	}

	public function getPrice() {
		if ($this->sumVisitor >= self::GROUP && $this->time >= self::LONGTERM) {

			$groupPrices 	= new Model_GroupPrices($this->numbers, $this->date);  
			$longTermPrices = new Model_LongTermPrices($this->numbers, $this->date);  
			if ($groupPrices->getPrice() > $longTermPrices->getPrice()) {
				return $longTermPrices;
			} else {
				return $groupPrices;
			}
		}

		if ($this->sumVisitor >= self::GROUP) {
			return new Model_GroupPrices($this->numbers, $this->date);  
		}
		if ($this->time >= self::LONGTERM) {
			return new Model_LongTermPrices($this->numbers, $this->date);  
		}

		return new Model_Prices($this->numbers, $this->date);
	}


}
