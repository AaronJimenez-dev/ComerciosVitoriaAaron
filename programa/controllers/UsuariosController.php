<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/ComerciosModel.php';

class UsuariosController extends BaseController {

    public function index() {
        $usuarios = UsuariosModel::getDatosMostrar();

        $this-> render('usuarios.view.php' ,[
            'usuarios' => $usuarios
        ]);
    }

    public function datosContacto() {
        $id = $_GET['id'];
        echo json_encode(UsuariosModel::getDatosContacto($id));
    }

    public function editar($id){
        $id = $_GET['id'];

        $usuario = UsuariosModel::getById($id);

        $this-> render('usuarios_edit_view.php',[
            'usuario' => $usuario
        ]);
    }      
    
    public function update($id){
        $id = $_GET['id'];

        $usuario = array(
            "id" => $_GET["id"],
            "nombre" => $_GET["nombre"],
            "apellidos" => $_GET["apellidos"],
            "email" => $_GET["email"],
            "fecha_nacimiento" => $_GET["fecha_nacimiento"],
            "telefono" => $_GET["telefono"],
        );

        $this->redirect('index.php?action=usuarios');
    }

    public function store($datos) {

    }
    
    public function destroy($id) {

    }
    
    public function destroyAll() {

    }
}