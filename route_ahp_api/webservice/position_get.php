<?php

header('Access-Control-Allow-Origin: *');

require_once '../model/posicion.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$servicio_id = json_decode(file_get_contents("php://input"))->servicio_id;


try {
    $obj = new posicion();
    $obj->setServicioId($servicio_id);
    $resultado = $obj->get_position_now();

    Funciones::imprimeJSON(200, "",$resultado);

} catch (Exception $exc) {
    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}