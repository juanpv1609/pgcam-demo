<?php

class AreasController extends Zend_Controller_Action
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
        $this->view->controlador = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->view->accion = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        $this->view->titulo_formulario = "Area";
        $this->view->icono = "fa-table";        
    }
    /**
     * indexAction()
     * * Esta accion lista las areas de la bbd
     * ! importamos el archivo areas.js
     * @param data obtiene la data del metodo tabla_area()
     * @param titulo almacena el nombre de la vista, se mostrara en el titulo de la pagina
     */
    public function indexAction()
    {
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/areas.js');
        echo $this->view->headScript();
        $this->view->data = $this->tabla_area();
        $this->view->titulo="Areas Registradas";
    }
    /**
     * crearAction()
     * * Esta accion crea nuevas areas
     * ! obtiene los datos mediante llamada ajax
     * @param area_nombre obtiene el valor enviado por ajax
     * @param obj Crea un objeto tipo DbTable py realiza el metodo insertararea
     */
    public function crearAction()
    {
        $this->_helper->viewRenderer->setNoRender();        // No necesitamos el render de la vista en una llamada ajax. 
        $this->_helper->layout->disableLayout();            // Solo si estas usando Zend_Layout 
        if ($this->getRequest()->isXmlHttpRequest()) {      // Detectamos si es una llamada AJAX  
            $area_nombre = $this->getRequest()->getParam('nombre'); 
            $obj = new Application_Model_DbTable_Areas();  
            $obj->insertararea($area_nombre);
            echo $this->tabla_area();
        }
    }
    /**
     * editarAction()
     * * Esta accion edita areas
     * ! obtiene los datos mediante llamada ajax
     * @param area_id obtiene el valor enviado por ajax
     * @param area_nombre obtiene el valor enviado por ajax
     * @param obj Crea un objeto tipo DbTable py realiza el metodo actualizararea
     */
    public function editarAction()
    {
        $this->_helper->viewRenderer->setNoRender();            //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout();                // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {          //Detectamos si es una llamada AJAX
            $area_id = $this->getRequest()->getParam('id');
            $area_nombre = $this->getRequest()->getParam('nombre');
            $obj = new Application_Model_DbTable_Areas();
            $obj->actualizararea($area_id, $area_nombre);
            echo $this->tabla_area();
        }
    }
    /**
     * eliminarAction()
     * * Esta accion elimina areas
     * ! obtiene los datos mediante llamada ajax
     * TODO controlar que si tiene dependencia en la bdd NO ELIMINAR
     * @param area_id obtiene el valor enviado por ajax
     * @param obj Crea un objeto tipo DbTable py realiza el metodo eliminararea
     */
    public function eliminarAction()
    {
        $this->_helper->viewRenderer->setNoRender();            //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout();                // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {          //Detectamos si es una llamada AJAX
            $area_id = $this->getRequest()->getParam('id');
            $obj = new Application_Model_DbTable_Areas();
            $obj->eliminararea($area_id);
            echo $this->tabla_area();
        }
    }
    /**
     * tabla_area()
     * * Esta funcion crea el template HTML que sera mostrado en el view
     * ! se ejecuta despues de: index, crear, editar, eliminar
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
     */

    public function tabla_area()
    {
        $obj = new Application_Model_DbTable_Areas();
        $datosarea = $obj->listar();
        $Listaarea = '';
        if (!$datosarea) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron result ados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            $Listaarea .= '<table class="table table-bordered  table-sm   dataTable" id="dataTableAreas" width="100%" >
                <thead class="table-dark">
                <tr>
                    <th >ID</th>
                    <th >DESCRIPCION</th>
                    <th >ESTADO</th>
                    <th >ACCION</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datosarea as $item):

                $Listaarea .= "<tr>";
            $Listaarea .= "<td>" . $item->area_id . "</td>";
            $Listaarea .= "<td>" . $item->area_nombre . "</td>";
            $nombre="'". $item->area_nombre ."'";
            $Listaarea .= "<td>Activa</td>
                    <td>
                    <div class='btn-group' role='group' aria-label='Basic example'>
                        
                        <!--  debo enviar la busqueda por ajax -->
                        <button type='button' class='btn btn-outline-dark btn-sm border-0 ' 
                        onclick='editarModal(". $item->area_id .",`". $item->area_nombre ."`)' >
                            <i class='far fa-edit  '></i>
                        </button>
                        <button type='button' class='btn btn-outline-danger btn-sm border-0 ' onclick='eliminar(". $item->area_id .")' >
                            <i class='far fa-trash-alt '></i>
                        </button>
                        </div>
                        
                    </td>
                </tr>";
            endforeach;

            $Listaarea .= "</tbody></table>";
        }

        return $Listaarea;
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
        if (!$auth->hasIdentity()) {                               
             /* Si no existe una sesion activa: redirige al login*/
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