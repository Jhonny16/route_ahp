<?php
/**
 * Created by PhpStorm.
 * User: jhonny
 * Date: 19/03/20
 * Time: 11:47 PM
 */
header('Access-Control-Allow-Origin: *');

require_once '../model/vehiculo.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$vehiculo_id = json_decode(file_get_contents("php://input"))->id;

try {
    $obj = new vehiculo();
    $obj->setId($vehiculo_id);

    $resultado = $obj->read();

    if($resultado){
        Funciones::imprimeJSON(200, "",$resultado);
    }else{
        Funciones::imprimeJSON(203, "No hay periodos","");
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}