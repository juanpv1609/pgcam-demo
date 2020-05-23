<?php
class Zend_View_Helper_GetNotificaciones extends Zend_View_Helper_Abstract
{
    public function getNotificaciones()
    {
        $fc = Zend_Controller_Front::getInstance()->getRequest()->getBaseUrl();
        $Listaarea='';
        $cont=0;
        $obj = new Application_Model_DbTable_Notificaciones();
        $datos = $obj->listar();
        $Listaarea .= '<li class="nav-item dropdown no-arrow dropdown-notifications show" data-toggle="popover"  data-content="Notificaciones">
        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
                     aria-haspopup="true" aria-expanded="false">            
                     <i class="far fa-bell"></i>';
        if (!$datos) {
            $Listaarea .= '<span class="badge badge-danger badge-counter">0</span>
                  </a>
                  <div class=" dropdown-menu dropdown-menu-right shadow animated--grow-in " 
                     aria-labelledby="alertsDropdown">
                     <h6 class="dropdown-header dropdown-notifications-header p-4">
                     <i class="far fa-bell pr-2"></i>Notificaciones
                     </h6>
                     <a class="dropdown-item dropdown-notifications-item text-left small   pl-4" href="#">                                 
                           <div class="dropdown-notifications-item-content-text">No se encontraron resultados!</div>
                     </a>';
            $Listaarea .= '</div></li>';
        } else {
            $contador = (count($datos)>9) ? '9+' : (count($datos));
            $Listaarea .= '<span class="badge badge-danger badge-counter">'.$contador.'</span>
                  </a>
                  <div class=" dropdown-menu dropdown-menu-right shadow animated--grow-in "
                     aria-labelledby="alertsDropdown">
                     <h6 class="dropdown-header dropdown-notifications-header p-4">
                     <i class="far fa-bell pr-2"></i>Notificaciones
                     </h6>';
            foreach ($datos as $item):
            $Listaarea .= '<a class="dropdown-item dropdown-notifications-item text-left small  p-4 " 
               href="'. $fc.'/listar_paciente?ci='.$item->p_ci.'"> 
                                               
                                 <div class="dropdown-notifications-item-content text-xxs">
                                    <div class="dropdown-notifications-item-content-details ">
                                    <i class="fas fa-calendar-alt  pr-2"></i>'. $item->not_fecha_creacion .'</div>
                                    <div class="text-wrap">'. $item->not_mensaje .'</div >
                                 </div>
                           </a>';
            endforeach;
            $Listaarea .= "<a class='dropdown-item dropdown-notifications-footer text-center text-xs ' 
            href='". $fc."/notificaciones'>Mostrar todo</a>
                     </div>
                  </li>";
        }

        return $Listaarea;
    }
}
