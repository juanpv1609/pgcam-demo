<?php

class AreasController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->initView();
        $this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->user = Zend_Auth::getInstance()->getIdentity();
        $this->view->controlador = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->view->accion = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        $this->view->titulo_formulario = "Area";
        $this->view->icono = "fa-table";

    }

    public function indexAction()
    {
        // $this->view->datos = $table->listar();
        //$table_m = new Application_Model_DbTable_Areas();
        //$datos=$table_m->listar();
        $this->view->data = $this->tabla_area();
        $this->view->titulo = "Areas Registradas";

    }

    public function crearAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $area_nombre = $this->getRequest()->getParam('nombre');

            $table = new Application_Model_DbTable_Areas();
            $table->insertararea($area_nombre);
            echo $this->tabla_area();
        }


    }
    
    public function editarAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $area_id = $this->getRequest()->getParam('id');
            $area_nombre = $this->getRequest()->getParam('nombre');
            $table = new Application_Model_DbTable_Areas();
            $table->actualizararea($area_id,$area_nombre);
            echo $this->tabla_area();
        }

    }
    public function eliminarAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $area_id = $this->getRequest()->getParam('id');
            $table = new Application_Model_DbTable_Areas();
            $table->eliminararea($area_id);
            echo $this->tabla_area();
        }

    }

    public function tabla_area()
    {
        $table_m = new Application_Model_DbTable_Areas();
        $datosarea = $table_m->listar();
        $Listaarea = '';
        if (!$datosarea) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {

            $Listaarea .= '<table class="table table-sm " id="dataTableAreas" width="100%">
                <thead>
                <tr>
                    <th class="text-primary">ID</th>
                    <th class="text-primary">DESCRIPCION</th>
                    <th class="text-primary">ESTADO</th>
                    <th class="text-primary ">ACCION</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datosarea as $item):

                $Listaarea .= "<tr>";
                $Listaarea .= "<td>" . $item->area_id . "</td>";
                $Listaarea .= "<td>" . $item->area_nombre . "</td>";

                $Listaarea .= '<td>Activa</td>
                    <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        
                        <!--  debo enviar la busqueda por ajax -->
                        <button type="button" class="btn btn-outline-warning btn-sm " onclick="editarModal('. $item->area_id .')" >
                            <i class="fa fa-edit  "></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('. $item->area_id .')" >
                            <i class="fa fa-trash "></i>
                        </button>
                        </div>
                        
                    </td>
                </tr>';
            endforeach;

            $Listaarea .= "</tbody></table>";
        }

        return $Listaarea;
    }

}



