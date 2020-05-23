<?php
class Zend_View_Helper_SetActive extends Zend_View_Helper_Abstract
{

    public function setActive($controlador,$accion)
    {
        
        $page = new Zend_Navigation_Page_Mvc(array(
            'action' => $accion,
            'controller' => $controlador
            )); 
        
        return ($page->isActive()) ? "active" : " ";
    }
}
