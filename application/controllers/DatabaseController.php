<?php

class DatabaseController extends Zend_Controller_Action
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
        $this->view->titulo_formulario = "Area";
        $this->view->icono = "fa-server";
    }
    /**
     * indexAction()
     * * Esta accion lista las areas de la bbd
     * ! importamos el archivo areas.js
     * @param data obtiene la data del metodo tabla_area()
     * @param titulo almacena el nombre de la vista, se mostrara en el titulo de la pagina
     */
    public function indexAction()
    {
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/bdd.js');
        echo $this->view->headScript();
        $this->view->data_tablas = $this->tablas();
        $this->view->titulo="Base de datos";
    }
    
    /**
     * tabla_area()
     * * Esta funcion crea el template HTML que sera mostrado en el view
     * ! se ejecuta despues de: index, crear, editar, eliminar
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
     */

    public function tablas()
    {
        $obj = new Application_Model_DbTable_Bdd();
        $data = $obj->listarTablas();
        $Listaarea = '';
        if (!$data) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron result ados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            $Listaarea .= '<div class="row row-cols-1 row-cols-md-3" >';

            foreach ($data as $item):
                $Listaarea .= '<div class="col mb-2 " >';
            $Listaarea .= '<h5>Tabla: '. $item->tabla_nombre .'</h5>';

            $dicc = $obj->diccionarioTabla($item->tabla_nombre);
            $llaves = $obj->llavesTabla($item->tabla_nombre);
                
            $Listaarea .= '<table class="table table-bordered  table-sm"  width="100%" >
                    <thead class="table-dark text-center">';
            $Listaarea .= "<tr>";
            $Listaarea .="<th>LLAVE</th>";
            $Listaarea .="<th>COLUMNA</th>";
            $Listaarea .="<th>TIPO</th>";
            $Listaarea .="<th>NULO</th>";
            $Listaarea .="<th>DESCRIPCION</th>";
            $Listaarea .= "</tr>";

            $Listaarea .= '</thead>
                    <tbody>';
                

            foreach ($dicc as $d):
                    foreach ($llaves as $l):
                        $pk = (($d->columna_nombre==$l->column_name) and ($l->constraint_type=='PRIMARY KEY')) ? 'PK' : '';
            endforeach;
            $Listaarea .= "<tr>";
            $Listaarea .= "<td class='text-center'>" . $pk . "</td>";
            $Listaarea .= "<td>" . $d->columna_nombre . "</td>";

            $Listaarea .= "<td>" . $d->columna_tipo_dato . "(" . $d->columna_longitud . ")</td>";
            $Listaarea .= "<td>" . $d->columna_nulo . "</td>";
            $Listaarea .= "<td>" . $d->columna_descripcion . "</td>";


            $Listaarea .= "</tr>";

            endforeach;
            $Listaarea .= "</tbody></table>";
            $Listaarea .= '</div >';




            endforeach;
            $Listaarea .= "</div>";
        }

        return $Listaarea;
    }
}
