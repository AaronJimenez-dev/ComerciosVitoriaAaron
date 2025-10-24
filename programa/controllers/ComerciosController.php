<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/ComerciosModel.php';

require_once __DIR__ . '/ImagenesController.php';
require_once __DIR__ . '/CategoriasController.php';
require_once __DIR__ . '/UsuariosController.php';

require_once __DIR__ . '/../models/ImagenesModel.php';
require_once __DIR__ . '/../models/CategoriasModel.php';
require_once __DIR__ . '/../models/UsuariosModel.php';


class ComerciosController extends BaseController {
    
    public function index() {
        //coger todos los anuncios
        $anuncios = ComerciosModel::getAll();

        $this->render('index.view.php', [
            'anuncios' => $anuncios
        ]);
    }
    
    public function editar() {
        $id = $_POST['id'];

        $anuncio = ComerciosModel::getById($id);
        $imagenes = ImagenesModel::getByAnuncio($id);
        $categorias = CategoriasModel::getAll();

        $this->render('editarAnuncio.php', [
            'anuncio' => $anuncio,
            'imagenes' => $imagenes,
            'categorias' => $categorias
        ]);
    }

    
    public function store() {
        session_start();
        $anuncio = array(
            "titulo" => $_POST["titulo"],
            "descripcion" => $_POST["descripcion"],
            "direccion" => $_POST["direccion"],
            "precio" => $_POST["precio"],
            "id_usuario" => $_SESSION['id'],
            "id_categoria" => $_POST["id_categoria"]
        );
        var_dump($_FILES['imagenes']);
        $imagenes = $_FILES['imagenes'] ?? null;
        if (!empty($imagenes))
            ComerciosModel::create($anuncio,$imagenes);
        $this->redirect('index.php');
    }

    public function update(){
        $id = $_POST['id'];
        $anuncio = [
            $id,
            $_POST['titulo'],
            $_POST['descripcion'],
            $_POST['direccion'],
            $_POST['precio'],
            $_POST['categoria']
        ];

        // Actualizar anuncio
        ComerciosModel::edit($anuncio);

        // Actualizar imágenes si se envían
        $imagenesNuevas = $_FILES['imagenes'] ?? [];
        $imagenesBorrar = json_decode($_POST['imagenesBorradas'] ?? '[]', true);
        if (!empty($imagenesNuevas) || !empty($imagenesBorrar)) {
            ImagenesModel::edit($id, $imagenesNuevas, $imagenesBorrar);
        }

        // Redirigir al listado de sus anuncios
        $this->redirect('perfilUsuario.php');
    }

    public function desactivar(){
        header('Content-Type: application/json');

        //coger el JSON y transformarlo utilizando la funcion file_get_contents()
        $data = json_decode(file_get_contents('php://input'), true);

        $id = $data['id_anuncio'] ?? null;
        $desactivar = $data['desactivar'] ?? null;

        if (!$id) {
            echo json_encode(['error' => 'Datos incompletos']);
            exit;
        }

        ComerciosModel::desactivar($id,$desactivar);
        echo json_encode(['succes' => true]);
    }

    
    public function destroy() {
        $id = $_POST["id"];
        ImagenesModel::deleteById($id);
        ComerciosModel::deleteById($id);
        $this->redirect('index.php');
    }
    
    /*public function destroyAll() {
        ComerciosModel::deleteAll();
        $this->redirect('index.php');
    }*/
}