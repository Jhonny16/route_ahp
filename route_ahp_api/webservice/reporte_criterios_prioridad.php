<?php
header('Access-Control-Allow-Origin: *');

require_once '../model/reporte.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}
$token = $_SERVER["HTTP_TOKEN"];

$objeto = new reporte();

$res = $objeto->criterios_prioridad_reciclador();
if ($res) {
    Funciones::imprimeJSON(200, "Consulta exitosa", $res);
} else {
    Funciones::imprimeJSON(203, "No hubo resutados en la busqueda.", $res);
}
