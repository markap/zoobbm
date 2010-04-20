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
    }

    /**
     * the user must be logged in
     * redirect to the mustbe page
     * set redirect session to current page
     * 
     * @author Martin Kapfhammer
     * @param array $getParams 
     */
    protected function mustBeLogged(array $getParams = array ())
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {

			$controller = $this->_getParam('controller');
			$action 	= $this->_getParam('action');

			$get = '';
			if (!empty($getParams)) {
				foreach ($getParams as $value) {
					$get .= "/$value/$this->_getParam($value)";
				}
			}

			$redirectSession = new Zend_Session_Namespace('redirect');
			$redirectSession->next = "/$controller/$action" . $get;
			$this->_redirect('/user/mustbe');
		}
    }

    /**
     * show all users of the community
     */
    public function indexAction()
    {
        $this->mustBeLogged();
                        
		$userDb = new Model_DbTable_User();
		$users  = $userDb->getUsers();
			
		$profileDb = new Model_DbTable_Profile();
		$community = array();
		foreach ($users as $key => $user) {
			$profile = $profileDb->getProfile($user['userid']);
			$profile['image'] = ($profile['image'] !== "") ? $profile['image'] : 'default';
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

    /**
     * show the user profile
     */
    public function profileAction()
    {
        $this->mustBeLogged(array('user'));

		// show the user profile
		$userId = $this->_getParam('user');
		$this->view->userId = $userId;

		try {
			$userDb = new Model_DbTable_User();
			$user	= $userDb->fetchUser($userId);
				
			$profileDb = new Model_DbTable_Profile();   
			$profile   = $profileDb->getProfile($userId);
			$profile['image'] = ($profile['image'] !== '') ? $profile['image'] : 'default';
		} catch (Exception $e) {
			$user 	 = false;	
			$profile = false;
		}

		$this->view->user     = $user;
		$this->view->profile  = $profile;
    }

    /**
     * write a message to a user
     */
    public function messageAction()
    {
        $this->mustBeLogged(array('to'));
                        
		$toId 	= $this->_getParam('to');
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
			$this->view->form = new Form_Message();
		}
    }

    /**
     * show all received messages
     */
    public function showmessagesAction()
    {
        $this->mustBeLogged();
                        
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
				'read_'    => $message['read_'],
				'fullname' => $user['firstname'] . ' ' .
							  $user['lastname']
			);		
			if ($message['read_'] === 'N') {
				$messagesDb->setMessageRead($message['mid']);
			}
		}

		//pagination
		$page = $this->_getParam('page', 1);
		$paginator = Zend_Paginator::factory($messages);
		$paginator->setItemCountPerPage(3);
		$paginator->setCurrentPageNumber($page);

		$this->view->messages = $paginator;
    }

    /**
     * show sended messages
     */
    public function showsendmessagesAction()
    {
        $this->mustBeLogged();

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

    /**
     * add friends
     */
    public function addfriendAction()
    {
        $this->mustBeLogged(array('user'));

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

    /**
     * show my friends
     */
    public function friendsAction()
    {
        $this->mustBeLogged();

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

    /**
     * show friend requests
     */
    public function friendrequestAction()
    {
        $this->mustBeLogged();

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


	/**
 	 * ajax call for friendsearching
	 */
    public function findfriendajaxAction()
    {
        $this->_helper->layout->disableLayout();
		$name['name'] = $this->_getParam('name');
		$findFriend = new Model_FindFriend($name);
		$this->view->result = $findFriend->checkName();
    }


	/**
	 * shows the form for friendsearching
	 */
    public function findfrienddefaultAction()
    {
		$this->view->form = new Form_FindFriendByName();
    }


}






