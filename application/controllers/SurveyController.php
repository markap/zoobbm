<?php

class SurveyController extends Zend_Controller_Action {

    public function init() {
    }

	
	/**
	 * show a survey
	 */
    public function indexAction() {
		$surveyDb   = new Model_DbTable_Survey();	
		$surveySession = new Zend_Session_Namespace('survey');
		$isSurveyDone  = $surveySession->done;
		if ($isSurveyDone !== true) {
			$request 	= $this->getRequest();
			if ($request->isPost()) {
				$postValues = $request->getPost();
				if (!empty($postValues)) {
					// update data
					$surveyData = $surveyDb->getSurveyData();
					$selectedKey	= $postValues['survey'];	
					$selectedNumber = $surveyData[$selectedKey];
					$newNumber = (int)$selectedNumber + 1;
					$surveyDb->updateSurvey($selectedKey, $newNumber);
					$this->view->success = "Vielen Dank für Ihre Teilnahme an der Umfrage";
					$surveySession->done = true;
						
				} else { // nothing checked
					$this->view->error = "Bitte wählen Sie ein Tier aus, um an der Abstimmung teilzunehmen";
				}
			}
		} 


		$surveyData = $surveyDb->getSurveyData();
		$countVotes = 0;
		foreach ($surveyData as $number) {
			$countVotes = $countVotes + $number;
		}
		$surveyPercentage = array();
		foreach ($surveyData as $key => $number) {
			$percantage = $number/$countVotes * 100;	
			$surveyPercentage[$key] = (int)$percantage; 

		}

		$this->view->surveyPercentage = $surveyPercentage;
		$this->view->showSurvey       = $surveySession->done;
    }

}

