<?php
/**
 * Created by PhpStorm.
 * User: jhonny
 * Date: 22/03/20
 * Time: 07:43 PM
 */

header('Access-Control-Allow-Origin: *');

require_once '../model/colegio.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$token = $_SERVER["HTTP_TOKEN"];
$direccion = json_decode(file_get_contents("php://input"))->direccion;
$id = json_decode(file_get_contents("php://input"))->colegio_id;

if ($direccion=="" or $direccion == null){
    Funciones::imprimeJSON(203, "Debe colocar direccion", "");
    exit();
}


try {
    $objeto = new colegio();
    $objeto->setId($id);
    $objeto->setDireccion($direccion);
    $res = $objeto->update_direccion();

    if ($res == true) {
        Funciones::imprimeJSON(200, "Se actualizo la direccion", $res);
    } else {
        Funciones::imprimeJSON(203, "Actualizo disponibilidad, no hay servicios pendientes", $res);
    }


} catch (Exception $exc) {
    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}
