<?php
class Zend_View_Helper_SetActive extends Zend_View_Helper_Abstract
{

    public function setActive($controladorName)
    {
        $controlador = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        if ($controladorName == $controlador) {
            return "active";
        }
    }
}
