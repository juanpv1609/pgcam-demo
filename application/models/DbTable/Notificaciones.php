<?php

class Application_Model_DbTable_Notificaciones extends Zend_Db_Table_Abstract
{

    protected $_name = 'notificaciones';
    /**
     * listar()
     * * esta funcion lista 10 de las notificaciondes desde la bdd
     * ? se muestra en la barra de navegacion
     * ? muestra un limite de 10 notificaciones donde constan los siguientes datos:
     * * creador, fecha, contenido
     */
    public function listar()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT n.*, u.usu_nombres, u.usu_apellidos, c.causa_descripcion
        FROM notificaciones n
        JOIN usuario u
        ON u.usu_id=n.not_autor
        JOIN causa c
        ON c.causa_id=n.not_causa
        WHERE n.not_estado=0
        ORDER BY 4 DESC";
        return $db->fetchAll($select);
    }
    /**
     * listarTodo()
     * * esta funcion lista todas las notificaciondes desde la bdd
     * ? se muestra en la barra de navegacion
     * ? muestra un limite de 10 notificaciones donde constan los siguientes datos:
     * * creador, fecha, contenido
     */
    public function listarTodo()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT n.*, u.usu_nombres, u.usu_apellidos, c.causa_descripcion
        FROM notificaciones n
        JOIN usuario u
        ON u.usu_id=n.not_autor
        JOIN causa c
        ON c.causa_id=n.not_causa
        ORDER BY 4 DESC";
        return $db->fetchAll($select);
    }
    /**
     * insertarNotificacion()
     * * esta funcion crea notificaciones
     * ! inserta las notificaciones en la bdd
     * TODO: esta accion debe realizarla un trigger desde postgres
     * ! consideraciones que deben generar una notificacion:
     * ? Ingreso o alta de un  paciente
     * ? Asignacion de cama
     * ? Cambio de cama
     * ? Defuncion
     * ? camas disponibles menor a 10%
     */
    public function insertarNotificacion($mensaje, $usuario,$causa_id,$cedula)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO notificaciones(not_mensaje, not_autor, not_fecha_creacion, not_estado, not_causa,p_ci)
        VALUES ('".$mensaje."',".$usuario.",(select current_timestamp(0)),0,".$causa_id.",'".$cedula."');";
        return $db->fetchRow($select);
    }
    /**
     * editarNotificacion()
     * * esta funcion edita notificaciones cambia su estado
     * ! inserta las notificaciones en la bdd
     * TODO: esta accion debe realizarla un trigger desde postgres
     * ! consideraciones que deben generar una notificacion:
     */
    public function editarNotificacion($id)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE notificaciones
        SET not_estado=1
        WHERE not_id=".$id.";";
        return $db->fetchRow($select);
    }

}

