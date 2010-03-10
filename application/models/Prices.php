<?php

/**
 * calculates prices 
 * @package models
 */
class Model_Prices implements Model_PricesInterface {

	const ADULTSPRICE	 	= 8.00;
	const CHILDSPRICE 		= 3.50;
	const STUDENTSPRICE 	= 5.00;
	const TARIF				= 'Basis';

	protected $adults 		= 0;
	protected $childs 		= 0;
	protected $students 	= 0;
	protected $date			= null;


	public function __construct(array $numbers, array $date) {
		$this->adults 	= $numbers['adults'];
		$this->childs 	= $numbers['childs'];
		$this->students = $numbers['students'];
		$this->date		= $date;
	}

	public function getPrice() {
		$sum = 
			$this->adults 	* static::ADULTSPRICE +			
			$this->childs 	* static::CHILDSPRICE +			
			$this->students * static::STUDENTSPRICE;
		return $sum;	
	}

	public function getContent() {
		$content = array(
			'tarif'  	=> static::TARIF,
			'adult' 	=> array($this->adults, $this->adults * static::ADULTSPRICE),
			'child' 	=> array($this->childs, $this->childs * static::CHILDSPRICE),
			'student' 	=> array($this->students, $this->students * static::STUDENTSPRICE),
			'sum'	  	=> $this->getPrice(),
			'date'		=> $this->date
		);
		return $content;
	}

}
