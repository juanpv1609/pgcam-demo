<?php
class Zend_View_Helper_GetProvincias extends Zend_View_Helper_Abstract
{

    public function getProvincias()
    {
        $obj = new Application_Model_DbTable_Admision();
        $datos = $obj->listarProvincias();
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
            $Listaarea .= '<label for="comboProv">Provincia:</label>';

            $Listaarea .= '<select class="form-control form-control-sm " name="comboProv" id="comboProv"
             onchange="getCantones();"  required autocomplete="off">
             <option value="" ></option>';
            foreach ($datos as $item):
                $Listaarea .= "<option value='" . $item->id_provincia . "' >" . strtoupper($item->nombre_provincia) . "</option>";
            endforeach;
            $Listaarea .= "</select>
                <div class='valid-feedback'>
                        </div>
                        <div class='invalid-feedback'>
                        </div></div>";
        }
        return $Listaarea;
    }
}
