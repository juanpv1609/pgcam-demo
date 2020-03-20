<?php
class PacienteController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
        $this->initView();
        //$this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->user = Zend_Auth::getInstance()->getIdentity();
        $this->view->controlador = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->view->accion = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        $this->view->titulo_formulario = "Paciente";
        $this->view->icono = "fa-procedures";
    }

    public function indexAction()
    {
        // action body
        $this->_helper->redirector('listar', 'paciente'); //direccionamos al listar
    }

    public function listarAction()
    {
        $this->view->titulo = "Lista de pacientes";
        $this->view->data = $this->tabla_pacientes();
    }

    public function registrarAction()
    {
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/paciente.js');
        echo $this->view->headScript();
        $this->view->titulo = "Registro de admisión";
    }
    public function admisionAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) { //Detectamos si es una llamada AJAX
            
            //$apellido_paterno = $this->getRequest()->getParam('apellido_paterno');
            //$apellido_materno = $this->getRequest()->getParam('apellido_materno');
            //$primer_nombre = $this->getRequest()->getParam('primer_nombre');
            //$segundo_nombre = $this->getRequest()->getParam('segundo_nombre');
            $cedula = $this->getRequest()->getParam('cedula');
            $telefono = $this->getRequest()->getParam('telefono');
            // $comboParroq = $this->getRequest()->getParam('comboParroq');
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
            //$response = array();
            $obj = new Application_Model_DbTable_Admision();
            $data_p = $obj->admisionpaciente($apellido_paterno, $apellido_materno, $primer_nombre, $segundo_nombre, $cedula, $telefono, $comboParroq, $barrio, $direccion, $fecha_n, $lugar_n, $comboNacionalidad, $comboGrupo, $comboEdad, $comboGenero, $comboEstado, $comboInstruccion, $ocupacion, $trabajo, $comboTipoSeguro, $referido, $contacto_nombre, $contacto_parentezco, $contacto_direccion, $contacto_telefono, $comboFormaLLeg, $fuente_info, $institucion, $institucion_telefono);
            //echo $this->tabla_area();
           // $response = array();
        }
    }
    public function buscaAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $paciente= $this->getRequest()->getParam('paciente');
            $obj = new Application_Model_DbTable_Admision();
            $data = $obj->buscaPaciente($paciente);
            $response = array(); //Declaro un array para enviar los datos a la vista
        }
        if ($data) {
            $response['data'] = $data;
            $json = json_encode($response);
            echo $json;
        }
    }
    public function asignarAction()
    {
        $this->view->titulo = "Formulario de asignacion de cama";
    }

    public function cambioAction()
    {
        $this->view->titulo = "Cambio / Egreso de paciente";
    }
    public function tabla_pacientes()
    {
        $table_m = new Application_Model_DbTable_Admision();
        $datosarea = $table_m->listarPacientes();
        $Listaarea = '';
        if (!$datosarea) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            $Listaarea .= '<table class="table table-sm dataTable" id="dataTableAreas" width="100%">
                <thead>
                <tr>
                    <th class="text-primary">HC</th>
                    <th class="text-primary">CEDULA</th>
                    <th class="text-primary">NOMBRE</th>
                    <th class="text-primary">SEXO</th>
                    <th class="text-primary">EDAD</th>
                    <th class="text-primary">F NAC</th>
                    <th class="text-primary">SEGURO</th>
                    <th class="text-primary ">ACCION</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datosarea as $item):

                $Listaarea .= "<tr>";
            $Listaarea .= "<td>" . $item->p_hc . "</td>";
            $Listaarea .= "<td>" . $item->p_ci . "</td>";
            $Listaarea .= "<td>" . $item->p_apellidos ." " . $item->p_nombres . "</td>";
            $Listaarea .= "<td>" . $item->p_sexo . "</td>";
            $Listaarea .= "<td>" . $item->p_edad . "</td>";
            $Listaarea .= "<td>" . $item->p_fecha_n . "</td>";
            $Listaarea .= "<td>" . $item->p_tipo_seguro . "</td>";
            $Listaarea .= "<td>
                    <div class='btn-group' role='group' aria-label='Basic example'>
                        
                        <!--  debo enviar la busqueda por ajax -->
                        <button type='button' class='btn btn-outline-info btn-sm' onclick='eliminar(". $item->p_id .")' >
                            <i class='fas fa-eye '></i>
                        </button>
                        <button type='button' class='btn btn-outline-secondary btn-sm ' 
                        onclick='editarModal(". $item->p_id .",`". $item->p_ci ."`)' >
                            <i class='fas fa-edit  '></i>
                        </button>
                        <button type='button' class='btn btn-outline-danger btn-sm' onclick='eliminar(". $item->p_id .")' >
                            <i class='fas fa-trash '></i>
                        </button>
                        </div>
                        
                    </td>
                </tr>";
            endforeach;

            $Listaarea .= "</tbody></table>";
        }

        return $Listaarea;
    }
    public function cantonAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $prov = $this->getRequest()->getParam('prov');
            //echo $this->select_canton($prov);
        }
        $obj = new Application_Model_DbTable_Admision();
        $datos = $obj->listarCantones($prov);
        $Listaarea = '';
        if (!$datos) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            foreach ($datos as $item):
                $Listaarea .= "<option value='". $item->id_canton ."'>" . $item->nombre_canton . "</option>";
            endforeach;
        }
        echo $Listaarea;
    }
    public function parroquiaAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $canton = $this->getRequest()->getParam('canton');
            //echo $this->select_canton($prov);
        }
        $obj = new Application_Model_DbTable_Admision();
        $datos = $obj->listarParroquias($canton);
        $Listaarea = '';
        if (!$datos) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            foreach ($datos as $item):
                $Listaarea .= "<option value='". $item->id_parroquia ."'>" . $item->nombre_parroquia . "</option>";
            endforeach;
        }
        echo $Listaarea;
    }
}
