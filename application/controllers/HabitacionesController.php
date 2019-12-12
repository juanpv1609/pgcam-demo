<?php

class HabitacionesController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->initView();
        $this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->user = Zend_Auth::getInstance()->getIdentity();
        $this->view->controlador=Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->view->accion=Zend_Controller_Front::getInstance()->getRequest()->getActionName();
    }

    public function indexAction()
    {
        // action body
        $table_m = new Application_Model_DbTable_Habitaciones();
        //$datos=$table_m->listar();
        $this->view->data=$datos=$table_m->listar();
        $this->view->titulo="Habitaciones Registradas";
    }


}

