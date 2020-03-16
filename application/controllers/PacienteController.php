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
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            
            $apellido_paterno = $this->getRequest()->getParam('apellido_paterno');
            //$apellido_materno = $this->getRequest()->getParam('apellido_materno');
            $primer_nombre = $this->getRequest()->getParam('primer_nombre');
            //$segundo_nombre = $this->getRequest()->getParam('segundo_nombre');
            $cedula = $this->getRequest()->getParam('cedula');
            $telefono = $this->getRequest()->getParam('telefono');
            /* $comboParroq = $this->getRequest()->getParam('comboParroq');
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
            $institucion_telefono = $this->getRequest()->getParam('institucion_telefono'); */
            //$response = array();
            $obj2 = new Application_Model_DbTable_Admision();
            $obj2->admisionp($apellido_paterno,$primer_nombre,$cedula,$telefono);
            //echo $this->tabla_area();
           // $response = array();
        }
        //$response['data'] = $datos;
        //$json = json_encode($response);
        //echo $json;
        

    }

    public function asignarAction()
    {
        $this->view->titulo = "Formulario de asignacion de cama";
        
    }

    public function cambioAction()
    {
        $this->view->titulo = "Cambio / Egreso de paciente";
        
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













