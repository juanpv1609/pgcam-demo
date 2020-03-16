<?php

class Application_Model_DbTable_Admision extends Zend_Db_Table_Abstract
{

    protected $_name = 'admision';

    
    public function admisionpaciente($apellido_paterno,$apellido_materno,$primer_nombre,$segundo_nombre,$cedula
        ,$telefono,$comboParroq,$barrio,$direccion,$fecha_n,$lugar_n,$comboNacionalidad
        ,$comboGrupo,$comboEdad,$comboGenero,$comboEstado,$comboInstruccion,$ocupacion
        ,$trabajo,$comboTipoSeguro,$referido,$contacto_nombre,$contacto_parentezco,$contacto_direccion
        ,$contacto_telefono,$comboFormaLLeg,$fuente_info,$institucion,$institucion_telefono){
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO pg_paciente(p_ci, p_nombres, p_apellidos, p_telefono, p_parroq, 
        p_barrio, p_direccion, p_fecha_n, p_lugar_n, p_nacionalidad, p_grupo_c, p_edad, 
        p_sexo, p_est_civil, p_instruccion, p_fecha_admi, p_ocupacion, p_trabajo, p_tipo_seguro, 
        p_referido, p_contacto, p_contacto_parentezco, p_contacto_direc, p_contacto_tlfn, 
        p_forma_llegada, p_fuente_inf, p_quien_entrega, p_quien_entrega_tlfn) 
        VALUES ('".$cedula."', '".$primer_nombre." ".$segundo_nombre."', '".$apellido_paterno." ".$apellido_materno."', '".$telefono."', '".$comboParroq."',
        '".$barrio."', '".$direccion."', '".$fecha_n."', '".$lugar_n."', ".$comboNacionalidad.", ".$comboGrupo.", ".$comboEdad.", 
        '".$comboGenero."', ".$comboEstado.", ".$comboInstruccion.", (select now()), '".$ocupacion."', '".$trabajo."', ".$comboTipoSeguro.", 
        '".$referido."', '".$contacto_nombre."', '".$contacto_parentezco."', '".$contacto_direccion."', '".$contacto_telefono."', ".$comboFormaLLeg.", '".$fuente_info."', 
        '".$institucion."', '".$institucion_telefono."')";
        return $db->fetchRow($select);
    }
    public function admisionp($apellido_paterno,$primer_nombre,$cedula,$telefono){
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO p(p_nombre, p_apellido, p_cedula, p_telefono) 
        VALUES ('".$primer_nombre."', '".$apellido_paterno."', '".$cedula."','".$telefono."')";
        return $db->fetchRow($select);
    }


    public function listarProvincias()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from provincia";
        return $db->fetchAll($select);
    }
    public function listarCantones($prov_id)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from canton where id_provincia='".$prov_id."'";
        return $db->fetchAll($select);
    }
    public function listarParroquias($canton_id)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from parroquia where id_canton='".$canton_id."'";
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

}

