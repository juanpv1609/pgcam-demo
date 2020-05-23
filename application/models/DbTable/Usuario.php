<?php

class Application_Model_DbTable_Usuario extends Zend_Db_Table_Abstract
{
    protected $_name = 'usuario';
    /**
     * listar_usuarios
     * * esta funcion retorna los usuarios 
     * * hace JOIN con perfiles y usu_estado
     */
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
    /**
     * listar_usuario
     * * esta funcion retorna informacion del usuario logeado
     * * hace JOIN con perfiles y usu_estado
     * @param usu_id: es el id del usuario logeado
     */

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
        WHERE usu_id=" . $usu_id;
        return $db->fetchRow($select);
    }
    /**
     * crearusuario
     * * esta funcion crea un nuevo usuaio
     * ! esta funcion sirve tanto desde la vista registro como para la vista crear usuario
     * @param nombre: es el nombre del usuario
     * @param apellido: es el apellido del usuario
     * @param correo: es el correo del usuario
     * @param clave: es el clave del usuario
     * @param perfil: es el perfil del usuario
     * @param estado: es el estado del usuario
     * ? se crea las iniciales haciendo substring del nombre y apellido
     */

    public function crearusuario($nombre, $apellido, $correo, $clave, $perfil, $estado)
    {
        $iniciales = substr($nombre, 0, 1) . substr($apellido, 0, 1);
        $iniciales = strtoupper($iniciales);
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "INSERT INTO usuario(usu_nombres,usu_apellidos,usu_iniciales, correo, clave,perf_id,usu_estado_id,fecha_creacion)
                    VALUES ('" . $nombre . "','" . $apellido . "','" . $iniciales . "','" . $correo . "',MD5('" . $clave . "')," . $perfil . "," . $estado . ",(SELECT now())); ";
        return $db->fetchRow($select);
    }    
    /**
     * actualizarEstadoUsuario
     * * esta funcion cambia el estado de un usuario
     * ! esta funcion esta permitida solo para el perfil administrador
     * @param id: es el id del usuario que se desea cambiar su estado
     * @param estado: es el estado del usuario que se desea cambiar su estado
     */

    public function actualizarEstadoUsuario($id, $estado)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE usuario
                SET  usu_estado_id=" . $estado . "
                WHERE usu_id=" . $id . ";";
        return $db->fetchRow($select);
    }
    /**
     * actualizarusuario
     * * esta funcion cambia la informacion de un usuario
     * ! esta funcion esta permitida solo para el perfil administrador
     * @param id: es el id del usuario que se desea actualizar
     * @param nombre: es el nombre del usuario que se desea actualizar
     * @param apellido: es el apellido del usuario que se desea actualizar
     * @param correo: es el correo del usuario que se desea actualizar
     * @param perfil: es el perfil del usuario que se desea actualizar
     * @param estado: es el estado del usuario que se desea actualizar
     */

    public function actualizarusuario($id, $nombre, $apellido, $correo, $perfil, $estado)
    {
        $iniciales = substr($nombre, 0, 1) . substr($apellido, 0, 1);
        $iniciales = strtoupper($iniciales);

        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE usuario
        SET usu_nombres='" . $nombre . "', correo='" . $correo . "', usu_estado_id=" . $estado . ",
            perf_id=" . $perfil . ", usu_apellidos='" . $apellido . "', usu_iniciales='" . $iniciales . "'
      WHERE usu_id=" . $id . ";";
        return $db->fetchRow($select);
    }
    /**
     * actualizarinfousuario
     * * esta funcion cambia la informacion de un usuario
     * ! esta funcion esta permitida solo por el usuario logueado
     * ! para esto es necesario confirmar la contrasenia
     * ? no puedeo modificar el estado, esto solo lo hace el administrador
     * @param id: es el id del usuario que se desea actualizar
     * @param nombre: es el nombre del usuario que se desea actualizar
     * @param apellido: es el apellido del usuario que se desea actualizar
     * @param correo: es el correo del usuario que se desea actualizar
     * @param confirma_clave: se debe verifiacar la clave del usuario
     */

    public function actualizarinfousuario($id, $nombre, $apellido, $correo,$confirma_clave)
    {
        $iniciales = substr($nombre, 0, 1) . substr($apellido, 0, 1);
        $iniciales = strtoupper($iniciales);

        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE usuario
        SET usu_nombres='" . $nombre . "', correo='" . $correo . "', usu_apellidos='" . $apellido . "', usu_iniciales='" . $iniciales . "'
      WHERE usu_id=" . $id . "
      AND clave=MD5('".$confirma_clave."');";
        return $db->fetchRow($select);
    }
    /**
     * actualizarclaveusuario
     * * esta funcion actualiza la clave de un usuario
     * ! esta funcion esta permitida solo por el usuario logueado
     * ? se debe confirmar la clave actual
     * @param id: es el id del usuario que se desea actualizar
     * @param clave_actual: es la clave_actual del usuario 
     * @param nueva_clave: es la nueva_clave del usuario que se desea actualizar
     */

    public function actualizarclaveusuario($id, $clave_actual, $nueva_clave)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE usuario
        SET clave=MD5('" . $nueva_clave . "')
      WHERE usu_id=" . $id . "
      AND clave=MD5('" . $clave_actual . "');";
        return $db->fetchRow($select);
    }
     /**
     * actualizarclave_recuperacion
     * * esta funcion actualiza la clave de un usuario
     * ! esta funcion esta permitida desde el formulario RECUPERAR CLAVE
     * ? se envia un correo con la clave provisional, la cual debe ser cambiada personalmente
     * ? en el proximo inicio de sesion
     * @param correo: es el correo del usuario que se desea actualizar
     * @param nueva_clave: es la nueva_clave del usuario que se desea actualizar CODIGO PSEUDOALEATORIO 
     */
    public function actualizarclave_recuperacion($correo, $nueva_clave)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE usuario
        SET clave=MD5('" . $nueva_clave . "')
        WHERE correo='" . $correo . "';";
        return $db->fetchRow($select);
    }
    /**
     * actualizar_ultima_conexion_usuario
     * * esta funcion actualiza la ultima conexion de un usuario
     * ! esta funcion se ejecuta cuando el usuario termina la sesion
     * ? se envia un correo con la clave provisional, la cual debe ser cambiada personalmente
     * ? en el proximo inicio de sesion
     * @param id: es el id del usuario que se desea actualizar
     */

    public function actualizar_ultima_conexion_usuario($id)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "UPDATE usuario
        SET ultima_conexion=(select current_timestamp(0))
       WHERE usu_id=" . $id . ";";
        return $db->fetchRow($select);
    }
    
    /**
     * eliminarusuario
     * * esta funcion eliminar un usuario
     * @param id: es el id del usuario que se desea eliminar
     */

    public function eliminarusuario($id)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "DELETE FROM usuario
        WHERE usu_id=" . $id . ";";
        return $db->fetchRow($select);
    }
    /**
     * obtienePerfil
     * * esta funcion obtiene el perfil de un usuario
     * ? necesario para establecer la ruta por defecto al momento de iniciar sesion
     * @param id: es el id del usuario 
     */
    public function obtienePerfil($id)
    {
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "SELECT *
        FROM usuario u
        JOIN perfiles p
        ON p.perf_id=u.perf_id
        WHERE u.usu_id=" . $id;
        return $db->fetchRow($select);
    }
   /**
     * listar_estado
     * * esta funcion lista los estados de la tabla estado_usuario
     * ? ACTIVO / INACTIVO
     * * necesarios para la creacion o edicion de usuarios
     */
    public function listar_estado()
    {
        //devuelve todos los registros de la tabla
        $db = Zend_Registry::get('pgdb');
        //opcional, esto es para que devuelva los resultados como objetos $row->campo
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $select = "select * from usu_estado";
        return $db->fetchAll($select);
    }
    /**
     * enviaEmail
     * * esta funcian generica envia un email mediante SMTP al usuario
     * * ejemplo: creacion de cuenta, recuperacion de contraseña
     * @param destinatario: es la direccion a quien se envia el correo
     * @param contenido: es el contenido 
     * @param asunto: es el asunto 
     * ! en ambiente de desarrollo se usa el smtp.mailtrap.io
     * ? si se desea usar en produccion cambiar las variables por el proveedor de correo: gmail, zimbra, etc
     */

    public function enviaEmail($destinatario, $contenido, $asunto)
    {
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
    /**
     * leeEmail
     * * esta funcian generica lee y muestra los correos mediante SMTP al usuario
     * * ejemplo: creacion de cuenta, recuperacion de contraseña
     * ! no se encuentra habilitada
     */

    public function leeEmail()
    {
        $mail = new Zend_Mail_Storage_Pop3(array(
            'auth' => 'login',
            'host'     => 'pop3.mailtrap.io',
            'user'     => 'f2d6e876eeddf3',
            'password' => '13f1714f4f2789',
            'port' => 1100));
        return $mail;
    }
}
