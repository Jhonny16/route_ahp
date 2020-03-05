<?php

require_once '../model/vehiculo.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$vehiculo_id = json_decode(file_get_contents("php://input"))->vehiculo_id;
$chofer_id = json_decode(file_get_contents("php://input"))->chofer_id;
$fecha_inicio = json_decode(file_get_contents("php://input"))->fecha_inicio;
$fecha_fin = json_decode(file_get_contents("php://input"))->fecha_fin;


try {


    $obj = new vehiculo();

    $resultado = $obj->lista_asignacion($vehiculo_id, $chofer_id, $fecha_inicio, $fecha_fin);
    if($resultado){
        Funciones::imprimeJSON(200, "Se actualizo la hora de salida",$resultado);
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}