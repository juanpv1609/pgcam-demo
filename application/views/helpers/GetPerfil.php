<?php
class Zend_View_Helper_GetPerfil extends Zend_View_Helper_Abstract
{

    public function getPerfil()
    {
        $obj = new Application_Model_DbTable_Usuario();
        $datos = $obj->listar_perfiles();
        $Listaarea = '<div  class="form-group">';
        if (!$datos) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                </div>';
        } else {

            $Listaarea .= '<select class="custom-select" name="comboPerfil" id="comboPerfil" required autocomplete="off">
             <option value="" >Seleccione un perfil</option>';
            foreach ($datos as $item):
                $Listaarea .= "<option value='" . $item->perf_id . "' >" . $item->perf_nombre . "</option>";
            endforeach;
            $Listaarea .= "</select>
                        <div class='valid-feedback'></div>
                        <div class='invalid-feedback'>
                        Debe seleccionar un perfil</div>
                        </div>";
        }
        return $Listaarea;
    }
}
