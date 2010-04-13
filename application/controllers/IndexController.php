<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
		$stylesheet = $this->_getParam('style');
		$backLink 	= str_replace('_', '/', $this->_getParam('back'));
		if ($stylesheet && $backLink) {
			setcookie("style", $stylesheet, time()+36000, "/", ""); 
			$this->_redirect($backLink);
		}
    }

    public function findfrienddefaultAction()
    {
        // action body
    }


}





