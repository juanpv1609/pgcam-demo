<?php

class Application_Model_DbTable_Especialidades extends Zend_Db_Table_Abstract
{

    protected $_name = 'especialidades';

    public function listar()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from especialidad
                    join piso
                    on piso.piso_id=especialidad.piso_id
                    join area
                    on area.area_id=piso.area_id;";
        return $db->fetchAll($select);
    }
    public function insertarespecialidad($nombre, $piso)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO especialidad(piso_id, especialidad_nombre)
        VALUES (" . $piso . ",'" . $nombre . "'); ";
        return $db->fetchRow($select);
        //$this->listar();
    }
    public function actualizarespecialidad($id, $nombre, $piso)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE especialidad
        SET piso_id=" . $piso . ",especialidad_nombre='" . $nombre . "'
      WHERE especialidad_id=" . $id . "; ";
        return $db->fetchRow($select);
        //$this->listar();
    }
    public function eliminarespecialidad($id)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "DELETE FROM especialidad
        WHERE especialidad_id=" . $id . ";";
        return $db->fetchRow($select);
        //$this->listar();
    }
}
