<?php

class Application_Model_DbTable_Usuario extends Zend_Db_Table_Abstract
{

    protected $_name = 'usuario';
    
    public function insertarusuario($nombre,$apellido, $correo, $clave,$comboPerfil) {
        $iniciales=substr($nombre,0,1).substr($apellido,0,1);
        $iniciales = strtoupper($iniciales);
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO usuario(usu_nombres,usu_apellidos,usu_iniciales, correo, clave,usu_estado_id,perf_id,fecha_creacion)
                    VALUES ('".$nombre."','".$apellido."','".$iniciales."','".$correo."',MD5('".$clave."'),2,".$comboPerfil.",(SELECT now())); ";
                    return $db->fetchRow($select);
    }
    public function crearusuario($nombre,$apellido, $correo, $clave ,$perfil,$estado) {
        $iniciales=substr($nombre,0,1).substr($apellido,0,1);
        $iniciales = strtoupper($iniciales);

        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO usuario(usu_nombres,usu_apellidos,usu_iniciales, correo, clave,perf_id,usu_estado_id,fecha_creacion)
                    VALUES ('".$nombre."','".$apellido."','".$iniciales."','".$correo."',MD5('".$clave."'),".$perfil.",".$estado.",(SELECT now())); ";
                    return $db->fetchRow($select);
    }
    public function actualizarEstadoUsuario($id,$estado) {

        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE usuario
                SET  usu_estado_id=".$estado."
                WHERE usu_id=".$id.";";
         return $db->fetchRow($select);
    }
    public function actualizarusuario($id,$nombre,$apellido, $correo ,$perfil,$estado) {
        $iniciales=substr($nombre,0,1).substr($apellido,0,1);
        $iniciales = strtoupper($iniciales);

        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE usuario
        SET usu_nombres='".$nombre."', correo='".$correo."', usu_estado_id=".$estado.", 
            perf_id=".$perfil.", usu_apellidos='".$apellido."', usu_iniciales='".$iniciales."'
      WHERE usu_id=".$id.";";
                    return $db->fetchRow($select);
    }
    public function actualizarinfousuario($id,$nombre,$apellido, $correo ) {
        $iniciales=substr($nombre,0,1).substr($apellido,0,1);
        $iniciales = strtoupper($iniciales);

        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE usuario
        SET usu_nombres='".$nombre."', correo='".$correo."', usu_apellidos='".$apellido."', usu_iniciales='".$iniciales."'
      WHERE usu_id=".$id.";";
          return $db->fetchRow($select);
    }
    public function actualizarclaveusuario($id,$nueva_clave ) {
        
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE usuario
        SET clave=MD5('".$nueva_clave."')
      WHERE usu_id=".$id.";";
    return $db->fetchRow($select);
    }
    public function actualizarclave_recuperacion($correo,$nueva_clave) {
        
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE usuario
        SET clave=MD5('".$nueva_clave."')
        WHERE correo='".$correo."';";
        return $db->fetchRow($select);
    }
    public function actualizar_ultima_conexion_usuario($id) {
        
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE usuario
        SET ultima_conexion=(select current_timestamp(0)) 
       WHERE usu_id=".$id.";";
                    return $db->fetchRow($select);
    }
    public function obtiene_ultima_conexion_usuario($id) {
        
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT DATE_PART('day',current_timestamp(0)::timestamp -(SELECT ultima_conexion
        FROM usuario
        WHERE usu_id=".$id.")::timestamp) as dias,DATE_PART('hour',current_timestamp(0)::timestamp -(SELECT ultima_conexion
        FROM usuario
        WHERE usu_id=".$id.")::timestamp) as horas,DATE_PART('min',current_timestamp(0)::timestamp -(SELECT ultima_conexion
        FROM usuario
        WHERE usu_id=".$id.")::timestamp) as min;;";
                    return $db->fetchRow($select);
    }
    public function eliminarusuario($id) {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "DELETE FROM usuario
        WHERE usu_id=".$id.";";
        return $db->fetchRow($select);
        //$this->listar();
    }
    public function obtienePerfil($perfil_id)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT *
        FROM usuario u
        JOIN perfiles p
        ON p.perf_id=u.perf_id
        WHERE u.usu_id=".$perfil_id;
        return $db->fetchRow($select);
    }
    public function existeUsuario($correo)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT correo
        FROM usuario
        WHERE correo='".$correo."';";
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
    public function listar_usuario($usu_id)
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
        ON e.usu_estado_id=u.usu_estado_id
        WHERE usu_id=".$usu_id;
        return $db->fetchRow($select);
    }
    public function cuenta_usuarios_perfil($perfil_id)
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select u.usu_iniciales from perfiles p
        join usuario u
        on p.perf_id=u.perf_id
        where p.perf_id=".$perfil_id;
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
    public function listar_estado()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from usu_estado";
        return $db->fetchAll($select);
    }
    public function enviaEmail($destinatario,$contenido,$asunto){
        // ---2) configuración del smtp y datos para realizar la autenticación en el mismo 
        $config = array( 
            'auth' => 'login', 
            'username' => 'f2d6e876eeddf3', 
            'password' => '13f1714f4f2789', 
            'port' => 2525); 
            
        $transport = new Zend_Mail_Transport_Smtp('smtp.mailtrap.io', $config); 
        // generacion de clave aleatoria
        
        $mail = new Zend_Mail("UTF-8"); 
        $mail->setBodyHtml($contenido);
        $mail->setFrom('juanpv1609@gmail.com', 'PG-CAM Admin'); //quien envia el correo
        $mail->addTo($destinatario); //destinatario
        $mail->setSubject($asunto);                        
        $mail->send($transport);           
            
    }
}

