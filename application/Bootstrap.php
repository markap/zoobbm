<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	/**
	 * Allows autoloading 
	 * so you have not to include classes
	 * 
	 * @author Martin Kapfhammer
	 */
	 protected function _initAutoload() {
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'  => APPLICATION_PATH
        ));
        return $moduleLoader;
    }


}

