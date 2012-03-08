<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initPropel()
    {

		require_once 'propel/Propel.php';
		Propel::init(APPLICATION_PATH . '/configs/blackjack-conf.php');

    }

    protected function _initJquery()
    {

		$this->bootstrap('view');
		$view = $this->getResource('view');

		$view->headScript()->appendFile('/js/vendor/jquery-1.6.min.js');

    }

    protected function _initTwitterBootstrap()
    {

        $this->bootstrap('jquery');
        $this->bootstrap('backbone');
		$this->bootstrap('view');
		$view = $this->getResource('view');

		//$view->headScript()->appendFile('/js/vendor/bootstrap.min.js');

		$view->headLink()->appendStylesheet('/css/vendor/bootstrap.min.css');
		$view->headLink()->appendStylesheet('/css/vendor/bootstrap-responsive.min.css');

		$view->headMeta()->appendName('viewport', 'width=device-width, initial-scale=1.0');

    }

    protected function _initBackbone()
    {

        $this->bootstrap('jquery');
		$this->bootstrap('view');
		$view = $this->getResource('view');

		$view->headScript()->appendFile('/js/vendor/json2.js');
		$view->headScript()->appendFile('/js/vendor/underscore.js');
		$view->headScript()->appendFile('/js/vendor/backbone.js');

    }

    protected function _initMetaTags()
    {

		$this->bootstrap('view');
		$view = $this->getResource('view');

		$view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');

    }

    protected function _initCss()
    {

		$this->bootstrap('view');
		$view = $this->getResource('view');

		$view->headLink()->appendStylesheet('/css/main.css');
        $view->headLink()->appendStylesheet('/css/cards.css');

    }

}
