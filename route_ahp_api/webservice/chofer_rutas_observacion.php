<?php

require_once '../model/ruta_servicio.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$ruta_servicio_id = json_decode(file_get_contents("php://input"))->ruta_servicio_id;
$observacion = json_decode(file_get_contents("php://input"))->observacion;


try {


    $obj = new ruta_servicio();
    $obj->setId($ruta_servicio_id);
    $obj->setObservacion($observacion);

    $resultado = $obj->observacion();

    if($resultado){
        Funciones::imprimeJSON(200, "Se actualizo la hora de salida",$resultado);
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}