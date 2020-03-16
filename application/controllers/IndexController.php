<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->initView();
        //$this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->user = Zend_Auth::getInstance()->getIdentity();
        //$this->view->controlador="Dashboard";
        $this->view->controlador=Zend_Controller_Front::getInstance()->getRequest()->getControllerName();

        $this->view->accion=Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        $this->view->icono = "fa-chart-pie";
        //$this->view->controlador=Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        //$this->view->accion=Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        //$this->view->proyecto=$this->_request->getBasePath();
      
    }

    public function indexAction()
    {
        // action body
        $this->view->titulo="Index";
    }

    public function dashboardAction()
    {
        // action body
        $this->view->titulo="Dashboard";
        $this->view->controlador="Dashboard";

        $this->view->accion="Index";

    }


}



