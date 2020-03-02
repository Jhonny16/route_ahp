<?php

require_once '../model/servicio.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$chofer_id = json_decode(file_get_contents("php://input"))->chofer_id;


try {
    $obj = new servicio();
    $resultado = $obj->lista_servicios_chofer($chofer_id);

    if($resultado){
        Funciones::imprimeJSON(200, "",$resultado);
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}