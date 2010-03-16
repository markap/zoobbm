<?php

/**
 * Table-Data-Gateway-Pattern Implementation
 * used to write and select ticket data 
 *
 * @package models/DbTable
 */
class Model_DbTable_Ticket extends Zend_Db_Table_Abstract {

	/**
	 * Table name 
	 * @var string 
	 */
	protected $_name = 'ticket';

	
	/**
	 * books ticket 
	 * in relation to user id
	 * 
	 * @author Martin Kapfhammer
	 * @param string $userId 
	 * @param array $number
	 * @param array $date
	 * @return string id of the ticket
	 */
	public function bookTicket($userId, array $number, array $date, array $arguments) {

		$data = array('userid' 		=> (int)$userId,
                      'adults' 		=> $number['adults'],
                      'childs' 		=> $number['childs'],
                      'students' 	=> $number['students'],
                      'starttime' 	=> $date['startdate'],
					  'endtime'   	=> $date['enddate'],
					  'description'	=> $arguments['description']
                    );

		return $this->insert($data);
	}

}
