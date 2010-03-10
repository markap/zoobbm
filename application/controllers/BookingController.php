<?php

class BookingController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->view->form = new Form_Booking();
    }

    public function singleAction()
    {
        // action body
    }

    public function groupAction()
    {
        // action body
    }

    public function guideAction()
    {
        // action body
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


}









