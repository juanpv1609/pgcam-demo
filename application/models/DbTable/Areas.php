<?php

class Application_Model_DbTable_Areas extends Zend_Db_Table_Abstract
{

    protected $_name = 'area';

    public function listar()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from area";
        return $db->fetchAll($select);
    }
    public function insertararea($nombre) {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO area(area_nombre)
        VALUES ('".$nombre."'); ";
        return $db->fetchRow($select);
        $this->listar();
    }

}

