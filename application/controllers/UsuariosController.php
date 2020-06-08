<?php
class UsuariosController extends Zend_Controller_Action
{
    /**
     * init()
     * * Esta funcion se ejecuta antes de cualquier action
     * ! Se pueden setear variables globales para verlas en las views
     * ?
     * TODO: separar usuarios y perfiles
     * @param user almacena los datos de sesion
     * @param controlador,accion almacena el nombre del controlador y de la accion respectivamente
     */
    public function init()
    {
        $this->initView();
        $this->view->user = Zend_Auth::getInstance()->getIdentity();
        $this->view->controlador = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->view->accion = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        $this->view->titulo_formulario = "Usuarios";
        $this->view->icono = "fa-users-cog";
    }
    /**
     * indexAction()
     * * Esta accion lista los usuarios de la bbd
     * ! importamos el archivo usuarios.js
     * @param data_usuarios obtiene la data del metodo tabla_usuarios()
     * @param data_perfiles obtiene la data del metodo select_perfiles()
     * @param data_estado obtiene la data del metodo select_estado()
     * @param titulo almacena el nombre de la vista, se mostrara en el titulo de la pagina
     */
    public function indexAction()
    {
        $this->view->headScript()->appendFile($this->_request->getBaseUrl() . '/functions/usuarios.js');
        echo $this->view->headScript();
        $this->view->data_usuarios = $this->tabla_usuarios();
        $this->view->data_perfiles = $this->select_perfiles();
        $this->view->data_estado = $this->select_estado();
        $this->view->titulo = "Lista de usuarios";
    }
    /**
     * crearAction()
     * * Esta accion crea nuevos usuarios desde vista index USUARIOS
     * ! obtiene los datos mediante llamada ajax
     * @param obj Crea un objeto tipo DbTable y realiza el metodo crearusuario
     * ? 1. Obtiene los datos mediante AJAX
     * ? 2. Verifica si existe el usuario
     * ? 3. Crea el usuario
     * ? 4. Envia un email via SMTP al nuevo usuario
     */
    public function crearAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) { //Detectamos si es una llamada AJAX
            $nombres = $this->getRequest()->getParam('nombres');
            $apellidos = $this->getRequest()->getParam('apellidos');
            $correo = $this->getRequest()->getParam('correo');
            $perfil = $this->getRequest()->getParam('perfil');
            $estado = $this->getRequest()->getParam('estado');
            $clave = "HGOPNA2020";                  //clave generica para los nuevos usuarios
            /* validator: verifica si el correo no existe en la BDD  */
            $validator = new Zend_Validate_Db_NoRecordExists(array(
                                                            'table' => 'usuario', //nombre de la tabla
                                                            'field' => 'correo'     // campo
                                                            ));
                                                            
            if ($validator->isValid($email)) {
                $obj = new Application_Model_DbTable_Usuario();
                $obj->crearusuario($nombres, $apellidos, $correo, $clave, $perfil, $estado);

                //envio de correo de bienvenida
                $asunto = 'Bienvenido!';
                $contenido = '<html lang="en">
                <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Bienvenido al sistema PG-CAM</title>
                </head>
                <body><br>
                <h3>PG-CAM <small> Admin</small></h3>
                    <p>Hola! <code>' . $nombres . ' ' . $apellidos . '</code> su cuenta ha sido creada exitosamente, sin embargo para poder ingresar al sistema
                    debera requerir al administrador que active su cuenta.</p>
                    <p>Puede iniciar sesion accediendo al siguiente link: <a href="http://localhost/zend/public/auth/login">PG-CAM Login</a></p>

                    <p>Este correo ha sido generado automaticamente. No debe responder</p>

                </body>
                </html>';
                $obj->enviaEmail($correo, $contenido, $asunto);
                echo $this->tabla_usuarios();

            } else {
                echo '';
            }
        }
    }
    /**
     * editarAction()
     * * Esta accion actualiza la informacion del usuario excepto el password
     * ! obtiene los datos mediante llamada ajax
     * @param id dato enviado method POST via ajax (id del usuario)
     * @param nombres dato enviado method POST via ajax (nombres del usuario )
     * @param apellidos dato enviado method POST via ajax (apellidos del usuario )
     * @param correo dato enviado method POST via ajax (correo del usuario )
     * @param perfil dato enviado method POST via ajax (perfil del usuario )
     * @param estado dato enviado method POST via ajax (estado del usuario )
     * @param obj Crea un objeto tipo DbTable y realiza el metodo actualizarusuario
     * ! Se edita los campos del usuario menos la clave
     */
    public function editarAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) { //Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $nombres = $this->getRequest()->getParam('nombres');
            $apellidos = $this->getRequest()->getParam('apellidos');
            $correo = $this->getRequest()->getParam('correo');
            $perfil = $this->getRequest()->getParam('perfil');
            $estado = $this->getRequest()->getParam('estado');
            $obj = new Application_Model_DbTable_Usuario();
            $obj->actualizarusuario($id, $nombres, $apellidos, $correo, $perfil, $estado);
            echo $this->tabla_usuarios();
        }
    }
    /**
     * editarusuarioAction()
     * * Esta accion actualiza la informacion del usuario
     * * desde la vista perfil, es decir el usuario que inicio sesion modifica su informacion
     * ! obtiene los datos mediante llamada ajax
     * @param id dato enviado method POST via ajax (id del usuario)
     * @param nombres dato enviado method POST via ajax (nombres del usuario )
     * @param apellidos dato enviado method POST via ajax (apellidos del usuario )
     * @param correo dato enviado method POST via ajax (correo del usuario )
     * @param confirma_clave dato enviado method POST via ajax (confirma_clave del usuario )
     * @param obj Crea un objeto tipo DbTable y realiza el metodo actualizarinfousuario
     * TODO: verificar duplicidad en correo
     * ! es necesaria la clave para confirmar el cambio
     */

    public function editarusuarioAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        $msg='';
        if ($this->getRequest()->isXmlHttpRequest()) { //Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $nombres = $this->getRequest()->getParam('nombres');
            $apellidos = $this->getRequest()->getParam('apellidos');
            $correo = $this->getRequest()->getParam('correo');
            $confirma_clave = $this->getRequest()->getParam('confirma_clave');
            $obj = new Application_Model_DbTable_Usuario();
            $msj = $obj->actualizarinfousuario($id, $nombres, $apellidos, $correo, $confirma_clave);
        }
            echo ($msj) ? true : false; //comprueba si se realizo el cambio y retorna TRUE caso contrario FALSE

    }
    /**
     * editarclaveAction()
     * * Esta accion permite el cambio de clave del usuario
     * ! obtiene los datos mediante llamada ajax
     * @param user dato enviado method POST via ajax (id del usuario)
     * @param clave_actual dato enviado method POST via ajax (clave_actual del usuario )
     * @param nueva_clave dato enviado method POST via ajax (nueva_clave del usuario )
     * @param obj Crea un objeto tipo DbTable y realiza el metodo actualizarclaveusuario
     */
    public function editarclaveAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) { //Detectamos si es una llamada AJAX
            $user = Zend_Auth::getInstance()->getIdentity();
            $clave_actual = $this->getRequest()->getParam('clave_actual');
            $nueva_clave = $this->getRequest()->getParam('nueva_clave');
            $obj = new Application_Model_DbTable_Usuario();
            $obj->actualizarclaveusuario($user->usu_id, $clave_actual, $nueva_clave);
        }
    }
    /**
     * eliminarAction()
     * * Esta accion permite la eliminacion de un usuario
     * ! obtiene los datos mediante llamada ajax
     * @param id dato enviado method POST via ajax (id del usuario)
     * @param obj Crea un objeto tipo DbTable y realiza el metodo eliminarusuario
     */
    public function eliminarAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) { //Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $obj = new Application_Model_DbTable_Usuario();
            $obj->eliminarusuario($id);
            echo $this->tabla_usuarios();
        }
    }
    /**
     * perfilAction()
     * * Esta accion muestra el perfil del usuario logeado
     * ! importamos el archivo usuarios.js
     * @param data obtiene la data del metodo listar_usuario()
     * @param user obtiene la identidad del usuario logeado
     * @param titulo almacena el nombre de la vista, se mostrara en el titulo de la pagina
     */
    public function perfilAction()
    {
        $this->view->headScript()->appendFile($this->_request->getBaseUrl() . '/functions/perfiles.js');
        echo $this->view->headScript();
        $user = Zend_Auth::getInstance()->getIdentity();
        $obj = new Application_Model_DbTable_Usuario();
        $this->view->data = $obj->listar_usuario($user->usu_id);
        $this->view->titulo = "Informacion de usuario";
    }
    /**
     * perfilesAction()
     * * Esta accion lista los perfiles de usuario de la bbd
     * ! importamos el archivo usuarios.js
     * @param data_perfiles obtiene la data del metodo tabla_perfiles()
     * @param titulo almacena el nombre de la vista, se mostrara en el titulo de la pagina
     */
    public function perfilesAction()
    {
        $this->view->headScript()->appendFile($this->_request->getBaseUrl() . '/functions/permisos.js');
        echo $this->view->headScript();
        $this->view->headScript()->appendFile($this->_request->getBaseUrl() . '/functions/perfiles.js');
        echo $this->view->headScript();
        $this->view->data_perfiles = $this->tabla_perfiles();
        $this->view->titulo = "Lista de perfiles";
    }
    /**
     * crearperfilAction()
     * * Esta accion crea nuevos perfiles desde vista perfiles
     * ! obtiene los datos mediante llamada ajax
     * @param nombre_perfil dato enviado method POST via ajax
     * @param color dato enviado method POST via ajax
     * @param obj Crea un objeto tipo DbTable y realiza el metodo insertarUsuario
     * TODO: ingresar la ruta por defecto
     */
    public function crearperfilAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) { //Detectamos si es una llamada AJAX
            $nombre_perfil = $this->getRequest()->getParam('nombre_perfil');
            $color = $this->getRequest()->getParam('color');
            $obj = new Application_Model_DbTable_Perfiles();
            $obj_permiso = new Application_Model_DbTable_Permisos();
            $data = $obj->crearperfil($nombre_perfil, $color);
            // CREAR PERMISOS AUTOMATICAMENTE            
            $obj_permiso->crear_permiso($data->perf_id);
            echo $this->tabla_perfiles();
        }       

    }
    /**
     * editarperfilAction()
     * * Esta accion actualiza la informacion del perfil
     * ! obtiene los datos mediante llamada ajax
     * @param id dato enviado method POST via ajax (id del perfil)
     * @param nombre_perfil dato enviado method POST via ajax (nombre_perfil del perfil )
     * @param color dato enviado method POST via ajax (color del perfil )
     * @param obj Crea un objeto tipo DbTable y realiza el metodo actualizarPerfil
     * TODO: modificar la ruta por defecto
     */
    public function editarperfilAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) { //Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $nombre_perfil = $this->getRequest()->getParam('nombre_perfil');
            $color = $this->getRequest()->getParam('color');
            $controlador = $this->getRequest()->getParam('controlador');
            $accion = $this->getRequest()->getParam('accion');
            $obj = new Application_Model_DbTable_Perfiles();
            $obj->actualizarPerfil($id, $nombre_perfil, $color,$controlador,$accion);
            echo $this->tabla_perfiles();
        }
    }
    /**
     * eliminarperfilAction()
     * * Esta accion permite la eliminacion de un perfil
     * ! obtiene los datos mediante llamada ajax
     * @param id dato enviado method POST via ajax (id del perfil)
     * @param obj Crea un objeto tipo DbTable y realiza el metodo eliminarusuario
     * ! si un perfil contiene usuarios NO lo elimina
     */
    public function eliminarperfilAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) { //Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $perfil = new Application_Model_DbTable_Perfiles();
            $permiso = new Application_Model_DbTable_Permisos();
            //$permiso->eliminar_permisos($id);
            $perfil->eliminarpefil($id);
            echo $this->tabla_perfiles();
        }
    }
    /**
    * permisosAction()
    * * Esta accion permite listar los permisos de cada perfil
    * ! obtiene los datos mediante llamada ajax
    * @param id dato enviado method POST via ajax (id del perfil)
    */
    public function permisosAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) { //Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            echo $this->tabla_permisos($id);
        }
    }
    
    /**
     * editapermisosAction()
     * * Esta accion permite editar los permisos de cada perfil
     * * Esta accion la realiza mediante toggle button: ALLOW / DENY
     * ! obtiene los datos mediante llamada ajax
     * @param id dato enviado method POST via ajax (id del permiso)
     * @param opcion dato enviado method POST via ajax (opcion del permiso ALLOW o DENY)
     * @param perf_id dato enviado method POST via ajax (perf_id del perfil)
     * @param obj Crea un objeto tipo DbTable py realiza el metodo actualizarPermisosPerfil
     */
    public function editapermisosAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) { //Detectamos si es una llamada AJAX
            $opcion = $this->getRequest()->getParam('opcion');
            $id = $this->getRequest()->getParam('id');
            $perf_id = $this->getRequest()->getParam('perf_id');
            $obj = new Application_Model_DbTable_Permisos();
            $obj->actualizarPermisosPerfil($id, $opcion);
            echo $this->tabla_permisos($perf_id);
        }
    }
    /**
     * estadoAction()
     * * Esta accion actualiza el estado de un usuario mediate toggle button
     * * (ACTIVO / INACTIVO)
     * ! obtiene los datos mediante llamada ajax
     * @param estado dato enviado method POST via ajax (estado: ON o OFF)
     * @param id dato enviado method POST via ajax (id del usuario )
     * @param obj Crea un objeto tipo DbTable y realiza el metodo crear_permiso
     */

    public function estadoAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) { //Detectamos si es una llamada AJAX
            $estado = $this->getRequest()->getParam('opcion');
            $id = $this->getRequest()->getParam('id');
            $obj = new Application_Model_DbTable_Usuario();
            $obj->actualizarEstadoUsuario($id, $estado);
            $this->view->data_usuarios = $this->tabla_usuarios();
        }
    }
    
    /**
     * tabla_usuarios()
     * * Esta funcion crea el template HTML que sera mostrado en el view
     * ! se ejecuta despues de: index, crear, editar, eliminar USUARIOS
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
     */
    public function tabla_usuarios()
    {
        $obj = new Application_Model_DbTable_Usuario();
        $datosarea = $obj->listar_usuarios();
        $Listaarea = '';
        if (!$datosarea) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            $Listaarea .= '<table class="table  table-bordered table-sm dataTable" id="dataTableUsuarios" width="100%">
                <thead class="table-dark" >
                <tr>
                <th>ID</th>

                    <th>NOMBRE</th>
                    <th>CORREO</th>
                    <th>ALIAS</th>
                    <th>PERFIL</th>
                    <th>ESTADO</th>
                    <th>ULTIMO ACCESO</th>
                    <th>ACCION</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datosarea as $item):
                $estado = ($item->usu_estado_id == 1) ? "checked" : "";
            $Listaarea .= "<tr>";
            $Listaarea .= "<td>" . $item->usu_id . "</td>";
            $Listaarea .= "<td>" . strtoupper($item->usu_nombres) . " " . strtoupper($item->usu_apellidos) . "</td>";
            $Listaarea .= "<td>" . $item->correo . "</td>";
            $Listaarea .= "<td>" . $item->usu_iniciales . "</td>";
            $Listaarea .= "<td>" . strtoupper($item->perf_nombre) . "</td>";
            $Listaarea .= '<td class="text-center"><input class="toggle-event" type="checkbox" ' . $estado .  ' onchange="activaDesactivaUsuario('. $item->usu_id .','. $item->perf_id .','.$item->usu_estado_id.');"
                data-toggle="toggle" data-on="<i class=\'fas fa-check-circle\' ></i>" data-off="<i class=\'fas fa-ban\' ></i>"
			                data-onstyle="success" data-offstyle="danger" data-size="xs"
			                value=' . $item->usu_id . '></td>';
            $Listaarea .= "<td >" . $item->ultima_conexion . "</td>";
            $Listaarea .= " <td>
			                <div class='btn-group' role='group' aria-label='Basic example'>
			                <button type='button' class='btn btn-outline-dark btn-sm  border-0 '
			                onclick='editarModalU(" . $item->usu_id . ",`" . $item->usu_nombres . "`,`" . $item->usu_apellidos . "`,`" . $item->correo . "`," . $item->usu_estado_id . "," . $item->perf_id . ")' >
			                    <i class='far fa-edit  '></i>
			                </button>
			                <button type='button' class='btn btn-outline-danger btn-sm border-0 ' onclick='eliminarU(" . $item->usu_id . ",". $item->perf_id .")' >
			                    <i class='far fa-trash-alt'></i>
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
     * tabla_permisos()
     * * Esta funcion crea el template HTML que sera mostrado en el view
     * ! se ejecuta despues de: index, crear, editar, eliminar PERMISOS
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
        * @param id dato enviado method POST via ajax (id del permiso)

     */
    public function tabla_permisos($id)
    {
        $obj = new Application_Model_DbTable_Permisos();
        $datosarea =  $obj->listar_permisos_perfil($id);
        $Listaarea = '';
        if (!$datosarea) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            foreach ($datosarea as $item):
                $datos=  $obj->listar_permisos_usuario_ctrl($id, $item->ctrl_nombre);
            $Listaarea .= '<table class="table  table-bordered table-sm "  width="100%">';

            $Listaarea .= "<thead class='thead-dark'>";
            $Listaarea .= "<tr >";
            $Listaarea .= '<th colspan='.count($datosarea).' class="align-middle text-center">'.strtoupper($item->ctrl_nombre).'</th>';
            $Listaarea .= "</tr >";
            $Listaarea .= "</thead>";
            $Listaarea .= "<tbody class='text-center'>";
            $Listaarea .= "<tr >";
 
                          
            foreach ($datos as $i):
                $estado = ($i->permiso=='allow') ? "checked" : "";
                $accion_nombre = ($i->accion_nombre=='index') ? 'listar' : $i->accion_nombre;
                $Listaarea .= '<td  ><div class="d-flex justify-content-between align-middle"><strong>' . strtoupper($accion_nombre) . '</strong>
                        <div class="custom-control custom-checkbox small">
                        <input class="custom-control-input" onchange="activaDesactivaPermisos('. $i->usu_perm_id .',`'.$i->permiso.'`);"  
                        type="checkbox" '.$estado.'  value=' . $i->usu_perm_id . ' id="'.$i->usu_perm_id.'">
                        <label class="custom-control-label" for="'.$i->usu_perm_id.'"></label>
                        </div>
                        </div>
                        </td>';
            endforeach;
            $Listaarea .= "</tr>";
            $Listaarea .= "</tbody></table>";

            endforeach;
        }

        return $Listaarea;
    }
    /**
     * tabla_perfiles()
     * * Esta funcion crea el template HTML que sera mostrado en el view
     * ! se ejecuta despues de: index, crear, editar, eliminar PERFILES
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar_perfiles
        * @param id dato enviado method POST via ajax (id del permiso)
        * @param fc obtiene la baseURl() localhost/pgcam/public
     */
    public function tabla_perfiles()
    {
        $fc = Zend_Controller_Front::getInstance()->getRequest()->getBaseUrl();
        $obj = new Application_Model_DbTable_Perfiles();
        $datosarea = $obj->listar_perfiles();
        $Listaarea = '';
        if (!$datosarea) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            $Listaarea .= '<table class="table  table-bordered table-sm dataTable" id="dataTablePerfiles" width="100%">
                <thead class="thead-dark" >
                <tr>
                    <th>ID</th>
                    <th>DESCRIPCION</th>
                    <th class="text-center" title="Usuarios" ><i class="fas fa-users"></i></th>
                    <th>RUTA PREDETERMINADA</th>
                    <th>ESTADO</th>
                    <th>PERMISOS</th>
                    <th>ACCION</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datosarea as $item):
                $cuenta = $obj->cuenta_usuarios_perfil($item->perf_id);
            $estado= $obj->listar_controladores($item->perf_id, 1);
            $boton = (!$estado) ? 'disabled Title="Ya estan todos los permisos"' : '';
            $Listaarea .= "<tr>";
            $Listaarea .= "<td class='d-flex justify-content-between'>" . $item->perf_id . "<span class='badge badge-" . $item->perf_color . " '>&nbsp;</span></td>";
            $Listaarea .= "<td>" . strtoupper($item->perf_nombre) . "</td>";
            $Listaarea .= "<td class='text-center'>" . count($cuenta) . "</td>";
            $Listaarea .= "<td><a class='btn-link' href='" . $fc . "/" . $item->perf_controlador . "/" . $item->perf_accion . "'>
			                " . $fc . "/" . $item->perf_controlador . "/" . $item->perf_accion . "</a></td>";

            $Listaarea .= "<td>Activa</td>";
            $Listaarea .= '<td><div class="btn-group" role="group" aria-label="Basic example">
                <button type="button"  class="btn btn-dark btn-sm border-0" 
                                onclick="editarModalUpermisos(' . $item->perf_id . ',`' . $item->perf_nombre . '`)">
                                <i class="fas fa-user-lock  mr-2"></i><span class="text">Editar</span></button>
                                </div></td>';
            $Listaarea .= "<td>
			                    <div class='btn-group' role='group' aria-label='Basic example'>

			                        <!--  debo enviar la busqueda por ajax -->
                                    <button type='button' class='btn btn-outline-dark btn-sm  border-0 ' 
                                    onclick='editarModalUperfil(" . $item->perf_id . ",`" . $item->perf_nombre . "`,`" . $item->perf_color ."`)' >
			                            <i class='far fa-edit  '></i>
			                        </button>
			                        <button type='button' class='btn btn-outline-danger btn-sm border-0 ' onclick='eliminarPerfil(" . $item->perf_id . ")' >
			                            <i class='far fa-trash-alt'></i>
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
     * select_perfiles()
     * * Esta funcion crea el template SELECT HTML que sera mostrado en el view
     * * Obtiene una lista de los perfiles
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar_perfiles
     */
    public function select_perfiles()
    {
        $obj = new Application_Model_DbTable_Perfiles();
        $datosarea = $obj->listar_perfiles();
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
            $Listaarea .= '<label for="comboPerfil">Seleccione un perfil:</label>';

            $Listaarea .= '<select class="custom-select" name="comboPerfil" id="comboPerfil">';
            foreach ($datosarea as $item):
                $Listaarea .= "<option value='" . $item->perf_id . "'>" . strtoupper($item->perf_nombre) . "</option>";
            endforeach;
            $Listaarea .= "</select></div>";
        }
        return $Listaarea;
    }
    /**
     * select_estado()
     * * Esta funcion crea el template SELECT HTML que sera mostrado en el view
     * * Obtiene una lista de los estados ACTIVO - INACTIVO
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar_estado
     */
    public function select_estado()
    {
        $obj = new Application_Model_DbTable_Usuario();
        $datosarea = $obj->listar_estado();
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
            $Listaarea .= '<label for="comboEstado">Estado:</label>';

            $Listaarea .= '<select class="custom-select" name="comboEstado" id="comboEstado">';
            foreach ($datosarea as $item):
                $Listaarea .= "<option value='" . $item->usu_estado_id . "'>" . strtoupper($item->usu_estado_nombre) . "</option>";
            endforeach;
            $Listaarea .= "</select></div>";
        }
        return $Listaarea;
    }
    /**
     * selectcontroladoresAction()
     * * Esta funcion crea el template SELECT HTML que sera mostrado en el view
     * * Obtiene una lista de los controladores desde la bdd
     * ! obtiene los datos mediante llamada ajax
     * @param perfil dato enviado method POST via ajax (id del perfil)
     * @param op dato enviado method POST via ajax (0=rutas 1=PERMISOS)
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar_controladores
     */

    public function selectcontroladoresAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) { //Detectamos si es una llamada AJAX
            $perfil = $this->getRequest()->getParam('perfil');
            $op = $this->getRequest()->getParam('op');
        }
        $obj = new Application_Model_DbTable_Perfiles();
        $datosarea = $obj->listar_controladores($perfil, $op);
        $Listaarea = '';
        if (!$datosarea) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                </div>';
        } else {
            foreach ($datosarea as $item):
                $Listaarea .= "<option value='" . $item->ctrl_id . "'>" . strtoupper($item->ctrl_nombre) . "</option>";
            endforeach;
            $Listaarea .= "";
        }
        echo $Listaarea;
    }
    /**
     * selectaccionesAction()
     * * Esta funcion crea el template SELECT HTML que sera mostrado en el view
     * * Obtiene una lista de las acciones que corresponden a cada controlador desde la bdd
     * ! obtiene los datos mediante llamada ajax
     * @param perfil dato enviado method POST via ajax (id del perfil)
     * @param op dato enviado method POST via ajax (0=rutas 1=PERMISOS)
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar_acciones
     */

    public function selectaccionesAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) { //Detectamos si es una llamada AJAX
            $comboControladores = $this->getRequest()->getParam('comboControladores');
            $perfil = $this->getRequest()->getParam('perfil');
            $op = $this->getRequest()->getParam('op');
        }
        $obj = new Application_Model_DbTable_Perfiles();
        $datosarea = $obj->listar_acciones($comboControladores, $perfil, $op);
        $Listaarea = '';
        if (!$datosarea) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                </div>';
        } else {
            foreach ($datosarea as $item):
                $Listaarea .= "<option value='" . $item->accion_id . "'>" . strtoupper($item->accion_nombre) . "</option>";
            endforeach;
            $Listaarea .= "";
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
