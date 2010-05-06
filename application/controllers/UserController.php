<?php

class UserController extends Zend_Controller_Action {


	/**
	 * userid of the logged user
	 * @var string
	 */
	protected $userId = null;

	/**
	 * userData of the logged user
	 * @var array
	 */
	protected $userData = null;

	/**
	 * @var Zend_Session_Namespace
	 */
	protected $userSession = null;


	/**
	 * called for every action
	 */
    public function init() {
		$this->userSession  	= new Zend_Session_Namespace('user');
		$this->userData	= $this->userSession->user;
		$this->userId 	= $this->userData['userid'];
    }


	/**
     * the user must be logged in
     * redirect to the mustbe page
     * set redirect session to current page
     *
     * @author Martin Kapfhammer
     */
	protected function mustBeLogged() {

  		if (!Zend_Auth::getInstance()->hasIdentity()) {

			$controller = $this->_getParam('controller');
			$action 	= $this->_getParam('action');

            $redirectSession = new Zend_Session_Namespace('redirect');
            $redirectSession->next = "/$controller/$action";
            $this->_redirect('/user/mustbe');
        }
	}


	/**
	 * redirect to the userprofile
	 */
    public function indexAction() {
		$this->_redirect('/user/profile');
    }


	/**
	 * login action
	 */
    public function loginAction() {

        $loginForm = new Form_Login();
                                                                
		// user authentification component
		$auth = Zend_Auth::getInstance();	
		if ($auth->hasIdentity()) {
			$this->_redirect('/index'); // if logged in, redirect to start
		}

		$request = $this->getRequest();

		if ($request->isPost()) {
			$username = $request->getPost('username');
			$password = $request->getPost('password');

			if (!empty($username) && !empty($password)) {
				$authAdapter = new Model_AuthAdapter($username, $password);
				$result = $auth->authenticate($authAdapter);
				if ($result->isValid()) {
					// get user data
					$userData = $authAdapter->getUser();
					$userSession = new Zend_Session_Namespace('user');
					$userSession->user = $userData;

					// if redirect session is set, redirect there
					$redirectSession = new Zend_Session_Namespace('redirect');
					if (isset($redirectSession->next)) {
						$this->_redirect($redirectSession->next);
					} else {
						$this->_redirect('/index');
					}

				} else {
					$this->view->error = 'Benutzerdaten nicht gefunden';
				}
			} else {
				$this->view->error = 'Bitte alle Felder ausfüllen';
			}
		}

		$this->view->loginForm = $loginForm;
    }


	/**
	 * register new users
	 */
    public function registerAction() {
        // action body
		$form = new Form_Register();
		$request  = $this->getRequest();
		$registered = false;  // hide form when successfull registered
		$this->view->errors = array();

		if ($request->isPost()) {

			$postValues = $request->getPost();
			if (Model_Validation_NotEmpty::notEmpty($postValues) === false) { 

					if ($form->isValid($postValues)) {

						$registerValidation = new Model_Validation_RegisterValidation();
						$errors = $registerValidation->validate($postValues);

						// error array emtpy -> register successfull
						if (empty($errors)) {
							// create user and profile
							$user = new Model_DbTable_User();
							$userId = $user->saveUser($postValues);
							$profile = new Model_DbTable_Profile();
							$profile->createProfile($userId);

							$this->view->success = true;
							$registered = true;

						} else { //there are errors, so display them
							$this->view->errors = $errors;
						}
					} else {
						$this->view->errors[] = 'Captcha Wert ist falsch';
					}
			} else {
				$this->view->errors[] = 'Bitte alle Felder ausfüllen';
			}
		}

		if ($registered !== true) {
			$this->view->form = $form;
		}
    }


	/**
	 * logout the user
	 * unset the redirect session to avoid
	 * that the next user will be redirect 
	 * the wrong way
	 */
    public function logoutAction() {
        // action body
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		$redirectSession = new Zend_Session_Namespace('redirect');
		unset($redirectSession->next); 
		$this->_redirect('/');

    }


	/**
	 * show mustbe when the user must be logged, but is not
	 */
    public function mustbeAction() {
        // just render view
    }


