<?php

/** 
 * Form class for the editing of private data 
 * @package forms
 */
class Form_EditPrivate extends Zend_Form {

    /**
     * inits the form
     * 
     * @author Martin Kapfhammer 
     */
    public function init() {

        $this->setMethod('post');

        // description
        $description = new Zend_Form_Element_Textarea('description');
        $description->setLabel('Über mich:')
			  ->setAttribs(array('cols' => 32, 'rows' => 3))
		  	  ->setRequired(true)
		      ->addFilter('StringTrim')
		      ->addFilter('StripTags');

		// Type of User
		$type = new Zend_Form_Element_Select('type');
		$type->setLabel('Mein Typ:');

		// visits 
		$visit = new Zend_Form_Element_Select('visit');
		$visit->setLabel('Wie oft besuche ich den Mannheimer Zoo?');
		
		// favourite animals
		$animals = new Zend_Form_Element_MultiCheckbox('animal');
		$animals->setLabel('Lieblingstiere');


		// Submit Button
        $submit = new Zend_Form_Element_Submit('Speichern');
        $submit->setAttrib('id', 'register_submit');

        // Add Elements to Form
        $this->addElements(array($description,
								 $type,
								 $visit,
								 $animals,
                                 $submit
                            ));

    }

	/** 
	 * converts data from mysql in the right format
	 */
	public function convertOptions(array $options, $id, $name, $select = false) {
		if ($select === true) {
			$ret[0] = '';
		}
		foreach ($options as $option) {
			$ret[$option[$id]] = $option[$name];
		}
		return $ret;
	}

}
