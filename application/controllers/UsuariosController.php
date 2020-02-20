<?php

class UsuariosController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->initView();
        $this->view->baseUrl = $this->_request->getBaseUrl();
        $this->view->user = Zend_Auth::getInstance()->getIdentity();
        $this->view->controlador = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->view->accion = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        $this->view->titulo_formulario = "Usuarios";
        $this->view->icono = "fa-users-cog";
    }

    public function indexAction()
    {
        // action body
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/usuarios.js');
        echo $this->view->headScript();
        $this->view->data_usuarios = $this->tabla_usuarios();
        $this->view->data_perfiles = $this->select_perfiles();
        $this->view->data_estado = $this->select_estado();

        $this->view->titulo = "Lista de usuarios";

    }

    public function perfilesAction()
    {
        // action body
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/usuarios.js');
        echo $this->view->headScript();
        $this->view->data_perfiles = $this->tabla_perfiles();
        $this->view->titulo = "Lista de perfiles";
    }
    public function perfilAction()
    {
        // action body
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/usuarios.js');
        echo $this->view->headScript();
        $this->view->user = Zend_Auth::getInstance()->getIdentity();

        $this->view->titulo = "Informacion de usuario";
    }

    public function crearAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $nombres = $this->getRequest()->getParam('nombres');
            $apellidos = $this->getRequest()->getParam('apellidos');
            $correo = $this->getRequest()->getParam('correo');
            $perfil = $this->getRequest()->getParam('perfil');
            $estado = $this->getRequest()->getParam('estado');
            $clave="HGOPNA2020";
            $table = new Application_Model_DbTable_Usuario();
            $table->crearusuario($nombres,$apellidos, $correo, $clave,$perfil,$estado);
            echo $this->tabla_usuarios();
        }


    }

    public function editarAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $nombres = $this->getRequest()->getParam('nombres');
            $apellidos = $this->getRequest()->getParam('apellidos');
            $correo = $this->getRequest()->getParam('correo');
            $perfil = $this->getRequest()->getParam('perfil');
            $estado = $this->getRequest()->getParam('estado');
            $table = new Application_Model_DbTable_Usuario();
            $table->actualizarusuario($id,$nombres,$apellidos, $correo,$perfil,$estado);
            echo $this->tabla_usuarios();
        }

    }

    public function eliminarAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $id = $this->getRequest()->getParam('id');
            $table = new Application_Model_DbTable_Usuario();
            $table->eliminarusuario($id);
            echo $this->tabla_usuarios();
        }

    }

    public function tabla_usuarios()
    {
        $table_m = new Application_Model_DbTable_Usuario();
        $datosarea = $table_m->listar_usuarios();
        $Listaarea = '';
        if (!$datosarea) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {

            $Listaarea .= '<table class="table table-sm dataTable" id="dataTableUsuarios" width="100%">
                <thead>
                <tr>
                    <th class="text-primary">ID</th>
                    <th class="text-primary">NOMBRE</th>
                    <th class="text-primary">CORREO</th>
                    <th class="text-primary">ALIAS</th>
                    <th class="text-primary">PERFIL</th>
                    <th class="text-primary ">ESTADO</th>
                    <th class="text-primary">ACCION</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datosarea as $item):

                $Listaarea .= "<tr>";

                $Listaarea .= "<td>" . $item->usu_id . "</td>";
                $Listaarea .= "<td>" . $item->usu_nombres . " ". $item->usu_apellidos . "</td>";
                $Listaarea .= "<td>" . $item->correo . "</td>";
                $Listaarea .= "<td>" . $item->usu_iniciales . "</td>";
                $Listaarea .= "<td>" . $item->perf_nombre . "</td>";
                $Listaarea .= "<td>" . $item->usu_estado_nombre . "</td>";
                //$data_array =array($item->usu_id.','.$item->usu_nombres.','.$item->usu_apellidos.','.$item->correo);
                $Listaarea .= " <td>
                <div class='btn-group' role='group' aria-label='Basic example'>
                
                <!--  debo enviar la busqueda por ajax -->
                <button type='button' class='btn btn-outline-warning btn-sm ' 
                onclick='editarModal(". $item->usu_id .",`". $item->usu_nombres ."`,`". $item->usu_apellidos ."`,`". $item->correo ."`,". $item->usu_estado_id .",". $item->perf_id .")' >
                    <i class='fas fa-edit  '></i>
                </button>
                <button type='button' class='btn btn-outline-danger btn-sm' onclick='eliminar(". $item->usu_id .")' >
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

    public function tabla_perfiles()
    {
        $table_m = new Application_Model_DbTable_Usuario();
        $datosarea = $table_m->listar_perfiles();
        $Listaarea = '';
        if (!$datosarea) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {

            $Listaarea .= '<table class="table table-sm dataTable" id="dataTablePerfiles" width="100%">
                <thead>
                <tr>
                    <th class="text-primary">ID</th>
                    <th class="text-primary">DESCRIPCION</th>
                    <th class="text-primary">ESTADO</th>
                    <th class="text-primary ">ACCION</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datosarea as $item):

                $Listaarea .= "<tr>";
                $Listaarea .= "<td>" . $item->perf_id . "</td>";
                $Listaarea .= "<td>" . $item->perf_nombre . "</td>";

                $Listaarea .= '<td>Activa</td>
                    <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        
                        <!--  debo enviar la busqueda por ajax -->
                        <button type="button" class="btn btn-outline-warning btn-sm " onclick="editarModal('. $item->perf_id .')" >
                            <i class="fa fa-edit  "></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar('. $item->perf_id .')" >
                            <i class="fa fa-trash "></i>
                        </button>
                        </div>
                        
                    </td>
                </tr>';
            endforeach;

            $Listaarea .= "</tbody></table>";
        }

        return $Listaarea;
    }

    public function select_perfiles()
    {
        $table_m = new Application_Model_DbTable_Usuario();
        $datosarea = $table_m->listar_perfiles();
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

            $Listaarea .= '<select class="form-control" name="comboPerfil" id="comboPerfil">';
            foreach ($datosarea as $item):
                $Listaarea .= "<option value='". $item->perf_id ."'>" . $item->perf_nombre . "</option>";
            endforeach;
            $Listaarea .= "</select></div>";
        }
        return $Listaarea;
    }

    public function select_estado()
    {
        $table_m = new Application_Model_DbTable_Usuario();
        $datosarea = $table_m->listar_estado();
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

            $Listaarea .= '<select class="form-control" name="comboEstado" id="comboEstado">';
            foreach ($datosarea as $item):
                $Listaarea .= "<option value='". $item->usu_estado_id ."'>" . $item->usu_estado_nombre . "</option>";
            endforeach;
            $Listaarea .= "</select></div>";
        }
        return $Listaarea;
    }

    


}





