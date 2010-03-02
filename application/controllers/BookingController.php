<?php

class BookingController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // user must be logged in
		if (!Zend_Auth::getInstance()->hasIdentity()) {
			$redirectSession = new Zend_Session_Namespace('redirect');
			$redirectSession->next = '/Booking';
			$this->_redirect('/user/mustbe');
		}
    }

    public function singleAction()
    {
        // user must be logged in
		if (!Zend_Auth::getInstance()->hasIdentity()) {
			$redirectSession = new Zend_Session_Namespace('redirect');
			$redirectSession->next = '/Booking/Single';
			$this->_redirect('/user/mustbe');
		}

		// get full name of the user
		$userSession = new Zend_Session_Namespace('user');
		$user		 = $userSession->user;
		$name		 = $user['firstname'] . ' ' . $user['lastname'];





		$form = new Form_Booking();

		$this->view->name = $name;
		$this->view->form = $form;
    }

    public function groupAction()
    {
        // action body
    }

    public function guideAction()
    {
        // action body
    }


}







