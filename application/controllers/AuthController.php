<?php

class AuthController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->initView();
        //$this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->user = Zend_Auth::getInstance()->getIdentity();
        $this->view->controlador = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->view->accion = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        //$this->_helper->layout()->disableLayout();

    }

    public function indexAction()
    {
        $this->_helper->redirector('login', 'auth'); //direccionamos al menu de inicio
    }

    public function loginAction()
    {
        // action body
        $this->view->titulo="Iniciar Sesión"; 
        $usuario;
        $clave;
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
                    if ($data->usu_estado_id==1) { //verifica si el usuario esta activo 1=activo
                        $auth->getStorage()->write($data); //creamos la sesion para el usuario
                    //inserta la fecha de conexion
                /* control de rutas */
                    $this->ruta_usuario($data->usu_id);
                    }else {// //Si las credenciales no son validas mostramos un error

                        $this->view->message = '<div class="alert alert-warning alert-dismissible">
                            <button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
                            <i class="fas fa-exclamation-circle"></i><strong> Error: </strong><span>"El usuario no esta habilitado!"</span>
                            <span>Comuniquese con el administrador!</span></div>';
                        //$this->view->usuario_value=$usuario;
                        //$this->view->clave_value=$clave;
    
                    }
                    

                } else {// //Si las credenciales no son validas mostramos un error
                    switch ($result->getCode()) {
                        case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                            // usuario inexistente
                            $this->view->message = '<div class="alert alert-danger alert-dismissible">
                        <button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
                        <i class="fas fa-exclamation-circle"></i><strong> Error: </strong><span >"El usuario que intenta acceder no existe!"</span></div>';
                            break;
                        case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                            // password erroneo
                            $this->view->message = '<div class="alert alert-danger alert-dismissible">
                        <button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
                        <i class="fas fa-exclamation-circle"></i><strong> Error: </strong><span>"La contraseña ingresada es incorrecta!"</span></div>';
                        $this->view->usuario_value=$usuario; //para que no se borre el campo de usuario
                        $this->view->clave_value="";
                            break; 
                        default:
                            /* otro error */
                            $this->view->message = '<div class="alert alert-danger alert-dismissible">
                        <button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
                        <i class="fas fa-exclamation-circle"></i><strong> Error: </strong><span>"Error desconocido!"</span></div>';
                            break;
                    }
                    
                    //$this->view->usuario_value=$usuario;
                    //$this->view->clave_value=$clave;

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
    public function ultima_conexion($usu_id){
        $obj = new Application_Model_DbTable_Usuario();
        $obj->actualizar_ultima_conexion_usuario($usu_id);

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
                return $this->_helper->redirector($perfil->perf_accion, $perfil->perf_controlador); //direccionamos al menu de inicio

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
        $this->user = Zend_Auth::getInstance()->getIdentity();
        $this->ultima_conexion($this->user->usu_id);
        Zend_Auth::getInstance()->clearIdentity(); //cerramos la sesion del usuario
        $this->_redirect('auth/login'); // direccionamos al login
    }

    public function registerAction()
    {
        // action body
        $this->_helper->layout->setLayout('login');
        $this->view->titulo="Registro"; 


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
            $comboPerfil = $this->getRequest()->getParam('comboPerfil');
            $obj = new Application_Model_DbTable_Usuario();
            $existe_correo = $obj->existeUsuario($email);
            $response = array(); //Declaro un array para enviar los datos a la vista

            if (!$existe_correo) {
                $datos = $obj->insertarusuario($nombre,$apellido, $email, $clave,$comboPerfil);
                $asunto='Bienvenido al sistema';
                $contenido='<html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Bienvenido al sistema PG-CAM</title>
                </head>
                <body><br>
                    <h3>PG-CAM <small> Admin</small></h3> 
                        <p>Hola! <code>'.$nombre.' '.$apellido.'</code> su cuenta ha sido creada exitosamente, sin embargo para poder ingresar al sistema
                        deberá requerir al administrador que active su cuenta.</p>
                        <p>Puede iniciar sesión accediendo al siguiente link: <a href="http://localhost/zend/public/auth/login">PG-CAM Login</a></p>
                        
                        <p>Este correo ha sido generado automaticamente. No debe responder</p>
                    
                </body>
                </html>';
                //envio el email con los datos creados
                $obj->enviaEmail($email,$contenido,$asunto);
            }  
        }

        $response['data'] = $existe_correo;
        $json = json_encode($response);
        echo $json;

         

    }
    public function recuperaAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $correo_recuperacion= $this->getRequest()->getParam('recuperar');
            //---1) verificar si existe el usuario en el sistema
            $obj = new Application_Model_DbTable_Usuario();
            $existe_correo = $obj->existeUsuario($correo_recuperacion);
            $response = array(); //Declaro un array para enviar los datos a la vista
        }
        $response['data'] = $existe_correo;
        $json = json_encode($response);
        echo $json;
        if ($existe_correo) {
            $asunto='Recuperacion de clave';
            $clave_provisional= substr(md5(time()), 0, 8); //genera una cadena alfanumerica aleatoria de 8 caracteres
            $contenido='<html lang="en">
            <head>
               <meta charset="UTF-8">
               <meta name="viewport" content="width=device-width, initial-scale=1.0">
               <title>Recuperación de contraseña</title>
            </head>
            <body><br>
               <h3>PG-CAM <small> Admin</small></h3> 
                  <p>Hola! <code>'.$correo_recuperacion.'</code> su nueva contraseña provisional es la siguiente: <strong>
                  <span style="background-color: yellow;">'.$clave_provisional.'</span></strong>
                  , una vez que haya ingresado al sistema, deberá cambiar su contraseña.</p>
                  <p>Puede iniciar sesion accediendo al siguiente link: <a href="http://localhost/zend/public/auth/login">PG-CAM Login</a></p>
                  
                 <p>Este correo ha sido generado automaticamente. No debe responder</p>
               
            </body>
            </html>';
            //envio el email con los datos creados
            $obj->enviaEmail($correo_recuperacion,$contenido,$asunto);
            //------------actualizar la clave
            $obj->actualizarclave_recuperacion($correo_recuperacion,$clave_provisional);
        }
        
    }
    
    
}





