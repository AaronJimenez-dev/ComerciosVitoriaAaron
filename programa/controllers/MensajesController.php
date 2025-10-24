<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/MensajesModel.php';

class MensajesController extends BaseController {
    
    public static function getConversaciones(){
        header('Content-Type: application/json');
        $id_emisor = $_GET['id_emisor'] ?? null;
        echo json_encode(MensajesModel::getConversaciones($id_emisor));
    }

    public static function getMensajes(){
        header('Content-Type: application/json');
        $id_emisor = $_GET['id_emisor'] ?? null;
        $id_receptor = $_GET['id_receptor'] ?? null;
        if (!$id_emisor || !$id_receptor) {
            echo json_encode(['error' => 'Datos incompletos']);
            exit;
        }
        echo json_encode(MensajesModel::getMensajes($id_emisor,$id_receptor));
    }

    public static function sendMensajes(){
        header('Content-Type: application/json');

        //coger el JSON y transformarlo utilizando la funcion file_get_contents()
        $data = json_decode(file_get_contents('php://input'), true);

        $id_emisor = $data['id_emisor'] ?? null;
        $id_receptor = $data['id_receptor'] ?? null;
        $mensaje = $data['mensaje'] ?? null;

        if (!$id_emisor || !$id_receptor || !$mensaje) {
            echo json_encode(['error' => 'Datos incompletos']);
            exit;
        }
        MensajesModel::sendMensajes($id_emisor,$id_receptor,$mensaje);
        echo json_encode(['succes' => true]);
    }
}