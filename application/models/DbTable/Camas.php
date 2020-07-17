<?php

class Application_Model_DbTable_Camas extends Zend_Db_Table_Abstract
{

    protected $_name = 'camas';
    /**
     * listar()
     * * Esta funcion lista las camas registradas mediante consulta SQL
     * ? devuelve los resultados como objetos $row->campo
     * ? realiza un join con las tablas HABITACION,ESPECIALIDAD,PISOS,AREA
     */

    public function listar()
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from cama
        join habitacion
        on habitacion.habitacion_id=cama.habitacion_id
        join especialidad
        on especialidad.especialidad_id=habitacion.especialidad_id
        join piso
        on piso.piso_id=especialidad.piso_id
        join area
        on area.area_id=piso.area_id
        join cama_estado
        on cama_estado.cama_estado_id=cama.cama_estado_id
        join cama_tipo
        on cama_tipo.cama_tipo_id=cama.cama_tipo_id;";
        return $db->fetchAll($select);
    }
    /**
     * insertarcama()
     * * Esta funcion inserta una cama
     * @param nombre: nombre de la cama a ingresar
     * @param habitacion: habitacion de la cama a ingresar
     * @param cama_estado: cama_estado de la cama a ingresar
     * ? devuelve los resultados como objetos $row->campo
     * ? si la especialidad ya existe omite la transaccion
     */

    public function insertarcama($nombre, $habitacion, $cama_estado,$tipo_cama)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO cama(habitacion_id, cama_nombre,cama_estado_id,cama_tipo_id)
        VALUES (" . $habitacion . ",'" . $nombre . "'," . $cama_estado . "," . $tipo_cama . "); ";
        return $db->fetchRow($select);
    }
    /**
     * actualizarcama()
     * * Esta funcion edita una cama
     * @param id: id de la cama a editar
     * @param nombre: dato a editar
     * @param habitacion: dato a editar
     * @param cama_estado: dato a editar
     * ? devuelve los resultados como objetos $row->campo
     */

    public function actualizarcama($id, $nombre, $habitacion, $cama_estado,$tipo_cama)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE cama
        SET habitacion_id=" . $habitacion . ",cama_nombre='" . $nombre . "',cama_estado_id=" . $cama_estado . ",cama_tipo_id=".$tipo_cama."
      WHERE cama_id=" . $id . "; ";
        return $db->fetchRow($select);
    }
    /**
     * actualizarEstadoCama()
     * * Esta funcion edita el estado de una cama
     * @param id: id de la cama a editar
     * @param cama_estado: dato a editar
     * ? devuelve los resultados como objetos $row->campo
     */

    public function actualizarEstadoCama($id, $cama_estado)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE cama
        SET cama_estado_id=" . $cama_estado . "
      WHERE cama_id=" . $id . "; ";
        return $db->fetchRow($select);
    }
    /**
     * eliminarcama()
     * * Esta funcion elimina una cama
     * @param id: id de la cama a eliminar
     * ? devuelve los resultados como objetos $row->campo
     * ? si existe dependencia de llaves foraneas se omite la transaccion
     */

    public function eliminarcama($id)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "DELETE FROM cama
        WHERE cama_id=" . $id . ";";
        return $db->fetchRow($select);
    }
    /**
     * listar_estado_cama()
     * * Esta funcion lista los registros de la tabla estado_cama
     * ? devuelve los resultados como objetos $row->campo
     */

    public function listar_estado_cama()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from cama_estado order by 1";
        return $db->fetchAll($select);
    }
    /**
     * listar_tipo_cama()
     * * Esta funcion lista los registros de la tabla estado_cama
     * ? devuelve los resultados como objetos $row->campo
     */

    public function listar_tipo_cama()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from cama_tipo";
        return $db->fetchAll($select);
    }
    /**
     * listar_tipo_cama_servicio()
     * * Esta funcion lista los registros de la tabla estado_cama
     * ? devuelve los resultados como objetos $row->campo
     */

    public function listar_tipo_cama_servicio($especialidad)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from cama_tipo
        where especialidad_id=".$especialidad;
        return $db->fetchAll($select);
    }
    /**
     * busca_especialidad()
     * * Esta funcion lista los registros de la tabla estado_cama
     * ? devuelve los resultados como objetos $row->campo
     */

    public function busca_especialidad($especialidad,$sala)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from cama_tipo c
join especialidad e
ON e.especialidad_id=c.especialidad_id
where c.especialidad_id=".$especialidad."
and c.cama_tipo_id=".$sala;
        return $db->fetchAll($select);
    }
    /**
     * listar_camas_disponibilidad()
     * * Esta funcion lista la disponibilidad de camas de acuerdo a su estado
     * @param estado: disponible, ocupada, desinfeccion
     * ? devuelve los resultados como objetos $row->campo
     * ? realiza un join con las tablas ESPECIALIDAD,PISOS,AREA
     */

    public function listar_camas_disponibilidad($estado)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from cama
        join habitacion
        on habitacion.habitacion_id=cama.habitacion_id
        join especialidad
        on especialidad.especialidad_id=habitacion.especialidad_id
        join piso
        on piso.piso_id=especialidad.piso_id
        join area
        on area.area_id=piso.area_id
        join cama_estado
        on cama_estado.cama_estado_id=cama.cama_estado_id
        where cama.cama_estado_id=".$estado.";";
        return $db->fetchAll($select);
    }
}
