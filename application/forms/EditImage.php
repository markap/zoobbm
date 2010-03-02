<?php

/** 
 * Form class for the editing of the profile image 
 * @package forms
 */
class Form_EditImage extends Zend_Form {

    /**
     * inits the form
     * 
     * @author Martin Kapfhammer 
     */
    public function init() {

        $this->setMethod('post');
		$this->setAttrib('enctype', 'multipart/form-data');


		// Image Element
		$image = new Zend_Form_Element_File('image');
		$image->setLabel('Profil Bild:');
		$image->setDestination('/home/www/zooproject/public/img/uploads');
		$image->addValidator('Count', false, 1);
		$image->addValidator('Size', false, 204800);
		$image->addValidator('Extension', false, 'jpg,png,gif');

		// Submit Element
		$submit = new Zend_Form_Element_Submit('OK');
		$submit->setAttrib('id', 'submitbutton');
		


		$this->addElements(array($image, $submit));


    }
}

