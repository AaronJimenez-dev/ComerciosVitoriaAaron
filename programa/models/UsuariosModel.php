<?php
require_once __DIR__ . '/Database.php';

class UsuariosModel {
    
    public static function getAll() {
        $dbh = Database::getConnection();
        $stmt = $dbh->prepare("SELECT *
                                FROM usuarios");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getDatosContacto($id_usuario) {
    $dbh = Database::getConnection();
    $stmt = $dbh->prepare("SELECT email, telefono 
                           FROM usuarios 
                           WHERE id = :id_usuario");
    $stmt->execute(['id_usuario' => $id_usuario]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getDatosMostrar() {
    $dbh = Database::getConnection();
    $stmt = $dbh->prepare("SELECT nombre, apellidos, email, user_name 
                            FROM usuarios");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function getById($id) {
        $dbh = Database::getConnection();
        $stmt = $dbh->prepare("SELECT *
                                FROM usuarios
                                WHERE id = :id");
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getIdByUsername($username) {
        $dbh = Database::getConnection();
        $stmt = $dbh->prepare("SELECT id
                                FROM usuarios
                                WHERE user_name = :user_name");
        $stmt->execute([
            'user_name' => $username
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function create($datos) {
        $dbh = Database::getConnection();
        $stmt= $dbh->prepare("INSERT INTO usuarios(user_name,nombre,apellidos,email,password,tipo_usuario,fecha_nacimiento,telefono)
                            values (:user_name,:nombre,:apellidos,:email,:password, default,:fecha_nacimiento,:telefono)"
        );
        $data = array(
            "user_name" => $datos[0],
            "nombre" => $datos[1],
            "apellidos" => $datos[2],
            "email" => $datos[3],
            "password" => $datos[4],
            "fecha_nacimiento" => $datos[5],
            "telefono" => $datos[6] ?? null
        );
        if(!$stmt->execute($data)) throw new Exception("No se pudo añadir el usuario a la base de datos");
    }
    
    public static function deleteById($id) {
        $dbh = Database::getConnection();
        $stmt= $dbh->prepare("DELETE FROM usuarios
                                WHERE id = :id"
        );
        $data = array('id' => $id);
        if (!$stmt->execute($data)):
            throw new Exception("No se pudo borrar el usuario a la base de datos");
        endif;
    }
    
    public static function deleteAll() {
        $dbh = Database::getConnection();
        $stmt= $dbh->prepare("DELETE FROM usuarios");
        if(!$stmt->execute()) throw new Exception("No se pudo borrar ningún anuncio de la base de datos");
    }
    
    public static function edit($datos) {
        $dbh = Database::getConnection();
        $stmt= $dbh->prepare("UPDATE usuarios
                            SET nombre = :nombre, apellidos = :apellidos, email = :email, fecha_nacimiento = :fecha_nacimiento, telefono = :telefono
                            WHERE id = :id"
        );
        $data = array(
            "id" => $datos[0],
            "nombre" => $datos[1],
            "apellidos" => $datos[2],
            "email" => $datos[3],
            "fecha_nacimiento" => $datos[4],
            "telefono" => $datos[5]
        );
        if(!$stmt->execute($data)) throw new Exception("No se pudo editar el perfil del usuario");
    }
}