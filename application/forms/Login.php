<?php


/**
 * Form Class for the user login
 * @package forms
 */
class Form_Login extends Zend_Form {

	/**
	 * inits the form
	 *
	 * @author Martin Kapfhammer
	 */
	public function init() {

  		//username 
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Benutzername')
                 ->setRequired(true)
                 ->addFilter('StripTags')
                 ->addFilter('StringTrim')
                 ->addValidator('NotEmpty');

        //password
        $pass = new Zend_Form_Element_Password('password');
        $pass->setLabel('Passwort')
             ->setRequired(true)
             ->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty');

        //submit
        $submit = new Zend_Form_Element_Submit('OK');
        $submit->setAttrib('id', 'submitbutton');


        //Zu gemeinsamer Form zusammenfügen
        $this->addElements(array($username, $pass, $submit));
	}



}


