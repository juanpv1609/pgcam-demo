<?php
class PacienteController extends Zend_Controller_Action
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
    }
    /**
     * indexAction()
     * * Esta accion no esta en uso, redirecciona a listar
    */

    public function indexAction()
    {
        $this->_helper->redirector('listar', 'paciente'); //direccionamos al listar
    }
    /**
     * listarAction()
     * * Esta accion lista los pacientes  de la bbd
     * ! importamos el archivo paciente.js
     * @param data obtiene la data del metodo tabla_pacientes()
     * @param titulo almacena el nombre de la vista, se mostrara en el titulo de la pagina
     */
    public function listarAction()
    {
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/paciente.js');
        echo $this->view->headScript();
        $this->view->titulo = "Lista de pacientes";
        $this->view->icono = "fa-list";

        $this->view->data = $this->tabla_pacientes();
    }
    /**
     * registrarAction()
     * * Esta accion muestra el formulario 008 de registro por emergencia
     * ! importamos el archivo paciente.js
     */

    public function registrarAction()
    {
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/paciente.js');
        echo $this->view->headScript();
        $this->view->titulo = "Registro de admisión";
        $this->view->icono = "fa-file-signature";
    }
    /**
     * historialAction()
     * * Esta accion muestra el formulario 008 de registro por emergencia
     * ! importamos el archivo paciente.js
     */

    public function historialAction()
    {
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/paciente.js');
        echo $this->view->headScript();
        $this->view->titulo = "Historial del paciente";
        $this->view->icono = "fa-history";
    }
    /**
     * admisionAction()
     * * Esta accion recibe los datos del formulario 008
     * ! obtiene los datos mediante llamada ajax
     * @param obj: un objeto tipo AdmisionPaciente para insertar el registrp en la bdd
     * */

    public function admisionAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) { //Detectamos si es una llamada AJAX
            
            $apellido_paterno = $this->getRequest()->getParam('apellido_paterno');
            $apellido_materno = $this->getRequest()->getParam('apellido_materno');
            $primer_nombre = $this->getRequest()->getParam('primer_nombre');
            $segundo_nombre = $this->getRequest()->getParam('segundo_nombre');
            $cedula = $this->getRequest()->getParam('cedula');
            $telefono = $this->getRequest()->getParam('telefono');
            $comboParroq = $this->getRequest()->getParam('comboParroq');
            $barrio = $this->getRequest()->getParam('barrio');
            $direccion = $this->getRequest()->getParam('direccion');
            $fecha_n = $this->getRequest()->getParam('fecha_n');
            $lugar_n = $this->getRequest()->getParam('lugar_n');
            $comboNacionalidad = $this->getRequest()->getParam('comboNacionalidad');
            $comboGrupo = $this->getRequest()->getParam('comboGrupo');
            $comboEdad = $this->getRequest()->getParam('comboEdad');
            $comboGenero = $this->getRequest()->getParam('comboGenero');
            $comboEstado = $this->getRequest()->getParam('comboEstado');
            $comboInstruccion = $this->getRequest()->getParam('comboInstruccion');
            $ocupacion = $this->getRequest()->getParam('ocupacion');
            $trabajo = $this->getRequest()->getParam('trabajo');
            $comboTipoSeguro = $this->getRequest()->getParam('comboTipoSeguro');
            $referido = $this->getRequest()->getParam('referido');
            $contacto_nombre = $this->getRequest()->getParam('contacto_nombre');
            $contacto_parentezco = $this->getRequest()->getParam('contacto_parentezco');
            $contacto_direccion = $this->getRequest()->getParam('contacto_direccion');
            $contacto_telefono = $this->getRequest()->getParam('contacto_telefono');
            $comboFormaLLeg = $this->getRequest()->getParam('comboForma');
            $fuente_info = $this->getRequest()->getParam('fuente_info');
            $institucion = $this->getRequest()->getParam('institucion');
            $institucion_telefono = $this->getRequest()->getParam('institucion_telefono');
            
            $usuario = Zend_Auth::getInstance()->getIdentity();

            $obj = new Application_Model_DbTable_Admision();
            $data_p = $obj->admisionpaciente(
                $apellido_paterno,
                $apellido_materno,
                $primer_nombre,
                $segundo_nombre,
                $cedula,
                $telefono,
                $comboParroq,
                $barrio,
                $direccion,
                $fecha_n,
                $lugar_n,
                $comboNacionalidad,
                $comboGrupo,
                $comboEdad,
                $comboGenero,
                $comboEstado,
                $comboInstruccion,
                $ocupacion,
                $trabajo,
                $comboTipoSeguro,
                $referido,
                $contacto_nombre,
                $contacto_parentezco,
                $contacto_direccion,
                $contacto_telefono,
                $comboFormaLLeg,
                $fuente_info,
                $institucion,
                $institucion_telefono,
                $usuario->usu_id
            );
            /**
             * ! El codigo a continuacion es provisional
             * para comprobar el funcionamiento de las notificaciones
             * TODO: Crear un trigger que inserte automaticamente los datos
             * TODO: en la table notificaciones
             * @param causa_id: igual a 7 porque 7: "Ingreso de paciente"
             * @param mensaje: el contenido de la notificacion
             * @param ususario: quien genero la notificacion
            * */

            $causa_id=8; // 7: registro de paciente
            
            $mensaje = 'REGISTRO AL PACIENTE CON CI:'.$cedula;
            $notificacion = new Application_Model_DbTable_Notificaciones();
            $notificacion->insertarNotificacion($mensaje, $usuario->usu_id, $causa_id, $cedula);
            $notificacion->listar();
        }
    }
    /**
     * admisionAction()
     * * Esta accion recibe los datos del formulario 008
     * ! obtiene los datos mediante llamada ajax
     * @param obj: un objeto tipo AdmisionPaciente para insertar el registrp en la bdd
     * */

    public function editaadmisionAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) { //Detectamos si es una llamada AJAX
            
            $apellido_paterno = $this->getRequest()->getParam('apellido_paterno');
            $apellido_materno = $this->getRequest()->getParam('apellido_materno');
            $primer_nombre = $this->getRequest()->getParam('primer_nombre');
            $segundo_nombre = $this->getRequest()->getParam('segundo_nombre');
            $cedula = $this->getRequest()->getParam('cedula');
            $telefono = $this->getRequest()->getParam('telefono');
            $comboParroq = $this->getRequest()->getParam('comboParroq');
            $barrio = $this->getRequest()->getParam('barrio');
            $direccion = $this->getRequest()->getParam('direccion');
            $fecha_n = $this->getRequest()->getParam('fecha_n');
            $lugar_n = $this->getRequest()->getParam('lugar_n');
            $comboNacionalidad = $this->getRequest()->getParam('comboNacionalidad');
            $comboGrupo = $this->getRequest()->getParam('comboGrupo');
            $comboEdad = $this->getRequest()->getParam('comboEdad');
            $comboGenero = $this->getRequest()->getParam('comboGenero');
            $comboEstado = $this->getRequest()->getParam('comboEstado');
            $comboInstruccion = $this->getRequest()->getParam('comboInstruccion');
            $ocupacion = $this->getRequest()->getParam('ocupacion');
            $trabajo = $this->getRequest()->getParam('trabajo');
            $comboTipoSeguro = $this->getRequest()->getParam('comboTipoSeguro');
            $referido = $this->getRequest()->getParam('referido');
            $contacto_nombre = $this->getRequest()->getParam('contacto_nombre');
            $contacto_parentezco = $this->getRequest()->getParam('contacto_parentezco');
            $contacto_direccion = $this->getRequest()->getParam('contacto_direccion');
            $contacto_telefono = $this->getRequest()->getParam('contacto_telefono');
            $comboFormaLLeg = $this->getRequest()->getParam('comboForma');
            $fuente_info = $this->getRequest()->getParam('fuente_info');
            $institucion = $this->getRequest()->getParam('institucion');
            $institucion_telefono = $this->getRequest()->getParam('institucion_telefono');
            
            $obj = new Application_Model_DbTable_Admision();
            $data_p = $obj->edicionPaciente(
                $apellido_paterno,
                $apellido_materno,
                $primer_nombre,
                $segundo_nombre,
                $cedula,
                $telefono,
                $comboParroq,
                $barrio,
                $direccion,
                $fecha_n,
                $lugar_n,
                $comboNacionalidad,
                $comboGrupo,
                $comboEdad,
                $comboGenero,
                $comboEstado,
                $comboInstruccion,
                $ocupacion,
                $trabajo,
                $comboTipoSeguro,
                $referido,
                $contacto_nombre,
                $contacto_parentezco,
                $contacto_direccion,
                $contacto_telefono,
                $comboFormaLLeg,
                $fuente_info,
                $institucion,
                $institucion_telefono
            );
        }
    }
    /**
     * buscaAction()
     * * Esta accion busca un paciente en la bdd
     * ! obtiene los datos mediante llamada ajax
     * @param data obtiene la data del metodo buscaPaciente()
     * @param paciente id unico del paciente enviado via post
     * @param obj Crea un objeto tipo DbTable y realiza el metodo buscaPaciente
     * */

    public function buscaAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $paciente= $this->getRequest()->getParam('paciente');
            $opcion= $this->getRequest()->getParam('opcion');
            $obj = new Application_Model_DbTable_Admision();
            $data = $obj->buscaPaciente($paciente, $opcion);
            $response = array(); //Declaro un array para enviar los datos a la vista
        }
        if ($data) {
            $response['data'] = $data;
            $json = json_encode($response);
            echo $json;
        }
    }
    /**
    * buscacamapacienteAction()
    * * Esta accion busca la cama actual que tiene un paciente en la bdd
    * ! obtiene los datos mediante llamada ajax
    * @param data obtiene la data del metodo buscaCamaPaciente()
    * @param paciente id unico del paciente enviado via post
    * @param obj Crea un objeto tipo DbTable y realiza el metodo buscaCamaPaciente
    * */

    public function buscacamapacienteAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $paciente= $this->getRequest()->getParam('paciente');
            $opcion= $this->getRequest()->getParam('opcion');
            $obj = new Application_Model_DbTable_Admision();

            $data = $obj->buscaCamaPaciente($paciente);
            $response = array(); //Declaro un array para enviar los datos a la vista
        }
        $response['data'] = $data;
        $json = json_encode($response);
        echo $json;
    }
    /**
     * asignarAction()
     * * Esta accion muestra la view del formulario de asignacion de cama
     * ! importamos el archivo paciente.js
     * * */

    public function asignarAction()
    {
        $this->view->titulo = "Asignacion de cama";
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/paciente.js');
        echo $this->view->headScript();
        $this->view->icono = "fa-procedures";
    }
    /**
     * asignarcamaAction()
     * * Esta accion asigna una cama y diagnosticos a un paciente existente
     * * y que no tenga una cama asignada
     * ! obtiene los datos mediante llamada ajax
     * * PROCESO O LOGICA
     * @param causa_id: igual a 1 yaa que 1:'Asignacion de cama'
     * ? 1. Obtiene los datos via AJAX
     * ? 2. Crea el objeto tipo DBTable_Admision
     * ? 3. Verifica si el paciente ya tiene una cama (Evita duplicados)
     * ? 4. Si no tiene una cama asignada, SE LE ASIGNA UNA
     * ? 5. Se actualiza el estado de la cama a OCUPADA
     * ? 6. Crea una notificacion al sistema
     * ! los pasos 5 y 6 son provisionales
     * TODO: se debe crear un trigger para las notificaciones y para cambiar el estado de la cama
     */

    public function asignarcamaAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $paciente_hc = $this->getRequest()->getParam('paciente_hc');
            $opcion = $this->getRequest()->getParam('opcion');
            $cedula = $this->getRequest()->getParam('cedula');
            $cama_id = $this->getRequest()->getParam('cama_id');
            $cie10_cod = $this->getRequest()->getParam('cie10_cod');
            $cie10_tipo = $this->getRequest()->getParam('cie10_tipo');
            $especialidad_id = $this->getRequest()->getParam('especialidad_id');
            $obj = new Application_Model_DbTable_Admision();
            $causa_id=1; // 1: asignacion de cama
            //-------- verificar si ya tiene una cama asignada----------
            $cama_asignada = $obj->verificaCamaPacienteIngreso($paciente_hc, $causa_id);
            if (!$cama_asignada) {
                $usuario = Zend_Auth::getInstance()->getIdentity();
                $obj->asignaCamaPaciente($paciente_hc, $cedula, $cama_id, $causa_id, $cie10_cod, $cie10_tipo, $usuario->usu_id, $opcion);
                
                $notificacion = new Application_Model_DbTable_Notificaciones();
                //$notificacion->insertarNotificacion($mensaje, $usuario->usu_id, $causa_id,$cedula);
                $notificacion->listar();

                echo $this->tabla_hab_cama($especialidad_id);
            } else { //retorna un mensaje de error que dira que el paciente ya tiene una cama asignada
                echo 'error';
            }
        }
    }
    /**
     * updatecamaAction()
     * * Esta accion realiza el cambio de cama de un paciente
     * * y que no tenga una cama asignada
     * ! obtiene los datos mediante llamada ajax
     * * PROCESO O LOGICA
     * @param causa_id: igual a 1 yaa que 1:'Asignacion de cama'
     * ? 1. Obtiene los datos via AJAX
     * ? 2. Crea el objeto tipo DBTable_Admision
     * ? 3. Verifica si el paciente ya tiene una cama (Evita duplicados)
     * ? 4. Si no tiene una cama asignada, SE LE ASIGNA UNA
     * ? 5. Se actualiza el estado de la cama a OCUPADA
     * ? 6. Crea una notificacion al sistema
     * ! los pasos 5 y 6 son provisionales
     * TODO: se debe crear un trigger para las notificaciones y para cambiar el estado de la cama
     */

    public function updatecamaAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $cama_paciente_id = $this->getRequest()->getParam('cama_paciente_id');
            $opcionCausa = $this->getRequest()->getParam('opcionCausa');
            $cama_id = $this->getRequest()->getParam('cama_id');
            $cie10_cod = $this->getRequest()->getParam('cie10_cod');
            $cie10_tipo = $this->getRequest()->getParam('cie10_tipo');
            $especialidad_id = $this->getRequest()->getParam('especialidad_id');
            $obj = new Application_Model_DbTable_Admision();
            $causa_id=1; // 1: asignacion de cama
            //-------- verificar si ya tiene una cama asignada----------
            
            $usuario = Zend_Auth::getInstance()->getIdentity();
            $obj->updateCamaPaciente(
                $cama_paciente_id,
                $opcionCausa,
                $cama_id,
                $cie10_cod,
                $cie10_tipo,
                $usuario->usu_id
            );
            echo $this->tabla_hab_cama($especialidad_id);

            $notificacion = new Application_Model_DbTable_Notificaciones();
            //$notificacion->insertarNotificacion($mensaje, $usuario->usu_id, $causa_id,$cedula);
            $notificacion->listar();

            //echo $this->tabla_hab_cama($especialidad_id);
        }
    }
    /**
     * cambioAction()
     * * Esta accion muestra la vista cambio
     * ! importamos el archivo paciente.js
     * TODO: esta pendiente todo
     * */

    public function cambioAction()
    {
        $this->view->titulo = "Cambio o egreso de paciente";
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/paciente.js');
        echo $this->view->headScript();
        $this->view->icono = "fa-exchange-alt";
    }
    /**
     * tabla_pacientes()
     * * Esta funcion crea el template HTML que sera mostrado en el view
     * ! se ejecuta despues de: index, crear, editar, eliminar
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
     */

    public function tabla_pacientes()
    {
        $obj = new Application_Model_DbTable_Admision();
        $data_paciente_cama = $obj->listarPacientesCama();
        $data_paciente = $obj->listarPacientes();
        
        $cadena_paciente_cama = '';
        $cadena_paciente = '';
        if (!$data_paciente_cama) {
            $cadena_paciente_cama .= '';
        } else {
            $cadena_paciente_cama .= '<div class="card mb-4">
            <div class="card-header"><h6>Pacientes con cama asignada</h6> </div>
            <div class="card-body">';
            $cadena_paciente_cama .= '<table class="table table-bordered table-sm dataTable pb-4" id="dataTablePacienteCama" width="100%" >
                <thead class="thead-dark">
                <tr >
                    <th >ID</th>
                    <th >CEDULA</th>
                    <th >PACIENTE</th>
                    <th >FECHA ASIG. DE CAMA</th>
                    <th >ORIGEN</th>
                    <th >ACCION</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($data_paciente_cama as $item):
            $data = $obj->Paciente_info($item->p_id, $item->paciente_ci, $item->entrada);
            $origen_paciente= ($item->entrada==1) ? 'EMERGENCIA' : 'C EXTERNA';
            $boton_editar= ($item->entrada==2) ? 'd-none' : '';
            $cadena_paciente_cama .= "<tr>";
            $cadena_paciente_cama .= "<td>" . $item->cama_paciente_id . "</td>";
            $cadena_paciente_cama .= "<td>" . $item->paciente_ci . "</td>";
            $cadena_paciente_cama .= "<td>". $data->nombre ."</td>";
            $cadena_paciente_cama .= "<td>" . $item->fecha_ingreso . "</td>";
            $cadena_paciente_cama .= "<td>" . $origen_paciente . "</td>";
            $cadena_paciente_cama .= "<td>
                    <div class='btn-group' role='group' aria-label='Basic example'>
                        <button type='button' class='btn btn-outline-info btn-sm border-0' title='Ver mas'
                        onclick='mostrarModalMasInfo(". $item->cama_paciente_id .",". $item->p_id .",". $item->entrada .")' ><i class='far fa-eye  '></i>
                        </button>
                        <a type='button' href='".$this->_request->getBaseUrl()."/registrar_paciente?id=".$item->p_id."'
                        class='".$boton_editar." btn btn-outline-dark btn-sm  border-0 ' 
                        onclick='editarModal(". $item->p_id .",`". $item->paciente_ci ."`)' >
                            <i class='far fa-edit  '></i>
                        </a>
                        </div>                        
                    </td>
                </tr>";
            endforeach;
            $cadena_paciente_cama .= "</tbody></table></div></div>";
        }
        /**
             * ? pacientes sin cama asignada
             * ! evita duplicados en la consulta
             */
        
        if (!$data_paciente) {
            $cadena_paciente .= '';
        } else {
            $cadena_paciente .= '<div class="card mb-4">
            <div class="card-header"><h6>Pacientes sin cama asignada</h6> </div>
            <div class="card-body">';
            $cadena_paciente .= '<table class="table table-bordered table-sm dataTable" id="dataTablePaciente" width="100%" >
                <caption>Pacientes sin cama asignada</caption>
                <thead class="thead-light">
                <tr>
                    <th >HC</th>
                    <th >CEDULA</th>
                    <th >PACIENTE</th>
                    <th >ORIGEN</th>
                    <th >ASIG. DE CAMA</th>
                    <th >ACCION</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($data_paciente as $d):
            $cadena_paciente .= "<tr>";
            $cadena_paciente .= "<td>" . $d->p_id . "</td>";
            $cadena_paciente .= "<td>" . $d->p_ci . "</td>";
            $cadena_paciente .= "<td>". $d->p_nombres ." ". $d->p_apellidos ."</td>";
            
            $cadena_paciente .= "<td>EMERGENCIA</td>";
            $cadena_paciente .= "<td><a type='button' href='".$this->_request->getBaseUrl()."/asignar_cama_paciente?id=".$d->p_id."'
                        class=' btn btn-purple btn-sm  '  >
                            Asignar cama
                        </a></td>";

            $cadena_paciente .= "<td>
                    <div class='btn-group' role='group' aria-label='Basic example'>
                        <button type='button' class='btn btn-outline-info btn-sm border-0' title='Ver mas'
                        onclick='mostrarModalMasInfo(". $d->p_id .",1)' ><i class='far fa-eye  '></i>
                        </button>
                        <a type='button' href='".$this->_request->getBaseUrl()."/registrar_paciente?id=".$d->p_id."'
                        class=' btn btn-outline-dark btn-sm  border-0 ' 
                        onclick='editarModal(". $d->p_id .",`". $d->p_ci ."`)' >
                            <i class='far fa-edit  '></i>
                        </a>
                        </div>                        
                    </td>
                </tr>";
            endforeach;
            $cadena_paciente .= "</tbody></table></div></div>";
        }


        return $cadena_paciente_cama." ".$cadena_paciente;
    }
    /**
     * getcamasAction()
     * * Esta accion lista las camas dependiendo de la especialidad
     * ! obtiene los datos mediante llamada ajax
     * TODO: controlar que si tiene dependencia en la bdd NO ELIMINAR
     * @param especialidad_id obtiene el valor enviado por ajax
     */

    public function getcamasAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $especialidad_id = $this->getRequest()->getParam('especialidad_id');
            echo $this->tabla_hab_cama($especialidad_id);
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

    public function tabla_hab_cama($especialidad_id)
    {
        $obj = new Application_Model_DbTable_Admision();
        $datos_habitacion = $obj->buscaHab($especialidad_id);
        $Listaarea = '';
        if (!$datos_habitacion) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            $Listaarea .= '<table class="table  table-bordered  table-sm " >
            <caption class="text-xs">
            <small>Estado:</small>
               <span class="badge badge-danger text-wrap px-2">Ocupada </span>
               <span class="badge badge-secondary text-wrap px-2">Disponible</span>
               <span class="badge badge-warning text-wrap px-2">Desinfeccion</span></caption>
            <tbody >
                <tr>
                <th class="align-middle text-primary bg-gray-100" colspan="2" rowspan="2">'.$datos_habitacion[0]->especialidad_nombre.'</th>
                <th  colspan="'.count($datos_habitacion).'" class="bg-gray-100 align-middle text-center" >HABITACION</th>
            </tr>';
            $Listaarea .= "<tr>";
            //-----FORMA LAS HABITACIONES: cabecera de las tablas
            foreach ($datos_habitacion as $item):
                $Listaarea .= "<th>" . $item->habitacion_nombre . "</th>";
            endforeach;
            $Listaarea .= "</tr>";
            //---FORMA LAS COLUMNAS CAMAS
            $Listaarea .= '<tr>
                            <th rowspan="4" class="bg-gray-100 align-middle text-center">CAMA</th>
                        </tr>';
            /**
             * ? El codigo a continuacion crea la matriz de camas
             * ? SIENDO: filas=3 y columnas='cantidad de habitaciones de la especialidad'
             * ? Laso for llega hasta 3 porque en la bdd existe 3 'nombres de camas'
             */
             
            for ($i=1; $i <=3 ; $i++) { //forma las 3 filas
                $Listaarea .= "<tr><th>".$i."</th>";
                foreach ($datos_habitacion as $item):
                    $cama = $obj->buscaCamaEstado($item->habitacion_id, $i); //Verifica el estado de la cama de eso dependera su color y si esta habilitada
                    $icono = ($cama) ? '<i class="fas fa-bed"></i>' : ''; // se crea un icono para las camas existentes
                    /**
                     * @param titulo: operador ternario que verifica si la cama esta disponible para mostrar un titulo
                     */
                $titulo = (($cama)&&($cama->cama_estado_id==0)) ? ' title="Clic para reservar esta cama" ' : ' title="No es posible reservar esta cama"';
                /**
                 * @param eligeCama(): metodo que envia parametros a una funcion javascript y verifica si se puede o no reservar dicha cama
                 */
                $Listaarea .= '<td class="p-0">
                        <button class="btn btn-outline-'.$cama->cama_estado_color. ' btn-block p-1 border-0 rounded-0" id="'.$cama->cama_id.'" 
                        '.$titulo.'
                        onclick="eligeCama('.$datos_habitacion[0]->especialidad_id.',`'.$datos_habitacion[0]->especialidad_nombre.'`,`'.$item->habitacion_nombre.'`,`'.$cama->cama_nombre.'`,'.$cama->cama_id.','.$cama->cama_estado_id.');">
                        '.$icono.'</button></td>';
                endforeach;
                $Listaarea .= "</tr>";
            }
            
            $Listaarea .= "</tbody></table>";
        }

        return $Listaarea;
    }
    /**
     * cantonAction()
     * * Esta accion lista los cantones desde la bdd dependiendo de la provincia enviada
     * ! obtiene los datos mediante llamada ajax
     * @param prov obtiene el valor enviado por ajax
     * @param obj Crea un objeto tipo DbTable py realiza el metodo eliminararea
     */

    public function cantonAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $prov = $this->getRequest()->getParam('prov');
        }
        $obj = new Application_Model_DbTable_Admision();
        $datos = $obj->listarCantones($prov);
        $Listaarea = '';
        if (!$datos) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron iconoultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            $Listaarea .= "<option value=''>Seleccione uno</option>";

            foreach ($datos as $item):
                $Listaarea .= "<option value='". $item->id_canton ."'>" . $item->nombre_canton . "</option>";
            endforeach;
        }
        echo $Listaarea;
    }
    /**
     * parroquiaAction()
     * * Esta accion lista las parroquias desde la bdd dependiendo del canton enviado
     * ! obtiene los datos mediante llamada ajax
     * @param canton obtiene el valor enviado por ajax
     * @param obj Crea un objeto tipo DbTable py realiza el metodo eliminararea
     */

    public function parroquiaAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $canton = $this->getRequest()->getParam('canton');
        }
        $obj = new Application_Model_DbTable_Admision();
        $datos = $obj->listarParroquias($canton);
        $Listaarea = '';
        if (!$datos) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron iconoultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            $Listaarea .= "<option value=''>Seleccione uno</option>";
            foreach ($datos as $item):
                $Listaarea .= "<option value='". $item->id_parroquia ."'>" . $item->nombre_parroquia . "</option>";
            endforeach;
        }
        echo $Listaarea;
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
