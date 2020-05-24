<?php

class NotificacionesController extends Zend_Controller_Action
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
        $this->view->icono = "fa-bell";        
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
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/notificaciones.js');
        echo $this->view->headScript();
        $this->view->data = $this->tabla_notificaciones();
        $this->view->titulo="Notificaciones";
    }
    public function editarAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $estado = $this->getRequest()->getParam('estado');
            $obj = new Application_Model_DbTable_Notificaciones();
            $obj->editarNotificacion($id,$estado);
            $obj->listar();

            echo $this->tabla_notificaciones();

        }

    }
    public function eliminarAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $obj = new Application_Model_DbTable_Notificaciones();
            $obj->eliminarNotificacion($id);
            $obj->listar();
            echo $this->tabla_notificaciones();

        }

    }
    
/**
     * tabla_area()
     * * Esta funcion crea el template HTML que sera mostrado en el view
     * ! se ejecuta despues de: index, crear, editar, eliminar
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
     */

    public function tabla_notificaciones()
    {
        $fc = Zend_Controller_Front::getInstance()->getRequest()->getBaseUrl();
        $usuario = Zend_Auth::getInstance()->getIdentity();

        $estado ='';
        $obj = new Application_Model_DbTable_Notificaciones();
        $datosarea = $obj->listarTodo();
        $Listaarea = '';
        if (!$datosarea) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            $Listaarea .= '<table class="table table-bordered  table-sm   dataTable" id="dataTableNotificaciones" width="100%" >
                <thead class="table-dark">
                <tr>
                    <th >ID</th>
                    <th >CAUSA</th>
                    <th >DETALLE</th>
                    <th >USUARIO</th>
                    <th >FECHA CREACION</th>
                    <th >ESTADO</th>
                    <th >ACCION</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datosarea as $item):
                //if(isset($this->user) /*&&($this->user->perf_id==1)*/)
            $estado = ($item->not_estado==0) ? 'Creada' : 'Leida';
            $boton_eliminar=(($item->not_estado==0) ) ? 'disabled' : '';
            $Listaarea .= "<tr>";
            $Listaarea .= "<td>" . $item->not_id . "</td>";
            $Listaarea .= "<td>" . $item->causa_descripcion . "</td>";
            $Listaarea .= "<td>" . strtoupper($item->not_mensaje) . "</td>";
            $Listaarea .= "<td>" . $item->usu_nombres . " " . $item->usu_apellidos . "</td>";
            $Listaarea .= "<td>" . $item->not_fecha_creacion . "</td>";
            $Listaarea .= "<td>".$estado."</td>";

            $Listaarea .= "  <td>
                    <div class='btn-group' role='group' aria-label='Basic example'>
                    <a type='button' class='btn btn-outline-primary btn-sm border-0 ' 
                        href='".$fc."/listar_paciente?ci=".$item->p_ci."' title='Ver'>
                            <i class='fas fa-search '></i>
                        </a>";
                        /**
                         * ? el codigo a continuacion permite marcar o desmarcar una notificacion
                         * ! LEIDA o NO LEIDA
                         */
                        $Listaarea .= ($item->not_estado==0) ? "<button type='button' class='btn btn-outline-success btn-sm border-0 ' 
                                    onclick='NotificacionEstado(". $item->not_id .",1)' title='Marcar como leida'>
                                        <i class='fas fa-eye  '></i>
                                    </button>" : "<button type='button' class='btn btn-outline-danger btn-sm border-0 ' 
                                    onclick='NotificacionEstado(". $item->not_id .",0)' title='Marcar como no leida'>
                                        <i class='fas fa-eye-slash  '></i>
                                    </button>" ;
                        /**
                         * ? El siguiente codigo permite visualizar el boton eliminar solo al perfil administrador
                         */
                        $Listaarea .= ($usuario->perf_id==1) ? "<button type='button' class='btn btn-outline-danger btn-sm border-0 ' $boton_eliminar
                                    onclick='eliminarNotificacion(". $item->not_id .")' title='Eliminar'>
                                        <i class='far fa-trash-alt '></i>
                                    </button>" : "";               

                $Listaarea .= "</div>
                        
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