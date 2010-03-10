<?php


/**
 * Form Class for the ImagePicking 
 * @package forms
 */
class Form_ImagePicker extends Zend_Form {

	/**
	 * @var array
	 */
	protected $categories = array(); 


	/**
	 * inits the form
	 *
	 * @author Martin Kapfhammer
	 */
	public function init() {

		$this->setMethod('post');

		// animal select box
		$select = new Zend_Form_Element_Select('category');
		$select->setLabel('Kategorie');
		$select->setMultiOptions($this->categories);

        //submit
        $submit = new Zend_Form_Element_Submit('OK');
        $submit->setAttrib('id', 'submitbutton');


        //Zu gemeinsamer Form zusammenfügen
        $this->addElements(array($select, $submit, $submit));
	}

	/**
	 * Setter for the categories
	 * provides right format for select box
	 * 
	 * @author Martin Kapfhammer
	 * @param array $categories
	 * @return $this -> fluent interface
	 */
	public function setCategories(array $categories) {

		$this->categories['zoo, tierpark'] = 'Alle Tiere'; 
		foreach ($categories as $category) {
			$this->categories[$category['name']] = $category['name']; 
		}

		return $this;
	}



}


