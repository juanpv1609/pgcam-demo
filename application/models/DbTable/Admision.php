<?php

class Application_Model_DbTable_Admision extends Zend_Db_Table_Abstract
{
    protected $_name = 'admision';

    public function admisionpaciente($apellido_paterno, $apellido_materno, $primer_nombre, $segundo_nombre, $cedula, $telefono, $comboParroq, $barrio, $direccion, $fecha_n, $lugar_n, $comboNacionalidad, $comboGrupo, $comboEdad, $comboGenero, $comboEstado, $comboInstruccion, $ocupacion, $trabajo, $comboTipoSeguro, $referido, $contacto_nombre, $contacto_parentezco, $contacto_direccion, $contacto_telefono, $comboFormaLLeg, $fuente_info, $institucion, $institucion_telefono)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO pg_paciente(p_ci, p_nombres, p_apellidos, p_telefono, p_parroq,
        p_barrio, p_direccion, p_fecha_n, p_lugar_n, p_nacionalidad, p_grupo_c, p_edad,
        p_sexo, p_est_civil, p_instruccion, p_fecha_admi, p_ocupacion, p_trabajo, p_tipo_seguro,
        p_referido, p_contacto, p_contacto_parentezco, p_contacto_direc, p_contacto_tlfn,
        p_forma_llegada, p_fuente_inf, p_quien_entrega, p_quien_entrega_tlfn)
        VALUES ('" . $cedula . "', '" . strtoupper($primer_nombre) . " " . strtoupper($segundo_nombre) . "', '" . strtoupper($apellido_paterno) . " " . strtoupper($apellido_materno) . "', '" . $telefono . "', '" . $comboParroq . "',
        '" . $barrio . "', '" . $direccion . "', '" . $fecha_n . "', '" . $lugar_n . "', " . $comboNacionalidad . ", " . $comboGrupo . ", " . $comboEdad . ",
        '" . $comboGenero . "', " . $comboEstado . ", " . $comboInstruccion . ", (select current_timestamp(0)), '" . $ocupacion . "', '" . $trabajo . "', " . $comboTipoSeguro . ",
        '" . $referido . "', '" . strtoupper($contacto_nombre) . "', '" . strtoupper($contacto_parentezco) . "', '" . $contacto_direccion . "', '" . $contacto_telefono . "', " . $comboFormaLLeg . ", '" . $fuente_info . "',
        '" . $institucion . "', '" . $institucion_telefono . "')";
        return $db->fetchRow($select);
    }

    public function listarPacientes()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from pg_paciente";
        return $db->fetchAll($select);
    }
    public function buscaPaciente($paciente)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select g.*, p.*, c.*,r.*,l.*,u.*,e.*,i.*
        from pg_paciente g
        join parroquia p
        on p.id_parroquia=g.p_parroq
        join canton c
        on c.id_canton=p.id_canton
        join provincia r
        on r.id_provincia=c.id_provincia
        join listas l
        on l.id_lista=g.p_nacionalidad
        join grupo_cultural u
        on u.id_grupcultural=g.p_grupo_c
        join estado_civil e
        on e.id_estcivil=g.p_est_civil
        join instruccion_paciente i
        on i.id_instruccion=g.p_instruccion
        where g.p_hc=" . $paciente;
        return $db->fetchRow($select);
    }

    public function listarProvincias()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from provincia order by 1";
        return $db->fetchAll($select);
    }
    public function listarCantones($prov_id)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from canton where id_provincia='" . $prov_id . "'";
        return $db->fetchAll($select);
    }
    public function listarParroquias($canton_id)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from parroquia where id_canton='" . $canton_id . "'";
        return $db->fetchAll($select);
    }
    public function listarNacionalidad()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from listas where padre_lista=1 order by 2;";
        return $db->fetchAll($select);
    }
    public function listarGrupoCultural()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from grupo_cultural;";
        return $db->fetchAll($select);
    }
    public function listarEstadoCivil()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from estado_civil;";
        return $db->fetchAll($select);
    }
    public function listarInstruccion()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from instruccion_paciente order by 1;";
        return $db->fetchAll($select);
    }
    public function listarTipoSeguro()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from listas where padre_lista=2 order by 1;";
        return $db->fetchAll($select);
    }
    public function listarFormaLlegada()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from paciente_forma_llegada;";
        return $db->fetchAll($select);
    }
    //---------busca camas
    public function buscaHab($especialidad_id)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from habitacion h
        join especialidad e
        on e.especialidad_id=h.especialidad_id
        where e.especialidad_id=".$especialidad_id;
        return $db->fetchAll($select);
    }
    /*  public function buscaHabCama($especialidad_id)
     {
         //devuelve todos los registros de la tabla
         $db = Zend_Registry::get('pgdb');
         //opcional, esto es para que devuelva los resultados como objetos $row->campo
         $db->setFetchMode(Zend_Db::FETCH_OBJ);
         $select = "select c.cama_id,c.cama_nombre,h.habitacion_id,h.habitacion_nombre,e.especialidad_id,e.especialidad_nombre,d.cama_estado_color
         from cama c
            join habitacion h
            on h.habitacion_id=c.habitacion_id
            join especialidad e
            on e.especialidad_id=h.especialidad_id
            join cama_estado d
            on d.cama_estado_id=c.cama_estado
            where e.especialidad_id=".$especialidad_id."
            ORDER BY 4;";
         return $db->fetchAll($select);
     } */
    public function buscaCamaEstado($habitacion_id, $cama_nombre)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select c.cama_id,c.cama_nombre,c.cama_estado,h.habitacion_id,h.habitacion_nombre,d.cama_estado_color
        from cama c
           join habitacion h
           on h.habitacion_id=c.habitacion_id
           join cama_estado d
           on d.cama_estado_id=c.cama_estado
           where h.habitacion_id=".$habitacion_id."
           and c.cama_nombre='".$cama_nombre."';";
        return $db->fetchRow($select);
    }
    public function asignaCamaPaciente($paciente_hc, $cedula, $cama_id, $cie10_cod,$cie10_tipo)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO cama_paciente( paciente_id, paciente_ci, cama_id, diagnosticos, 
        tipo_diagnosticos, fecha_cama_paciente, observacion)
        VALUES (".$paciente_hc.", '".$cedula."', ".$cama_id.", ARRAY[".$cie10_cod."], 
        ARRAY [ ".$cie10_tipo." ], current_timestamp(0), 'Primer ingreso');";
        return $db->fetchRow($select);
    }
}
