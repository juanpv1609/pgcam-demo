<?php

class CamasController extends Zend_Controller_Action
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
        //$this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->user = Zend_Auth::getInstance()->getIdentity();
        $this->view->controlador=Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->view->accion=Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        $this->view->icono = "fa-bed";
    }
        /**
     * indexAction()
     * * Esta accion lista las especialidades de la bbd
     * ! importamos el archivo camas.js
     * @param data obtiene la data del metodo tabla_camas()
     * @param data_habitaciones obtiene la data del metodo select_habitacion()
     * @param data_estado_cama obtiene la data del metodo select_estado_cama()
     * @param titulo almacena el nombre de la vista, se mostrara en el titulo de la pagina
     */

    public function indexAction()
    {
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/camas.js');
        echo $this->view->headScript();
        $this->view->data = $this->tabla_camas();
        $this->view->data_habitaciones = $this->select_habitacion();
        $this->view->data_tipo = $this->select_tipo();

        $this->view->data_estado_cama = $this->select_estado_cama();
        $this->view->titulo="Camas Registradas";
    }
    /**
     * crearAction()
     * * Esta accion crea nuevas camas
     * ! obtiene los datos mediante llamada ajax
     * @param cama_nombre obtiene el valor enviado por ajax
     * @param habitacion obtiene el valor enviado por ajax
     * @param cama_estado obtiene el valor enviado por ajax
     * @param obj Crea un objeto tipo DbTable py realiza el metodo insertararea
     */

    public function crearAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $cama_nombre = $this->getRequest()->getParam('nombre');
            $tipo_cama = $this->getRequest()->getParam('tipo_cama');
            $habitacion = $this->getRequest()->getParam('habitacion');

            $cama_estado = $this->getRequest()->getParam('cama_estado');
            $obj = new Application_Model_DbTable_Camas();
            $obj->insertarcama($cama_nombre, $habitacion, $cama_estado,$tipo_cama);
            echo $this->tabla_camas();
        }
    }
    /**
     * editarAction()
     * * Esta accion edita especialidades
     * ! obtiene los datos mediante llamada ajax
     * @param id obtiene el valor enviado por ajax
     * @param cama_nombre obtiene el valor enviado por ajax
     * @param habitacion obtiene el valor enviado por ajax
     * @param cama_estado obtiene el valor enviado por ajax
     * @param obj Crea un objeto tipo DbTable py realiza el metodo insertararea
     */

    public function editarAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $habitacion = $this->getRequest()->getParam('habitacion');
            $cama_nombre = $this->getRequest()->getParam('nombre');
            $tipo_cama = $this->getRequest()->getParam('tipo_cama');
            $cama_estado = $this->getRequest()->getParam('cama_estado');

            $obj = new Application_Model_DbTable_Camas();
            $obj->actualizarcama($id, $cama_nombre, $habitacion, $cama_estado,$tipo_cama);
            echo $this->tabla_camas();
        }
    }
    /**
     * eliminarAction()
     * * Esta accion elimina especialidades
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
            $obj = new Application_Model_DbTable_Camas();
            $obj->eliminarcama($id);
            echo $this->tabla_camas();
        }
    }
    /**
     * select_tipo()
     * * Esta funcion crea el template SELECT HTML que sera mostrado en el view
     * * Obtiene una lista de las habitaciones
     * ! se ejecuta despues de: index, crear, editar, eliminar
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
     */

    public function select_tipo()
    {
        $obj = new Application_Model_DbTable_Camas();
        $datos = $obj->listar_tipo_cama();
        $Lista = '<div  class="form-group">';
        if (!$datos) {
            $Lista .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                </div>';
        } else {
            $Lista .= '<label for="comboTipoCama">Seleccione el tipo de cama:</label>';

            $Lista .= '<select class="custom-select" name="comboTipoCama" id="comboTipoCama">';
            foreach ($datos as $item):
                $Lista .= "<option value='". $item->cama_tipo_id ."'>" . $item->cama_tipo_descripcion . "</option>";
            endforeach;
            $Lista .= "</select></div>";
        }
        return $Lista;
    }
    /**
     * select_habitacion()
     * * Esta funcion crea el template SELECT HTML que sera mostrado en el view
     * * Obtiene una lista de las habitaciones
     * ! se ejecuta despues de: index, crear, editar, eliminar
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
     */

    public function select_habitacion()
    {
        $obj = new Application_Model_DbTable_Habitaciones();
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
            $Listaarea .= '<label for="comboHabitacion">Seleccione una habitacion:</label>';

            $Listaarea .= '<select class="custom-select" name="comboHabitacion" id="comboHabitacion">';
            foreach ($datosarea as $item):
                $Listaarea .= "<option value='". $item->habitacion_id ."'>" . $item->habitacion_nombre . "</option>";
            endforeach;
            $Listaarea .= "</select></div>";
        }
        return $Listaarea;
    }
    /**
     * select_estado_cama()
     * * Esta funcion crea el template SELECT HTML que sera mostrado en el view
     * * Obtiene una lista de los estados de las camas: disponible, ocupada, en desinfeccion
     * ! se ejecuta despues de: index, crear, editar, eliminar
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
     */

    public function select_estado_cama()
    {
        $obj = new Application_Model_DbTable_Camas();
        $datosarea = $obj->listar_estado_cama();
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
    /**
     * tabla_camas()
     * * Esta funcion crea el template HTML que sera mostrado en el view
     * ! se ejecuta despues de: index, crear, editar, eliminar
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
     */

    public function tabla_camas()
    {
        $obj = new Application_Model_DbTable_Camas();
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
            $cadena .= '<table class="table  table-bordered table-sm dataTable" id="dataTableCamas" width="100%" > 
                <thead class="table-dark" >
                <tr>
                    <th >ID</th>
                    <th >DESCRIPCION</th>
                    <th >SALA</th>
                    <th >HABITACION</th>
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
                $cadena .= "<td>" . $item->cama_id . "</td>";
                $cadena .= "<td>Cama " . $item->cama_nombre . "</td>";
                $cadena .= "<td>" . $item->cama_tipo_descripcion . "</td>";
                $cadena .= "<td>" . $item->habitacion_nombre . "</td>";
                $cadena .= "<td>" . $item->especialidad_nombre . "</td>";
                $cadena .= "<td>" . $item->piso_nombre . "</td>";
                $cadena .= "<td>" . $item->area_nombre . "</td>";

                $cadena .= "<td>". $item->cama_estado_descripcion ."</td>
                <td>
                <div class='btn-group' role='group' aria-label='Basic example'>
                
                <!--  debo enviar la busqueda por ajax -->
                <button type='button' class='btn btn-outline-dark btn-sm border-0 ' 
                onclick='editarModal(". $item->cama_id .",". $item->habitacion_id .",". $item->cama_estado_id .",`". $item->cama_nombre ."`)' >
                    <i class='far fa-edit  '></i>
                </button>
                <button type='button' class='btn btn-outline-danger btn-sm border-0' onclick='eliminar(". $item->cama_id .")' >
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