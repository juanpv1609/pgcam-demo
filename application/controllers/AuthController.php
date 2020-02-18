<?php

class AuthController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->initView();
        $this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->user = Zend_Auth::getInstance()->getIdentity();
        //$this->_helper->layout()->disableLayout();

    }

    public function indexAction()
    {
        $this->_helper->redirector('login', 'auth'); //direccionamos al menu de inicio
    }

    public function loginAction()
    {
        // action body
        $this->_helper->layout->setLayout('login');
        if ($this->_request->isPost()) {
            Zend_Loader::loadClass('Zend_Filter_StripTags'); //Filtro para parametros de imgreso
            $f = new Zend_Filter_StripTags();
            $usuario = $f->filter($this->_request->getPost('email')); //capturamos y filtramos el nombre de usuario
            $clave = $f->filter($this->_request->getPost('password')); //capturamos y filtramos el password del usuario

            if (empty($usuario)) {//Verificamos el user name si es vacio enviamos un error
                $this->view->message = '<div class="alert alert-danger alert-dismissible">
                    <button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
                    <i class="icon fa fa-ban"></i>Por favor ingrese su nombre de usuario</div>';
            } else { //Declaro un array para enviar los datos a la vista
                Zend_Loader::loadClass('Zend_Auth_Adapter_DbTable'); //Cargamos laclase  de Zend Auth
                $dbAdapter = Zend_Registry::get('pgdb'); //a traves de la variable de registro de la conexion creamos el adpatador
                $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
                $authAdapter->setTableName('usuario');  //configuramos la tabla de usuarios
                $authAdapter->setIdentityColumn('correo'); //configuramos el nombre de usuario para su verificacion
                $authAdapter->setCredentialColumn('clave'); // configuramos el password para su verificacion

                $authAdapter->setIdentity($usuario);   //registramos el username enviado para su validacion
                $authAdapter->setCredential(md5($clave)); //registramos contrase帽a enviado para su validacion

                $auth = Zend_Auth::getInstance(); //creamos una intancia de Zend Auth
                $result = $auth->authenticate($authAdapter); // enviamos los parametros para su validacion


                if ($result->isValid()) {
                    $data = $authAdapter->getResultRowObject(null, 'clave'); //almacenamos los datos excepto el password
                    $auth->getStorage()->write($data); //creamos la sesion para el usuario
                    
                    /* control de rutas
                    */
                    $this->ruta_usuario($data->usu_id);

                } else {// //Si las credenciales no son validas mostramos un error
                    $this->view->message = '<div class="alert alert-danger alert-dismissible">
                        <button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
                        Las credenciales de autenticacion no son validas</div>';
                    $this->view->usuario_value=$usuario;
                    $this->view->clave_value=$clave;

                }
            }
        } else { //Recibo un error por Gets
            $error = $this->_getParam('e', 1);
            if ($error == 0) {
                $this->view->message = '<div class="alert alert-danger alert-dismissible">
                    <button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
                    Las credenciales de autenticacion proporcionadas no tienen permiso para acceder a este modulo</div>';
            }
        }

    }
    public function ruta_usuario($usu_id){
        /* controlar el perfil de usuario y de acuerdo a este direccionar la pagina */
        $obj = new Application_Model_DbTable_Usuario();
            $perfil = $obj->obtienePerfil($usu_id);
        switch ($perfil->perf_id) {
            case 1:
                return $this->_helper->redirector($perfil->perf_accion, $perfil->perf_controlador); //direccionamos al menu de inicio
                break;
            case 2:
                return $this->_helper->redirector('index', 'index'); //direccionamos al menu de inicio

                break;
            case 3:
                return $this->_helper->redirector('index', 'index'); //direccionamos al menu de inicio

                break;
            case 4:
                return $this->_helper->redirector($perfil->perf_accion, $perfil->perf_controlador); //direccionamos al menu de inicio

                break;
            default:
            return $this->_helper->redirector('index', 'index'); //direccionamos al menu de inicio

                break;
        }
    }
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity(); //cerramos la sesion del usuario
        $this->_redirect('auth/login'); // direccionamos al login
    }

    public function registerAction()
    {
        // action body
        $this->_helper->layout->setLayout('login');


    }
    public function agregarAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $nombre= $this->getRequest()->getParam('nombre');
            $apellido= $this->getRequest()->getParam('apellido');
            $email = $this->getRequest()->getParam('email');
            $clave = $this->getRequest()->getParam('clave');
            $table = new Application_Model_DbTable_Usuario();
            $datos = $table->insertarusuario($nombre,$apellido, $email, $clave);
            $response = array(); //Declaro un array para enviar los datos a la vista
        }
        $response['data'] = $datos;
        $json = json_encode($response);
        echo $json;

    }


}





