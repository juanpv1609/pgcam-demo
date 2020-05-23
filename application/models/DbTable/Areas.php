<?php

class Application_Model_DbTable_Areas extends Zend_Db_Table_Abstract
{

    protected $_name = 'area';
    /**
     * listar()
     * * Esta funcion lista las areas registradas mediante consulta SQL
     * ? devuelve los resultados como objetos $row->campo
     */
    public function listar()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from area";
        return $db->fetchAll($select);
    }
    /**
     * insertararea()
     * * Esta funcion inserta un area
     * @param nombre: dato a ingresar 
     * ? devuelve los resultados como objetos $row->campo
     * ? si el area ya existe omite la transaccion
     */

    public function insertararea($nombre)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO area(area_nombre)
        VALUES ('" . strtoupper($nombre) . "')
        ON CONFLICT (area_nombre)
	    DO NOTHING;";
        return $db->fetchRow($select);
    }
    /**
     * actualizararea()
     * * Esta funcion edita un area
     * @param id: id del area a editar
     * @param nombre: dato a editar
     * ? devuelve los resultados como objetos $row->campo
     */

    public function actualizararea($id, $nombre)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE area
        SET area_nombre='" . strtoupper($nombre) . "'
        WHERE area_id=" . $id . ";";
        return $db->fetchRow($select);
    }
    /**
     * eliminararea()
     * * Esta funcion elimina un area
     * @param id: id del area a eliminar
     * ? devuelve los resultados como objetos $row->campo
     * ? si existe dependencia de llaves foraneas se omite la transaccion
     */

    public function eliminararea($id)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "DELETE FROM area
        WHERE area_id=" . $id . ";";
        return $db->fetchRow($select);
    }

}
