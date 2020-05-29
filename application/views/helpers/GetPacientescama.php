<?php
class Zend_View_Helper_GetPacientescama extends Zend_View_Helper_Abstract
{
    public function getPacientescama()
    {
        $obj = new Application_Model_DbTable_Admision();
        $data_paciente_cama = $obj->listarPacientesCama();
        $Listaarea = '<div  class="form-group">';
        if (!$data_paciente_cama) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                </div>';
        } else {

            $Listaarea .= '<select name="opcionPaciente" id="opcionPaciente" class="form-control js-example-basic-single" required 
            data-placeholder="Seleccione un paciente" onchange="setDatosPaciente();">
             <option value="" ></option>';
            foreach ($data_paciente_cama as $item):
               $data = $obj->Paciente_info($item->p_id, $item->paciente_ci, $item->entrada);
                $Listaarea .= "<option value='" . $item->cama_paciente_id . "' >" . $data->nombre . "</option>";
            endforeach;
            $Listaarea .= "</select>
                        <div class='valid-feedback'></div>
                        <div class='invalid-feedback'></div>
                        </div>";
        }
        return $Listaarea;
    }
}
