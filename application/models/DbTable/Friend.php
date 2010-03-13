<?php

/**
 * Table-Data-Gateway-Pattern Implementation
 * used to write and select friend data
 *
 * @package models/DbTable
 */
class Model_DbTable_Friend extends Zend_Db_Table_Abstract {

	/**
	 * Table name 
	 * @var string 
	 */
	protected $_name = 'friend';


	/**
	 * creates/inserts new friend 
	 * @author Martin Kapfhammer
	 * @param string $from
	 * @param string $to
	 * @param array $data
	 */
	public function saveFriend($from, $to, $data) {
	  	$data = array('fromid' 	=> $from, 
                      'toid' 	=> $to,
                      'message' => $data['message'], 
                      'subject' => $data['subject'],
					  'active'  => 'N'
                    );

       	$this->insert($data);
	}


	/**
	 * get friend requests
	 * 
	 * @author Martin Kapfhammer
	 * @param string $userId
	 * @return array $result
	 */
	public function getFriendsRequest($userId) {
		$orderBy = array('fid DESC');
		$where   = array('toid = ' . $userId,
						 'active = \'N\'');
		$result  = $this->fetchAll($where, $orderBy);
		return $result->toArray();
	}


	/**
	 * get friends
	 *
	 * @author Martin Kapfhammer
	 * @param string $userId
	 * @return array $result
	 */
	public function getFriends($userId) {
		$orderBy = array('fid DESC');
		$where   = '(fromid = ' . $userId . 
						 ' OR toid = ' . $userId .
						 ') AND active = "Y"';
			
		$result  = $this->fetchAll($where, $orderBy);
		return $result->toArray();
	}


	/**
	 * set friend active
	 * 
	 * @author Martin Kapfhammer
	 * @param string $friendId
	 */
	public function setFriendActive($friendId) {
		$data 	= array('active' => 'Y');
		$where 	= array('fid = ' . $friendId);	

		$this->update($data, $where);
	}

}
