<?php

class Application_Model_DbTable_Usuario extends Zend_Db_Table_Abstract
{

    protected $_name = 'usuario';
    
    public function insertarusuario($nombre,$apellido, $correo, $clave) {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO usuario(usu_nombres, correo, clave,usu_estado_id,perf_id)
    VALUES ('".$nombre." ".$apellido."','".$correo."',MD5('".$clave."'),1,1); ";
        return $db->fetchRow($select);
    }

}

