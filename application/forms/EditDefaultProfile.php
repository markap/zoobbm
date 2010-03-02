<?php

/** 
 * Form class for the editing of default data 
 * @package forms
 */
class Form_EditDefaultProfile extends Zend_Form {

    /**
     * inits the form
     * 
     * @author Martin Kapfhammer 
     */
    public function init() {

        $this->setMethod('post');

        // First Name
        $firstName = new Zend_Form_Element_Text('firstname');
        $firstName->setLabel('Vorname:')
                  ->setRequired(true)
                  ->addFilter('StringTrim')
                  ->addFilter('StripTags');

        // Last Name
        $lastName = new Zend_Form_Element_Text('lastname');
        $lastName->setLabel('Nachname:')
                 ->setRequired(true)
                 ->addFilter('StringTrim')
                 ->addFilter('StripTags');


  		// Email
        $mail = new Zend_Form_Element_Text('mail');
        $mail->setLabel('Email:')
             ->setRequired(true)
             ->addFilter('StringTrim')
             ->addFilter('StripTags');

        // Email wiederholen
        $mailRepeat = new Zend_Form_Element_Text('mail_repeat');
        $mailRepeat->setLabel('Email wiederholen:')
                   ->setRequired(true)
                   ->addFilter('StringTrim')
                   ->addFilter('StripTags');


        // Password
        $pass = new Zend_Form_Element_Password('password');
        $pass->setLabel('Passwort:')
             ->setRequired(true)
             ->addFilter('StringTrim')
             ->addFilter('StripTags');

        // Password repeat
        $passRepeat = new Zend_Form_Element_Password('password_repeat');
        $passRepeat->setLabel('Passwort wiederholen:')
                   ->setRequired(true)
                   ->addFilter('StringTrim')
                   ->addFilter('StripTags');


		// Newsletter?
		$newletter = new Zend_Form_Element_Checkbox('newsletter');
		$newletter->setLabel('Newsletter erhalten?');

		// Submit Button
        $submit = new Zend_Form_Element_Submit('Speichern');
        $submit->setAttrib('id', 'register_submit');

        // Add Elements to Form
        $this->addElements(array($firstName,
                                 $lastName,
                                 $mail,
                                 $mailRepeat,
                                 $pass,
                                 $passRepeat,
								 $newletter,
                                 $submit
                            ));

    }
}

