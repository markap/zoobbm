<?php

class BookingController extends Zend_Controller_Action
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
    public function init() {
        // userid from session
		$userSession 	= new Zend_Session_Namespace('user');
		$userData    	= $userSession->user;
		$this->userId   = $userData['userid'];
		$this->fullname = $userData['firstname'] . ' ' . $userData['lastname'];
    }

    public function indexAction() {

		if ($this->getRequest()->isPost()) {
			$postValues = $this->getRequest()->getPost();

			$numbers = array(
					'adults' 		=> $postValues['adult'],
					'childs' 		=> $postValues['child'],
					'students' 		=> $postValues['student']
				);

			$date = array(
					'startdate' => $postValues['startdate'],
					'enddate'	=> $postValues['enddate']
				);
			
			$arguments = array(
					'description' => $postValues['description']
			);
		
			try {
				$prices	 = Model_PricesFactory::getInstance($numbers, $date)->getPrice();
				$ticketDb = new Model_DbTable_Ticket();
				$ticketDb->bookTicket($this->userId, $numbers, $date, $arguments); 
				$content = $prices->getContent();

			} catch (Exception $e) {
				$error = $e->getMessage();
			}

			$this->view->error 		= isset($error) ? $error : null;
			$this->view->content 	= isset($content) ? $content : null;
		} 

		if (!isset($content)) {
        	$this->view->form = new Form_Booking();
		}
    }


    public function priceajaxAction() {
		$this->_helper->layout->disableLayout();
		$numbers = array(
				'adults' 		=> $this->_getParam('adult'),
				'childs' 		=> $this->_getParam('child'),
				'students' 		=> $this->_getParam('student')
			);

		$date = array(
				'startdate' => $this->_getParam('startdate'),
				'enddate'	=> $this->_getParam('enddate')
			);
		
		try {
			$prices	 = Model_PricesFactory::getInstance($numbers, $date)->getPrice();
			$content = $prices->getContent();
		} catch (Model_NoBookPriceException $e) {
			$error = $e->getMessage();
		} catch (Model_NoDateException $e) {
			$error = $e->getMessage();
		} catch (Model_NoStartPriceException $e) {
			$error = $e->getMessage();
		} catch (Model_StartBiggerEndPriceException $e) {
			$error = $e->getMessage();
		} catch (Model_OldDateException $e) {
			$error = $e->getMessage();
		}

		$this->view->error 		= isset($error) ? $error : null;
		$this->view->content 	= isset($content) ? $content : null;
    }


}









