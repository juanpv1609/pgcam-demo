<?php

class CamasController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
        $this->initView();
        //$this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->user = Zend_Auth::getInstance()->getIdentity();
        $this->view->controlador=Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->view->accion=Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        $this->view->icono = "fa-bed";
    }

    public function indexAction()
    {
        // action body
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/camas.js');
        echo $this->view->headScript();
        $this->view->data = $this->tabla_camas();
        $this->view->data_habitaciones = $this->select_habitacion();
        $this->view->data_estado_cama = $this->select_estado_cama();
        $this->view->titulo="Camas Registradas";
    }
    public function crearAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $cama_nombre = $this->getRequest()->getParam('nombre');
            $habitacion = $this->getRequest()->getParam('habitacion');
            $cama_estado = $this->getRequest()->getParam('cama_estado');

            $table = new Application_Model_DbTable_Camas();
            $table->insertarcama($cama_nombre, $habitacion, $cama_estado);
            echo $this->tabla_camas();
        }
    }

    public function editarAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $habitacion = $this->getRequest()->getParam('habitacion');
            $cama_nombre = $this->getRequest()->getParam('nombre');
            $cama_estado = $this->getRequest()->getParam('cama_estado');

            $table = new Application_Model_DbTable_Camas();
            $table->actualizarcama($id, $cama_nombre, $habitacion, $cama_estado);
            echo $this->tabla_camas();
        }
    }
    public function eliminarAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $table = new Application_Model_DbTable_Camas();
            $table->eliminarcama($id);
            echo $this->tabla_camas();
        }
    }
    public function select_habitacion()
    {
        $table_m = new Application_Model_DbTable_Habitaciones();
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
            $Listaarea .= '<label for="comboHabitacion">Seleccione una habitacion:</label>';

            $Listaarea .= '<select class="custom-select" name="comboHabitacion" id="comboHabitacion">';
            foreach ($datosarea as $item):
                $Listaarea .= "<option value='". $item->habitacion_id ."'>" . $item->habitacion_nombre . "</option>";
            endforeach;
            $Listaarea .= "</select></div>";
        }
        return $Listaarea;
    }
    public function select_estado_cama()
    {
        $table_m = new Application_Model_DbTable_Camas();
        $datosarea = $table_m->listar_estado_cama();
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
            $Listaarea .= '<label for="comboEstadoCama">Estado de la cama:</label>';

            $Listaarea .= '<select class="custom-select" name="comboEstadoCama" id="comboEstadoCama">';
            foreach ($datosarea as $item):
                $Listaarea .= "<option value='". $item->cama_estado_id ."'>" . $item->cama_estado_descripcion . "</option>";
            endforeach;
            $Listaarea .= "</select></div>";
        }
        return $Listaarea;
    }
    public function tabla_camas()
    {
        $table_m = new Application_Model_DbTable_Camas();
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
            $cadena .= '<table class="table table-sm dataTable" id="dataTableCamas" width="100%">
                <thead>
                <tr>
                    <th class="text-primary">ID</th>
                    <th class="text-primary">DESCRIPCION</th>
                    <th class="text-primary">HABITACION</th>
                    <th class="text-primary">ESPECIALIDAD</th>
                    <th class="text-primary">PISO</th>
                    <th class="text-primary">AREA</th>
                    <th class="text-primary">ESTADO</th>
                    <th class="text-primary ">ACCION</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datos as $item):

                $cadena .= "<tr>";
            $cadena .= "<td>" . $item->cama_id . "</td>";
            $cadena .= "<td>Cama " . $item->cama_nombre . "</td>";
            $cadena .= "<td>" . $item->habitacion_nombre . "</td>";
            $cadena .= "<td>" . $item->especialidad_nombre . "</td>";
            $cadena .= "<td>" . $item->piso_nombre . "</td>";
            $cadena .= "<td>" . $item->area_nombre . "</td>";

            $cadena .= "<td>". $item->cama_estado_descripcion ."</td>
                <td>
                <div class='btn-group' role='group' aria-label='Basic example'>
                
                <!--  debo enviar la busqueda por ajax -->
                <button type='button' class='btn btn-outline-dark btn-sm border-0 ' 
                onclick='editarModal(". $item->cama_id .",". $item->habitacion_id .",". $item->cama_estado .",`". $item->cama_nombre ."`)' >
                    <i class='fas fa-edit  '></i>
                </button>
                <button type='button' class='btn btn-outline-danger btn-sm border-0' onclick='eliminar(". $item->cama_id .")' >
                    <i class='fas fa-trash '></i>
                </button>
                </div>
                </td>
                </tr>";
            endforeach;

            $cadena .= "</tbody></table>";
        }

        return $cadena;
    }
}
