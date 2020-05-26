<?php

class ReportesController extends Zend_Controller_Action
{

    /**
     * init()
     * * Esta funcion se ejecuta antes de cualquier action
     * ! Se pueden setear variables globales para verlas en las views
     * ? 
     * TODO: ninguna
     * @param user almacena los datos de sesion
     * @param controlador,accion almacena el nombre del controlador y de la accion respectivamente
     */
    public function init()
    {
        $this->initView();
        $this->view->user = Zend_Auth::getInstance()->getIdentity();
        $this->view->controlador = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->view->accion = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        $this->view->titulo_formulario = "Reportes";
        $this->view->icono = "fa-chart-line";        
    }
    /**
     * indexAction()
     * * Esta accion lista las areas de la bbd
     * ! importamos el archivo areas.js
     * @param data obtiene la data del metodo tabla_area()
     * @param titulo almacena el nombre de la vista, se mostrara en el titulo de la pagina
     */
    public function indexAction()
    {
        //$this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/areas.js');
        //echo $this->view->headScript();
        //$this->view->data = $this->tabla_area();
        $this->view->titulo="Reportes";
    }


}

