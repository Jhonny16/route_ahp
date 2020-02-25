<?php

require_once '../model/colegio.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$colegio_id = json_decode(file_get_contents("php://input"))->colegio_id;


try {
    $obj = new colegio();
    $obj->setId($colegio_id);

    $resultado = $obj->lista();
    if($resultado){
        Funciones::imprimeJSON(200, "",$resultado);
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}
