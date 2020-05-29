<?php
class Zend_View_Helper_GetCausa extends Zend_View_Helper_Abstract
{
    public function getCausa()
    {
        $obj = new Application_Model_DbTable_Admision();
        $data_causa = $obj->listarCausa();
        $Listaarea = '<div  class="form-group">';
        if (!$data_causa) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                </div>';
        } else {

            $Listaarea .= '<select name="opcionCausa" id="opcionCausa" class="form-control  js-example-basic-single text-xxs"  
            required data-placeholder="Seleccione una causa" onchange="getCamasCambio()" disabled >
             <option value="" ></option>';
            foreach ($data_causa as $item):
                $Listaarea .= "<option value='" . $item->causa_id . "' >" . $item->causa_descripcion . "</option>";
            endforeach;
            $Listaarea .= "</select>
                        <div class='valid-feedback'></div>
                        <div class='invalid-feedback'></div>
                        </div>";
        }
        return $Listaarea;
    }
}
