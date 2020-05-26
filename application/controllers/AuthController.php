<?php

class AuthController extends Zend_Controller_Action
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
     * * Esta accion no esta en uso
     * ! redirecciona a login
     */

    public function indexAction()
    {
        $this->_helper->redirector('login', 'auth'); //direccionamos al login
    }
    /**
     * loginAction()
     * * Esta accion permite el login de usuarios
     * ! recibimos los parametros via POST
     * @param usuario campo input enviado via POST
     * @param clave campo input enviado via POST
     * @param titulo almacena el nombre de la vista, se mostrara en el titulo de la pagina
     */

    public function loginAction()
    {
        $this->view->titulo="Iniciar Sesión";
        $this->_helper->layout->setLayout('login');                     //Establece otro layout
        if ($this->_request->isPost()) {                                //Verifica si el request es tipo POST
            Zend_Loader::loadClass('Zend_Filter_StripTags');            //Filtro para parametros de ingreso
            $f = new Zend_Filter_StripTags();
            $usuario = $f->filter($this->_request->getPost('email'));   //capturamos y filtramos el nombre de usuario
            $clave = $f->filter($this->_request->getPost('password'));  //capturamos y filtramos el password del usuario
            
            Zend_Loader::loadClass('Zend_Auth_Adapter_DbTable');        //Cargamos la clase  de Zend Auth
            $dbAdapter = Zend_Registry::get('pgdb');                    //a traves de la variable de registro de la conexion creamos el adpatador
            $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
            $authAdapter->setTableName('usuario');                      //configuramos la tabla de usuarios
            $authAdapter->setIdentityColumn('correo');                  //configuramos el nombre de usuario para su verificacion
            $authAdapter->setCredentialColumn('clave');                 //configuramos el password para su verificacion

            $authAdapter->setIdentity($usuario);                        //registramos el username enviado para su validacion
            $authAdapter->setCredential(md5($clave));                   //registramos contraseña enviado para su validacion

            $auth = Zend_Auth::getInstance();                           //creamos una intancia de Zend Auth
            $result = $auth->authenticate($authAdapter);                //enviamos los parametros para su validacion

            if ($result->isValid()) {
                $data = $authAdapter->getResultRowObject(null, 'clave'); //almacenamos los datos excepto el password
                
                if ($data->usu_estado_id==1) {                          //verifica si el usuario esta activo 1=activo
                    $auth->getStorage()->write($data);                  //creamos la sesion para el usuario
                    $this->ruta_usuario($data->usu_id);                 // funcion que controla las rutas segun el perfil
                } else {                                                //Si el ussuario no esta activo mostramos un error
                    $this->view->message = '<div class="alert alert-info alert-dismissible animated shake">
                        <button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
                        <i class="fas fa-exclamation-circle"></i><strong> Error: </strong><span>"El usuario no esta habilitado!"</span>
                        <span>Comuniquese con el administrador!</span></div>';
                }
            } else {                                                    //Si las credenciales no son validas mostramos un error
                switch ($result->getCode()) {
                    case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                        // usuario inexistente
                        $this->view->message = '<div class="alert alert-danger alert-dismissible animated shake">
                    <button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
                    <i class="fas fa-exclamation-circle"></i><strong> Error: </strong><span >"El usuario que intenta acceder no existe!"</span></div>';
                        break;
                    case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                        // password erroneo
                        $this->view->message = '<div class="alert alert-danger alert-dismissible animated shake">
                        <button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
                        <i class="fas fa-exclamation-circle"></i><strong> Error: </strong><span>"La contraseña ingresada es incorrecta!"</span></div>';
                        
                        break;
                    default:
                        /* otro error */
                        $this->view->message = '<div class="alert alert-danger alert-dismissible animated shake">
                    <button class="close" type="button" data-dismiss="alert" aria-hidden="true">x</button>
                    <i class="fas fa-exclamation-circle"></i><strong> Error: </strong><span>"Error desconocido!"</span></div>';
                        break;
                }
            }
        }
    }
    /**
     * ruta_usuario()
     * * controlar el perfil de usuario y de acuerdo a este direccionar la pagina
     * @param usu_id se recibe desde login el usu_id que se autentico
     * */

    public function ruta_usuario($usu_id)
    {
        $obj = new Application_Model_DbTable_Usuario();
        $perfil = $obj->obtienePerfil($usu_id);
        return $this->_helper->redirector($perfil->perf_accion, $perfil->perf_controlador); //direccionamos al menu de inicio
        
    }
    /**
     * logoutAction()
     * * Esta accion finaliza la sesion
     * * guarda la ultima conexion a la BDD
     * * Redirecciona al Login
     * */

    public function logoutAction()
    {
        $this->user = Zend_Auth::getInstance()->getIdentity();
        $obj = new Application_Model_DbTable_Usuario();
        $obj->actualizar_ultima_conexion_usuario($this->user->usu_id);
        Zend_Auth::getInstance()->clearIdentity(); //cerramos la sesion del usuario
        $this->_redirect('iniciar_sesion'); // direccionamos al login
    }
    /**
     * registerAction()
     * * Esta accion muestra la vista de registro
     * * Utiliza un layout independiente
     * */

    public function registerAction()
    {
        $this->_helper->layout->setLayout('login');
        $this->view->titulo="Registro";
    }
    /**
     * agregarAction()
     * * Esta accion crea nuevos usuarios desde la vista registro
     * ! obtiene los datos mediante llamada ajax
     * @param validator: verifica que no exista ya el usuario ingresado
     * * retorna 1 o 0
     * * 1: El usuario se creo correctamente
     * * 0: El correo ingresado ya existe
     */

    public function agregarAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $nombre= $this->getRequest()->getParam('nombre');
            $apellido= $this->getRequest()->getParam('apellido');
            $email = $this->getRequest()->getParam('email');
            $clave = $this->getRequest()->getParam('clave');
            $comboPerfil = $this->getRequest()->getParam('comboPerfil');
            $obj = new Application_Model_DbTable_Usuario();
            /* validator: verifica si el correo no existe en la BDD  */
            $validator = new Zend_Validate_Db_NoRecordExists(array(
                                                            'table' => 'usuario', //nombre de la tabla
                                                            'field' => 'correo'     // campo
                                                            ));
                                                            
            if ($validator->isValid($email)) {              //verifica si el correo no existe
                $datos = $obj->crearusuario($nombre, $apellido, $email, $clave, 2, $comboPerfil);
                /**
                 * ? El codigo a continuacion envia un email al usuario creado mediante SMTP
                 * */
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
                $obj->enviaEmail($email, $contenido, $asunto);
                
                echo 1; //retorna 1
            } else { //si el correo ya existe retorna 0
                echo 0;
            }
        }
    }
    /**
     * recuperaAction()
     * * Esta accion envia una clave provisional al usuario
     * * mediante email.
     * ! obtiene los datos mediante llamada ajax
     * @param correo_recuperacion obtiene el valor enviado por ajax
     * @param piso_id obtiene el valor enviado por ajax
     * @param obj Crea un objeto tipo DbTable py realiza el metodo insertararea
     */

    public function recuperaAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $correo_recuperacion= $this->getRequest()->getParam('recuperar');
            $obj = new Application_Model_DbTable_Usuario();
            /* validator: verifica si el correo existe en la BDD  */
            $validator = new Zend_Validate_Db_RecordExists(array(
                                                            'table' => 'usuario', //nombre de la tabla
                                                            'field' => 'correo'     // campo
                                                            ));
            $response = array(); //Declaro un array para enviar los datos a la vista
                                                            
            if ($validator->isValid($correo_recuperacion)) {              //verifica si el correo no existe
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
                $obj->enviaEmail($correo_recuperacion, $contenido, $asunto);
                //------------actualizar la clave
                $obj->actualizarclave_recuperacion($correo_recuperacion, $clave_provisional);
                $response['existe'] = true; //envia existe = true

            }else{
                $response['existe'] = false; //envia existe = false

            }
        }
        $json = json_encode($response);
        echo $json;
    }
}
