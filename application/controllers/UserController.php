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
	 * called for every action
	 */
    public function init() {
		$userSession  	= new Zend_Session_Namespace('user');
		$this->userData	= $userSession->user;
		$this->userId 	= $this->userData['userid'];
    }

    public function indexAction()
    {
		$this->_redirect('/user/profile');
    }

    public function loginAction()
    {
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

    public function registerAction()
    {
        // action body
		$form = new Form_Register();
		$request  = $this->getRequest();
		$registered = false;  // hide form when successfull registered

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
						$this->view->errors = array();
						$this->view->errors[] = 'Captcha Wert ist falsch';
					}
			} else {
				$this->view->errors = array(); 
				//PHP BUG !!! 
				// more info: http://weierophinney.net/matthew/
				// archives/131-Overloading-arrays-in-PHP-5.2.0.html
				$this->view->errors[] = 'Bitte alle Felder ausfüllen';
			}
		}

		if ($registered !== true) {
			$this->view->form = $form;
		}
    }

    public function logoutAction()
    {
        // action body
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		$redirectSession = new Zend_Session_Namespace('redirect');
		unset($redirectSession->next); 
		$this->_redirect('/');

    }

    public function mustbeAction()
    {
        // just render view
    }

    public function profileAction()
    {
        // user must be logged in
		if (!Zend_Auth::getInstance()->hasIdentity()) {
			$redirectSession = new Zend_Session_Namespace('redirect');
			$redirectSession->next = '/User/Profile';
			$this->_redirect('/user/mustbe');
		}
		
		// DB Profile Class
		$profileDb = new Model_DbTable_Profile();	
		$profile   = $profileDb->getProfile($this->userId);

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

    public function editdefaultprofileAction()
    {
        $userSession = new Zend_Session_Namespace('user');
                        		$userData	 = $userSession->user;
                        		$userId		 = $userData['userid'];
                        
                        		$form = new Form_EditDefaultProfile();
                        
                        		// handle request
                        		$request = $this->getRequest();
                        		if ($request->isPost()) {
                        
                        			$postValues = $request->getPost();
                        
                        			if (Model_Validation_NotEmpty::notEmpty($postValues) === false) { 
                        
                        					if ($form->isValid($postValues)) {
                        
                        						$registerValidation = new Model_Validation_RegisterValidation();
                        						$errors = $registerValidation->validateNames(array(
                        																		'firstname' => $postValues['firstname'],
                        																		'lastname'  => $postValues['lastname']))
                        													 ->validateMail(array(
                        																		'mail' 		  => $postValues['mail'],
                        																		'mail_repeat' => $postValues['mail_repeat']))
                        													 ->getErrors();
                        																										
                        
                        						// error array emtpy -> register successfull
                        						if (empty($errors)) {
                        							// update user in db
                        							$user = new Model_DbTable_User();
                        							$user->updateUser($userId, $postValues);
                        	
                        							//update user in session also
                        							$updatedUserData = array('userid' 		=> $userData['userid'],
                        													 'firstname' 	=> $postValues['firstname'], 
                        													 'lastname' 	=> $postValues['lastname'],
                        													 'username' 	=> $userData['username'],
                        													 'password' 	=> $userData['password'],
                        													 'mail' 		=> $postValues['mail'],
                        													 'newsletter' 	=> $postValues['newsletter']
                        													);
                        							$userSession->user = $updatedUserData;
                
                									$this->view->success = true;
                        							
                        						} else {
                        							$this->view->errors = $errors;
                        						}
                        					}
                        			} else {
                        				$this->view->errors = array();
                        				$this->view->errors[] = 'Bitte alle Felder ausfüllen';
                        			}
                        		}
                        			
                        
                        		$userData	 = $userSession->user;
                        		// fill formular with current values
                        		$form->populate(array('firstname' 	=> $userData['firstname'],
                        							  'lastname'  	=> $userData['lastname'],
                        							  'mail'	  	=> $userData['mail'],
                        							  'mail_repeat' => $userData['mail'],
                        							  'newsletter'  => ($userData['newsletter'] === 'Y') ? 1 : 0
                        							)); 
                        
                        		$this->view->form 	  = $form;
    }

    public function editimageAction()
    {
        // action body
                        		$form = new Form_EditImage();
                        
                        		// userid from session
                        		$userSession = new Zend_Session_Namespace('user');
                        		$userData	 = $userSession->user;
                        		$userId 	 = $userData['userid'];
                        
                        		// DB Profile Class
                        		$profileDb = new Model_DbTable_Profile();	
                        		
                        		$request = $this->getRequest();
                        		if ($request->isPost()) {
                        			if ($form->isValid($request->getPost())) {
                        				if ($form->getElement('image')->receive()) {
                        					$pathName = $form->getElement('image')->getFileName();
                        					// get file-name
                        					$fileName  = substr($pathName, strrpos($pathName, '/') + 1);
                        					$profileDb->updateImage($userId, $fileName);
                        				}
                        			}
                        		}
                        
                        		$profile = $profileDb->getProfile($userId);	
                        		$image 	 = $profile['image'];
                        
                        		$this->view->image 	= $image;
                        		$this->view->form 	= $form;
    }

    public function editaddressAction()
    {
        // action body
                		$form = new Form_EditAddress();
                
                		// userid from session
                		$userSession = new Zend_Session_Namespace('user');
                		$userData	 = $userSession->user;
                		$userId 	 = $userData['userid'];
                
                
                		$profileDb = new Model_DbTable_Profile();
                        $profile   = $profileDb->getProfile($userId);	
                
                		// request handling
                		$request = $this->getRequest();
                        
                		if ($request->isPost()) {
                
                			$postValues = $request->getPost();
							$profileDb->updateAddress($userId, $postValues);	
							$profile = $profileDb->getProfile($userId);
							$this->view->success = true;
                
                		}
                
                		$form->populate($profile);
                		$this->view->form = $form;
    }

    public function editprivateAction()
    {
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

    public function changepasswordAction()
    {
        // action body
		$form = new Form_ChangePassword();

		// userid from session
		$userSession = new Zend_Session_Namespace('user');
		$userData	 = $userSession->user;
		$userId 	 = $userData['userid'];


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
						$userDb->updatePassword($userId, $postValues['password']);
						$this->view->success = true;		
					} else {
						$this->view->errors = $errors;
					}

				} else {
					$this->view->errors = array();
					$this->view->errors[] = 'Benutzerdaten nicht gefunden'; 
				}
			} else {
				$this->view->errors = array();
				$this->view->errors[] = 'Bitte alle Felder ausfüllen';
			}
		}

		$this->view->form = $form;
    }


}




