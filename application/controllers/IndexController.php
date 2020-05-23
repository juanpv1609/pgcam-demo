<?php

class IndexController extends Zend_Controller_Action
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
        $this->view->icono = "fa-tachometer-alt";
    }
    /**
     * indexAction()
     * * Esta accion no esta en uso, direcciona al action: dashboard
     * */

    public function indexAction()
    {
        $this->_helper->redirector('dashboard', 'index'); //direccionamos al menu de inicio
    }
    /**
     * dashboardAction()
     * * Esta accion consulta varios modelos de la bdd para mostrarlos
     * * de manera grafica en el dashboard
     * */
    public function dashboardAction()
    {
        $this->view->titulo="Panel de control";
        $this->view->controlador="Dashboard";
        $this->view->accion="Index";
        /**
         *  @param obj_paciente: Cuenta el # de pacientes en la BDD
         * */
        $obj_paciente = new Application_Model_DbTable_Admision();
        $this->view->cuenta_pacientes = count($obj_paciente->listarPacientes());
        /**
         *  @param obj_usuarios: Cuenta el # de usuarios en la BDD
         * */
        $obj_usuarios = new Application_Model_DbTable_Usuario();
        $this->view->cuenta_usuarios = count($obj_usuarios->listar_usuarios());
        /**
         *  @param obj: Objeto del DBTable Estadistica
         * @param data: lista el estado de las camas
         * @param total: contiene el #total de camas
         * @param cuenta_camas_disp: contiene el #total de camas_disponibles
         * @param cuenta_camas_ocup: contiene el #total de camas_ocupadas
         * @param cuenta_camas_desinf: contiene el #total de camas_desinfeccion
         * */
        $obj = new Application_Model_DbTable_Estadistica();
        $data = $obj->camasEstado();
        $total=($data[0]->cuenta_camas)+($data[1]->cuenta_camas)+($data[2]->cuenta_camas);
        $this->view->cuenta_camas_disp = number_format((($data[0]->cuenta_camas)*100/$total),1);
        $this->view->cuenta_camas_ocup = number_format((($data[1]->cuenta_camas)*100/$total),1);
        $this->view->cuenta_camas_desinf = number_format((($data[2]->cuenta_camas)*100/$total),1);
    }
    /**
     * camasporservicioAction()
     * * Esta accion cuenta las camas por escpecialidad
     * * mediante una opcion enviada via post
     * ! obtiene los datos mediante llamada ajax
     * @param data obtiene la data del metodo camasPorServicio()
     * @param opcion puede ser: (3 = TODAS) (0=Disponibles) (1=Ocupadas) (2=Desinfeccion)
     * */

    public function camasporservicioAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX

            $opcion = $this->getRequest()->getParam('opcion');
            $obj = new Application_Model_DbTable_Estadistica();
            $data = $obj->camasPorServicio();
            $response = array(); //Declaro un array para enviar los datos a la vista
        }
        if ($data) {
            $response['data'] = $data;
            $json = json_encode($response);
            echo $json;
        }
    }
    /**
     * camasestadoAction()
     * * Esta accion cuenta las camas totales
     * ! obtiene los datos mediante llamada ajax
     * @param data obtiene la data del metodo camasEstado()
     * */

    public function camasestadoAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $obj = new Application_Model_DbTable_Estadistica();
            $data = $obj->camasEstado();
            
            $response = array(); //Declaro un array para enviar los datos a la vista
        }
        if ($data) {
            $response['data'] = $data;
            $json = json_encode($response);
            echo $json;
        }
    }
    /**
     * mapacamasAction()
     * * Esta accion cuenta las camas totales
     * ! obtiene los datos mediante llamada ajax
     * @param data obtiene la data del metodo camasEstado()
     * */

    public function mapacamasAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $especialidad_id = $this->getRequest()->getParam('especialidad_id');
            echo $this->tabla_mapa_camas($especialidad_id);
        }
    }
    /**
     * tabla_hab_cama()
     * * Esta funcion crea el template table HTML que sera mostrado en el view
     * ! MUY IMPORTANTE
     * * Crea la matriz de camas y especialidades
     * @param especialidad_id obtiene el valor enviado por ajax
     * @param obj Crea un objeto tipo DbTable py realiza el metodo buscaHab
     */

    public function tabla_mapa_camas($especialidad_id)
    {
        $obj = new Application_Model_DbTable_Admision();
        $data = $obj->mapaCamas($especialidad_id);
        $Listaarea = '';
        if (!$data) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron iconoultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            $Listaarea .= '<div class="row row-cols-1  row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">';
            foreach ($data as $item):
                $paciente = $obj->Paciente_info($item->p_id, $item->paciente_ci, $item->entrada);

                $Listaarea .= '<div class="col mb-4 tarjeta">
                            
                                    <div class="card lift h-100 rounded shadow-sm  px-0 ">
                                    <div class="ribbon-wrapper ">
                                <div class="ribbon bg-'.$item->especialidad_color.'"></div>
                            </div>
                                        <div class="card-header h-25 pl-3 py-2">';
            $Listaarea .= '             <span class="card-title font-weight-bold ">'.$paciente->nombre.'</span>';
            $Listaarea .= '             </div>
                                    <div class="card-body pb-0 px-3">';
            $Listaarea .= '             <div class="row no-gutters align-items-center">
                                    
                                        <div class="col mr-2">
                                        <p class="text ">'.$item->especialidad_nombre.'<br>';
            $Listaarea .= '                     Hab: '.$item->habitacion_nombre.'<br>';
            $Listaarea .= '                     Cama: '.$item->cama_nombre.'</p></div>
                                    <div class="col-auto "><i class="fas fa-user-tag fa-3x text-gray-500 "></i></div>';
            $Listaarea .= '         </div>
                                </div>
                                    <div class="card-footer pl-3 py-2">
                                        <small class="text-muted ">Fecha de ingreso: '.$item->fecha_ingreso.'</small>
                                    </div>
                                </div>
                            </div>';
            endforeach;
            $Listaarea .= "</div>";
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
