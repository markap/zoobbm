<?php

/** 
 * Form class for the booking of tickets 
 * @package forms
 */
class Form_Booking extends Zend_Form {

    /**
     * inits the form
     * 
     * @author Martin Kapfhammer 
     */
    public function init() {

        $this->setMethod('post');

       // Startdate 
        $startdate = new Zend_Form_Element_Text('startdate');
        $startdate->setLabel('Start:')
                  ->setRequired(true)
			  	  ->setAttrib('readonly', 'readonly')
                  ->addFilter('StringTrim')
                  ->addFilter('StripTags');

 		// Enddate
        $enddate = new Zend_Form_Element_Text('enddate');
        $enddate->setLabel('Ende: (Nur wenn mehrtägig)')
                  ->setRequired(true)
			  	  ->setAttrib('readonly', 'readonly')
                  ->addFilter('StringTrim')
				  ->addFilter('StripTags');
	
		$range = range(0, 100);

		// Adults 
		$adults = new Zend_Form_Element_Select('adult');
		$adults->setLabel('Anzahl Erwachsener:')
			 ->setMultiOptions($range);

		// Childs 
		$childs = new Zend_Form_Element_Select('child');
		$childs->setLabel('Anzahl Kinder:')
			 ->setMultiOptions($range);

		// Students and so on ... 
		$students = new Zend_Form_Element_Select('student');
		$students->setLabel('Anzahl:')
			 ->setMultiOptions($range);

        // description
        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Sonstige Informationen:')
			  ->setAttribs(array('cols' => 32, 'rows' => 3))
		  	  ->setRequired(true)
		      ->addFilter('StringTrim')
		      ->addFilter('StripTags');


        // Add Elements to Form
        $this->addElements(array($startdate,
								 $enddate,
								 $adults,
								 $childs,
								 $students,
								 $description,
                            ));

    }

}

