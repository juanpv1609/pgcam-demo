<?php

class Application_Model_DbTable_Habitaciones extends Zend_Db_Table_Abstract
{

    protected $_name = 'habitaciones';
    /**
     * listar()
     * * Esta funcion lista las habitaciones registradas mediante consulta SQL
     * ? devuelve los resultados como objetos $row->campo
     * ? realiza un join con las tablas ESPECIALIDAD,PISOS,AREA
     */

    public function listar()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from habitacion
                    join especialidad
                    on especialidad.especialidad_id=habitacion.especialidad_id
                    join piso
                    on piso.piso_id=especialidad.piso_id
                    join area
                    on area.area_id=piso.area_id;";
        return $db->fetchAll($select);
    }
    /**
     * insertarespecialidad()
     * * Esta funcion inserta una especialidad
     * @param nombre: nombre de la habitacion a ingresar
     * @param especialidad: especialidad de la habitacion a ingresar
     * ? devuelve los resultados como objetos $row->campo
     * ? si la especialidad ya existe omite la transaccion
     */

    public function insertarhabitacion($nombre, $especialidad)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO habitacion(especialidad_id, habitacion_nombre)
        VALUES (" . $especialidad . ",'" . $nombre . "'); ";
        return $db->fetchRow($select);
    }
    /**
     * actualizarhabitacion()
     * * Esta funcion edita una habitacion
     * @param id: id de la habitacion a editar
     * @param nombre: dato a editar
     * @param especialidad: dato a editar
     * ? devuelve los resultados como objetos $row->campo
     */

    public function actualizarhabitacion($id, $nombre, $especialidad)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE habitacion
        SET especialidad_id=" . $especialidad . ",habitacion_nombre='" . $nombre . "'
      WHERE habitacion_id=" . $id . "; ";
        return $db->fetchRow($select);
    }
    /**
     * eliminarhabitacion()
     * * Esta funcion elimina una habitacion
     * @param id: id de la habitacion a eliminar
     * ? devuelve los resultados como objetos $row->campo
     * ? si existe dependencia de llaves foraneas se omite la transaccion
     */

    public function eliminarhabitacion($id)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "DELETE FROM habitacion
        WHERE habitacion_id=" . $id . ";";
        return $db->fetchRow($select);
    }
}
