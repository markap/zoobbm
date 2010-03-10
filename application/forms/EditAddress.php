<?php

/** 
 * Form class for the editing of the address data 
 * @package forms
 */
class Form_EditAddress extends Zend_Form {

    /**
     * inits the form
     * 
     * @author Martin Kapfhammer 
     */
    public function init() {

        $this->setMethod('post');

        // Phone  
        $phone = new Zend_Form_Element_Text('phone');
        $phone->setLabel('Telefonnummer:')
		  	  ->setRequired(true)
		      ->addFilter('StringTrim')
		      ->addFilter('StripTags');

		// Birthday  
        $birth = new Zend_Form_Element_Text('birth');
        $birth->setLabel('Geburtstag:')
		  	  ->setRequired(true)
		      ->addFilter('StringTrim')
		      ->addFilter('StripTags');

        // street
        $street = new Zend_Form_Element_Text('street');
        $street->setLabel('Straße:')
                 ->setRequired(true)
                 ->addFilter('StringTrim')
                 ->addFilter('StripTags');

		// number
        $number = new Zend_Form_Element_Text('number');
        $number->setLabel('Hausnummer:')
                 ->setRequired(true)
                 ->addFilter('StringTrim')
                 ->addFilter('StripTags');


  		// PLZ 
        $plz = new Zend_Form_Element_Text('plz');
        $plz->setLabel('Postleitzahl:')
             ->setRequired(true)
             ->addFilter('StringTrim')
             ->addFilter('StripTags');

		// city 
        $city = new Zend_Form_Element_Text('city');
        $city->setLabel('Stadt:')
             ->setRequired(true)
             ->addFilter('StringTrim')
             ->addFilter('StripTags');


		// Submit Button
        $submit = new Zend_Form_Element_Submit('Speichern');
        $submit->setAttrib('id', 'register_submit');

        // Add Elements to Form
        $this->addElements(array($phone,
								 $birth,
                                 $street,
                                 $number,
                                 $plz,
								 $city,
                                 $submit
                            ));

    }
}

