<?php

class Application_Model_DbTable_Bdd extends Zend_Db_Table_Abstract
{
    protected $_name = 'bdd';
    /**
     * listarTablas()
     * * Esta funcion lista las areas registradas mediante consulta SQL
     * ? devuelve los resultados como objetos $row->campo
     */
    public function listarTablas()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT t1.TABLE_NAME AS tabla_nombre
                    FROM INFORMATION_SCHEMA.COLUMNS t1
                    INNER JOIN PG_CLASS t2 ON (t2.RELNAME = t1.TABLE_NAME)    
                    WHERE t1.TABLE_SCHEMA = 'public'
                    GROUP BY t1.TABLE_NAME
                    ORDER BY
                    t1.TABLE_NAME;";
        return $db->fetchAll($select);
    }
    /**
     * diccionarioTabla($tabla)
     * * Esta funcion lista las areas registradas mediante consulta SQL
     * ? devuelve los resultados como objetos $row->campo
     */
    public function diccionarioTabla($tabla)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT
                    t1.TABLE_NAME AS tabla_nombre,
                    t1.COLUMN_NAME AS columna_nombre,
                    t1.COLUMN_DEFAULT AS columna_defecto,
                    t1.IS_NULLABLE AS columna_nulo,
                    t1.DATA_TYPE AS columna_tipo_dato,
                    COALESCE(t1.NUMERIC_PRECISION,
                    t1.CHARACTER_MAXIMUM_LENGTH) AS columna_longitud,
                    PG_CATALOG.COL_DESCRIPTION(t2.OID,
                    t1.DTD_IDENTIFIER::int) AS columna_descripcion
                FROM 
                    INFORMATION_SCHEMA.COLUMNS t1
                    INNER JOIN PG_CLASS t2 ON (t2.RELNAME = t1.TABLE_NAME)
                    
                WHERE 
                    t1.TABLE_SCHEMA = 'public'
                    AND t1.TABLE_NAME='".$tabla."';";
        return $db->fetchAll($select);
    }
    public function llavesTabla($tabla)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT tc.table_name,
                    tc.constraint_name,
                    tc.constraint_type,
                    kcu.column_name,
                    ccu.table_name AS references_table,
                    ccu.column_name AS references_field
                    FROM information_schema.table_constraints tc
                    LEFT JOIN information_schema.key_column_usage kcu
                    ON tc.constraint_catalog = kcu.constraint_catalog
                    AND tc.constraint_schema = kcu.constraint_schema
                    AND tc.constraint_name = kcu.constraint_name
                    LEFT JOIN information_schema.referential_constraints rc
                    ON tc.constraint_catalog = rc.constraint_catalog
                    AND tc.constraint_schema = rc.constraint_schema
                    AND tc.constraint_name = rc.constraint_name
                    LEFT JOIN information_schema.constraint_column_usage ccu
                    ON rc.unique_constraint_catalog = ccu.constraint_catalog
                    AND rc.unique_constraint_schema = ccu.constraint_schema
                    AND rc.unique_constraint_name = ccu.constraint_name
                    WHERE lower(tc.constraint_type) in ('foreign key', 'primary key')
                    and tc.table_name='".$tabla."';";
        return $db->fetchAll($select);
    }
    
}
