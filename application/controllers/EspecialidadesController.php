<?php

class EspecialidadesController extends Zend_Controller_Action
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
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/especialidades.js');
        echo $this->view->headScript();
        $this->view->data = $this->tabla_especialidad();
        $this->view->data_pisos = $this->select_piso();
        
        $this->view->titulo="Especialidades Registradas"; 
        $this->view->icono = "fa-medkit";

    }
    public function crearAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $especialidad_nombre = $this->getRequest()->getParam('nombre');
            $piso_id = $this->getRequest()->getParam('piso');

            $table = new Application_Model_DbTable_Especialidades();
            $table->insertarespecialidad($especialidad_nombre,$piso_id);
            echo $this->tabla_especialidad();
        }


    }

    public function editarAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $piso_id = $this->getRequest()->getParam('piso');
            $especialidad_nombre = $this->getRequest()->getParam('nombre');
            $table = new Application_Model_DbTable_Especialidades();
            $table->actualizarespecialidad($id,$especialidad_nombre,$piso_id);
            echo $this->tabla_especialidad();
        }

    }
    public function eliminarAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $table = new Application_Model_DbTable_Especialidades();
            $table->eliminarespecialidad($id);
            echo $this->tabla_especialidad();
        }

    }
    public function select_piso(){
        $table_m = new Application_Model_DbTable_Pisos();
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
            $Listaarea .= '<label for="comboArea">Seleccione un piso:</label>';

            $Listaarea .= '<select class="form-control" name="comboPiso" id="comboPiso">';
            foreach ($datosarea as $item):
                $Listaarea .= "<option value='". $item->piso_id ."'>" . $item->piso_nombre . "</option>";
            endforeach;
            $Listaarea .= "</select></div>";
        }
        return $Listaarea;
    }
    public function tabla_especialidad()
    {
        $table_m = new Application_Model_DbTable_Especialidades();
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

            $cadena .= '<table class="table table-sm dataTable" id="dataTableEspecialidad" width="100%">
                <thead>
                <tr>
                    <th class="text-primary">ID</th>
                    <th class="text-primary">DESCRIPCION</th>
                    <th class="text-primary">PISO</th>
                    <th class="text-primary">AREA</th>
                    <th class="text-primary">ESTADO</th>
                    <th class="text-primary ">ACCION</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datos as $item):

                $cadena .= "<tr>";
                $cadena .= "<td>" . $item->especialidad_id . "</td>";
                $cadena .= "<td>" . $item->especialidad_nombre . "</td>";
                $cadena .= "<td>" . $item->piso_nombre . "</td>";
                $cadena .= "<td>" . $item->area_nombre . "</td>";

                $cadena .= '<td>Activa</td>
                    <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                    
                    <!--  debo enviar la busqueda por ajax -->
                    <button type="button" class="btn btn-outline-warning btn-sm " onclick="editarModal('. $item->especialidad_id .')" >
                        <i class="fa fa-edit  "></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('. $item->especialidad_id .')" >
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

