<?php

class HabitacionesController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->initView();
       // $this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->user = Zend_Auth::getInstance()->getIdentity();
        $this->view->controlador=Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->view->accion=Zend_Controller_Front::getInstance()->getRequest()->getActionName();
    }

    public function indexAction()
    {
        // action body
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/habitaciones.js');
        echo $this->view->headScript();
        $this->view->data = $this->tabla_habitaciones();
        $this->view->data_especialidades = $this->select_piso();
        $this->view->titulo="Habitaciones Registradas";
        $this->view->icono = "fa-door-open";

    }
    public function crearAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $habitacion_nombre = $this->getRequest()->getParam('nombre');
            $especialidad = $this->getRequest()->getParam('especialidad');

            $table = new Application_Model_DbTable_Habitaciones();
            $table->insertarhabitacion($habitacion_nombre,$especialidad);
            echo $this->tabla_habitaciones();
        }


    }

    public function editarAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $especialidad = $this->getRequest()->getParam('especialidad');
            $habitacion_nombre = $this->getRequest()->getParam('nombre');
            $table = new Application_Model_DbTable_Habitaciones();
            $table->actualizarhabitacion($id,$habitacion_nombre,$especialidad);
            echo $this->tabla_habitaciones();
        }

    }
    public function eliminarAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $table = new Application_Model_DbTable_Habitaciones();
            $table->eliminarhabitacion($id);
            echo $this->tabla_habitaciones();
        }

    }
    public function select_piso(){
        $table_m = new Application_Model_DbTable_Especialidades();
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
            $Listaarea .= '<label for="comboEspecialidad">Seleccione una especialidad:</label>';

            $Listaarea .= '<select class="form-control" name="comboEspecialidad" id="comboEspecialidad">';
            foreach ($datosarea as $item):
                $Listaarea .= "<option value='". $item->especialidad_id ."'>" . $item->especialidad_nombre . "</option>";
            endforeach;
            $Listaarea .= "</select></div>";
        }
        return $Listaarea;
    }
    public function tabla_habitaciones()
    {
        $table_m = new Application_Model_DbTable_Habitaciones();
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

            $cadena .= '<table class="table table-sm dataTable" id="dataTableHabitaciones" width="100%">
                <thead>
                <tr>
                    <th class="text-primary">ID</th>
                    <th class="text-primary">DESCRIPCION</th>
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
                $cadena .= "<td>" . $item->habitacion_id . "</td>";
                $cadena .= "<td>" . $item->habitacion_nombre . "</td>";
                $cadena .= "<td>" . $item->especialidad_nombre . "</td>";
                $cadena .= "<td>" . $item->piso_nombre . "</td>";
                $cadena .= "<td>" . $item->area_nombre . "</td>";

                $cadena .= "<td>Activa</td>
                    <td>
                    <div class='btn-group' role='group' aria-label='Basic example'>
                    
                    <!--  debo enviar la busqueda por ajax -->
                    <button type='button' class='btn btn-outline-warning btn-sm ' 
                    onclick='editarModal(". $item->habitacion_id .",". $item->especialidad_id .",`". $item->habitacion_nombre ."`)' >
                        <i class='fas fa-edit  '></i>
                    </button>
                    <button type='button' class='btn btn-outline-danger btn-sm' onclick='eliminar(". $item->habitacion_id .")' >
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

