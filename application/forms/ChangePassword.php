<?php

/** 
 * Form class for creating a new password 
 * @package forms
 */
class Form_ChangePassword extends Zend_Form {

    /**
     * inits the form
     * 
     * @author Martin Kapfhammer 
     */
    public function init() {

        $this->setMethod('post');
		$this->setAttrib('id', 'register');

        // Username
        $user = new Zend_Form_Element_Text('user');
        $user->setLabel('Benutzername:')
             ->setRequired(true)
             ->addFilter('StringTrim')
             ->addFilter('StripTags');

		// Altes Password
        $oldPass = new Zend_Form_Element_Password('old_password');
        $oldPass->setLabel('Altes Passwort:')
             ->setRequired(true)
             ->addFilter('StringTrim')
             ->addFilter('StripTags');


        // Password
        $pass = new Zend_Form_Element_Password('password');
        $pass->setLabel('Neues Passwort:')
             ->setRequired(true)
             ->addFilter('StringTrim')
             ->addFilter('StripTags');

        // Password repeat
        $passRepeat = new Zend_Form_Element_Password('password_repeat');
        $passRepeat->setLabel('Neues Passwort wiederholen:')
                   ->setRequired(true)
                   ->addFilter('StringTrim')
                   ->addFilter('StripTags');


		// Submit Button
        $submit = new Zend_Form_Element_Submit('Speichern');
        $submit->setAttrib('id', 'register_submit');

        // Add Elements to Form
        $this->addElements(array(
                                 $user,
                                 $oldPass,
                                 $pass,
                                 $passRepeat,
                                 $submit
                            ));

    }
}

