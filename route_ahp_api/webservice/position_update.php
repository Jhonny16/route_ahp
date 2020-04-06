<?php


header('Access-Control-Allow-Origin: *');

require_once '../model/posicion.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$token = $_SERVER["HTTP_TOKEN"];
$servicio_id = json_decode(file_get_contents("php://input"))->servicio_id;
$latitud_actual = json_decode(file_get_contents("php://input"))->latitud_actual;
$longitud_actual = json_decode(file_get_contents("php://input"))->longitud_actual;


try {
    $objeto = new posicion();
    $res = $objeto->update_position($servicio_id, $latitud_actual, $longitud_actual);

    if ($res == true) {
        Funciones::imprimeJSON(200, "Se actualizo la posicion", $res);
    } else {
        Funciones::imprimeJSON(203, "Actualizo disponibilidad, no hay servicios pendientes", $res);
    }


} catch (Exception $exc) {
    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}
