<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->initView();
        $this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->user = Zend_Auth::getInstance()->getIdentity();
        $this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->controlador=Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->view->accion=Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        
    }

    public function indexAction()
    {
        // action body
        $this->view->estado_activo=$datos="active";
        $this->view->titulo="Index";
    }


}

