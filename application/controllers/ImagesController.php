<?php

class ImagesController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
	{
		// get categories from db    
		$categoryDb = new Model_DbTable_Category();
		$categories   = $categoryDb->getCategories();

		// new form 
		$form = new Form_ImagePicker();
		$form->setCategories($categories)->init();
		
		$searchKey = 'zoo, tierpark'; //default search key

		// check request
		$request = $this->getRequest();	
		if ($request->isPost()) {

			$postValues = $request->getPost();
			$searchKey  = $postValues['category'] . ', zoo';
		}

		// connect to Flickr
		$flickr = new Zend_Service_Flickr('dc57ebb47b856c6d62f47c098d88388e');
		$images = $flickr->tagSearch($searchKey, array('per_page' => 32));

		$imageList = array();
		foreach ($images as $image) {
			$imageList[] = array(
				'title' => $image->title,
				'url'   => $image->Small->clickUri,
				'image' => $image->Small->uri
			);
	
		}
		
		$this->view->imageList = $imageList;
		$this->view->form 	   = $form;
    }


}

