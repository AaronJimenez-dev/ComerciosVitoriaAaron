<?php
require_once __DIR__ . '/Database.php';

class ComerciosModel {
    
    public static function getAll() {
        $dbh = Database::getConnection();
        $stmt = $dbh->prepare("SELECT *
                                FROM anuncios
                                WHERE estado = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $dbh = Database::getConnection();
        $stmt = $dbh->prepare("SELECT *
                                FROM anuncios
                                WHERE id = :id");
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function create($datos,$imagenes = []) {
        $dbh = Database::getConnection();
        $stmt= $dbh->prepare("INSERT INTO anuncios(titulo,descripcion,direccion,precio,id_usuario,id_categoria,estado)
                            values (:titulo,:descripcion,:direccion,:precio,:id_usuario,:id_categoria,true)"
        );
        $data = array(
            "titulo" => $datos["titulo"], 
            "descripcion" => $datos["descripcion"], 
            "direccion" => $datos["direccion"], 
            "precio" => $datos["precio"], 
            "id_usuario" => $datos["id_usuario"], 
            "id_categoria" => $datos["id_categoria"]
        );
        if($stmt->execute($data)){
            echo "correcto";
            if ($imagenes && isset($imagenes['tmp_name'])) 
                ImagenesModel::create($dbh,$imagenes);
        }else{
            throw new Exception("No se pudo añadir el anuncio a la base de datos");
        }    
    }
    
    public static function deleteById($id) {
        $dbh = Database::getConnection();
        $stmt= $dbh->prepare("DELETE FROM anuncios
                                WHERE id = :id"
        );
        $data = array('id' => $id);
        if (!$stmt->execute($data)):
            throw new Exception("No se pudo borrar el anuncio a la base de datos");
        endif;
    }
    
    public static function deleteAll($id_usuario) {
        $dbh = Database::getConnection();
        $stmt= $dbh->prepare("DELETE FROM anuncios
                                WHERE id_usuario = :id_usuario");
        if(!$stmt->execute([
            'id_usuario' => $id_usuario
        ])):
            throw new Exception("No se pudo borrar ningún anuncio de la base de datos");
        endif;
    }
    
    public static function edit($datos) {
        $dbh = Database::getConnection();
        $stmt= $dbh->prepare("UPDATE anuncios
                            SET titulo = :titulo, descripcion = :descripcion, direccion = :direccion, precio = :precio, id_categoria = :id_categoria
                            WHERE id = :id"
        );
        $data = array(
            "id" => $datos[0],
            "titulo" => $datos[1],
            "descripcion" => $datos[2],
            "direccion" => $datos[3],
            "precio" => $datos[4],
            "id_categoria" => $datos[5]
        );
        if(!$stmt->execute($data)) throw new Exception("No se pudo editar el anuncio");
    }

    public static function desactivar($id,$desactivar) {
        $dbh = Database::getConnection();
        $stmt= $dbh->prepare("UPDATE anuncios
                            SET estado = :desactivar
                            WHERE id = :id"
        );
        $data = array(
            "id" => $id,
            "desactivar" => $desactivar
        );
        if(!$stmt->execute($data)) throw new Exception("No se pudo editar el estado del anuncio");
    }
}