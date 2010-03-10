<?php

class AmazonController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		// Verbindung zum AmazonWebservice aufnehmen
		$query = new Zend_Service_Amazon_Query(
							'AKIAI3S5LPYDW7CCEXFQ',
							'DE',
							'+vabmr8+C4eyYe5DbmISmHsBZc9NOHVX/QbK6WM9'
 		); 
      	$query->category('Books')->Keywords('tiere');
      	$results = $query->search();
 
		$books = array();
    	foreach ($results as $result) {
			$books[] = array(
				'title'  => $result->Title,
				'image'  => $result->DetailPageURL
			);
      	}

		$this->view->books = $books;


    }









}

