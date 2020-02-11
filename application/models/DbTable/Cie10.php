<?php

class Application_Model_DbTable_Cie10 extends Zend_Db_Table_Abstract
{

    protected $_name = 'cie10';

    public function listar()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT *
        FROM cie10_sub_categoria ";
        return $db->fetchAll($select);
    }
}

