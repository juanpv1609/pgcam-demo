<?php
class Zend_View_Helper_GetMensajes extends Zend_View_Helper_Abstract
{

     /* public function getMensajes()
    {
      $obj_mail = new Application_Model_DbTable_Usuario();
      $mail = $obj_mail->leeEmail();
      $mail = new LimitIterator($mail, 1, 5);
        $Listaarea .= '<li class="nav-item dropdown no-arrow " data-toggle="popover"  data-content="Notificaciones">
        <a class="nav-link dropdown-toggle" href="#" id="msgDropdown" role="button" data-toggle="dropdown"
                     aria-haspopup="true" aria-expanded="false">            
                     <i class="far fa-envelope"></i>';
        if (!$mail) {
            $Listaarea .= '<span class="badge badge-warning badge-counter">0</span>
                  </a>
                  <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in "
                     aria-labelledby="msgDropdown">
                     <h6 class="dropdown-header pl-4">
                     <i class="far fa-envelope pr-2"></i>Mensajes
                     </h6>
                     <a class="dropdown-item text-left small py-2 pl-4" href="#">                                 
                           <span>No se encontraron resultados!</span>
                     </a>';
                $Listaarea .= '</div></li>';
        } else {

        $Listaarea .= '<span class="badge badge-warning badge-counter">'.$mail->countMessages().'</span>
                  </a>
                  <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in "
                     aria-labelledby="msgDropdown">
                     <h6 class="dropdown-header pl-4">
                     <i class="far fa-envelope pr-2"></i> Mensajes
                     </h6>';            
            foreach ($mail as $message):
               $Listaarea .= '<a class="dropdown-item text-left small py-2 pl-4" href="#">                                 
                                 <div>
                                    <div class="small text-gray-600"><i class="fas fa-calendar-alt  pr-2"></i>'. $message->from .'</div>
                                    <span>'. $message->subject.'</span>
                                 </div>
                           </a>';
            endforeach;
            $Listaarea .= '<a class="dropdown-item text-center small text-gray-500" href="#">Mostrar todo</a>
                     </div>
                  </li>';
        }

        return $Listaarea;
    }  */
}
