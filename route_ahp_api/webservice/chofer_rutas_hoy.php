<?php

require_once '../model/ruta_servicio.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$chofer_id = json_decode(file_get_contents("php://input"))->chofer_id;
$servicio_id = json_decode(file_get_contents("php://input"))->servicio_id;


try {
    $obj = new ruta_servicio();
    $resultado = $obj->rutas_chofer_hoy($servicio_id,$chofer_id);

    if($resultado){
        Funciones::imprimeJSON(200, "",$resultado);
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}