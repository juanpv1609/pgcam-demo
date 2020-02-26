<?php

class Cie10Controller extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->initView();
        $this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->user = Zend_Auth::getInstance()->getIdentity();
        $this->view->controlador=Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->view->accion=Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        $this->view->icono = "fa-heartbeat";

    }

    public function indexAction()
    {
        $this->_helper->redirector('descripcion', 'cie10'); //direccionamos al menu de inicio

    }

    public function descripcionAction()
    {
        // action body
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/cie10.js');
        echo $this->view->headScript();
        $this->view->titulo = "Diagnosticos por descripcion";
        

    }

    public function categoriaAction()
    {
        // action body
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/cie10.js');
        echo $this->view->headScript();
        $this->view->titulo = "Diagnosticos por categoria";

    }

    public function capituloAction()
    {
        // action body
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/cie10.js');
        echo $this->view->headScript();
        $this->view->titulo = "Diagnosticos por capitulo";
        $this->view->data = $this->tabla_capitulo();


    }
    public function buscaAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $dato = $this->getRequest()->getParam('dato');
            echo $this->tabla_cie10($dato);

        }
    }
    public function buscacategoriaAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $dato = $this->getRequest()->getParam('dato');
            echo $this->tabla_cie10_categoria($dato);

        }
    }
    public function tabla_cie10($dato)
    {
        $table_m = new Application_Model_DbTable_Cie10();
        $datos = $table_m->listar($dato);
        $cadena = '';
        if (!$datos) {
            $cadena .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {

            $cadena .= '<table class="table table-sm" id="dataTableCie10" width="100%">
                <thead>
                <tr>
                    <th class="text-primary">COD</th>
                    <th class="text-primary">DESCRIPCION SUB CATEGORIA</th>
                    <th class="text-primary ">ACCION</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datos as $item):

                $cadena .= "<tr>";
                $cadena .= "<td>" . $item->sub_cod . "</td>";
                $cadena .= "<td>" . $item->descripcion_sub . "</td>";

                $cadena .= '<td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                    
                    <!--  debo enviar la busqueda por ajax -->
                    <button type="button" class="btn btn-outline-warning btn-sm " onclick="editarModal('. $item->piso_id .')" >
                        <i class="fa fa-edit  "></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('. $item->piso_id .')" >
                        <i class="fa fa-trash "></i>
                    </button>
                    </div>
                    </td>
                </tr>';
            endforeach;

            $cadena .= "</tbody></table>";
        }

        return $cadena;
    }
    public function tabla_cie10_categoria($dato)
    {
        $table_m = new Application_Model_DbTable_Cie10();
        $datos = $table_m->listar_categoria($dato);
        $cadena = '';
        if (!$datos) {
            $cadena .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {

            $cadena .= '<table class="table table-sm" id="dataTableCie10" width="100%">
                <thead>
                <tr>
                    <th class="text-primary">COD</th>
                    <th class="text-primary">DESCRIPCION CATEGORIA</th>
                    <th class="text-primary ">ACCION</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datos as $item):

                $cadena .= "<tr>";
                $cadena .= "<td>" . $item->cod . "</td>";
                $cadena .= "<td>" . $item->descripcion . "</td>";

                $cadena .= '<td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                    
                    <!--  debo enviar la busqueda por ajax -->
                    <button type="button" class="btn btn-outline-warning btn-sm " onclick="editarModal('. $item->cod .')" >
                        <i class="fa fa-edit  "></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('. $item->cod .')" >
                        <i class="fa fa-trash "></i>
                    </button>
                    </div>
                    </td>
                </tr>';
            endforeach;

            $cadena .= "</tbody></table>";
        }

        return $cadena;
    }
    public function tabla_capitulo()
    {
        $table_m = new Application_Model_DbTable_Cie10();
        $datos = $table_m->listar_capitulo();
        $cadena = '';
        if (!$datos) {
            $cadena .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {

            $cadena .= '<table class="table table-sm" id="dataTableCie10Capitulo" width="100%">
                <thead>
                <tr>
                    <th class="text-primary">COD</th>
                    <th class="text-primary">CAPITULO</th>
                    <th class="text-primary ">ACCION</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datos as $item):

                $cadena .= "<tr>";
                $cadena .= "<td>" . $item->cie10_capitulo_id . "</td>";
                $cadena .= "<td>" . $item->descripcion . "</td>";

                $cadena .= '<td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                    
                    <!--  debo enviar la busqueda por ajax -->
                    <button type="button" class="btn btn-outline-warning btn-sm " onclick="editarModal('. $item->cie10_capitulo_id .')" >
                        <i class="fa fa-edit  "></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('. $item->cie10_capitulo_id .')" >
                        <i class="fa fa-trash "></i>
                    </button>
                    </div>
                    </td>
                </tr>';
            endforeach;

            $cadena .= "</tbody></table>";
        }

        return $cadena;
    }
    


}