	/**
	 * show the profile of the logged user
	 */
    public function profileAction() {

		$this->mustBeLogged();
		
		// DB Profile Class
		$profileDb = new Model_DbTable_Profile();	
		$profile   = $profileDb->getProfile($this->userId);
		$visitDb = new Model_DbTable_Visit();
		$typeDb  = new Model_DbTable_Type();
		$profile['visit'] = $visitDb->getVisit($profile['visit']);
		$profile['type'] = $typeDb->getType($profile['type']);
		$profile['image'] = ($profile['image'] !== "") ? $profile['image'] : 'default.jpg';

		// animals
		$hasAnimalDb = new Model_DbTable_HasAnimal();
		$animals 	 = $hasAnimalDb->getAnimalNames($this->userId);

		$messageDb = new Model_DbTable_Message();
		$unreadMessages = $messageDb->getUnreadMessages($this->userId);

		$this->view->userData = $this->userData;
		$this->view->profile  = $profile;
		$this->view->animals  = $animals;
		$this->view->unreadMessages = $unreadMessages;
    }


	/**
	 * edit basic user data
	 */
    public function editdefaultprofileAction() {
		$this->mustBeLogged();

		$form = new Form_EditDefaultProfile();

		// handle request
		$request = $this->getRequest();
		if ($request->isPost()) {

			$postValues = $request->getPost();

			if (Model_Validation_NotEmpty::notEmpty($postValues) === false) { 

				$errors = $this->validateNewRegisterData($postValues);
				if (empty($errors)) {  // no errors? changes successfull!

					$user = new Model_DbTable_User();
					$user->updateUser($this->userId, $postValues);

					$this->userSession->user = $this->getNewUserSessionData($this->userData, $postValues);
					$this->view->success = true;
					
				} else {
					$this->view->errors = $errors;
				}
			} else {
				$this->view->errors = array();
				$this->view->errors[] = 'Bitte alle Felder ausfüllen';
			}
		}
			
		$form->populate($this->getEditDefaultProfileFormData($this->userSession->user));
		$this->view->form = $form;
    }


	/**
	 * validates the new register data
	 * and return an error array
	 *
	 * @author Martin Kapfhammer
	 * @param array $postValues
	 * @return array errors of the validation, could be empty
	 */
	protected function validateNewRegisterData(array $postValues) {
		$registerValidation = new Model_Validation_RegisterValidation();
		$registerValidation->validateNames(array(
										'firstname' => $postValues['firstname'],
										'lastname'  => $postValues['lastname']
										));
		$registerValidation->validateMail(array(
										'mail' 		  => $postValues['mail'],
										'mail_repeat' => $postValues['mail_repeat']
										));
		return $registerValidation->getErrors();
	}


	/**
	 * returns updated user session data
	 *
	 * @author Martin Kapfhammer
	 * @param array $oldUserData data from the current session
	 * @param array $postValues data from $_POST
	 * @return array $updatedUserData
	 */
	protected function getNewUserSessionData(array $oldUserData, array $postValues) {
		$updatedUserData = array('userid' 		=> $oldUserData['userid'],
							 	 'firstname' 	=> $postValues['firstname'], 
							 	 'lastname' 	=> $postValues['lastname'],
							 	 'username' 	=> $oldUserData['username'],
							 	 'password' 	=> $oldUserData['password'],
							 	 'mail' 		=> $postValues['mail'],
							 	 'newsletter' 	=> $postValues['newsletter']
							);
		return $updatedUserData;
	}


	/**
	 * returns data for the "EditDefaultProfileForm" 
	 *
	 * @author Martin Kapfhammer
	 * @param array $userData
	 * @return array data for form populating
	 */
	protected function getEditDefaultProfileFormData(array $userData) {
		return array('firstname' 	=> $userData['firstname'],
					  'lastname'  	=> $userData['lastname'],
					  'mail'	  	=> $userData['mail'],
					  'mail_repeat' => $userData['mail'],
					  'newsletter'  => ($userData['newsletter'] === 'Y') ? 1 : 0
					); 
	}


