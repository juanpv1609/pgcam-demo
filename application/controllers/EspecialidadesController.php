<?php

class EspecialidadesController extends Zend_Controller_Action
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
        $this->view->icono = "fa-medkit";

    }
    /**
     * indexAction()
     * * Esta accion lista las especialidades de la bbd
     * ! importamos el archivo especialidades.js
     * @param data obtiene la data del metodo tabla_especialidad()
     * @param data_pisos obtiene la data del metodo select_piso()
     * @param titulo almacena el nombre de la vista, se mostrara en el titulo de la pagina
     */

    public function indexAction()
    {
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/especialidades.js');
        echo $this->view->headScript();
        $this->view->data = $this->tabla_especialidad();
        $this->view->data_pisos = $this->select_piso();        
        $this->view->titulo="Especialidades Registradas";
    }
    /**
     * crearAction()
     * * Esta accion crea nuevas especialidades
     * ! obtiene los datos mediante llamada ajax
     * @param especialidad_nombre obtiene el valor enviado por ajax
     * @param piso_id obtiene el valor enviado por ajax
     * @param obj Crea un objeto tipo DbTable y realiza el metodo insertararea
     */

    public function crearAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $especialidad_nombre = $this->getRequest()->getParam('nombre');
            $alias = $this->getRequest()->getParam('alias');
            $piso_id = $this->getRequest()->getParam('piso');
            $color = $this->getRequest()->getParam('color');
            $obj = new Application_Model_DbTable_Especialidades();
            $obj->insertarespecialidad($especialidad_nombre, $piso_id,$color,$alias);
            echo $this->tabla_especialidad();
        }
    }
    /**
     * editarAction()
     * * Esta accion edita especialidades
     * ! obtiene los datos mediante llamada ajax
     * @param id obtiene el valor enviado por ajax
     * @param especialidad_nombre obtiene el valor enviado por ajax
     * @param piso_id obtiene el valor enviado por ajax
     * @param obj Crea un objeto tipo DbTable py realiza el metodo insertararea
     */

    public function editarAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $piso_id = $this->getRequest()->getParam('piso');
            $especialidad_nombre = $this->getRequest()->getParam('nombre');
            $alias = $this->getRequest()->getParam('alias');
            $color = $this->getRequest()->getParam('color');
            $obj = new Application_Model_DbTable_Especialidades();
            $obj->actualizarespecialidad($id, $especialidad_nombre, $piso_id,$color,$alias);
            echo $this->tabla_especialidad();
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
            $obj = new Application_Model_DbTable_Especialidades();
            $obj->eliminarespecialidad($id);
            echo $this->tabla_especialidad();
        }
    }
    /**
     * select_piso()
     * * Esta funcion crea el template SELECT HTML que sera mostrado en el view
     * * Obtiene una lista de los pisos
     * ! se ejecuta despues de: index, crear, editar, eliminar
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
     */

    public function select_piso()
    {
        $obj = new Application_Model_DbTable_Pisos();
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
            $Listaarea .= '<label for="comboArea">Seleccione un piso:</label>';

            $Listaarea .= '<select class="custom-select" name="comboPiso" id="comboPiso">';
            foreach ($datosarea as $item):
                $Listaarea .= "<option value='". $item->piso_id ."'>" . $item->piso_nombre . "</option>";
            endforeach;
            $Listaarea .= "</select></div>";
        }
        return $Listaarea;
    }
    /**
     * tabla_especialidad()
     * * Esta funcion crea el template HTML que sera mostrado en el view
     * ! se ejecuta despues de: index, crear, editar, eliminar
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
     */

    public function tabla_especialidad()
    {
        $obj = new Application_Model_DbTable_Especialidades();
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
            $cadena .= '<table class="table  table-bordered table-sm dataTable" id="dataTableEspecialidad" width="100%">
                <thead class="table-dark" >
                <tr>
                    <th >ID</th>
                    <th >DESCRIPCION</th>
                    <th >ALIAS</th>
                    <th >PISO</th>
                    <th >AREA</th>
                    <th >ESTADO</th>
                    <th ">ACCION</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datos as $item):

                $cadena .= "<tr>";
            $cadena .= "<td>" . $item->especialidad_id . "</td>";
            $cadena .= "<td  >" . $item->especialidad_nombre . "</td>";
            $cadena .= "<td class='d-flex justify-content-between'>" . $item->especialidad_alias . "<span class='badge badge-" . $item->especialidad_color . " ' >&nbsp;</span></td>";
            $cadena .= "<td>" . $item->piso_nombre . "</td>";
            $cadena .= "<td>" . $item->area_nombre . "</td>";

            $cadena .= "<td>Activa</td>
                    <td>
                    <div class='btn-group' role='group' aria-label='Basic example'>
                    
                    <!--  debo enviar la busqueda por ajax -->
                    <button type='button' class='btn btn-outline-dark btn-sm  border-0 ' 
                    onclick='editarModal(". $item->especialidad_id .",". $item->piso_id .",`". $item->especialidad_nombre ."`,`". $item->especialidad_color ."`,`". $item->especialidad_alias ."`)' >
                        <i class='far fa-edit  '></i>
                    </button>
                    <button type='button' class='btn btn-outline-danger btn-sm border-0 ' onclick='eliminar(". $item->especialidad_id .")' >
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