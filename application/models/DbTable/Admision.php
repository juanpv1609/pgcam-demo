<?php

class Application_Model_DbTable_Admision extends Zend_Db_Table_Abstract
{
    protected $_name = 'admision';
    /**
     * admisionpaciente
     * * esta funcion crea un paciente por emergencia de acuerdo al formulario 008
     * @param apellido_paterno: apellido_paterno del paciente a ingresar
     * @param apellido_materno: apellido_materno del paciente a ingresar
     * @param primer_nombre: primer_nombre del paciente a ingresar
     * @param segundo_nombre: segundo_nombre del paciente a ingresar
     * @param cedula: cedula del paciente a ingresar
     * @param telefono: telefono del paciente a ingresar
     * @param comboParroq: codigo de parroquia del paciente a ingresar
     * @param barrio: barrio del paciente a ingresar
     * @param direccion: direccion del paciente a ingresar
     * @param fecha_n: fecha_n del paciente a ingresar
     * @param lugar_n: lugar_n del paciente a ingresar
     * @param comboNacionalidad: id de la Nacionalidad del paciente a ingresar
     * @param comboGrupo: id del grupo cultural del paciente a ingresar
     * @param comboEdad: edad del paciente a ingresar
     * @param comboGenero: sexo del paciente a ingresar
     * @param comboEstado: estado civil del paciente a ingresar
     * @param comboInstruccion: nivel de instruccion del paciente a ingresar
     * @param ocupacion: ocupacion del paciente a ingresar
     * @param trabajo: trabajo del paciente a ingresar
     * @param comboTipoSeguro: tipo de seguro de salud del paciente a ingresar
     * @param referido: referido del paciente a ingresar
     * @param contacto_nombre: contacto_nombre del paciente a ingresar
     * @param contacto_parentezco: contacto_parentezco del paciente a ingresar
     * @param contacto_direccion: contacto_direccion del paciente a ingresar
     * @param contacto_telefono: contacto_telefono del paciente a ingresar
     * @param comboFormaLLeg: forma de llegada del paciente a ingresar
     * @param fuente_info: fuente_info del paciente a ingresar
     * @param institucion: institucion del paciente a ingresar
     * @param institucion_telefono: institucion_telefono del paciente a ingresar
     * ? los datos de contacto se ingresan a la BDD tipo array al igual que la institucion
     * TODO: definir que parametros son obligatorios y cuales no
     */
    public function admisionpaciente(
        $apellido_paterno,
        $apellido_materno,
        $primer_nombre,
        $segundo_nombre,
        $cedula,
        $telefono,
        $comboParroq,
        $barrio,
        $direccion,
        $fecha_n,
        $lugar_n,
        $comboNacionalidad,
        $comboGrupo,
        $comboEdad,
        $comboGenero,
        $comboEstado,
        $comboInstruccion,
        $ocupacion,
        $trabajo,
        $comboTipoSeguro,
        $referido,
        $contacto_nombre,
        $contacto_parentezco,
        $contacto_direccion,
        $contacto_telefono,
        $comboFormaLLeg,
        $fuente_info,
        $institucion,
        $institucion_telefono,
        $usuario
    ) {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO paciente(p_ci, p_nombres, p_apellidos, p_telefono, id_parroquia,
        p_barrio, p_direccion, p_fecha_n, p_lugar_n, nacionalidad_id, id_grupocultural, p_edad,
        p_sexo, id_estcivil, id_instruccion, p_fecha_admi, p_ocupacion, p_trabajo, tipo_seguro_id,
        p_referido, p_contacto,p_forma_lleg_id, p_fuente_inf, p_quien_entrega,usu_id)
        VALUES ('" . $cedula . "', '" . strtoupper($primer_nombre) . " " . strtoupper($segundo_nombre) . "', '" . strtoupper($apellido_paterno) . " " . strtoupper($apellido_materno) . "', '" . $telefono . "', '" . $comboParroq . "',
        '" . $barrio . "', '" . $direccion . "', '" . $fecha_n . "', '" . $lugar_n . "', " . $comboNacionalidad . ", " . $comboGrupo . ", " . $comboEdad . ",
        '" . $comboGenero . "', " . $comboEstado . ", " . $comboInstruccion . ", (select current_timestamp(0)), '" . $ocupacion . "', '" . $trabajo . "', " . $comboTipoSeguro . ",
        '" . $referido . "', ARRAY['" . strtoupper($contacto_nombre) . "','" . strtoupper($contacto_parentezco) . "','" . $contacto_direccion . "','" . $contacto_telefono . "'], " . $comboFormaLLeg . ", '" . $fuente_info . "',
        ARRAY['" . $institucion . "','" . $institucion_telefono . "'],".$usuario.");";
        return $db->fetchRow($select);
    }
    public function edicionPaciente(
        $apellido_paterno,
        $apellido_materno,
        $primer_nombre,
        $segundo_nombre,
        $cedula,
        $telefono,
        $comboParroq,
        $barrio,
        $direccion,
        $fecha_n,
        $lugar_n,
        $comboNacionalidad,
        $comboGrupo,
        $comboEdad,
        $comboGenero,
        $comboEstado,
        $comboInstruccion,
        $ocupacion,
        $trabajo,
        $comboTipoSeguro,
        $referido,
        $contacto_nombre,
        $contacto_parentezco,
        $contacto_direccion,
        $contacto_telefono,
        $comboFormaLLeg,
        $fuente_info,
        $institucion,
        $institucion_telefono
    ) {
        $usuario = Zend_Auth::getInstance()->getIdentity();
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select="UPDATE paciente
                SET p_ci='" . $cedula . "', p_nombres='" . strtoupper($primer_nombre) . " " . strtoupper($segundo_nombre) . "', 
                p_apellidos='" . strtoupper($apellido_paterno) . " " . strtoupper($apellido_materno) . "', id_parroquia='" . $comboParroq . "', 
                p_barrio='" . $barrio . "', p_direccion='" . $direccion . "', p_telefono='" . $telefono . "', p_fecha_n='" . $fecha_n . "', 
                p_lugar_n='" . $lugar_n . "', id_grupocultural=" . $comboGrupo . ", nacionalidad_id=" . $comboNacionalidad . ", 
                p_edad=" . $comboEdad . ", p_sexo='" . $comboGenero . "', id_estcivil=" . $comboEstado . ",id_instruccion=" . $comboInstruccion . ", 
                p_ocupacion='" . $ocupacion . "', p_trabajo='" . $trabajo . "', tipo_seguro_id=" . $comboTipoSeguro . ",p_referido='" . $referido . "', 
                p_contacto=ARRAY['" . strtoupper($contacto_nombre) . "','" . strtoupper($contacto_parentezco) . "','" . $contacto_direccion . "','" . $contacto_telefono . "'], 
                p_forma_lleg_id=" . $comboFormaLLeg . ", p_fuente_inf='" . $fuente_info . "', 
                p_quien_entrega=ARRAY['" . $institucion . "','" . $institucion_telefono . "'], usu_id=".$usuario->usu_id.", p_observacion='Actualizacion de datos'
                WHERE p_ci='" . $cedula . "';";
        return $db->fetchRow($select);
    }
    /**
     * listarPacientesCama
     * * esta funcion lista los pacientes que tienen una cama asignada de la bdd
     */
    public function listarPacientesCama()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select c.*, a.cama_nombre,h.habitacion_nombre,e.especialidad_alias,e.especialidad_nombre,e.especialidad_color
        from cama_paciente c
        join cama a
        on a.cama_id=c.cama_id
        join habitacion h
        on h.habitacion_id=a.habitacion_id
        join especialidad e
        on e.especialidad_id=h.especialidad_id
        where c.causa_id=6 or c.causa_id=7 or c.causa_id=1;";
        return $db->fetchAll($select);
    }
    /**
     * listarPacientesCamaCambio
     * * esta funcion lista los pacientes que tienen una cama asignada de la bdd
     * * para realizar un cambio o egreso
     */
    public function listarPacientesCamaCambio()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from cama_paciente
        where causa_id=6 or causa_id=7 or causa_id=1;";
        return $db->fetchAll($select);
    }
    /**
     * listarPacientes
     * * esta funcion lista los pacientes que NO tienen una cama asignada de la bdd
     * TODO: conectar con la bdd HGONA para obtener ese listado de pacientes
     * ! muestra los pacientes ingresados por el formulario 008
     */
    public function listarPacientes()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from paciente