	/**
	 * upload the user image
	 */
    public function editimageAction() {

		$this->mustBeLogged();
		
		$form = new Form_EditImage();

		// DB Profile Class
		$profileDb = new Model_DbTable_Profile();	
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
				if ($form->getElement('image')->receive()) {
					$pathName = $form->getElement('image')->getFileName();
					$fileName  = substr($pathName, strrpos($pathName, '/') + 1);
					$profileDb->updateImage($this->userId, $fileName);
				}
			}
		}

		$profile = $profileDb->getProfile($this->userId);	
		$image 	 = $profile['image'];

		$this->view->image 	= $image;
		$this->view->form 	= $form;
    }


	/**
	 * edit user addressdata, birthday, phone
	 */
    public function editaddressAction() {
		$this->mustBeLogged();

		$form = new Form_EditAddress();

		$profileDb = new Model_DbTable_Profile();
		$profile   = $profileDb->getProfile($this->userId);	

		// request handling
		$request = $this->getRequest();
		if ($request->isPost()) {
			$postValues = $request->getPost();
			$profileDb->updateAddress($this->userId, $postValues);	
			$profile = $profileDb->getProfile($this->userId);
			$this->view->success = true;
		}

		$form->populate($profile);
		$this->view->form = $form;
    }


	/**
	 * edit some personal data, e.g. favourite animals 
	 */
    public function editprivateAction() {
		$this->mustBeLogged();

		$form = new Form_EditPrivate();

		// set animals from Db to checkboxes 
		$categoryDb = new Model_DbTable_Category();
		$animals    = $categoryDb->getCategories();
		$animalCheckbox = $form->getElement('animal');
		$animalCheckbox->setMultiOptions($form->convertOptions($animals, 'catid', 'name'));

		// set visits from Db to select
		$visitDb   = new Model_DbTable_Visit();
		$visits    = $visitDb->getVisits();
		$visitSelect = $form->getElement('visit');
		$visitSelect->setMultiOptions($form->convertOptions($visits, 'vid', 'name', true));

		// set type from Db to select
		$typeDb  = new Model_DbTable_Type();
		$type    = $typeDb->getTypes();
		$typeSelect = $form->getElement('type');
		$typeSelect->setMultiOptions($form->convertOptions($type, 'tid', 'name', true));

		$profileDb 		= new Model_DbTable_Profile();
		$hasAnimalDb 	= new Model_DbTable_HasAnimal();

		// handle request
		$request = $this->getRequest();
		if ($request->isPost()) {
			$postValues = $request->getPost();
			$profileDb->updatePrivate($this->userId, $postValues);	
			if (isset($postValues['animal'])) {
				$hasAnimalDb->updateAnimals($this->userId, $postValues['animal']);
			}
			$this->view->success = 'Änderungen erfolgreich gespeichert'; 
		}		

		$profile = $profileDb->getProfile($this->userId);		
		$animals = $hasAnimalDb->getAnimals($this->userId);
		$animalCheckbox->setValue($animals);
		$form->populate($profile);

		$this->view->form = $form;
    }


	/**
	 * change the password
	 */
    public function changepasswordAction() {
		$this->mustBeLogged();

		$form = new Form_ChangePassword();
		$this->view->errors = array();

		$request = $this->getRequest();
		if ($request->isPost()) {

			$postValues = $request->getPost();

			if (Model_Validation_NotEmpty::notEmpty($postValues) === false) {

				$userDb    = new Model_DbTable_User();
				$validUser = $userDb->findCredentials($postValues['user'] , $postValues['old_password']);	
				
				if ($validUser !== false) {
					
					// check passwords
					$registerValidation = new Model_Validation_RegisterValidation();
					$errors = $registerValidation->validatePassword(array('password' 		=> $postValues['password'], 
																		  'password_repeat' => $postValues['password_repeat']))
												 ->getErrors();
					if (empty($errors)) {
						$userDb->updatePassword($this->userId, $postValues['password']);
						$this->view->success = true;		
					} else {
						$this->view->errors = $errors;
					}

				} else {
					$this->view->errors[] = 'Benutzerdaten nicht gefunden'; 
				}
			} else {
				$this->view->errors[] = 'Bitte alle Felder ausfüllen';
			}
		}

		$this->view->form = $form;
    }


}




