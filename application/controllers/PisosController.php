<?php

class PisosController extends Zend_Controller_Action
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
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/pisos.js');
        echo $this->view->headScript();
        $this->view->data = $this->tabla_piso();
        $this->view->data_area = $this->select_area();
        $this->view->titulo="Pisos Registrados";
        $this->view->icono = "fa-layer-group";

    }

    public function crearAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $piso_nombre = $this->getRequest()->getParam('nombre');
            $area_id = $this->getRequest()->getParam('area');

            $table = new Application_Model_DbTable_Pisos();
            $table->insertarpiso($piso_nombre,$area_id);
            echo $this->tabla_piso();
        }


    }

    public function editarAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $area_id = $this->getRequest()->getParam('area');
            $piso_nombre = $this->getRequest()->getParam('nombre');
            $table = new Application_Model_DbTable_Pisos();
            $table->actualizarpiso($id,$piso_nombre,$area_id);
            echo $this->tabla_piso();
        }

    }
    public function eliminarAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $table = new Application_Model_DbTable_Pisos();
            $table->eliminarpiso($id);
            echo $this->tabla_piso();
        }

    }
    public function select_area(){
        $table_m = new Application_Model_DbTable_Areas();
        $datosarea = $table_m->listar();
        $Listaarea = '<div  class="form-group">';
        if (!$datosarea) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                </div>';
        } else {
            $Listaarea .= '<label for="comboArea">Seleccione un area:</label>';

            $Listaarea .= '<select class="form-control" name="comboArea" id="comboArea">';
            foreach ($datosarea as $item):
                $Listaarea .= "<option value='". $item->area_id ."'>" . $item->area_nombre . "</option>";
            endforeach;
            $Listaarea .= "</select></div>";
        }
        return $Listaarea;
    }
    public function tabla_piso()
    {
        $table_m = new Application_Model_DbTable_Pisos();
        $datos = $table_m->listar();
        $cadena = '';
        if (!$datos) {
            $cadena .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {

            $cadena .= '<table class="table table-sm" id="dataTablePisos" width="100%">
                <thead>
                <tr>
                    <th class="text-primary">ID</th>
                    <th class="text-primary">DESCRIPCION</th>
                    <th class="text-primary">AREA</th>
                    <th class="text-primary">ESTADO</th>
                    <th class="text-primary ">ACCION</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datos as $item):

                $cadena .= "<tr>";
                $cadena .= "<td>" . $item->piso_id . "</td>";
                $cadena .= "<td>" . $item->piso_nombre . "</td>";
                $cadena .= "<td>" . $item->area_nombre . "</td>";

                $cadena .= '<td>Activa</td>
                    <td>
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
}

