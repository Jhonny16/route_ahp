<?php

require_once '../model/servicio.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$detalle_referencia = json_decode(file_get_contents("php://input"))->detalle_referencia;
$empresa_id = json_decode(file_get_contents("php://input"))->empresa_id;
$conductor_vehiculo_id = json_decode(file_get_contents("php://input"))->conductor_vehiculo_id;

try {
    $obj = new servicio();
    $obj->setReferenciaId($detalle_referencia);
    $obj->setEmpresaId($empresa_id);
    $resultado = $obj->create_servicio($conductor_vehiculo_id);
    if($resultado){
        Funciones::imprimeJSON(200, "Se generó el servicio ",$resultado);
    }else{
        Funciones::imprimeJSON(203, "No hay datos","");
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}

