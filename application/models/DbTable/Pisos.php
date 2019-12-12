<?php

class Application_Model_DbTable_Pisos extends Zend_Db_Table_Abstract
{

    protected $_name = 'piso';
    public function listar()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from piso join area on area.area_id=piso.area_id;";
        return $db->fetchAll($select);
    }

}

