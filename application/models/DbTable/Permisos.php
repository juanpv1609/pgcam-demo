<?php

class Application_Model_DbTable_Permisos extends Zend_Db_Table_Abstract
{
    protected $_name = 'permisos';

    /* public function permisos_perfil($perfil_id)
        {
            //devuelve todos los registros de la tabla
            $db = Zend_Registry::get('pgdb');
            //opcional, esto es para que devuelva los resultados como objetos $row->campo
            $db->setFetchMode(Zend_Db::FETCH_OBJ);
            $select = "SELECT controller_name as controlador,action_name as accion,permission as permiso
            FROM usu_permisos
            WHERE perf_id=" . $perfil_id;
            return $db->fetchAll($select);
        } */
    /**
     * actualizarPermisosPerfil
     * * esta funcion actualiza los permisos de un perfil
     * @param id: id del permiso que se pretende actualizar
     * @param op: op puede ser ALLOW o DENY
     * ? ALLOW= permitir
     * ? DENy= denegar
     *
     */
    public function actualizarPermisosPerfil($id, $opcion)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE usu_permisos
        SET permiso='".$opcion."'
      WHERE usu_perm_id=".$id;
        return $db->fetchRow($select);
    }
    /**
     * listar_permisos_usuario
     * * esta funcion lista los permisos de un usuario de acuerdo a su perfil
     * * utilizado para controlar el acceso a rutas y ciertas acciones
     * ! usado en el PreDispatch
     * @param perfil: perfil del usuario
     * ? se compara el controlador y la accion de la bdd con la ubicacion actual del usuario
     *
     */
    public function listar_permisos_usuario($perfil)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT p.perf_id,p.perf_nombre,c.ctrl_nombre,a.accion_nombre,u.*
        FROM usu_permisos u
        JOIN perfiles p
        ON p.perf_id=u.perf_id
        JOIN acciones a
        ON a.accion_id=u.accion_id
        JOIN controladores c
        ON c.ctrl_id=a.ctrl_id
        WHERE u.perf_id=".$perfil."
        ORDER BY 7";
        return $db->fetchAll($select);
    }
    /**
     * listar_permisos_perfil
     * * esta funcion lista los permisos de un perfil
     * * utilizado para poder modificar dichos permisos
     * @param perfil: id del perfil
     * ? obtiene los controladores
     *
     */

    public function listar_permisos_perfil($perfil)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT p.perf_id,p.perf_nombre,c.ctrl_nombre
        FROM usu_permisos u
        JOIN perfiles p
        ON p.perf_id=u.perf_id
        JOIN acciones a
        ON a.accion_id=u.accion_id
        JOIN controladores c
        ON c.ctrl_id=a.ctrl_id
        WHERE u.perf_id=".$perfil."
        GROUP BY p.perf_id,p.perf_nombre,c.ctrl_nombre";
        return $db->fetchAll($select);
    }
    /**
     * listar_permisos_usuario_ctrl
     * * esta funcion lista los permisos de un perfil
     * * utilizado para poder modificar dichos permisos
     * @param perfil: id del perfil
     * @param ctrl: controlador_id del cual obtener sus acciones
     * ? obtiene los acciones y permisos de acuerdo al controlador y perfil enviados
     *
     */

    public function listar_permisos_usuario_ctrl($perfil, $ctrl)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT p.perf_id,p.perf_nombre,c.ctrl_nombre,a.accion_nombre,u.*
        FROM usu_permisos u
        JOIN perfiles p
        ON p.perf_id=u.perf_id
        JOIN acciones a
        ON a.accion_id=u.accion_id
        JOIN controladores c
        ON c.ctrl_id=a.ctrl_id
        WHERE u.perf_id=".$perfil."
        AND ctrl_nombre='".$ctrl."'
        ORDER BY 7";
        return $db->fetchAll($select);
    }
    /**
     * crear_permiso
     * * esta funcion crea nuevos permisos para un perfil
     * @param perf_id: id del perfil
     * @param accion_id: accion_id al cual se va a establecer un permiso
     * @param permiso: ALLOW o DENY
     * ? ALLOW=permitir DENY= denegar
     * ! si el permiso ya existe solo se actualiza
     * TODO en el formulario ya se controla que solo se muestre los permisos faltantes
     *
     */

    public function crear_permiso($perf_id)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $perfil = new Application_Model_DbTable_Perfiles();
        $acciones = $perfil->listar_acciones_permisos(); //array con todas las acciones de la bdd
        $select ="INSERT INTO usu_permisos(perf_id, accion_id, permiso) VALUES ";
        foreach ($acciones as $accion) {
            $select .="(".$perf_id.",".$accion->accion_id.",'deny'), ";
        }
        $select = substr($select, 0, (strlen($select)-2));
        return $db->fetchAll($select);
    }
}
