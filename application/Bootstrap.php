<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initView()
    {
        $view = new Zend_View();
        $view->setEncoding('UTF-8');
        $view->doctype('XHTML1_STRICT');
        $view->headTitle('PG-CAM ');
        $view->headMeta()->appendHttpEquiv(
            'Content-Type',
            'text/html;charset=utf-8'
        );
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
        
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->setView($view);
        return $view;
    }
    protected function _initRoutes()
    {
        $ctrl = Zend_Controller_Front::getInstance();
        $router = $ctrl->getRouter();
        $router->addRoute(
            'panel_de_control',
            new Zend_Controller_Router_Route(
                'panel_de_control',
                array('controller' => 'index',
                        'action' => 'dashboard')
            )
        );
        $router->addRoute(
            'listar_paciente',
            new Zend_Controller_Router_Route(
                'listar_paciente',
                array('controller' => 'paciente',
                        'action' => 'listar')
            )
        );
        $router->addRoute(
            'registrar_paciente',
            new Zend_Controller_Router_Route(
                'registrar_paciente',
                array('controller' => 'paciente',
                        'action' => 'registrar')
            )
        );
        $router->addRoute(
            'asignar_cama_paciente',
            new Zend_Controller_Router_Route(
                'asignar_cama_paciente',
                array('controller' => 'paciente',
                        'action' => 'asignar')
            )
        );
        $router->addRoute(
            'cambio_cama_paciente',
            new Zend_Controller_Router_Route(
                'cambio_cama_paciente',
                array('controller' => 'paciente',
                        'action' => 'cambio')
            )
        );
        $router->addRoute(
            'listar_usuarios',
            new Zend_Controller_Router_Route(
                'listar_usuarios',
                array('controller' => 'usuarios',
                        'action' => 'index')
            )
        );
        $router->addRoute(
            'listar_perfiles',
            new Zend_Controller_Router_Route(
                'listar_perfiles',
                array('controller' => 'usuarios',
                        'action' => 'perfiles')
            )
        );
        $router->addRoute(
            'iniciar_sesion',
            new Zend_Controller_Router_Route(
                'iniciar_sesion',
                array('controller' => 'auth',
                        'action' => 'login')
            )
        );
        $router->addRoute(
            'nueva_cuenta',
            new Zend_Controller_Router_Route(
                'nueva_cuenta',
                array('controller' => 'auth',
                        'action' => 'register')
            )
        );
    }
}
