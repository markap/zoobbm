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

	
	/**
	 * returns all tickets booked in future
	 *
	 * @author Martin Kapfhammer
 	 * @param string $userId
	 * @return array $result
	 */
	public function getFutureTickets($userId) {
		$orderBy = array('starttime ASC');	
		$where   = 'userid = ' . $userId .
						' AND (starttime >= "' . date('d.m.Y')
						. '" OR endtime >= "' . date('d.m.Y'). '")';

		$result = $this->fetchAll($where, $orderBy);
		return $result->toArray();
	}


	/**
	 * returns all old tickets 
	 *
	 * @author Martin Kapfhammer
 	 * @param string $userId
	 * @return array $result
	 */
	public function getOldTickets($userId) {
		$orderBy = array('starttime ASC');	
		$where   = 'userid = ' . $userId .
						' AND (starttime < "' . date('d.m.Y')
						. '" AND endtime  < "' . date('d.m.Y'). '")';

		$result = $this->fetchAll($where, $orderBy);
		return $result->toArray();
	}


	/**
	 * deletes a ticket
	 *
	 * @author Martin Kapfhammer
	 * @param string $ticketId
	 */
	public function deleteTicket($ticketId) {
		$where = 'tid = ' . $ticketId;	
		$this->delete($where);
	}

}
