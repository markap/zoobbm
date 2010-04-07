<?php

/** 
 * Form class for finding friends 
 * @package forms
 */
class Form_FindFriendByName extends Zend_Form {

    /**
     * inits the form
     * 
     * @author Martin Kapfhammer 
     */
    public function init() {

        $this->setMethod('post');

        // Name  
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name eingeben:')
		  	  ->setRequired(true)
		      ->addFilter('StringTrim')
		      ->addFilter('StripTags');

        $this->addElements(array($name,
                            ));

    }

}