where p_id not in (select p_id from cama_paciente)
and p_ci not in (select paciente_ci from cama_paciente)";
        return $db->fetchAll($select);
    }
    /**
     * listarPacientes_CE
     * * esta funcion lista los pacientes de la bdd
     * TODO: conectar con la bdd HGONA para obtener ese listado de pacientes
     * ! muestra los pacientes ingresados por el formulario 008
     */
    public function Paciente_info($id,$paciente, $origen_paciente)
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
        }else if($origen_paciente==1){
            //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select concat(p_apellidos,' ',p_nombres) as nombre from paciente
        where p_ci='".$paciente."'
        OR p_id=".$id;
        return $db->fetchRow($select);

        }
    }

    /**
     * buscaPaciente
     * * esta funcion busca un paciente
     * @param paciente: id del paciente que se desea buscar
     */

    public function buscaPaciente($paciente, $opcion)
    {
        if ($opcion==2) {
            $configDb = array();
            $configDb['host'] = 'localhost';
            $configDb['username'] = 'postgres';
            $configDb['password'] = 'postgres';
            $configDb['dbname'] = 'hgona';
            $db2= Zend_Db::factory('PDO_PGSQL', $configDb);
            $db2->setFetchMode(Zend_Db::FETCH_OBJ);
            $select = "select * FROM paciente
            where hc_digital=" . $paciente."
            OR ci='".$paciente."';";
            return $db2->fetchRow($select);
        } else if ($opcion==1) {
            //devuelve todos los registros de la tabla
            $db = Zend_Registry::get('pgdb');
            //opcional, esto es para que devuelva los resultados como objetos $row->campo
            $db->setFetchMode(Zend_Db::FETCH_OBJ);
            $select = "select g.*, p.*, c.*,r.*,n.*,u.*,e.*,i.*,t.*
                from paciente g
                join parroquia p
                on p.id_parroquia=g.id_parroquia
                join canton c
                on c.id_canton=p.id_canton
                join provincia r
                on r.id_provincia=c.id_provincia
                join nacionalidad n
                on n.nacionalidad_id=g.nacionalidad_id
                join tipo_seguro t
                on t.tipo_seguro_id=g.tipo_seguro_id
                join grupo_cultural u
                on u.id_grupocultural=g.id_grupocultural
                join estado_civil e
                on e.id_estcivil=g.id_estcivil
                join instruccion_paciente i
                on i.id_instruccion=g.id_instruccion
                where g.p_id=" . $paciente."
                OR g.p_ci='". $paciente."'";
            return $db->fetchRow($select);
        }
    }
    /**
     * historialPaciente
     * * esta funcion busca la cama actual asiganada a un paciente
     * @param paciente: id del paciente que se desea buscar
     * ? realiza un join con CIE10, causa, cama
     * ! diagnosticos son de tipo array
     */

    public function historialPaciente($paciente)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT h.*,c.cama_nombre,h_a.habitacion_nombre,e.especialidad_nombre,c_a.causa_descripcion
                    FROM historial_cp h
                    JOIN cama c
                    ON c.cama_id = h.cama_id
                    JOIN habitacion h_a
                    ON h_a.habitacion_id = c.habitacion_id
                    JOIN especialidad e
                    ON e.especialidad_id = h_a.especialidad_id
                    JOIN causa c_a
                    ON c_a.causa_id = h.causa_id
                    WHERE h.paciente_ci='".$paciente."'
                    ORDER BY h.fecha_ingreso ;";
        return $db->fetchAll($select);
    }
    /**
     * buscaCamaPaciente
     * * esta funcion busca la cama actual asiganada a un paciente
     * @param paciente: id del paciente que se desea buscar
     * ? realiza un join con CIE10, causa, cama
     * ! diagnosticos son de tipo array
     */

    public function buscaCamaPaciente($paciente)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select c.*,a.*,h.*,e.*,u.causa_descripcion,s.*
        from cama_paciente c 
        join cama a
        on a.cama_id=c.cama_id
        join habitacion h
        on h.habitacion_id=a.habitacion_id
        join especialidad e
        on e.especialidad_id=h.especialidad_id
        join causa u
        on u.causa_id=c.causa_id
        join cie10_sub_categoria s
        on s.sub_cod=c.diagnosticos[1]
        or s.sub_cod=c.diagnosticos[2]
        or s.sub_cod=c.diagnosticos[3]
        where c.cama_paciente_id=".$paciente.";";
        return $db->fetchAll($select);
    }
    /**
     * listarProvincias
     * * esta funcion lista las provincias para el form 008
     */

    public function listarProvincias()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from provincia order by 1";
        return $db->fetchAll($select);
    }
    /**
     * listarCantones
     * * esta funcion lista los cantones de acuerdo a la provincia para el form 008
     * @param prov_id: id de la provincia en la cual buscar sus cantones
     */

    public function listarCantones($prov_id)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from canton where id_provincia='" . $prov_id . "'";
        return $db->fetchAll($select);
    }
    /**
     * listarParroquias
     * * esta funcion lista las parroquias de acuerdo al canton para el form 008
     * @param canton_id: id del canton en la cual buscar sus parroquias
     */

    public function listarParroquias($canton_id)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from parroquia where id_canton='" . $canton_id . "'";
        return $db->fetchAll($select);
    }
    /**
     * listarNacionalidad
     * * esta funcion lista las nacionalidades para el form 008
     */

    public function listarNacionalidad()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from nacionalidad order by 2;";
        return $db->fetchAll($select);
    }
    /**
     * listarGrupoCultural
     * * esta funcion lista los grupos culturales para el form 008
     */

    public function listarGrupoCultural()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from grupo_cultural;";
        return $db->fetchAll($select);
    }
    /**
     * listarEstadoCivil
     * * esta funcion lista los diferentes estados civiles para el form 008
     */

    public function listarEstadoCivil()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from estado_civil;";
        return $db->fetchAll($select);
    }
    /**
     * listarInstruccion
     * * esta funcion lista los diferentes niveles de instruccion para el form 008
     */

    public function listarInstruccion()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from instruccion_paciente order by 1;";
        return $db->fetchAll($select);
    }
    /**
     * listarTipoSeguro
     * * esta funcion lista los diferentes tipos de seguros de salud para el form 008
     */

    public function listarTipoSeguro()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from tipo_seguro order by 1;";
        return $db->fetchAll($select);
    }
    /**
     * listarFormaLlegada
     * * esta funcion lista las formas de llegada del paciente para el form 008
     */

    public function listarFormaLlegada()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from paciente_forma_llegada;";
        return $db->fetchAll($select);
    }
    /**
     * buscaHab
     * * esta funcion disenia la matriz de camas para la asignacion
     * ! importante
     * @param especialidad_id: camas correspondientes a esta especialidad
     * ? de acuerdo a la especialidad se obtiene las camas, su estado, su id
     */

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
    /**
     * mapaCamas
     * * esta funcion disenia la matriz de camas para la asignacion
     * ! importante
     * @param especialidad_id: camas correspondientes a esta especialidad
     * ? de acuerdo a la especialidad se obtiene las camas, su estado, su id
     */

    public function mapaCamas($especialidad_id)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select='';
        if ($especialidad_id==0) {
            $select = "SELECT  e.especialidad_nombre, e.especialidad_color, e.especialidad_alias,
                    h.habitacion_nombre,c.cama_nombre, 
                    c_p.p_id,c_p.paciente_ci,c_p.fecha_ingreso,c_p.entrada,c_p.diagnosticos
                    FROM cama_paciente c_p
                    JOIN cama c
                    ON c.cama_id=c_p.cama_id
                    JOIN habitacion h
                    ON h.habitacion_id=c.habitacion_id
                    JOIN especialidad e
                    ON e.especialidad_id=h.especialidad_id
                    where (c_p.causa_id=1 or c_p.causa_id=6 or c_p.causa_id=7);";
        } else {
            $select = "SELECT  e.especialidad_nombre, e.especialidad_color, e.especialidad_alias,
                    h.habitacion_nombre,c.cama_nombre, 
                    c_p.p_id,c_p.paciente_ci,c_p.fecha_ingreso,c_p.entrada,c_p.diagnosticos
                    FROM cama_paciente c_p
                    JOIN cama c
                    ON c.cama_id=c_p.cama_id
                    JOIN habitacion h
                    ON h.habitacion_id=c.habitacion_id
                    JOIN especialidad e
                    ON e.especialidad_id=h.especialidad_id
                    WHERE (c_p.causa_id=1 or c_p.causa_id=6 or c_p.causa_id=7)
                    and e.especialidad_id=".$especialidad_id;
        }
        
        return $db->fetchAll($select);
    }
    /**
     * buscaCamaEstado
     * * esta funcion obtiene el estado de cada cama para su asignacion de acuerdo a su habitacion
     * ! importante
     * @param habitacion_id: habitacion_id correspondientes a esta especialidad
     * @param cama_nombre: cama_nombre de la cual se desea conoces su estado
     * ? de acuerdo a la especialidad se obtiene las camas, su estado, su id
     */

    public function buscaCamaEstado($habitacion_id, $cama_nombre)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select c.cama_id,c.cama_nombre,c.cama_estado_id,h.habitacion_id,h.habitacion_nombre,d.cama_estado_color
        from cama c
           join habitacion h
           on h.habitacion_id=c.habitacion_id
           join cama_estado d
           on d.cama_estado_id=c.cama_estado_id
           where h.habitacion_id=".$habitacion_id."
           and c.cama_nombre='".$cama_nombre."';";
        return $db->fetchRow($select);
    }
    /**
     * asignaCamaPaciente
     * * esta funcion obtiene el estado de cada cama para su asignacion de acuerdo a su habitacion
     * ! importante
     * @param paciente_hc: paciente_hc que se pretende asignar una cama
     * @param cedula: cedula que se pretende asignar una cama
     * @param cama_id: cama_id que sera asignada
     * @param causa_id: causa_id la causa por la asignacion
     * @param cie10_cod: cie10_cod diagnosticos de ingreso tipo array
     * @param cie10_tipo: cie10_tipo tipo diagnosticos de ingreso tipo array
     * ? el tipo de diagnostico puede ser: presuntivo o definitivo
     */

    public function asignaCamaPaciente($paciente_hc, $cedula, $cama_id, $causa_id, $cie10_cod, $cie10_tipo, $usuario, $opcion)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO cama_paciente( p_id, paciente_ci, cama_id,causa_id, diagnosticos, 
        tipo_diagnosticos, fecha_ingreso, observacion,usu_id,entrada)
        VALUES (".$paciente_hc.", '".$cedula."', ".$cama_id.", ".$causa_id." , ARRAY[".$cie10_cod."], 
        ARRAY [ ".$cie10_tipo." ], current_timestamp(0), 'Primer ingreso',".$usuario.",".$opcion.");";
        return $db->fetchRow($select);
    }
    /**
     * updateCamaPaciente
     * * esta funcion realiza el cambio de cama de un paciente
     * ! importante
     * @param paciente_hc: paciente_hc que se pretende asignar una cama
     * @param cedula: cedula que se pretende asignar una cama
     * @param cama_id: cama_id que sera asignada
     * @param causa_id: causa_id la causa por la asignacion
     * @param cie10_cod: cie10_cod diagnosticos de ingreso tipo array
     * @param cie10_tipo: cie10_tipo tipo diagnosticos de ingreso tipo array
     * ? el tipo de diagnostico puede ser: presuntivo o definitivo
     */

    public function updateCamaPaciente($cama_paciente_id,
                $opcionCausa,
                $cama_id,
                $cie10_cod,
                $cie10_tipo,
                $usuario)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $observacion='';
        switch ($opcionCausa) {
            case 2:
                $observacion='Contrareferencia';
                break;
            case 3:
                $observacion='Defuncion';
                break;
            case 4:
                $observacion='Egreso / Alta';
                break;
            case 5:
                $observacion='Referencia';
                break;
            case 6:
                $observacion='Transferencia a otro servicio';
                break;
            case 7:
                $observacion='Cambio de cama';
                break;            
            default:
                $observacion='-----';
                break;
        }
        $select = "UPDATE public.cama_paciente
                    SET cama_id=".$cama_id.", causa_id=".$opcionCausa.",  
                        diagnosticos=ARRAY[".$cie10_cod."], tipo_diagnosticos=ARRAY[".$cie10_tipo."], fecha_ingreso=current_timestamp(0), observacion='".$observacion."', 
                        usu_id=".$usuario."
                    WHERE cama_paciente_id=".$cama_paciente_id.";";
        return $db->fetchRow($select);
    }
    /**
     * verificaCamaPacienteIngreso
     * * esta funcion verifica si el paciente ya tiene asignada una cama como primer ingreso
     * ! importante
     * @param paciente_id: paciente_id que se pretende buscar
     * @param causa_id: causa_id dato a verificar
     * ? si ya tiene asignada una cama no hace nada
     * ! se verifica lo siguiente:
     * * si la fecha de egreso es null quiere decir que tiene una cama y es primer ingreso
     */

    public function verificaCamaPacienteIngreso($paciente_id, $causa_id)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from cama_paciente
        where p_id=".$paciente_id."
        and causa_id=".$causa_id.";";
        return $db->fetchRow($select);
    }
    /**
     * listarCausa
     * * esta funcion lista las causas de movimiento de pacientes
     * ! importante
     * @param paciente_id: paciente_id que se pretende buscar
     * @param causa_id: causa_id dato a verificar
     * ? si ya tiene asignada una cama no hace nada
     * ! se verifica lo siguiente:
     * * si la fecha de egreso es null quiere decir que tiene una cama y es primer ingreso
     */

    public function listarCausa()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from causa
        where causa_estado=1
        order by 1 desc;";
        return $db->fetchAll($select);
    }
}
