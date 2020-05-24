<?php

class Application_Model_DbTable_Cie10 extends Zend_Db_Table_Abstract
{

    protected $_name = 'cie10';
    /**
     * listar()
     * * Esta funcion lista los diagnosticos de acuerdo a una busqueda tipo LIKE
     * ? devuelve los resultados como objetos $row->campo
     * @param dato: campo que se desea buscar
     * ! es necesario un filtro ya que son demasiados datos
     */

    public function listar($dato)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT s.sub_cod,s.descripcion_sub,c.descripcion
        FROM cie10_sub_categoria s
        JOIN cie10_categoria c
        ON c.cie10_categoria_id = s.cie10_categoria_id
        WHERE sub_cod LIKE '" . strtoupper($dato) . "%'
        OR descripcion_sub LIKE '" . strtoupper($dato) . "%';";
        return $db->fetchAll($select);
    }
    /**
     * listar_categoria()
     * * Esta funcion lista las categorias de acuerdo a una busqueda tipo LIKE
     * ? devuelve los resultados como objetos $row->campo
     * @param dato: campo que se desea buscar
     * ! es necesario un filtro ya que son demasiados datos
     */

    public function listar_categoria($dato)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT c.*, s.descripcion_sub,c_a.descripcion as des_capitulo
        FROM cie10_categoria c
        JOIN cie10_sub_capitulo s
        ON s.cie10_sub_capitulo_id = c.cie10_sub_capitulo_id
        JOIN cie10_capitulo c_a
        ON c_a.cie10_capitulo_id = s.cie10_capitulo_id
        WHERE c.cie10_categoria_id LIKE '" . strtoupper($dato) . "%'
        OR c.descripcion LIKE '" . ucwords($dato) . "%';";
        return $db->fetchAll($select);
    }
    /**
     * listar_capitulo()
     * * Esta funcion lista los capitulos no es necesario un filtro ya que no son demasiados datos
     * ? devuelve los resultados como objetos $row->campo
     * @param dato: campo que se desea buscar
     */
    public function listar_capitulo()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT *
        FROM cie10_capitulo ORDER BY 1;";
        return $db->fetchAll($select);
    }
    /**
     * listar_categoria()
     * * Esta funcion lista los subcapitulos de acuerdo al capitulo ingresado
     * ? devuelve los resultados como objetos $row->campo
     * @param capitulo_id: campo que se desea buscar
     */

    public function listar_sub_capitulo($capitulo_id)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT s.descripcion_sub
        FROM cie10_capitulo c
        JOIN cie10_sub_capitulo s 
        ON c.cie10_capitulo_id=s.cie10_capitulo_id
        WHERE c.cie10_capitulo_id=".$capitulo_id.";";
        return $db->fetchAll($select);
    }
}
