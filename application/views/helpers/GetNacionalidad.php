<?php
class Zend_View_Helper_GetNacionalidad extends Zend_View_Helper_Abstract
{

    public function getNacionalidad()
    {
        $obj = new Application_Model_DbTable_Admision();
        $datos = $obj->listarNacionalidad();
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
            $Listaarea .= '<label for="comboNacionalidad">Nacionalidad:</label>';

            $Listaarea .= '<select class="form-control form-control-sm " name="comboNacionalidad" id="comboNacionalidad" required autocomplete="off">
             <option value="" ></option>';
            foreach ($datos as $item):
                $Listaarea .= "<option value='" . $item->nacionalidad_id . "' >" . $item->descripcion . "</option>";
            endforeach;
            $Listaarea .= "</select>
                        <div class='valid-feedback'></div>
                        <div class='invalid-feedback'></div>
                        </div>";
        }
        return $Listaarea;
    }
}
