<?php
/**
 * Created by PhpStorm.
 * User: jhonny
 * Date: 21/03/20
 * Time: 11:44 AM
 */

header('Access-Control-Allow-Origin: *');

require_once '../model/colegio.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$colegio_id = json_decode(file_get_contents("php://input"))->id;

try {
    $obj = new colegio();
    $obj->setId($colegio_id);

    $resultado = $obj->read();

    if($resultado){
        Funciones::imprimeJSON(200, "",$resultado);
    }else{
        Funciones::imprimeJSON(203, "No hay colegio","");
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}