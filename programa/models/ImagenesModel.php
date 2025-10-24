<?php
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../config/config.php';

class ImagenesModel {

    // Devuelve todas las imágenes de un anuncio específico
    public static function getByAnuncio($id_anuncio) {
        $dbh = Database::getConnection();
        $stmt = $dbh->prepare("SELECT id,ruta FROM imagenes WHERE id_anuncio = :id_anuncio");
        $stmt->execute(['id_anuncio' => $id_anuncio]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve array vacío si no hay imágenes
    }

    public static function getById($id) {
        $dbh = Database::getConnection();
        $stmt = $dbh->prepare("SELECT ruta
                                FROM imagenes
                                WHERE id = :id");
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($dbh,$imagenes,$id = null){
        $id ?? $id = $dbh->lastInsertId(); //Si no llega un id, el id es el ultimo insertado
        self::addDirectory($dbh,$imagenes,$id);
    }
    
    private static function addDirectory($dbh,$imagenes,$id){

        // Crea la carpeta para las imágenes
        $carpetaCrear = MEDIA_FOLDER . $id;

        //Comprobar la existencia de la carpeta
         if (!file_exists($carpetaCrear)) mkdir($carpetaCrear, 0777, true);
            
        if (self::uploadImage($dbh, $imagenes,$id)):
            return true;
        else:
            throw new Exception("No se pudieron añadir las imagenes a la base de datos.");
        endif;
    }

    private static function uploadImage($dbh, $imagenes,$id_anuncio){
        /*
        tmp_name es un valor de $_FILES, que es una varialbe que guarda información de los archivos
        que se envian como type=file.
        tmp_name es la ruta temporal en la que php ha guardado esta imagen, asi que en el if
        comprobbamos que realmente se ha guardado.
        */
        $subidasCorrectas = 0;
        foreach ($imagenes['tmp_name'] as $index => $carpetaTemporal){
            if (is_uploaded_file($carpetaTemporal)) {

                //Aqui usamos basename para coger solo el nombre de la imagen, sin la ruta tmp_name
                $nombreArchivo = basename($imagenes['name'][$index]);

                $destino = MEDIA_FOLDER . $id_anuncio . '/' . $nombreArchivo;

                if (move_uploaded_file($carpetaTemporal, $destino)):
                    $stmt = $dbh->prepare("
                        INSERT INTO imagenes (id_anuncio, ruta)
                        VALUES (:id_anuncio, :ruta)
                    ");

                    if ($stmt->execute([
                        'id_anuncio' => $id_anuncio,
                        'ruta' => $destino
                    ])) {
                        $subidasCorrectas++;
                    }

                endif;
            }
        }
        return $subidasCorrectas;
    }

    private static function eliminarImagenServidor($ruta){
        if (file_exists($ruta)) {
            if (!unlink($ruta)) {
                throw new Exception("No se pudo eliminar la imagen del servidor");
            }
        }
    }
    
    public static function deleteById($id) {
        $dbh = Database::getConnection();
        $ruta = self::getById($id)["ruta"]  ;
        
        self::eliminarImagenServidor($ruta);

        $stmt= $dbh->prepare("DELETE FROM imagenes
                                WHERE id = :id"
        );
        $data = array('id' => $id);

        if (!$stmt->execute($data)):
            throw new Exception("No se pudo borrar la imagen a la base de datos");
        endif;
    }
    
    public static function deleteAll($id_anuncio) {
        $dbh = Database::getConnection();
        foreach (self::getByAnuncio($id_anuncio) as $imagen){
            self::eliminarImagenServidor($imagen['ruta']);
        }

        $stmt= $dbh->prepare("DELETE FROM imagenes
                                WHERE id_anuncio = :id_anuncio");
        if(!$stmt->execute([
            'id_anuncio' => $id_anuncio
        ])):
            throw new Exception("No se pudo borrar ninguna imagen de la base de datos");
        endif;
    }

    public static function edit($id,$imagenesNuevas,$imagenesBorrar) {
        $dbh = Database::getConnection();
        if($imagenesNuevas["error"][0] == 0)
            self::create($dbh,$imagenesNuevas,$id);
        foreach($imagenesBorrar as $imagenId){
            self::deleteById($imagenId);
        }
    }
}