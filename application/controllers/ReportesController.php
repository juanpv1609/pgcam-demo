<?php

class ReportesController extends Zend_Controller_Action
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
        $this->view->icono = "fa-chart-line";
    }

    /**
     * indexAction()
     * * Esta accion lista las areas de la bbd
     * ! importamos el archivo areas.js
     * @param data obtiene la data del metodo tabla_area()
     * @param titulo almacena el nombre de la vista, se mostrara en el titulo de la
     * pagina
     */
    public function indexAction()
    {
        
        //$this->view->data = $this->tabla_area();
        $this->view->titulo="Reportes";
    }

    public function generalAction()
    {
        // action body
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/reportes.js');
        echo $this->view->headScript();

        $this->view->titulo="Reporte General";
        $this->view->titulo_formulario = "Disponibilidad de Camas General";
    }

    public function servicioAction()
    {
        // action body
        $this->view->headScript()->appendFile($this->_request->getBaseUrl().'/functions/reportes.js');
        echo $this->view->headScript();
        $this->view->titulo="Reporte por Servicio";
        $this->view->titulo_formulario = "Disponibilidad de Camas por Servicio";
        $this->view->data_especialidades = $this->select_especialidades();
    }
    /**
     * especialidadAction()
     * * Esta accion cuenta las camas totales
     * ! obtiene los datos mediante llamada ajax
     * @param data obtiene la data del metodo camasEstado()
     * */

    public function especialidadAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) {//Detectamos si es una llamada AJAX
            $especialidad = $this->getRequest()->getParam('especialidad');
            $sala = $this->getRequest()->getParam('sala');
            $fecha = $this->getRequest()->getParam('fecha');
            echo $this->tabla_pacientes_servicio($especialidad, $sala, $fecha);
        }
    }
    /**
         * select_especialidades()
         * * Esta funcion crea el template SELECT HTML que sera mostrado en el view
         * * Obtiene una lista de las especialidades
         * ! se ejecuta despues de: index, crear, editar, eliminar
         * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
         */

    public function select_especialidades()
    {
        $obj = new Application_Model_DbTable_Especialidades();
        $datos = $obj->listar();
        $Lista = '<div  class="form-group">';
        if (!$datos) {
            $Lista .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div></div>';
        } else {
            $Lista .= '<label for="comboEspecialidad" class="text-dark mb-0">Especialidad:</label>
            <select class="form-control js-example-basic-single" name="comboEspecialidad" id="comboEspecialidad" required
            data-placeholder="Seleccione un Servicio" onchange="getSala();">
            <option value="" ></option>';
            foreach ($datos as $item):
                $Lista .= "<option value='". $item->especialidad_id ."'>" . $item->especialidad_nombre . "</option>";
            endforeach;
            $Lista .= "</select></div>";
        }
        return $Lista;
    }
    public function selectsalaAction()
    {
        $this->_helper->viewRenderer->setNoRender(); //No necesitamos el render de la vista en una llamada ajax.
        $this->_helper->layout->disableLayout(); // Solo si estas usando Zend_Layout
        if ($this->getRequest()->isXmlHttpRequest()) { //Detectamos si es una llamada AJAX
            $comboEspecialidad = $this->getRequest()->getParam('especialidad');
        }
        $obj = new Application_Model_DbTable_Camas();
        $datos = $obj->listar_tipo_cama_servicio($comboEspecialidad);
        $Lista = '<div  class="form-group">';
        if (!$datos) {
            $Lista .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div></div>';
        } else {
            $Lista .= '<label for="comboSala" class="text-dark mb-0">Sala:</label>
            <select class="form-control js-example-basic-single" name="comboSala" id="comboSala" required
            data-placeholder="Seleccione una Especialidad" >
            <option value="" ></option>';

            foreach ($datos as $item):
                $Lista .= "<option value='" . $item->cama_tipo_id . "'>" . strtoupper($item->cama_tipo_descripcion) . "</option>";
            endforeach;
            $Lista .= "";
            $Lista .= "</select></div>";
        }
        echo $Lista;
    }
    /**
     * tabla_pacientes()
     * * Esta funcion crea el template HTML que sera mostrado en el view
     * ! se ejecuta despues de: index, crear, editar, eliminar
     * @param obj Crea un objeto tipo DbTable py realiza el metodo listar
     */
    public function tabla_pacientes_servicio($especialidad, $sala, $fecha)
    {
        $obj = new Application_Model_DbTable_Estadistica();
        $total_ingresos = count($obj->listarIngresos($especialidad, $sala, $fecha));
        $total_egresos =count( $obj->listarEgresos($especialidad, $sala, $fecha));
        $total_entra = count($obj->listarTransEntra($especialidad, $sala, $fecha));
        $total_sale = count($obj->listarTransSale($especialidad, $sala, $fecha));
        $total_defunciones = count($obj->listarDefunciones($especialidad, $sala, $fecha));
        $data_camas = $obj->camasEstadoEspecialidad($especialidad, $sala);
        $total_camas=($data_camas[0]->cuenta_camas)+($data_camas[1]->cuenta_camas)-($data_camas[2]->cuenta_camas);

        $pacientes_oh=0;
        $cadena_ingresos = $this->ingresos($especialidad, $sala, $fecha);
        $cadena_egresos = $this->egresos($especialidad, $sala, $fecha);
        $cadena_transferencias_entra = $this->transferenciasEntra($especialidad, $sala, $fecha);
        $cadena_transferencias_sale = $this->transferenciasSale($especialidad, $sala, $fecha);
        $cadena_defunciones = $this->defunciones($especialidad, $sala, $fecha);

        $cabecera=$this->cabecera($especialidad, $sala, $fecha);
        $cadena='';      

        

                $cadena.='<div class="row py-2">
                    <div class="col-sm-12">'.$cabecera.'
                    </div>
                    </div>';

        
                    $cadena.='<div class="row py-2">
                    <div class="col-sm-6 pr-4">'.$cadena_ingresos.'
                    </div>
                    <div class="col-sm-6 pl-4">'.$cadena_egresos.'
                    </div>
                    </div>';

        $cadena.='<div class="row py-2">
                    <div class="col-sm-6 pr-4">'.$cadena_transferencias_entra.'
                    </div>
                    <div class="col-sm-6 pl-4">'.$cadena_transferencias_sale.'
                    </div>
                    </div>';
        $cadena.='<div class="row py-2">
                    <div class="col-sm-6">'.$cadena_defunciones.'
                    </div>
                    <div class="col-sm-6">
                    </div>
                    </div>';

$cadena_resumen = '<h6 class="text-center text-dark text-xxs font-weight-bold"><u>RESUMEN DEL DIA</u></h6 >';
        $cadena_resumen.='<table class="table table-sm table-borderedd text-dark" width="100%">
					<thead class="text-center">
						<tr >
							<td rowspan="0"></td>
							<td colspan="2" >INGRESOS</td>
							<th rowspan="0"></th>
							<td colspan="4">EGRESOS</td>
							<th rowspan="0"></th>
							<th rowspan="0"></th>
							<td colspan="3">RESUMEN DE CAMAS</td>
							<th rowspan="0"></th>
							<th rowspan="0"></th>
						</tr>
					</thead>
					<tbody>
						<tr class="text-center" style="font-size:9px;" >
							<td >EXISTENCIA PACIENTES O H</td>
							<td style="width: 5%;">INGRESOS</td>
							<td style="width: 5%;">TRANSFERENCIAS DE OTRAS ESPECIALIDADES</td>
							<td style="width: 5%;">TOTAL (2+3)</td>
							<td style="width: 5%;">ALTAS</td>
							<td>TRANSFERENCIAS A OTRAS ESPECIALIDADES</td>
							<td style="width: 5%;">DEF +48 H</td>
							<td style="width: 5%;">DEF -48 H</td>
							<td>TOTAL (5+6+7+8)</td>
							<td>TOTAL DIAS PACIENTE (1+4-6)</td>
							<td>CAMAS OCUPADAS</td>
							<td>CAMAS DESOCUPADAS</td>
							<td>CAMAS DAÑADAS O CONTAMINADAS</td>
							<td>CAMAS DISPONIBLES A 24 H. (11+12-13)</td>
							<td>TOTAL PACIENTES A LAS 24 H. (1+4-9)</td>
						</tr>
                        <tr class="bg bg-secondary text-white text-center">';
                        for ($i=1; $i <=15 ; $i++) {
                            $cadena_resumen.='<th>'.$i.'</th>';
                        }
                        $cadena_resumen.='</tr>';

                         $cadena_resumen.='<tr class="text-center">
                         <td>'.$pacientes_oh.'</td>
							<td>'.$total_ingresos.'</td>
							<td>'.$total_entra.'</td>
							<td>'.($total_ingresos+$total_entra).'</td>
							<td>'.$total_egresos.'</td>
							<td>'.$total_sale.'</td>
							<td>'.$total_defunciones.'</td>
							<td>'.$total_defunciones.'</td>
							<td>'.($total_egresos+$total_sale+$total_defunciones).'</td>
							<td>'.($pacientes_oh+($total_ingresos+$total_entra)-$total_sale).'</td>
							<td>'.$data_camas[1]->cuenta_camas.'</td>
							<td>'.$data_camas[0]->cuenta_camas.'</td>
							<td>'.$data_camas[2]->cuenta_camas.'</td>
							<td>'.$total_camas.'</td>
							<td>'.($pacientes_oh+($total_ingresos+$total_entra)-($total_egresos+$total_sale+$total_defunciones)).'</td>
														
						</tr>
					</tbody>
                </table>';

        $cadena.='<div class="row py-4">
                    <div class="col-sm-12">'.$cadena_resumen.'
                    </div>
                    </div>';

                    
        return $cadena;
    }
    public function cabecera($especialidad, $sala, $fecha)
    {
        $obj_cabecera = new Application_Model_DbTable_Camas();
        $data_cabecera=$obj_cabecera->busca_especialidad($especialidad, $sala);

        $arrayFecha= explode('-', $fecha);
        $cabecera.='<table class="table table-sm table-borderedd text-dark" id="tableIngresos" width="100%" >
                <thead >
                <tr >
                    <th >ESPECIALIDAD</th>
                    <th >SALA</th>
                    <th style="width: 40%;" class="text-center" colspan="3"><span >FECHA</span></th>
                </tr>
                </thead>
                <tbody>';
        $cabecera.=' <tr>
                        <td>'.$data_cabecera[0]->especialidad_nombre.'</td>
                        <td>'.$data_cabecera[0]->cama_tipo_descripcion.'</td>
                        <td >AÑO:'.$arrayFecha[0].'</td><td >MES:'.$arrayFecha[1].'</td><td >DIA:'.$arrayFecha[2].'</td>
                        </tr>';
        $cabecera.=' </tbody></table>';
        return $cabecera;
    }
    public function ingresos($especialidad, $sala, $fecha)
    {
        $obj = new Application_Model_DbTable_Estadistica();
        $data_ingresos = $obj->listarIngresos($especialidad, $sala, $fecha);
        $cadena_ingresos = '';  
        $total_ingresos=0;
    
        if (!$data_ingresos) {
            $cadena_ingresos.= '<h6 class="text-center text-dark text-xxs font-weight-bold"><u>INGRESOS</u></h6 >';
            $cadena_ingresos.='<table class="table table-sm table-borderedd text-dark" id="tableIngresos" width="100%" >
                <thead >
                <tr >
                    <td style="width: 10%;">CAMA</td>
                    <td >NO. HISTORIA CLINICA</td>
                    <td >APELLIDOS Y NOMBRES</td>
                </tr>
                </thead>
                <tbody>';
            $cadena_ingresos.=' <tr><td>&nbsp;</td><td></td><td></td></tr>';
            $cadena_ingresos .= "<tr >";
            $cadena_ingresos .= "<td colspan='3'>TOTAL INGRESOS: ".$total_ingresos."</td>";
            $cadena_ingresos .= "</tr >";
            $cadena_ingresos.=' </tbody></table>';
        } else {
            $cadena_ingresos .= '<h6 class="text-center text-dark text-xxs font-weight-bold"><u>INGRESOS</u></h6 >';
            $cadena_ingresos .= '<table class="table table-sm table-borderedd text-dark" id="tableIngresos" width="100%" >
                <thead >
                <tr >
                    <td style="width: 10%;">CAMA</td>
                    <td >NO. HISTORIA CLINICA</td>
                    <td >APELLIDOS Y NOMBRES</td>
                </tr>
                </thead>
                <tbody>';
            $total_ingresos=count($data_ingresos);
            foreach ($data_ingresos as $item):
                $data = $obj->Paciente_info($item->p_id, $item->paciente_ci, $item->entrada);
                $cadena_ingresos .= "<tr >";
                $cadena_ingresos .= "<td class='text-center'>" . $item->cama_nombre . "</td>";
                $cadena_ingresos .= "<td>". $item->paciente_ci ."</td>";
                $cadena_ingresos .= "<td>" . $data->nombre . "</td>";
                $cadena_ingresos .= "</td>";
                $cadena_ingresos .= "</tr>";
            endforeach;
            $cadena_ingresos .= "<tr >";
            $cadena_ingresos .= "<td colspan='3'>TOTAL INGRESOS: ".$total_ingresos."</td>";
            $cadena_ingresos .= "</tr >";

            $cadena_ingresos .= "</tbody></table>";
        }
        return $cadena_ingresos;
    }
    public function egresos($especialidad, $sala, $fecha)
    {
        $obj = new Application_Model_DbTable_Estadistica();
        $data_egresos = $obj->listarEgresos($especialidad, $sala, $fecha);
        $cadena_egresos = '';
        $total_egresos=0;
    

        if (!$data_egresos) {
            $cadena_egresos.= '<h6 class="text-center text-dark text-xxs font-weight-bold"><u>EGRESOS</u></h6 >';
            $cadena_egresos.='<table class="table table-sm table-borderedd text-dark"  id="tableIngresos" width="100%" >
                <thead >
                <tr >
                    <td style="width: 10%;">CAMA</td>
                    <td >NO. HISTORIA CLINICA</td>
                    <td >APELLIDOS Y NOMBRES</td>
                </tr>
                </thead>
                <tbody>';
                $cadena_egresos.=' <tr><td>&nbsp;</td><td></td><td></td></tr>';
            $cadena_egresos .= "<tr >";
            $cadena_egresos .= "<td colspan='3'>TOTAL EGRESOS: ".$total_egresos."</td>";
            $cadena_egresos .= "</tr >";

            $cadena_egresos.=' </tbody></table>';
        } else {
            $cadena_egresos .= '<h6 class="text-center text-dark text-xxs font-weight-bold"><u>EGRESOS</u></h6 >';
            $cadena_egresos .= '<table class="table table-sm table-borderedd text-dark"  id="tableIngresos" width="100%" >
                <thead >
                <tr >
                    <td style="width: 10%;">CAMA</td>
                    <td >NO. HISTORIA CLINICA</td>
                    <td >APELLIDOS Y NOMBRES</td>
                </tr>
                </thead>
                <tbody>';
            $total_egresos=count($data_egresos);
            foreach ($data_egresos as $item):
                $data = $obj->Paciente_info($item->p_id, $item->paciente_ci, $item->entrada);
            $cadena_egresos .= "<tr >";
            $cadena_egresos .= "<td class='text-center'>" . $item->cama_nombre . "</td>";
            $cadena_egresos .= "<td>". $item->paciente_ci ."</td>";
            $cadena_egresos .= "<td>" . $data->nombre . "</td>";
            $cadena_egresos .= "</td>";
            $cadena_egresos .= "</tr>";
            endforeach;
            $cadena_egresos .= "<tr >";
            $cadena_egresos .= "<td colspan='3'>TOTAL EGRESOS: ".$total_egresos."</td>";
            $cadena_egresos .= "</tr >";
            $cadena_egresos .= "</tbody></table>";
        }
        return $cadena_egresos;
    }
    public function transferenciasEntra($especialidad, $sala, $fecha)
    {
        $obj = new Application_Model_DbTable_Estadistica();
        $data_entra = $obj->listarTransEntra($especialidad, $sala, $fecha);
        $cadena_transferencias_entra = '';
        $total_entra=0;
    
        if (!$data_entra) {
            $cadena_transferencias_entra.= '<h6 class="text-center text-dark text-xxs font-weight-bold"><u>TRANSFERENCIAS DE OTRAS ESPECIALIDADES</u></h6 >';
            $cadena_transferencias_entra.='<table class="table table-sm table-borderedd text-dark"  id="tableIngresos" width="100%" >
                <thead >
                <tr >
                    <td style="width: 10%;">CAMA</td>
                    <td >NO. HISTORIA CLINICA</td>
                    <td >APELLIDOS Y NOMBRES</td>
                </tr>
                </thead>
                <tbody>';
                $cadena_transferencias_entra.=' <tr><td>&nbsp;</td><td></td><td></td></tr>';
            $cadena_transferencias_entra .= "<tr >";
            $cadena_transferencias_entra .= "<td colspan='3'>TOTAL TRANSFERENCIAS DE OTRAS ESPECIALIDADES: ".$total_entra."</td>";
            $cadena_transferencias_entra .= "</tr >";
            $cadena_transferencias_entra.=' </tbody></table>';
        } else {
            $cadena_transferencias_entra .= '<h6 class="text-center text-dark text-xxs font-weight-bold"><u>TRANSFERENCIAS DE OTRAS ESPECIALIDADES</u></h6 >';
            $cadena_transferencias_entra .= '<table class="table table-sm table-borderedd text-dark" id="tableIngresos" width="100%" >
                <thead >
                <tr >
                    <td style="width: 10%;">CAMA</td>
                    <td >NO. HISTORIA CLINICA</td>
                    <td >APELLIDOS Y NOMBRES</td>
                </tr>
                </thead>
                <tbody>';
            $total_entra=count($data_entra);
            foreach ($data_entra as $item):
                $data = $obj->Paciente_info($item->p_id, $item->paciente_ci, $item->entrada);
                $cadena_transferencias_entra .= "<tr >";
                $cadena_transferencias_entra .= "<td class='text-center'>" . $item->cama_nombre . "</td>";
                $cadena_transferencias_entra .= "<td>". $item->paciente_ci ."</td>";
                $cadena_transferencias_entra .= "<td>" . $data->nombre . "</td>";
                $cadena_transferencias_entra .= "</td>";
                $cadena_transferencias_entra .= "</tr>";
            endforeach;
            $cadena_transferencias_entra .= "<tr >";
            $cadena_transferencias_entra .= "<td colspan='3'>TOTAL TRANSFERENCIAS DE OTRAS ESPECIALIDADES: ".$total_entra."</td>";
            $cadena_transferencias_entra .= "</tr >";
            $cadena_transferencias_entra .= "</tbody></table>";
        }
        return $cadena_transferencias_entra;
    }
    public function transferenciasSale($especialidad, $sala, $fecha)
    {
        $obj = new Application_Model_DbTable_Estadistica();
        $data_sale = $obj->listarTransSale($especialidad, $sala, $fecha);
        $cadena_transferencias_sale = '';
        $total_sale=0;
    
        if (!$data_sale) {
            $cadena_transferencias_sale.= '<h6 class="text-center text-dark text-xxs font-weight-bold"><u>TRANSFERENCIAS A OTRAS ESPECIALIDADES</u></h6 >';
            $cadena_transferencias_sale.='<table class="table table-sm table-borderedd text-dark" id="tableIngresos" width="100%" >
                <thead >
                <tr >
                    <td style="width: 10%;">CAMA</td>
                    <td >NO. HISTORIA CLINICA</td>
                    <td >APELLIDOS Y NOMBRES</td>
                </tr>
                </thead>
                <tbody>';
                $cadena_transferencias_sale.=' <tr><td>&nbsp;</td><td></td><td></td></tr>';
            $cadena_transferencias_sale .= "<tr >";
            $cadena_transferencias_sale .= "<td colspan='3'>TOTAL TRANSFERENCIAS A OTRAS ESPECIALIDADES: ".$total_sale."</td>";
            $cadena_transferencias_sale .= "</tr >";
            $cadena_transferencias_sale.=' </tbody></table>';
        } else {
            $cadena_transferencias_sale .= '<h6 class="text-center text-dark text-xxs font-weight-bold"><u>TRANSFERENCIAS A OTRAS ESPECIALIDADES</u></h6 >';
            $cadena_transferencias_sale .= '<table class="table table-sm table-borderedd text-dark" id="tableIngresos" width="100%" >
                <thead >
                <tr >
                    <td style="width: 10%;">CAMA</td>
                    <td >NO. HISTORIA CLINICA</td>
                    <td >APELLIDOS Y NOMBRES</td>
                </tr>
                </thead>
                <tbody>';
            $total_sale=count($data_sale);
            foreach ($data_sale as $item):
                $data = $obj->Paciente_info($item->p_id, $item->paciente_ci, $item->entrada);
                $cadena_transferencias_sale .= "<tr >";
                $cadena_transferencias_sale .= "<td class='text-center'>" . $item->cama_nombre . "</td>";
                $cadena_transferencias_sale .= "<td>". $item->paciente_ci ."</td>";
                $cadena_transferencias_sale .= "<td>" . $data->nombre . "</td>";
                $cadena_transferencias_sale .= "</td>";
                $cadena_transferencias_sale .= "</tr>";
            endforeach;
            $cadena_transferencias_sale .= "<tr >";
            $cadena_transferencias_sale .= "<td colspan='3'>TOTAL TRANSFERENCIAS A OTRAS ESPECIALIDADES: ".$total_sale."</td>";
            $cadena_transferencias_sale .= "</tr >";

            $cadena_transferencias_sale .= "</tbody></table>";
        }

        return $cadena_transferencias_sale;
    }
    public function defunciones($especialidad, $sala, $fecha)
    {
        $obj = new Application_Model_DbTable_Estadistica();
        $data_defunciones = $obj->listarDefunciones($especialidad, $sala, $fecha);
        $cadena_defunciones = '';
        $total_defunciones=0;
        if (!$data_defunciones) {
            $cadena_defunciones.= '<h6 class="text-center text-dark text-xxs font-weight-bold"><u>DEFUNCIONES</u></h6 >';
            $cadena_defunciones.='<table class="table table-sm table-borderedd text-dark" id="tableIngresos" width="100%" >
                <thead >
                <tr >
                    <td style="width: 10%;">CAMA</td>
                    <td >NO. HISTORIA CLINICA</td>
                    <td >APELLIDOS Y NOMBRES</td>
                    <td style="width: 10%;"> - 48 H.</td>
                    <td style="width: 10%;"> + 48 H. </td>
                </tr>
                </thead>
                <tbody>';
                $cadena_defunciones.=' <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>';
            $cadena_defunciones .= "<tr >";
            $cadena_defunciones .= "<td colspan='3'>TOTAL DEFUNCIONES: ".$total_defunciones."</td>";
            $cadena_defunciones .= "<td></td>";
            $cadena_defunciones .= "<td></td>";

            $cadena_defunciones .= "</tr >";
            $cadena_defunciones.=' </tbody></table>';
        } else {
            $cadena_defunciones .= '<h6 class="text-center text-dark text-xxs font-weight-bold"><u>DEFUNCIONES</u></h6 >';
            $cadena_defunciones .= '<table class="table table-sm table-borderedd text-dark"  id="tableIngresos" width="100%" >
                <thead >
                <tr >
                    <td style="width: 10%;">CAMA</td>
                    <td >NO. HISTORIA CLINICA</td>
                    <td >APELLIDOS Y NOMBRES</td>
                    <td style="width: 10%;"> - 48 H.</td>
                    <td style="width: 10%;"> + 48 H. </td>
                </tr>
                </thead>
                <tbody>';
            $total_defunciones=count($data_defunciones);
            foreach ($data_defunciones as $item):
                $data = $obj->Paciente_info($item->p_id, $item->paciente_ci, $item->entrada);
            $cadena_defunciones .= "<tr >";
            $cadena_defunciones .= "<td class='text-center'>" . $item->cama_nombre . "</td>";
            $cadena_defunciones .= "<td>". $item->paciente_ci ."</td>";
            $cadena_defunciones .= "<td>" . $data->nombre . "</td>";
            $cadena_defunciones .= "<td></td>";
            $cadena_defunciones .= "<td></td>";
            $cadena_defunciones .= "</td>";
            $cadena_defunciones .= "</tr>";
            endforeach;
            $cadena_defunciones .= "<tr >";
            $cadena_defunciones .= "<td colspan='3'>TOTAL DEFUNCIONES: ".$total_defunciones."</td>";
            $cadena_defunciones .= "<td></td>";
            $cadena_defunciones .= "<td></td>";

            $cadena_defunciones .= "</tr >";

            $cadena_defunciones .= "</tbody></table>";
        }


        return $cadena_defunciones;
    }
    /**
     * preDispatch()
     * * Funcion para validacion de autenticacion
     * * Controla tambien el permiso de usuario a las diferentes rutas
     * ! se ejecuta antes de cualquier accion
     * @param auth obtiene el usuario que esta autenticado
     * @param controlador,accion almacena el nombre del controlador y de la accion
     * respectivamente
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
