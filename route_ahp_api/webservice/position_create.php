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
$pos_inicial = json_decode(file_get_contents("php://input"))->pos_inicial;
$pos_actual = json_decode(file_get_contents("php://input"))->pos_actual;
$pos_final = json_decode(file_get_contents("php://input"))->pos_final;

$objeto = new posicion();


$objeto->setServicioId($servicio_id);
$objeto->setPInicial($pos_inicial);
$objeto->setPActual($pos_actual);
$objeto->setPFinal($pos_final);

$res = $objeto->create();
if ($res) {
    Funciones::imprimeJSON(200, "Se guardo la posicion", $res);
} else {
    Funciones::imprimeJSON(203, "Error al guardar", "");
}


