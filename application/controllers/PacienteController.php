<?php
class PacienteController extends Zend_Controller_Action
{
   
    public function init()
    {
        /* Initialize action controller here */
        $this->initView();
        //$this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->user = Zend_Auth::getInstance()->getIdentity();
        $this->view->controlador = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->view->accion = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        $this->view->titulo_formulario = "Paciente";
        $this->view->icono = "fa-procedures";
    }

    public function indexAction()
    {
        // action body
        $this->_helper->redirector('listar', 'paciente'); //direccionamos al listar

    }

    public function listarAction()
    {
        $this->view->titulo = "Lista de pacientes";
        

    }

    public function registrarAction()
    {
        $this->view->titulo = "Registro de admisión";
        

    }

    public function asignarAction()
    {
        $this->view->titulo = "Formulario de asignacion de cama";
        
    }

    public function cambioAction()
    {
        $this->view->titulo = "Cambio / Egreso de paciente";
        
    }


}













