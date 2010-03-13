<?php

class AmazonController extends Zend_Controller_Action {

    public function init() {
    }

	
	/**
	 * show the books from amazon
	 */
    public function indexAction() {

		// Connect to AmazonWebservice 
		$query = new Zend_Service_Amazon(
							'AKIAI3S5LPYDW7CCEXFQ',
							'DE',
							'+vabmr8+C4eyYe5DbmISmHsBZc9NOHVX/QbK6WM9'
 		); 
		$results = $query->itemSearch(array(
								'SearchIndex' => 'Books',
								'Keywords'	  => 'tiere',
								'ResponseGroup' => 'Medium'
						));
 
		$books = array();
    	foreach ($results as $result) {
			$books[] = array(
				'title'  => $result->Title,
				'link'	 => $result->DetailPageURL,
				'image'  => $result->MediumImage
							? $result->MediumImage->Url->getUri() : ''
			);
      	}
		$this->view->books = $books;
    }



}

