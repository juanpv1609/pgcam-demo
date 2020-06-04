<?php
class Zend_View_Helper_GetEspecialidades extends Zend_View_Helper_Abstract
{

    public function getEspecialidades()
    {
        $obj = new Application_Model_DbTable_Especialidades();
        $datos = $obj->listar();
        $Listaarea = '';
        if (!$datos) {
            $Listaarea .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error !</strong> No se encontraron resultados
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            $Listaarea .= '<nav class="navbar  navbar-expand-lg navbar-light ">
                        <button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon small"></span>
                    </button>
                    <div class="collapse navbar-collapse " id="navbarNavDropdown">
                    <ul class="nav nav-tabs card-header-tabs">';

            foreach ($datos as $item):
                $Listaarea .= '<li class="nav-item ">
                <a class="nav-link " id="'. $item->especialidad_id .'" href="#" onclick="getCamas('. $item->especialidad_id .')" ><strong>' . $item->especialidad_nombre . '</strong></a></li>';
            endforeach;
            $Listaarea .= "</ul></div></nav>";
        }
        return $Listaarea;
    }
}