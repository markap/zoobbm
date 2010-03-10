<?php

class CommunityController extends Zend_Controller_Action
{

    /**
     * @var string
     * userid of the logged user
     */
    protected $userId = null;

    /**
     * @var string
     * name of the logged user
     */
    protected $fullname = null;


    /**
     * called for every actions 
     */
    public function init()
    {
        // userid from session
		$userSession 	= new Zend_Session_Namespace('user');
		$userData    	= $userSession->user;
		$this->userId   = $userData['userid'];
		$this->fullname = $userData['firstname'] . ' ' . $userData['lastname'];

		// user must be logged in for all community actions
		if (!Zend_Auth::getInstance()->hasIdentity()) {
			$action = $this->_getParam('action');
			$params = '';
			if ($action === 'profile' || $action === 'addfriend') {
				$params = '/user/' . $this->_getParam('user');	
			}	
			if ($action === 'message') {
				$params = '/to/' . $this->_getParam('to');	
			}	
			$redirectSession = new Zend_Session_Namespace('redirect');
			$redirectSession->next = '/community/' . $action . $params; 
			$this->_redirect('/user/mustbe');
		}

    }

    public function indexAction()
    {
        // action body
		$userDb = new Model_DbTable_User();
		$users  = $userDb->getUsers();
			
		$profileDb = new Model_DbTable_Profile();
		$community = array();
		foreach ($users as $key => $user) {
			$profile = $profileDb->getProfile($user['userid']);
			$community[$key] = array(
				'user' 		=> $user,
				'profile'	=> $profile
				); 
		} 

		//pagination
		$page = $this->_getParam('page', 1);
		$paginator = Zend_Paginator::factory($community);
		$paginator->setItemCountPerPage(3);
		$paginator->setCurrentPageNumber($page);


		$this->view->paginator = $paginator;
    }

    public function profileAction()
    {
        // show the user profile
		$userId = $this->_getParam('user');
		$this->view->userId = $userId;

		try {
			$userDb = new Model_DbTable_User();
			$user	= $userDb->fetchUser($userId);
				
			$profileDb = new Model_DbTable_Profile();   
			$profile   = $profileDb->getProfile($userId);
		} catch (Exception $e) {
			$user 	 = false;	
			$profile = false;
		}

		$this->view->user     = $user;
		$this->view->profile  = $profile;
    }

    public function messageAction()
    {
        $toId = $this->_getParam('to');
                                                
		$userDb = new Model_DbTable_User();
		$receiver	= $userDb->fetchUser($toId);
		$this->view->receiver = $receiver;


		// handle request
		$request = $this->getRequest();
		$success = false;
		if ($request->isPost()) {
			$postValues = $request->getPost();
			if (Model_Validation_NotEmpty::notEmpty($postValues) === false) {
				$messageDb = new Model_DbTable_Message();	
				$messageDb->saveMessage($this->userId, $toId, $postValues);
				$success = true;	
				$this->view->success = 'Private Nachricht erfolgreich gesendet.';
			} else {
				$this->view->error = 'Bitte Titel und Text ausfüllen';
			}
		}	

		// show form
		if ($success === false) {
			$this->view->form = new Form_Messages();
		}
    }

    public function showmessagesAction()
    {
		$messagesDb = new Model_DbTable_Message();
		$messages   = $messagesDb->getReceivedMessages($this->userId);

		$userDb = new Model_DbTable_User();
		foreach ($messages as $key => $message) {
			$user = $userDb->fetchUser($message['fromid']);
			$messages[$key] = array(
				'subject'  => $message['subject'],
				'message'  => $message['message'],
				'date'	   => $message['date'],
				'from' 	   => $user,
				'read' 	   => $message['read'],
				'fullname' => $user['firstname'] . ' ' .
							  $user['lastname']
			);		
			if ($message['read'] === 'N') {
				$messagesDb->setMessageRead($message['mid']);
			}
		}

		$this->view->messages = $messages;
    }

    public function showsendmessagesAction()
    {
		$messagesDb = new Model_DbTable_Message();
		$messages   = $messagesDb->getSendedMessages($this->userId);

		$userDb = new Model_DbTable_User();
		foreach ($messages as $key => $message) {
			$user = $userDb->fetchUser($message['toid']);
			$messages[$key] = array(
				'subject'  => $message['subject'],
				'message'  => $message['message'],
				'date'	   => $message['date'],
				'to' 	   => $user,
				'fullname' => $user['firstname'] . ' ' .
							  $user['lastname']
			);
		}

		$this->view->messages = $messages;
    }

    public function addfriendAction()
    {
        // action body
		$form = new Form_Message();

		$userDb = new Model_DbTable_User();
		$user = $userDb->fetchUser($this->userId);
		$friendId = $this->_getParam('user');
		$friend = $userDb->fetchUser($friendId);
		$fullnameUser = $user['firstname'] . ' ' . $user['lastname'];
		$fullnameFriend = $friend['firstname'] . ' ' . $friend['lastname'];
		$this->view->fullnameFriend = $fullnameFriend;

		$form->populate(array(
						'subject' => 'Freundschaftsanfrage',
						'message' => $fullnameUser . 
									' möchte dich als Freund hinzufügen!'
			));


		// handle request
		$request = $this->getRequest();
		$success = false;
		if ($request->isPost()) {
			$postValues = $request->getPost();
			$friendDb = new Model_DbTable_Friend();	
			$friendDb->saveFriend($this->userId, $friendId, $postValues);
			$success = true;	
			$this->view->success = 'Freundschaftsanfrage erfolgreich gesendet.';
		}	

		// show form
		if ($success === false) {
			$this->view->form = $form;
		}
    }

    public function friendsAction()
    {
        // action body
		$friendDb = new Model_DbTable_Friend();
		$friends  = $friendDb->getFriends($this->userId);

		$userDb = new Model_DbTable_User();
		$profileDb 		= new Model_DbTable_Profile();
		$friendsProfile = array();
		foreach ($friends as $key => $friend) {
			$friendId = ($friend['toid'] !== $this->userId) ? $friend['toid'] : $friend['fromid'];
			$user	 = $userDb->fetchUser($friendId);
			$profile = $profileDb->getProfile($friendId);
			$friendsProfile[$key] = array(
				'user' 		=> $user,
				'profile'	=> $profile
				); 
		} 

		$this->view->fullname  		= $this->fullname;
		$this->view->friendsProfile = $friendsProfile;
    }

    public function friendrequestAction()
    {
        // action body
		$friendDb = new Model_DbTable_Friend();
		$friends  = $friendDb->getFriendsRequest($this->userId);
			
		$userDb			= new Model_DbTable_User();
		$profileDb 		= new Model_DbTable_Profile();
		$friendsProfile = array();
		foreach ($friends as $key => $friend) {
			$user	 = $userDb->fetchUser($friend['fromid']);
			$profile = $profileDb->getProfile($friend['fromid']);
			$friendsProfile[$key] = array(
				'user' 		=> $user,
				'profile'	=> $profile,
				'friend'	=> $friend
				); 
		} 

		$this->view->friendsProfile = $friendsProfile;
    }

    public function friendajaxAction()
    {
        // action body
		$this->_helper->layout->disableLayout();
		$friendId = $this->_getParam('friend');
		$userId = $this->_getParam('user');

		$friendDb = new Model_DbTable_Friend();
		$friendDb->setFriendActive($friendId);
		$userDb   = new Model_DbTable_User();
		$user = $userDb->fetchUser($userId);
		$this->view->fullname = $user['firstname'] . ' ' . $user['lastname'];
    }


}

















