<?php

class Application_Model_DbTable_Perfiles extends Zend_Db_Table_Abstract
{
    protected $_name = 'perfiles';
    /**
     * listar_perfiles()
     * * esta funcion lista los perfiles de la bdd
     */
    public function listar_perfiles()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from perfiles order by 1";
        return $db->fetchAll($select);
    }
    /**
     * crearperfil()
     * * esta funcion crea nuevos perfiles
     * @param nombre: nombre del perfil (debe ser unico)
     * @param color: color del perfil (primary, secondary, success, warning, etc)
     * ! la ruta por defecto es index/dashboard
     * ? luego se puede modificar esta ruta por defecto
     */

    public function crearperfil($nombre, $color)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO perfiles(perf_nombre,perf_color,perf_controlador, perf_accion)
                    VALUES ('" . $nombre . "','".$color."','index','dashboard')
                    RETURNING perf_id; ";
        return $db->fetchRow($select);
    }
    /**
     * actualizarPerfil()
     * * esta funcion actualiza la informacionde un perfil
     * @param id: id del perfil (debe ser unico)
     * @param nombre: nombre del perfil (debe ser unico)
     * @param color: color del perfil
     * @param controlador: controlador del perfil
     * @param accion: accion del perfil
     * ! para establecer una ruta por defecto esta debe tener los pernisos
     * ? ejemplo: perfil INVITADO: permiso = 'allow' en ruta areas/index => se puede establecer la ruta por defecto = areas/index
     */

    public function actualizarPerfil($id, $nombre, $color, $controlador, $accion)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE perfiles
        SET perf_nombre='".$nombre."', perf_color='".$color."', perf_controlador='".strtolower($controlador)."', perf_accion='".strtolower($accion)."'
      WHERE perf_id=".$id." ;";
        return $db->fetchRow($select);
    }
    /**
     * eliminarpefil()
     * * esta funcion elimina un perfil
     * @param id: id del perfil que se pretende eliminar
     * ! si un perfil tiene usuarios no se eliminara
     */

    public function eliminarpefil($id)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = " DELETE FROM perfiles
        WHERE perf_id=" . $id . ";";
        return $db->fetchAll($select);
    }
    /**
     * cuenta_usuarios_perfil()
     * * esta funcion cuenta los usuarios que tiene un perfil
     * @param perfil_id: perfil_id del perfil
     */

    public function cuenta_usuarios_perfil($perfil_id)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select u.usu_iniciales from perfiles p
        join usuario u
        on p.perf_id=u.perf_id
        where p.perf_id=" . $perfil_id;
        return $db->fetchAll($select);
    }
    /**
     * listar_controladores_permisos()
     * * esta funcion lista los controladores desde la bdd
     * @param perfil_id: perfil_id del perfil
     * @param op: op del perfil
     * * se recibe una op como parametro para saber si vamos a asignar una ruta o un permiso
     * ? op=0 'se asignara una ruta'
     * ? op=1 'se asignara un permiso'
     * ! la variable op nos sirve para reutilizar el formulario
     */
    public function listar_controladores_permisos()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
        $select = "SELECT c.*
        FROM controladores c, acciones a                
        WHERE c.ctrl_id=a.ctrl_id
        GROUP BY c.ctrl_id,c.ctrl_nombre
        ORDER BY 1";
        
        return $db->fetchAll($select);
    }
    /**
     * listar_acciones_permisos()
     * * esta funcion lista los controladores desde la bdd
     * @param perfil_id: perfil_id del perfil
     * @param ctrl_id: id del controlador
     * @param op: op del perfil
     * * se recibe una op como parametro para saber si vamos a asignar una ruta o un permiso
     * ? op=0 'se asignara una ruta'
     * ? op=1 'se asignara un permiso'
     * ! la variable op nos sirve para reutilizar el formulario
     */

    public function listar_acciones_permisos()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);        
            $select = "SELECT a.accion_id
            FROM controladores c, acciones a        
            WHERE c.ctrl_id=a.ctrl_id
            ORDER BY 1;";        
        
        return $db->fetchAll($select);
    }
    /**
     * listar_controladores()
     * * esta funcion lista los controladores desde la bdd
     * @param perfil_id: perfil_id del perfil
     * @param op: op del perfil
     * * se recibe una op como parametro para saber si vamos a asignar una ruta o un permiso
     * ? op=0 'se asignara una ruta'
     * ? op=1 'se asignara un permiso'
     * ! la variable op nos sirve para reutilizar el formulario
     */
    public function listar_controladores($perfil, $op)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select='';
        if ($op==0) { // CONTROL DE RUTAS
            $select = "SELECT c.*
            FROM controladores c, acciones a        
                    WHERE c.ctrl_id=a.ctrl_id
                    AND a.accion_id IN (SELECT accion_id FROM usu_permisos u
                    WHERE u.perf_id=".$perfil."
                    AND u.permiso='allow')
                    GROUP BY c.ctrl_id,c.ctrl_nombre
                    ORDER BY 1;";
        } elseif ($op==1) { // CONTROL DE PERMISOS
            $select = "SELECT c.*
                FROM controladores c, acciones a                
                WHERE c.ctrl_id=a.ctrl_id
                AND a.accion_id NOT IN (SELECT accion_id FROM usu_permisos u
                WHERE u.perf_id=".$perfil.")
                GROUP BY c.ctrl_id,c.ctrl_nombre
                ORDER BY 1;";
        }
        return $db->fetchAll($select);
    }
    /**
     * listar_acciones()
     * * esta funcion lista los controladores desde la bdd
     * @param perfil_id: perfil_id del perfil
     * @param ctrl_id: id del controlador
     * @param op: op del perfil
     * * se recibe una op como parametro para saber si vamos a asignar una ruta o un permiso
     * ? op=0 'se asignara una ruta'
     * ? op=1 'se asignara un permiso'
     * ! la variable op nos sirve para reutilizar el formulario
     */

    public function listar_acciones($ctrl_id, $perfil, $op)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select='';
        if ($op==0) { // CONTROL DE RUTAS
            $select = "SELECT a.*
                    FROM controladores c, acciones a        
                    WHERE c.ctrl_id=a.ctrl_id
                    AND a.ctrl_id=".$ctrl_id."
                    AND a.accion_id  IN (SELECT accion_id FROM usu_permisos u
                    WHERE u.perf_id=".$perfil."
                    AND u.permiso='allow')
                    ORDER BY 1;";
        } elseif ($op==1) { // CONTROL DE PERMISOS
            $select = "SELECT a.*
                FROM controladores c, acciones a                
                WHERE c.ctrl_id=a.ctrl_id
                AND a.ctrl_id=".$ctrl_id."
                AND a.accion_id NOT IN (SELECT accion_id FROM usu_permisos u
                WHERE u.perf_id=".$perfil.")
                ORDER BY 1;";
        }
        
        return $db->fetchAll($select);
    }
}
