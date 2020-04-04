<?php
/**
 * Created by PhpStorm.
 * User: jhonny
 * Date: 21/03/20
 * Time: 11:44 AM
 */

header('Access-Control-Allow-Origin: *');

require_once '../model/licencia_certificado.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$conductor_id = json_decode(file_get_contents("php://input"))->conductor_id;

try {
    $obj = new licencia_certificado();
    $obj->setChoferId($conductor_id);

    $resultado = $obj->list_driver();

    if($resultado){
        Funciones::imprimeJSON(200, "",$resultado);
    }else{
        Funciones::imprimeJSON(203, "No hay colegio","");
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}