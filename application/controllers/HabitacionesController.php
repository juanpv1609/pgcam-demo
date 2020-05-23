<?php

class HabitacionesController extends Zend_Controller_Action
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
        /* Initialize action controller here */
        $this->initView();
        // $this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->user = Zend_Auth::getInstance()->getIdentity();
        $this->view->controlador=Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->view->accion=Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        $this->view->icono = "fa-door-open";

    }
    /**
     * indexAction()
     * * Esta accion lista las especialidades de la bbd
     * ! importamos el archivo habitaciones.js
     * @param data obtiene la data del metodo tabla_habitaciones()
     * @param data_especialidades obtiene la data del metodo select_especialidades()
     * @param titulo almacena el nombre de la vista, se mostrara en el titulo de la pagina
     */

    public function indexAction()
    {
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/habitaciones.js');
        echo $this->view->headScript();
        $this->view->data = $this->tabla_habitaciones();
        $this->view->data_especialidades = $this->select_especialidades();
        $this->view->titulo="Habitaciones Registradas";
    }
    /**
     * crearAction()
     * * Esta accion crea nuevas habitaciones
     * ! obtiene los datos mediante llamada ajax
     * @param habitacion_nombre obtiene el valor enviado por ajax
     * @param especialidad_id obtiene el valor enviado por ajax
     * @param obj Crea un objeto tipo DbTable py realiza el metodo insertararea
     */

    public function crearAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $habitacion_nombre = $this->getRequest()->getParam('nombre');
            $especialidad = $this->getRequest()->getParam('especialidad');
            $obj = new Application_Model_DbTable_Habitaciones();
            $obj->insertarhabitacion($habitacion_nombre, $especialidad);
            echo $this->tabla_habitaciones();
        }
    }
    /**
     * editarAction()
     * * Esta accion edita habitaciones
     * ! obtiene los datos mediante llamada ajax
     * @param id obtiene el valor enviado por ajax
     * @param habitacion_nombre obtiene el valor enviado por ajax
     * @param especialidad obtiene el valor enviado por ajax
     * @param obj Crea un objeto tipo DbTable py realiza el metodo insertararea
     */

    public function editarAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $especialidad = $this->getRequest()->getParam('especialidad');
            $habitacion_nombre = $this->getRequest()->getParam('nombre');
            $obj = new Application_Model_DbTable_Habitaciones();
            $obj->actualizarhabitacion($id, $habitacion_nombre, $especialidad);
            echo $this->tabla_habitaciones();
        }
    }
    /**
     * eliminarAction()
     * * Esta accion elimina habitaciones
     * ! obtiene los datos mediante llamada ajax
     * TODO: controlar que si tiene dependencia en la bdd NO ELIMINAR
     * @param id obtiene el valor enviado por ajax
     * @param obj Crea un objeto tipo DbTable py realiza el metodo eliminararea
     */

    public function eliminarAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $obj = new Application_Model_DbTable_Habitaciones();
            $obj->eliminarhabitacion($id);
            echo $this->tabla_habitaciones();
        }
    }
    /**
     * select_especialidades()
     * * Esta funcion crea el template SELECT HTML que sera mostrado en el view
     * * Obtiene una lista de las especialidades
     * ! se ejecuta despues de: index, crear, editar, eliminar
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
     */

    public function select_especialidades()
    {
        $obj = new Application_Model_DbTable_Especialidades();
        $datosarea = $obj->listar();
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

            $Listaarea .= '<select class="custom-select" name="comboEspecialidad" id="comboEspecialidad">';
            foreach ($datosarea as $item):
                $Listaarea .= "<option value='". $item->especialidad_id ."'>" . $item->especialidad_nombre . "</option>";
            endforeach;
            $Listaarea .= "</select></div>";
        }
        return $Listaarea;
    }
    /**
     * tabla_habitaciones()
     * * Esta funcion crea el template HTML que sera mostrado en el view
     * ! se ejecuta despues de: index, crear, editar, eliminar
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
     */

    public function tabla_habitaciones()
    {
        $obj = new Application_Model_DbTable_Habitaciones();
        $datos = $obj->listar();
        $cadena = '';
        if (!$datos) {
            $cadena .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            $cadena .= '<table class="table  table-bordered table-sm dataTable" id="dataTableHabitaciones" width="100%">
                <thead class="table-dark" >
                <tr>
                    <th >ID</th>
                    <th >DESCRIPCION</th>
                    <th >ESPECIALIDAD</th>
                    <th >PISO</th>
                    <th >AREA</th>
                    <th >ESTADO</th>
                    <th >ACCION</th>
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
                    <button type='button' class='btn btn-outline-dark btn-sm  border-0 ' 
                    onclick='editarModal(". $item->habitacion_id .",". $item->especialidad_id .",`". $item->habitacion_nombre ."`)' >
                        <i class='far fa-edit  '></i>
                    </button>
                    <button type='button' class='btn btn-outline-danger btn-sm border-0 ' onclick='eliminar(". $item->habitacion_id .")' >
                        <i class='far fa-trash-alt'></i>
                    </button>
                    </div>
                    </td>
                </tr>";
            endforeach;

            $cadena .= "</tbody></table>";
        }

        return $cadena;
    }
  /**
     * preDispatch()
     * * Funcion para validacion de autenticacion
     * * Controla tambien el permiso de usuario a las diferentes rutas
     * ! se ejecuta antes de cualquier accion
     * @param auth obtiene el usuario que esta autenticado
     * @param controlador,accion almacena el nombre del controlador y de la accion respectivamente
     * @param obj crea un objeto tipo usuario 
     * @param permisos consulta los permisos de usuario desde la bdd
     */

    public function preDispatch()
    {
        $auth = Zend_Auth::getInstance();
        $controlador=Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $accion=Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        if (!$auth->hasIdentity()) {                                /* Si no existe una sesion activa: redirige al login*/
            $this->_redirect("iniciar_sesion");

        } elseif ($auth->hasIdentity()) {
            $user = $auth->getIdentity();
            $obj = new Application_Model_DbTable_Permisos();
            $permisos = $obj->listar_permisos_usuario($user->perf_id);
            /**
             * * compara los permisos del usuario de la base de datos
             * * con la ruta actual, si no tiene acceso, envia a una pagina de error.
             */
            foreach ($permisos as $item) {                  
                if ($item->ctrl_nombre == $controlador and
                        $item->accion_nombre == $accion and
                        $item->permiso == 'deny') {
                            $this->_redirect('error/error?msg=sitio'); //--envia a una pagina de error de permiso
                }
            }
        }
    }
}