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
        $this->view->titulo = "Diagnosticos por descripcion";

    }

    public function categoriaAction()
    {
        // action body
        $this->view->titulo = "Diagnosticos por categoria";

    }

    public function capituloAction()
    {
        // action body
        $this->view->titulo = "Diagnosticos por capitulo";

    }
    public function tabla_cie10()
    {
        $table_m = new Application_Model_DbTable_Cie10();
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

            $cadena .= '<table class="table table-sm" id="dataTableCie10" width="100%">
                <thead>
                <tr>
                    <th class="text-primary">COD</th>
                    <th class="text-primary">DESCRIPCION</th>
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

    


}







