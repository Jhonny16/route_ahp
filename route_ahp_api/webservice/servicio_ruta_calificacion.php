<?php

require_once '../model/servicio.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$id = json_decode(file_get_contents("php://input"))->ruta_servicio_id ;
$calificacion = json_decode(file_get_contents("php://input"))->calificacion ;

try {
    $obj = new servicio();
    $obj->setRutaServicioId($id);
    $obj->setCalificacion($calificacion);

    $resultado = $obj->update_calificacion();

    if($resultado){
        Funciones::imprimeJSON(200, "Gracias por su preferencia",$resultado);
    }else{
        Funciones::imprimeJSON(203, "No hay datos","");
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}
