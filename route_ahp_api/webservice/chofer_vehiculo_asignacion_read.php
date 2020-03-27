<?php
/**
 * Created by PhpStorm.
 * User: jhonny
 * Date: 24/03/20
 * Time: 10:13 AM
 */

header('Access-Control-Allow-Origin: *');

require_once '../model/vehiculo.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$id = json_decode(file_get_contents("php://input"))->id;

try {
    $obj = new vehiculo();

    $resultado = $obj->read_asignacion($id);

    if($resultado){
        Funciones::imprimeJSON(200, "",$resultado);
    }else{
        Funciones::imprimeJSON(203, "No hay colegio","");
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}