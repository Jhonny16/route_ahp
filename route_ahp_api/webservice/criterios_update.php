<?php


header('Access-Control-Allow-Origin: *');

require_once '../model/criterio.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}
$token = $_SERVER["HTTP_TOKEN"];
$datos = json_decode(file_get_contents("php://input"))->p_datos;


try {


    $obj = new criterio();
    $obj->setGroup($datos);

    $result = $obj->update();
    if ($result) {
        Funciones::imprimeJSON(200, "Actualizados correctamente", $datos);
    } else {
        Funciones::imprimeJSON(203, "Error al momento de agregar", "");
    }


} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}