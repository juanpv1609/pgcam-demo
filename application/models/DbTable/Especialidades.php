<?php

class Application_Model_DbTable_Especialidades extends Zend_Db_Table_Abstract
{

    protected $_name = 'especialidades';
    /**
     * listar()
     * * Esta funcion lista las especialidades registradas mediante consulta SQL
     * ? devuelve los resultados como objetos $row->campo
     * ? realiza un join con las tablas PISOS,AREA
     */

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
                    on area.area_id=piso.area_id
                    order by 1;";
        return $db->fetchAll($select);
    }
    /**
     * insertarespecialidad()
     * * Esta funcion inserta una especialidad
     * @param nombre: nombre de la especialidad a ingresar
     * @param piso: piso de la especialidad a ingresar
     * ? devuelve los resultados como objetos $row->campo
     * ? si la especialidad ya existe omite la transaccion
     */

    public function insertarespecialidad($nombre, $piso,$color)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO especialidad(piso_id, especialidad_nombre,especialidad_color)
        VALUES (" . $piso . ",'" . strtoupper($nombre) . "','".$color."')
        ON CONFLICT (especialidad_nombre)
	    DO NOTHING; ";
        return $db->fetchRow($select);
    }
    /**
     * actualizarespecialidad()
     * * Esta funcion edita una especialidad
     * @param id: id de la especialidad a editar
     * @param nombre: dato a editar
     * @param piso: dato a editar
     * ? devuelve los resultados como objetos $row->campo
     */

    public function actualizarespecialidad($id, $nombre, $piso,$color)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE especialidad
        SET piso_id=" . $piso . ",especialidad_nombre='" . strtoupper($nombre) . "',especialidad_color='".$color."'
      WHERE especialidad_id=" . $id . "; ";
        return $db->fetchRow($select);
    }
    /**
     * eliminarespecialidad()
     * * Esta funcion elimina una especialidad
     * @param id: id del area a eliminar
     * ? devuelve los resultados como objetos $row->campo
     * ? si existe dependencia de llaves foraneas se omite la transaccion
     */

    public function eliminarespecialidad($id)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "DELETE FROM especialidad
        WHERE especialidad_id=" . $id . ";";
        return $db->fetchRow($select);
    }
}
