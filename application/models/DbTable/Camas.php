<?php

class Application_Model_DbTable_Camas extends Zend_Db_Table_Abstract
{

    protected $_name = 'camas';
    public function listar()
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
                        on area.area_id=piso.area_id;";
        return $db->fetchAll($select);
    }

}

