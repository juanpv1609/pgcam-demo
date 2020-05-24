<?php

class Cie10Controller extends Zend_Controller_Action
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
        $this->view->icono = "fa-heartbeat";
        
    }
    /**
     * indexAction()
     * * Esta accion no se encuentra en uso, redirecciona a descripcion view
     */

    public function indexAction()
    {
        $this->_helper->redirector('descripcion', 'cie10'); //direccionamos al listar

    }
    /**
     * descripcionAction()
     * * Esta accion muestra el formulario de busqueda 
     * * de los diagnosticos CIE10 por descripcion o codigo
     * ! importamos el archivo cie10.js
     * */

    public function descripcionAction()
    {
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/cie10.js');
        echo $this->view->headScript();
        $this->view->titulo = "Diagnosticos por descripcion";
    }
    /**
     * categoriaAction()
     * * Esta accion muestra el formulario de busqueda 
     * * de los diagnosticos CIE10 por categoria
     * ! importamos el archivo cie10.js
     * */
    public function categoriaAction()
    {
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/cie10.js');
        echo $this->view->headScript();
        $this->view->titulo = "Diagnosticos por categoria";
    }
    /**
     * capituloAction()
     * * Esta accion muestra el formulario de busqueda 
     * * de los diagnosticos CIE10 por capitulo
     * ! importamos el archivo cie10.js
     * */
    public function capituloAction()
    {
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/cie10.js');
        echo $this->view->headScript();
        $this->view->titulo = "Diagnosticos por capitulo";
        $this->view->data = $this->tabla_capitulo();
    }
     /**
     * listaAction()
     * * Esta accion muestra una lista de diagnosticos ingrsados por busqueda por el usuario
     * * Usada en el formulario de asignacion de cama
     * ! obtiene los datos mediante llamada ajax
     * @param dato ontenido mediante ajax, es el campo que se pretende buscar
     * @param id este campo es el input desde el cual se hace la busqueda, para que el resultado se muestre debajo de el
     * ? finalmente llama al metodo lista_cie10 que imprime un template HTML
    
     */

    public function listaAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $dato = $this->getRequest()->getParam('dato');
            $id = $this->getRequest()->getParam('id');
            echo $this->lista_cie10($dato,$id);
        }
    }
    /**
     * buscaAction()
     * * Esta accion muestra una tabla de diagnosticos ingrsados por busqueda por el usuario
     * * Usada en la vista descripcion
     * ! obtiene los datos mediante llamada ajax
     * @param dato ontenido mediante ajax, es el campo que se pretende buscar
     * ? finalmente llama al metodo tabla_cie10 que imprime un template HTML
     */

    public function buscaAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $dato = $this->getRequest()->getParam('dato');
            echo $this->tabla_cie10($dato);
        }
    }
    /**
     * buscaAbuscacategoriaActionction()
     * * Esta accion muestra una tabla de diagnosticos por categoria ingrsados por busqueda por el usuario
     * * Usada en la vista categoria
     * ! obtiene los datos mediante llamada ajax
     * @param dato ontenido mediante ajax, es el campo que se pretende buscar
     * ? finalmente llama al metodo tabla_cie10_categoria que imprime un template HTML
     */

    public function buscacategoriaAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $dato = $this->getRequest()->getParam('dato');
            echo $this->tabla_cie10_categoria($dato);
        }
    }
    /**
     * lista_cie10()
     * * Esta funcion crea el template HTML que sera mostrado en el view
     * @param dato ontenido mediante ajax, es el campo que se pretende buscar
     * @param id este campo es el input desde el cual se hace la busqueda, para que el resultado se muestre debajo de el
     
     * @param obj Crea un objeto tipo DbTable y realiza el metodo listar
     */

    public function lista_cie10($dato,$id)
    {
        $obj = new Application_Model_DbTable_Cie10();
        $datos = $obj->listar($dato);
        $cadena = '';
        if (!$datos) {
            $cadena .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            $cadena .= '<div class="list-group list-btn-group-sm ">';
            foreach ($datos as $item):

                $cadena .= '<button type="button" id="elemento" class="list-group-item list-group-item-action list-group-item-light text-xxs p-2" title="Clic para seleccionar"
                onclick="setDiagnostico(`'.$id.'`,`'. $item->sub_cod .'`,`'. $item->descripcion_sub .'`);">'. $item->sub_cod .'-'. $item->descripcion_sub .'</button>';
            endforeach;

            $cadena .= "</div>";
        }

        return $cadena;
    }
    /**
     * tabla_cie10()
     * * Esta funcion crea el template HTML que sera mostrado en el view
     * @param dato ontenido mediante ajax, es el campo que se pretende buscar
     * @param obj Crea un objeto tipo DbTable y realiza el metodo listar
     */

    public function tabla_cie10($dato)
    {
        $obj = new Application_Model_DbTable_Cie10();
        $datos = $obj->listar($dato);
        $cadena = '';
        if (!$datos) {
            $cadena .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            $cadena .= '<table class="table  table-bordered table-sm dataTable" id="dataTableCie10" width="100%">
                <thead class="table-dark" >
                <tr>
                    <th >COD</th>
                    <th >SUB CATEGORIA</th>
                    <th >CATEGORIA</th>
                    <th ></th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datos as $item):

                $cadena .= "<tr>";
            $cadena .= "<td>" . $item->sub_cod . "</td>";
            $cadena .= "<td>" . $item->descripcion_sub . "</td>";
            $cadena .= "<td>" . $item->descripcion . "</td>";
            $cadena .= "<td></td>";



            $cadena .= '</tr>';
            endforeach;

            $cadena .= "</tbody></table>";
        }

        return $cadena;
    }
    /**
     * tabla_cie10_categoria()
     * * Esta funcion crea el template HTML que sera mostrado en el view
     * @param dato ontenido mediante ajax, es el campo que se pretende buscar
     * @param obj Crea un objeto tipo DbTable y realiza el metodo listar_categoria
     */

    public function tabla_cie10_categoria($dato)
    {
        $obj = new Application_Model_DbTable_Cie10();
        $datos = $obj->listar_categoria($dato);
        $cadena = '';
        if (!$datos) {
            $cadena .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            $cadena .= '<table class="table  table-bordered table-sm dataTable" id="dataTableCie10Cat" width="100%">
                <thead class="table-dark" >
                <tr>
                    <th >COD</th>
                    <th >DESCRIPCION CATEGORIA</th>
                    <th >SUBCAPITULO</th>
                    <th >CAPITULO</th>
                    <th ></th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datos as $item):

                $cadena .= "<tr>";
            $cadena .= "<td>" . $item->cie10_categoria_id . "</td>";
            $cadena .= "<td>" . $item->descripcion . "</td>";
            $cadena .= "<td>" . $item->descripcion_sub . "</td>";
            $cadena .= "<td>" . $item->des_capitulo . "</td>";
            $cadena .= "<td></td>";



            $cadena .= '</tr>';
            endforeach;

            $cadena .= "</tbody></table>";
        }

        return $cadena;
    }
    /**
     * tabla_cie10_categoria()
     * * Esta funcion crea el template HTML que sera mostrado en el view
     * * para esta funcion no es necesario realizar una busqueda 
     * @param dato ontenido mediante ajax, es el campo que se pretende buscar
     * @param obj Crea un objeto tipo DbTable y realiza el metodo listar_capitulo
     */

    public function tabla_capitulo()
    {
        $obj = new Application_Model_DbTable_Cie10();
        $datos = $obj->listar_capitulo();
        $cadena = '';
        if (!$datos) {
            $cadena .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            $cadena .= '<table class="table  table-bordered table-sm dataTable" id="dataTableCie10Capitulo" width="100%">
                <thead class="table-dark" >
                <tr>
                    <th >COD</th>
                    <th >CAPITULO</th>
                </tr>
                </thead>
                <tbody>';
            foreach ($datos as $item):

                $cadena .= "<tr>";
            $cadena .= "<td>" . $item->cie10_capitulo_id . "</td>";
            $cadena .= "<td>" . $item->descripcion . "</td>";
            /* $subcapitulo = $table_m->listar_sub_capitulo($item->cie10_capitulo_id);
            foreach ($subcapitulo as $sub):
                $cadena .= "<td>" . $sub->descripcion_sub . "</td>";

            endforeach; */

            $cadena .= '</tr>';
            endforeach;

            $cadena .= "</tbody></table>";
        }

        return $cadena;
    }
}
