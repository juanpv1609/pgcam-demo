<?php

class Application_Model_DbTable_Usuario extends Zend_Db_Table_Abstract
{

    protected $_name = 'usuario';
    
    public function insertarusuario($nombre,$apellido, $correo, $clave) {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO usuario(usu_nombres, correo, clave,usu_estado_id,perf_id)
    VALUES ('".$nombre." ".$apellido."','".$correo."',MD5('".$clave."'),1,1); ";
        return $db->fetchRow($select);
    }
    public function obtienePerfil($perfil_id)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT p.perf_nombre
        FROM usuario u
        JOIN perfiles p
        ON p.perf_id=u.perf_id
        WHERE u.usu_id=".$perfil_id;
        return $db->fetchRow($select);
    }
    public function listar_usuarios()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT *
        FROM usuario u
        JOIN perfiles p
        ON p.perf_id=u.perf_id
        JOIN usu_estado e
        ON e.usu_estado_id=u.usu_estado_id";
        return $db->fetchAll($select);
    }
    public function listar_perfiles()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from perfiles";
        return $db->fetchAll($select);
    }

}

