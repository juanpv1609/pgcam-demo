<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
/* ////CONFIGURACION DE VARIABLE DE REGISTRO PARA LA CONEXION A LA BASE DE DATOS /////*/
//cargo mi archivo de configuracion application.ini en el objeto $config, en la forma de un arreglo.
//Cargamos solo los valores de la sección 'production', como indica el segundo parámetro, pues en esa //sección hemos definido los parámetros de conección a la base de datos.
$config = new Zend_Config_Ini('../application/configs/application.ini', 'production');

//creo un objeto DB factory() con los datos de la seccion resources del archivo application.ini
$pgdb = Zend_Db::factory('PDO_PGSQL', $config->resources->db->params);

//guardo en el registro el objeto DB Factory() creado, para poder usarlo despues en cualquier parte
Zend_Registry::set('pgdb', $pgdb);
$application->bootstrap()
            ->run();