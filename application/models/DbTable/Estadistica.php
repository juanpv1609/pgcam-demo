<?php

class Application_Model_DbTable_Estadistica extends Zend_Db_Table_Abstract
{
    protected $_name = 'estadistica';
    /**
     * camasPorServicio
     * * esta funcion lista las camas de cada servicio
     * @param opcion: estado de las camas y estas pueden ser: 
     * ? opcion=0 -> disponibles
     * ? opcion=1 -> ocupadas
     * ? opcion=2 -> desinfeccion
     * ? opcion=3 -> todas
     * ! usado para las graficas del dashboard
     */
    public function camasPorServicio()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
            $select = "SELECT e.especialidad_nombre, 
                sum(case when c.cama_estado_id=0 then 1 else 0 end) as disponibles,
                sum(case when c.cama_estado_id=1 then 1 else 0 end) as ocupadas,
                sum(case when c.cama_estado_id=2 then 1 else 0 end) as desinfeccion
            FROM cama c
            JOIN habitacion h
            ON h.habitacion_id=c.habitacion_id
            JOIN especialidad e
            ON e.especialidad_id=h.especialidad_id
            JOIN cama_estado s
            ON s.cama_estado_id=c.cama_estado_id
            GROUP BY e.especialidad_nombre
            ORDER BY 2 DESC";
        
        
        return $db->fetchAll($select);
    }
    /**
     * camasPorServicio
     * * esta funcion cuenta las camas disponibles, ocupadas y desinfeccion
     * ? disponibles
     * ? ocupadas
     * ? desinfeccion
     * ! usado para las graficas del dashboard
     */

    public function camasEstado()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT e.cama_estado_id, e.cama_estado_descripcion,count(e.cama_estado_descripcion) as cuenta_camas
        FROM cama c
        JOIN cama_estado e
        ON e.cama_estado_id=c.cama_estado_id
        GROUP BY (e.cama_estado_id,e.cama_estado_descripcion)
        ORDER BY 1";
        return $db->fetchAll($select);
    }
}
