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
    /**
     * camasPorServicio
     * * esta funcion cuenta las camas disponibles, ocupadas y desinfeccion
     * ? disponibles
     * ? ocupadas
     * ? desinfeccion
     * ! usado para las graficas del dashboard
     */

    public function camasEstadoEspecialidad($especialidad,$sala)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT e.cama_estado_id, e.cama_estado_descripcion,count(e.cama_estado_descripcion) as cuenta_camas
        FROM cama c
        JOIN cama_estado e
        ON e.cama_estado_id=c.cama_estado_id
        JOIN cama_tipo t
        ON t.cama_tipo_id=c.cama_tipo_id
        WHERE t.especialidad_id=".$especialidad."
        and t.cama_tipo_id=".$sala."
        GROUP BY (e.cama_estado_id,e.cama_estado_descripcion)
        ORDER BY 1";
        return $db->fetchAll($select);
    }
    public function listarIngresos($especialidad,$sala,$fecha)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
            $select = "SELECT c.p_id,c.cama_id,c.paciente_ci,c.entrada,c.fecha_ingreso,
        e.especialidad_nombre,t.cama_tipo_descripcion,a.cama_nombre
        FROM cama_paciente c
        JOIN cama a
        ON a.cama_id=c.cama_id
        JOIN habitacion h
        ON h.habitacion_id=a.habitacion_id
        JOIN especialidad e
        ON e.especialidad_id=h.especialidad_id
        JOIN cama_tipo t
        ON t.cama_tipo_id=a.cama_tipo_id
        WHERE (c.causa_id=7 OR c.causa_id=1)
        AND e.especialidad_id=".$especialidad."
        AND t.cama_tipo_id=".$sala."
        AND c.fecha_ingreso::Date = '".$fecha."'::Date;";
        return $db->fetchAll($select);
    }
    public function listarEgresos($especialidad,$sala,$fecha)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
            $select = "SELECT c.p_id,c.cama_id,c.paciente_ci,c.entrada,c.fecha_ingreso,
        e.especialidad_nombre,t.cama_tipo_descripcion,a.cama_nombre
        FROM cama_paciente c
        JOIN cama a
        ON a.cama_id=c.cama_id
        JOIN habitacion h
        ON h.habitacion_id=a.habitacion_id
        JOIN especialidad e
        ON e.especialidad_id=h.especialidad_id
        JOIN cama_tipo t
        ON t.cama_tipo_id=a.cama_tipo_id
        WHERE (c.causa_id=4)
        AND e.especialidad_id=".$especialidad."
        AND t.cama_tipo_id=".$sala."
        AND c.fecha_ingreso::Date = '".$fecha."'::Date;";
        return $db->fetchAll($select);
    }
    public function listarDefunciones($especialidad,$sala,$fecha)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
            $select = "SELECT c.p_id,c.cama_id,c.paciente_ci,c.entrada,c.fecha_ingreso,
        e.especialidad_nombre,t.cama_tipo_descripcion,a.cama_nombre
        FROM cama_paciente c
        JOIN cama a
        ON a.cama_id=c.cama_id
        JOIN habitacion h
        ON h.habitacion_id=a.habitacion_id
        JOIN especialidad e
        ON e.especialidad_id=h.especialidad_id
        JOIN cama_tipo t
        ON t.cama_tipo_id=a.cama_tipo_id
        WHERE (c.causa_id=3)
        AND e.especialidad_id=".$especialidad."
        AND t.cama_tipo_id=".$sala."
        AND c.fecha_ingreso::Date = '".$fecha."'::Date;";
        return $db->fetchAll($select);
    }
    public function listarTransEntra($especialidad,$sala,$fecha)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
            $select = "SELECT  c.p_id,c.cama_id,c.paciente_ci,c.entrada,h.fecha_ingreso,
        e.especialidad_nombre,t.cama_tipo_descripcion,a.cama_nombre,h.observacion
        FROM historial_cp h
        JOIN cama_paciente c
        ON c.paciente_ci=h.paciente_ci
        JOIN cama a
        ON a.cama_id=c.cama_id
        JOIN habitacion ha
        ON ha.habitacion_id=a.habitacion_id
        JOIN especialidad e
        ON e.especialidad_id=ha.especialidad_id
        JOIN cama_tipo t
        ON t.cama_tipo_id=a.cama_tipo_id
        WHERE h.causa_id=6
        AND e.especialidad_id=".$especialidad."
        AND t.cama_tipo_id=".$sala."
        AND h.observacion='entra'
        AND h.fecha_ingreso::Date = '".$fecha."'::Date
        AND h.fecha_egreso is null;";
        return $db->fetchAll($select);
    }
    public function listarTransSale($especialidad,$sala,$fecha)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
            $select = "SELECT  c.p_id,c.cama_id,c.paciente_ci,c.entrada,h.fecha_egreso,
        e.especialidad_nombre,t.cama_tipo_descripcion,a.cama_nombre,h.observacion
FROM historial_cp h
JOIN cama_paciente c
ON c.paciente_ci=h.paciente_ci
JOIN cama a
ON a.cama_id=h.cama_id
JOIN habitacion ha
ON ha.habitacion_id=a.habitacion_id
JOIN especialidad e
ON e.especialidad_id=ha.especialidad_id
JOIN cama_tipo t
ON t.cama_tipo_id=a.cama_tipo_id
WHERE h.causa_id=6
AND e.especialidad_id=".$especialidad."
AND t.cama_tipo_id=".$sala."
AND h.observacion='sale'
AND h.fecha_egreso::Date = '".$fecha."'::Date";
        return $db->fetchAll($select);
    }
    /**
     * listarPacientes_CE
     * * esta funcion lista los pacientes de la bdd
     * TODO: conectar con la bdd HGONA para obtener ese listado de pacientes
     * ! muestra los pacientes ingresados por el formulario 008
     */
    public function Paciente_info($id, $paciente, $origen_paciente)
    {
        if ($origen_paciente==2) { //si el origen es consulta externa
            $configDb = array();
            $configDb['host'] = 'localhost';
            $configDb['username'] = 'postgres';
            $configDb['password'] = 'postgres';
            $configDb['dbname'] = 'hgona';
            $db2= Zend_Db::factory('PDO_PGSQL', $configDb);
            $db2->setFetchMode(Zend_Db::FETCH_OBJ);
            $select = "select concat(apellido_paterno,' ',apellido_materno,' ',primer_nombre,' ',segundo_nombre) as nombre
                from paciente
                where ci='".$paciente."'
                OR hc_digital=".$id;
            return $db2->fetchRow($select);
        } elseif ($origen_paciente==1) {
            //devuelve todos los registros de la tabla
            $db = Zend_Registry::get('pgdb');
            //opcional, esto es para que devuelva los resultados como objetos $row->campo
            $db->setFetchMode(Zend_Db::FETCH_OBJ);
            $select = "select concat(p_apellidos,' ',p_nombres) as nombre,p_observacion from paciente
        where nuhc='".$paciente."'
        OR p_id=".$id;
            return $db->fetchRow($select);
        }
    }
}
