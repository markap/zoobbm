<?php


/**
 * Form Class for the Online Ticket Booking 
 * @package forms
 */
class Form_Booking extends Zend_Form {


	/**
	 * inits the booking form
	 *
	 * @author Martin Kapfhammer
	 */
	public function init() {

		$this->setMethod('post');

		// persons
		$countPerson = range(1,9);
		

        //submit
        $submit = new Zend_Form_Element_Submit('OK');
        $submit->setAttrib('id', 'submitbutton');


        //Zu gemeinsamer Form zusammenfügen
        $this->addElements(array($select, $submit, $submit));
	}



}


