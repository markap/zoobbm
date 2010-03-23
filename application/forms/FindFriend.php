<?php

/** 
 * Form class for finding friends 
 * @package forms
 */
class Form_FindFriend extends Zend_Form {

    /**
     * inits the form
     * 
     * @author Martin Kapfhammer 
     */
    public function init() {

        $this->setMethod('post');

        // Name  
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name:')
		  	  ->setRequired(true)
		      ->addFilter('StringTrim')
		      ->addFilter('StripTags');

		// age 
		$age = new Zend_Form_Element_Select('age');
		$age->setLabel('Ungefähres Alter:')
			->setMultiOptions(array(0 => '',
									1 => '15 Jahre oder jünger',
								    2 => 'Zwischen 15 - 20 Jahren',
								    3 => 'Zwischen 20 - 25 Jahren',	
								    4 => 'Zwischen 25 - 30 Jahren',	
								    5 => 'Zwischen 30 - 40 Jahren',	
								    6 => 'Zwischen 40 - 50 Jahre',
								    7 => '50 Jahre oder älter'
								));

		
		// favourite animals
		$animals = new Zend_Form_Element_MultiCheckbox('animal');
		$animals->setLabel('Lieblingstiere');

		// Type of User
		$type = new Zend_Form_Element_MultiCheckbox('type');
		$type->setLabel('Mein Typ:');

		// visits 
		$visit = new Zend_Form_Element_MultiCheckbox('visit');
		$visit->setLabel('Wie oft besuche ich den Mannheimer Zoo?');


		// Submit Button
        $submit = new Zend_Form_Element_Submit('Suchen');
        $submit->setAttrib('id', 'fiend_friend');

        // Add Elements to Form
        $this->addElements(array($name,
								 $age,
								 $animals,
								 $type,
								 $visit,
                                 $submit
                            ));

    }


	/** 
	 * converts data from mysql in the right format
	 *
	 * @author Martin Kapfhammer
	 *
 	 * @param array $options options of the later form element
	 * @param string $id name of the db id-column
	 * @param string $name name of the db name-column
	 * @return array $ret options in the right format
	 */
	public function convertOptions(array $options, $id, $name) {
		foreach ($options as $option) {
			$ret[$option[$id]] = $option[$name];
		}
		return $ret;
	}

}

