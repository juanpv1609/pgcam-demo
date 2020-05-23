<?php

class PisosController extends Zend_Controller_Action
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
        $this->view->controlador=Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->view->accion=Zend_Controller_Front::getInstance()->getRequest()->getActionName();
    }
    /**
     * indexAction()
     * * Esta accion lista los pisos desde la bdd
     * ! importamos el archivo pisos.js
     * @param data obtiene la data del metodo tabla_piso()
     * @param data_area obtiene la data del metodo select_area()
     * @param titulo almacena el nombre de la vista, se mostrara en el titulo de la pagina
     */

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
    /**
     * crearAction()
     * * Esta accion crea nuevos pisos
     * ! obtiene los datos mediante llamada ajax
     * @param piso_nombre obtiene el valor enviado por ajax
     * @param area_id obtiene el valor enviado por ajax
     * @param obj Crea un objeto tipo DbTable py realiza el metodo insertarpiso
     */

    public function crearAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $piso_nombre = $this->getRequest()->getParam('nombre');
            $area_id = $this->getRequest()->getParam('area');

            $obj = new Application_Model_DbTable_Pisos();
            $obj->insertarpiso($piso_nombre, $area_id);
            echo $this->tabla_piso();
        }
    }
    /**
     * editarAction()
     * * Esta accion edita pisos
     * ! obtiene los datos mediante llamada ajax
     * @param id obtiene el valor enviado por ajax
     * @param piso_nombre obtiene el valor enviado por ajax
     * @param area_id obtiene el valor enviado por ajax
     * @param obj Crea un objeto tipo DbTable py realiza el metodo actualizarpiso
     */

    public function editarAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $area_id = $this->getRequest()->getParam('area');
            $piso_nombre = $this->getRequest()->getParam('nombre');
            $obj = new Application_Model_DbTable_Pisos();
            $obj->actualizarpiso($id, $piso_nombre, $area_id);
            echo $this->tabla_piso();
        }
    }
    /**
     * eliminarAction()
     * * Esta accion elimina pisos
     * ! obtiene los datos mediante llamada ajax
     * TODO controlar que si tiene dependencia en la bdd NO ELIMINAR
     * @param id obtiene el valor enviado por ajax
     * @param obj Crea un objeto tipo DbTable py realiza el metodo eliminarpiso
     */

    public function eliminarAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $obj = new Application_Model_DbTable_Pisos();
            $obj->eliminarpiso($id);
            echo $this->tabla_piso();
        }
    }
    /**
     * select_area()
     * * Esta funcion crea el template select HTML con las areas
     * ! se ejecuta despues de: index, crear, editar, eliminar
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
     */

    public function select_area()
    {
        $obj = new Application_Model_DbTable_Areas();
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
            $Listaarea .= '<label for="comboArea">Seleccione un area:</label>';

            $Listaarea .= '<select class="custom-select" name="comboArea" id="comboArea">';
            foreach ($datosarea as $item):
                $Listaarea .= "<option value='". $item->area_id ."'>" . $item->area_nombre . "</option>";
            endforeach;
            $Listaarea .= "</select></div>";
        }
        return $Listaarea;
    }
    /**
     * tabla_piso()
     * * Esta funcion crea el template HTML con los pisos
     * ! se ejecuta despues de: index, crear, editar, eliminar
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
     */

    public function tabla_piso()
    {
        $obj = new Application_Model_DbTable_Pisos();
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
            $cadena .= '<table class="table  table-bordered table-sm dataTable" id="dataTablePisos" width="100%">
                <thead class="table-dark" >
                <tr>
                    <th >ID</th>
                    <th >DESCRIPCION</th>
                    <th >AREA</th>
                    <th >ESTADO</th>
                    <th >ACCION</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datos as $item):

                $cadena .= "<tr>";
            $cadena .= "<td>" . $item->piso_id . "</td>";
            $cadena .= "<td>" . $item->piso_nombre . "</td>";
            $cadena .= "<td>" . $item->area_nombre . "</td>";

            $cadena .= "<td>Activa</td>
                    <td>
                    <div class='btn-group' role='group' aria-label='Basic example'>
                    
                    <!--  debo enviar la busqueda por ajax -->
                    <button type='button' class='btn btn-outline-dark btn-sm  border-0 ' 
                    onclick='editarModal(". $item->piso_id .",". $item->area_id .",`". $item->piso_nombre ."`)' >
                        <i class='far fa-edit  '></i>
                    </button>
                    <button type='button' class='btn btn-outline-danger btn-sm border-0 ' onclick='eliminar(". $item->piso_id .")' >
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