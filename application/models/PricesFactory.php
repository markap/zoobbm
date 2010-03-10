<?php

/**
 * prices factory 
 * @package models
 */
class Model_PricesFactory { 

	const GROUP = 15;
	const LONGTERM = 3;

	protected $numbers = array();
	protected $sumVisitor = 0;
	protected $date = array();
	protected $time = 1;
	protected static $instance = null;

	private function __clone() {}

	protected function __construct(array $numbers, array $date) {
		$this->numbers = $numbers;
		$this->date    = $date;
		$this->sumVisitor = $this->sumNumbers($numbers);
		$this->setTime($date['startdate'], $date['enddate']);
	}

	protected function sumNumbers(array $numbers) {
		$sum = 0;
		foreach ($numbers as $value) {
			$sum = $sum + $value;
		}
		
		if ((int)$sum === 0) {
			throw new Model_NoBookPriceException('Keine Personenanzahl ausgewählt');
		}
		return $sum;
	}

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
		$enddateZ   = new Zend_Date($enddate);
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
