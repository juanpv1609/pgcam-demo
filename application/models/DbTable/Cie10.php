<?php

class Application_Model_DbTable_Cie10 extends Zend_Db_Table_Abstract
{

    protected $_name = 'cie10';

    public function listar($dato)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT *
        FROM cie10_sub_categoria
        WHERE sub_cod LIKE '" . strtoupper($dato) . "%'
        OR descripcion_sub LIKE '" . strtoupper($dato) . "%';";

        return $db->fetchAll($select);
    }
    public function listar_categoria($dato)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT *
        FROM cie10_categoria
        WHERE cod LIKE '" . strtoupper($dato) . "%'
        OR descripcion LIKE '" . ucwords($dato) . "%';";
        return $db->fetchAll($select);
    }
    public function listar_capitulo()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT *
        FROM cie10_capitulo;";
        return $db->fetchAll($select);
    }
}
