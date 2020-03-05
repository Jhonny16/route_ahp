<?php

require_once '../model/referencia.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$solicitud_id = json_decode(file_get_contents("php://input"))->solicitud_id ;


try {
    $obj = new referencia();
    $resultado = $obj->detalle($solicitud_id);
    if($resultado){
        Funciones::imprimeJSON(200, "",$resultado);
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}