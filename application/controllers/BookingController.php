<?php

class BookingController extends Zend_Controller_Action
{

    /**
     * @var string
     */
    protected $userId = null;

    /**
     * @var string
     */
    protected $fullname = null;

	/**
	 * @var string
	 */ 
	protected $username =null;


    /**
     * called for every actions 
     * 
     */
    public function init()
    {
        // userid from session
		$userSession 	= new Zend_Session_Namespace('user');
		$userData    	= $userSession->user;
		$this->userId   = $userData['userid'];
		$this->fullname = $userData['firstname'] . ' ' . $userData['lastname'];
		$this->username = $userData['username'];
    }

    public function indexAction()
    {
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
				$ticketId = $ticketDb->bookTicket($this->userId, $numbers, $date, $arguments); 
				$content = $prices->getContent();
				$this->view->ticketName = $this->writePdfTicket($ticketId, $content);

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

    public function priceajaxAction()
    {
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

	
	/**
	 * create a pdf for booking confirmation
	 *
	 * @author Martin Kapfhammer
	 * @param string $ticketId
	 * @param array $content the booking details
	 * @return string $ticketName
	 */
    protected function writePdfTicket($ticketId, array $content) {
		$pdf = new Model_BookingPdf();
		$pdf->setHeader('Online Ticketbuchung');
		$pdf->setUserData($this->userId, $this->fullname, $this->username);
		$pdf->setBookingDetails($content);
		$pdf->setPayText();
		$pdf->setEAN();
		$pdf->setFooter();
		$ticketName = 'booking' . $ticketId . '.pdf';
		$pdf->savePdf($ticketName);
		return $ticketName; 
    }


}











