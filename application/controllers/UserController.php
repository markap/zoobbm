<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
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
                        
                        		if ($request->isPost()) {
                        
                        			$postValues = $request->getPost();
                        			if (Model_Validation_NotEmpty::notEmpty($postValues) === false) { 
                        
                        					if ($form->isValid($postValues)) {
                        
                        						$errors = Model_Validation_RegisterValidation::validate($postValues);
                        
                        						// error array emtpy -> register successfull
                        						if (empty($errors)) {
                        							$user = new Model_DbTable_User();
                        							$user->saveUser($postValues);
                        							//$userSession = new Zend_Session_Namespace('user');
                        							//$userSession->mail = $postValues['mail'];
                        							//$this->_redirect('register/success');
                        
                        						} else { //there are errors, so display them
                        							$this->view->errors = $errors;
                        						}
                        					}
                        			} else {
                        				$this->view->errors = array(); 
                        				//PHP BUG !!! 
                        				// more info: http://weierophinney.net/matthew/
                        				// archives/131-Overloading-arrays-in-PHP-5.2.0.html
                        				$this->view->errors[] = 'Bitte alle Felder ausfüllen';
                        			}
                        		}
                        
                        		$this->view->form = $form;
    }

    public function logoutAction()
    {
        // action body
                        		$auth = Zend_Auth::getInstance();
                        		$auth->clearIdentity();
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

        // show the user profile 
		$userSession = new Zend_Session_Namespace('user');
		$userData	 = $userSession->user;

		$this->view->userData = $userData;
    }

    public function editdefaultprofileAction()
    {
        $userSession = new Zend_Session_Namespace('user');
                	$userData	 = $userSession->user;
        
        			$form = new Form_EditDefaultProfile();
                
                	$this->view->userData = $userData;
        			$this->view->form 	  = $form;
    }

    public function editimageAction()
    {
        // action body
		$form = new Form_EditImage();
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
				if ($form->getElement('image')->receive()) {
					$pathName = $form->getElement('image')->getFileName();
					// get file-name
					$fileName = substr($pathName, strrpos($pathName, '/') + 1);
					var_dump($fileName);
					
					
				}
			}
		}

		$this->view->form = $form;
    }


}















