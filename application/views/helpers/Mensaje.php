<?php
use Zend\View\Helper\AbstractHelper;
class Mensaje extends AbstractHelper{
 
  
  public function setActive($controladorName) {
    if ($controladorName=="areas") {
      return "active";
    }
  }
}

