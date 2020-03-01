<?php

require_once '../model/ruta_servicio.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$servicio_detalle_id = json_decode(file_get_contents("php://input"))->servicio_detalle_id;
$fecha = json_decode(file_get_contents("php://input"))->fecha;

try {
    $obj = new ruta_servicio();
    $obj->setServicioDetalleId($servicio_detalle_id);
    $obj->setFecha($fecha);
    $resultado = $obj->ruta_servicio_consulta();
    if($resultado){
        Funciones::imprimeJSON(200, "",$resultado);
    }else{
        Funciones::imprimeJSON(203, "No hay datos","");
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}

