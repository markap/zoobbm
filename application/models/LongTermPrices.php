<?php

/**
 * calculates prices for three or more days 
 * @package models
 */
class Model_LongTermPrices extends Model_Prices implements Model_PricesInterface { 

	const ADULTSPRICE	 	= 6.00;
	const CHILDSPRICE 		= 1.00;
	const STUDENTSPRICE 	= 2.00;
	const TARIF				= 'LongTerm';

}
