<?php

class GameController extends Zend_Controller_Action
{

    /**
     * @var Zend_Session_Namespace
     * 
     */
    protected $gameSession = null;

    public function init()
    {
        $this->gameSession = new Zend_Session_Namespace('game');
    }

    public function indexAction()
    {
        $questionIds = array(1,2,3,4,5);

        // use always the same game object
        if ($this->gameSession->game === null) {
            $this->gameSession->game = new Model_Game($questionIds);
        }
        $game = $this->gameSession->game;


        // refresh browser -> answer wrong
        if ($this->gameSession->waitForAnswer === true) {
            $game->getScore()->addWrongAnswer($game->getQuestion()->getQuestionId());
        }
        $this->gameSession->waitForAnswer = true;

        try {
            $question = $game->nextQuestion();
            $this->view->question   = $question->getQuestion();
            $this->view->answers    = $question->getAnswers();

            $this->view->playedQuestions = $game->getScore()->getPlayedQuestions();
            $this->view->rightAnswers = $game->getScore()->getRightAnswers();
            $this->view->wrongAnswers = $game->getScore()->getWrongAnswers();
        }
        catch (Model_Exception_GameEnd $e) {    // no more question available
            $this->gameSession->result = $game->getScore();
            $this->gameSession->game   = null;
            $this->gameSession->waitForAnswer = false;
            $this->_redirect('/game/result');
        }
        catch (Model_Exception_QuestionNotFound $e) {    // question does not exist
            $this->gameSession->waitForAnswer = false;
            error_log('question not found|' . $e->getId() . '|' . $e->getClassName());
            $this->view->pageNotFound = true;
            $this->view->errorId      = $e->getId();
            $this->view->className    = $e->getClassName();
        }
    }

    public function answerrequestAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {

            $this->_helper->layout->disableLayout();
            $selectedAnswerHash = $this->_getParam('answer');
            $game = $this->gameSession->game;
            $question = $game->getQuestion();
            $this->view->isAnswerRight = $game->checkAnswer($selectedAnswerHash);
            $this->view->myAnswer = $question->getAnswer($selectedAnswerHash);
            $this->view->rightAnswer = $question->getRightAnswer();

            $this->view->playedQuestions = $game->getScore()->getPlayedQuestions();
            $this->view->rightAnswers = $game->getScore()->getRightAnswers();
            $this->view->wrongAnswers = $game->getScore()->getWrongAnswers();

            $this->gameSession->waitForAnswer = false;
            $this->gameSession->oneBrowser = true;
            $this->gameSession->setExpirationHops(1, 'oneBrowser');

        } else {
            $this->_redirect('/question');
        }
    }

    public function resultAction()
    {
        $score = $this->gameSession->result;
        $this->view->playedQuestions = $score->getPlayedQuestions();
        $this->view->rightAnswers = $score->getRightAnswers();
        $this->view->wrongAnswers = $score->getWrongAnswers();
    }

    public function timeoverAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {

            $this->_helper->layout->disableLayout();
            $game = $this->gameSession->game;
            $question = $game->getQuestion();
            $this->view->rightAnswer = $question->getRightAnswer();

            $game->getScore()->addWrongAnswer($question->getQuestionId());
            $this->view->playedQuestions = $game->getScore()->getPlayedQuestions();
            $this->view->rightAnswers = $game->getScore()->getRightAnswers();
            $this->view->wrongAnswers = $game->getScore()->getWrongAnswers();

            $this->gameSession->waitForAnswer = false;
            $this->gameSession->oneBrowser = true;
            $this->gameSession->setExpirationHops(1, 'oneBrowser');

        } else {
            $this->_redirect('/question');
        }
    }


}





