<?php

/**
 * Table-Data-Gateway-Pattern Implementation
 * used to write and select message data
 *
 * @package models/DbTable
 */
class Model_DbTable_Message extends Zend_Db_Table_Abstract {

	/**
	 * Table name 
	 * @var string 
	 */
	protected $_name = 'message';


	/**
	 * creates/inserts new messages 
	 * @author Martin Kapfhammer
	 * @param string $from
	 * @param string $to
	 * @param array $message
	 */
	public function saveMessage($from, $to, $message) {
	  	$data = array('fromid' 	=> $from, 
                      'toid' 	=> $to,
                      'message' => $message['message'], 
                      'subject' => $message['subject'],
					  'date'    => date('d.m.Y'),
					  'read_'	=> 'N'
                    );

       	$this->insert($data);
	}


	/**
	 * returns the received messages
	 *
	 * @author Martin Kapfhammer
 	 * @param string $userId
	 * @return array $result
	 */
	public function getReceivedMessages($userId) {
		$orderBy = array('mid DESC');
		$where   = array('toid = ' . $userId);
			
		$result  = $this->fetchAll($where, $orderBy);
		return $result->toArray();
	}


	/**
	 * returns sended messages
	 *
	 * @author Martin Kapfhammer
 	 * @param string $userId
	 * @return array $result
	 */
	public function getSendedMessages($userId) {
		$orderBy = array('mid DESC');
		$where   = array('fromid = ' . $userId);
			
		$result  = $this->fetchAll($where, $orderBy);
		return $result->toArray();
	}


	/**
	 * counts the unread messages
 	 *
	 * @author Martin Kapfhammer
	 * @param string $userId
	 * @return integer $formattedResult
	 */
	public function getUnreadMessages($userId) {
		$select = $this->select();
		$select->from($this, array('COUNT(*) as read_'))
			   ->where('toid = ?', $userId)
			   ->where('read_ = ?', 'N');

		$result = $this->fetchAll($select);
		$formattedResult = $result->toArray();
		return $formattedResult[0]['read_'];
	}


	/**
	 * set messages read
	 * @author Martin Kapfhammer
	 * @param string $messageId
	 */
	public function setMessageRead($messageId) {
		$data 	= array('read_' => 'Y');
		$where	= 'mid = ' . $messageId;
		
		$this->update($data, $where);
	}

}
