<?php

class Application_Model_DbTable_Pisos extends Zend_Db_Table_Abstract
{

    protected $_name = 'piso';
    /**
     * listar()
     * * Esta funcion lista los pisos registrados mediante consulta SQL
     * ? devuelve los resultados como objetos $row->campo
     * ! realiza un join con la tabla AREA 
     */

    public function listar()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from piso join area on area.area_id=piso.area_id order by 1;";
        return $db->fetchAll($select);
    }
    /**
     * insertarpiso()
     * * Esta funcion inserta un piso
     * @param area: area_id a ingresar
     * @param nombre: dato a ingresar
     * ? devuelve los resultados como objetos $row->campo
     * ? si el piso ya existe omite la transaccion
     */

    public function insertarpiso($nombre, $area)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO piso(area_id, piso_nombre)
        VALUES (" . $area . ",'" . strtoupper($nombre) . "')
        ON CONFLICT (piso_nombre)
	    DO NOTHING;";
        return $db->fetchRow($select);
    }
    /**
     * actualizarpiso()
     * * Esta funcion edita un piso
     * @param id: id del piso a editar
     * @param area: area_id del piso a editar
     * @param nombre: dato a editar
     * ? devuelve los resultados como objetos $row->campo
     */

    public function actualizarpiso($id, $nombre, $area)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE piso
        SET area_id=" . $area . ",piso_nombre='" . strtoupper($nombre) . "'
      WHERE piso_id=" . $id . "; ";
        return $db->fetchRow($select);
    }
    /**
     * eliminarpiso()
     * * Esta funcion elimina un piso
     * @param id: id del piso a eliminar
     * ? devuelve los resultados como objetos $row->campo
     * ? si existe dependencia de llaves foraneas se omite la transaccion
     */

    public function eliminarpiso($id)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "DELETE FROM piso
        WHERE piso_id=" . $id . ";";
        return $db->fetchRow($select);
    }

}
