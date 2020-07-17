<?php

class SalaController extends Zend_Controller_Action
{

    /**
     * init()
     * * Esta funcion se ejecuta antes de cualquier action
     * ! Se pueden setear variables globales para verlas en las views
     * ?
     * TODO: ninguna
     * @param user almacena los datos de sesion
     * @param controlador,accion almacena el nombre del controlador y de la accion
     * respectivamente
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
     * * Esta accion muestra los pacientes internos
     */
    public function indexAction()
    {
         $this->_helper->layout->setLayout('sala');
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/sala.js');
        echo $this->view->headScript();
        $this->view->titulo="Pacientes Internos";

        $this->view->data = $this->tabla_pacientes_sala();

    }
/**
     * tabla_pacientes_sala()
     * * Esta funcion crea el template HTML que sera mostrado en el view
     * ! se ejecuta despues de: index, crear, editar, eliminar
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
     */
    public function tabla_pacientes_sala()
    {
        $obj = new Application_Model_DbTable_Admision();
        $data_paciente_cama = $obj->listarPacientesCamaSala();
        $cadena_paciente_cama = '';
        if (!$data_paciente_cama) {
            $cadena_paciente_cama .= '';
        } else {
            $cadena_paciente_cama .= '<table class="table text-dark "  data-page-length="10" id="TablePacienteCamaSala" width="100%" >
                <thead class="bg-primary text-white">
                <tr >
                <!--<th >FECHA</th>
                    <th >HORA</th>-->
                    <th >PACIENTE</th>
                    <th >ESPECIALIDAD</th>
                    <th >SALA</th>
                    <th >HABITACION</th>
                    <th >CAMA</th>
                    
                </tr>
                </thead>
                <tbody id="dataBody">';
            for ($i=0; $i < 5; $i++) {
                foreach ($data_paciente_cama as $item):
                    $sala=($item->cama_tipo_descripcion=='NINGUNA')?'':$item->cama_tipo_descripcion;
                    $arrayFecha = explode(' ', $item->fecha_ingreso);

                    $data = $obj->Paciente_info($item->p_id, $item->paciente_ci, $item->entrada);
                    $cadena_paciente_cama .= "<tr>";
                    //$cadena_paciente_cama .= "<td>" . $arrayFecha[0] . "</td>";
                    //$cadena_paciente_cama .= "<td>" . $arrayFecha[1] . "</td>";

                    $cadena_paciente_cama .= "<td>". $data->nombre ."</td>";

                    $cadena_paciente_cama .= "<td>" . $item->especialidad_nombre . "</td>";
                    $cadena_paciente_cama .= "<td>" . $sala . "</td>";
                    $cadena_paciente_cama .= "<td>" . $item->habitacion_nombre . "</td>";
                    $cadena_paciente_cama .= "<td>" . $item->cama_nombre . "</td>";
                                

                    $cadena_paciente_cama .= "</tr>";
                    $index++;
                endforeach;
            }
            

            $cadena_paciente_cama .= "</tbody></table>";
        }
        


        return $cadena_paciente_cama;
    }

}

