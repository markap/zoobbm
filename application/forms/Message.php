<?php

/** 
 * Form class for sending private messages 
 * @package forms
 */
class Form_Message extends Zend_Form {

    /**
     * inits the form
     * 
     * @author Martin Kapfhammer 
     */
    public function init() {

        $this->setMethod('post');

		// subject
		$subject = new Zend_Form_Element_Text('subject');
		$subject->setLabel('Betreff')
                 ->setRequired(true)
                 ->addFilter('StripTags')
                 ->addFilter('StringTrim')
                 ->addValidator('NotEmpty');

	
        // message
        $message = new Zend_Form_Element_Textarea('message');
        $message->setLabel('Text:')
			  ->setAttribs(array('cols' => 32, 'rows' => 3))
		  	  ->setRequired(true)
		      ->addFilter('StringTrim')
		      ->addFilter('StripTags');

		// Submit Button
        $submit = new Zend_Form_Element_Submit('Senden');
        $submit->setAttrib('id', 'message_submit');

        // Add Elements to Form
        $this->addElements(array($subject,
                                 $message,
                                 $submit
                            ));

    }

}

