<?php

/**
 * prices factory 
 * returns Model_PricesInterface for the different Tarifs
 * @package models
 */
class Model_PricesFactory { 

	/**
	 * Group Tarif -> 15 persons or more
	 */
	const GROUP = 15;

	/**
	 * Longterm Tarif -> 3 or more days
	 */
	const LONGTERM = 3;

	/**
 	 * seconds of one day
	 */
	const SECONDS_PER_DAY = 86400;

	/**
	 * instance of the price factory
	 * @var Model_PricesFactory
	 */
	protected static $instance = null;

	/**
	 * numbers of the people who booked
	 * @var array
	 */
	protected $numbers = array();
	
	/**
	 * sum of the people who booked
	 * @var integer
	 */
	protected $sumVisitor = 0;

	/**
	 * start and enddate
	 * @var array
	 */
	protected $date = array();

	/**
	 * time between start- and enddate
	 * @var integer
	 */
	protected $time = 1;


	/**
	 * return a instance of the price-factory
	 *
	 * @author Martin Kapfhammer
	 *
	 * @param array $numbers numbers of the people who booked
	 * @param array $date day(s) of booking
	 * @return Model_PricesFactory self::$instance
	 */
	static public function getInstance(array $numbers, array $date) {
		if (self::$instance === null) {
			self:: $instance = new Model_PricesFactory($numbers, $date);
		}
		return self::$instance;
	}


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
	 * validates the dates and
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
			throw new Model_OldDateException('Zeit liegt in der Vergangenheit');
		}
		if ($enddate) {
			$this->time = $enddateZ->sub($startdateZ)/self::SECONDS_PER_DAY + 1; 
		}
	}


	/**
	 * factory method
	 * return the right Prices-Object 
	 *
	 * @author Martin Kapfhammer
	 *
	 * @return Model_PricesInterface
	 */
	public function getPrice() {
		if ($this->isGroup() && $this->isLongTerm()) {
			return $this->getCheaperPrice();
		}
		if ($this->isGroup()) {
			return new Model_GroupPrices($this->numbers, $this->date);  
		}
		if ($this->isLongTerm()) {
			return new Model_LongTermPrices($this->numbers, $this->date);  
		}
		return new Model_Prices($this->numbers, $this->date);
	}


	/**
	 * checks if the booking is GroupTarif
	 * @author Martin Kapfhammer
	 * @return boolean
	 */
	private function isGroup() {
		return ($this->sumVisitor >= self::GROUP);
	}


	/**
	 * checks if the booking is LongTermTarif
	 * @author Martin Kapfhammer
	 * @return boolean
	 */
	private function isLongTerm() {
		return ($this->time >= self::LONGTERM);
	}


	/**
	 * booking is LongTerm and Group
	 * return the cheapest
	 * 
	 * @author Martin Kapfhammer
	 * @return Model_PricesInterface
	 */
	private function getCheaperPrice() {
		$groupPrices 	= new Model_GroupPrices($this->numbers, $this->date);  
		$longTermPrices = new Model_LongTermPrices($this->numbers, $this->date);  
		if ($groupPrices->getPrice() > $longTermPrices->getPrice()) {
			return $longTermPrices;
		} else {
			return $groupPrices;
		}
	}

}
