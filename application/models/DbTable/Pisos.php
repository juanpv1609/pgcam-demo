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
        $select = "select * from piso join area on area.area_id=piso.area_id order by 1;";
        return $db->fetchAll($select);
    }
    public function insertarpiso($nombre,$area) {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO piso(area_id, piso_nombre)
        VALUES (".$area.",'".$nombre."'); ";
        return $db->fetchRow($select);
        //$this->listar();
    }
    public function actualizarpiso($id,$nombre,$area) {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE piso
        SET area_id=".$area.",piso_nombre='".$nombre."'
      WHERE piso_id=".$id."; ";
        return $db->fetchRow($select);
        //$this->listar();
    }
    public function eliminarpiso($id) {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "DELETE FROM piso
        WHERE piso_id=".$id.";";
        return $db->fetchRow($select);
        //$this->listar();
    }

}

