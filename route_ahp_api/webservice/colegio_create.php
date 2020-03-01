<?php

require_once '../model/colegio.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';


if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$numero = json_decode(file_get_contents("php://input"))->numero;
$nombre = json_decode(file_get_contents("php://input"))->nombre;
$direccion = json_decode(file_get_contents("php://input"))->direccion;
$latitud = json_decode(file_get_contents("php://input"))->latitud;
$longitud = json_decode(file_get_contents("php://input"))->longitud;
$director = json_decode(file_get_contents("php://input"))->director;


try {


    $objper = new colegio();
    $objper->setNumero($numero);
    $objper->setNombre($nombre);
    $objper->setDireccion($direccion);
    $objper->setLatitud($latitud);
    $objper->setLongitud($longitud);
    $objper->setDirector($director);

    $result = $objper->create();
    if ($result) {
        Funciones::imprimeJSON(200, "Agregado Correcto", $result);
    } else {
        Funciones::imprimeJSON(203, "Error al momento de agregar", "");
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}
