<?php

require_once '../model/solicitud.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$solicitud_id = json_decode(file_get_contents("php://input"))->referencia_id;
$state = json_decode(file_get_contents("php://input"))->estado;


try {
    $obj = new solicitud();
    $obj->setId($solicitud_id);
    $obj->setEstado($state);

    $resultado = $obj->update();

    if($resultado){
        Funciones::imprimeJSON(200, "",$resultado);
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}
