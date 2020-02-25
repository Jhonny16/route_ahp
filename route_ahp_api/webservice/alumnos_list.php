<?php

require_once '../model/persona.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$apoderado_id = json_decode(file_get_contents("php://input"))->apoderado_id;


try {
    $obj = new persona();
    $obj->setApoderadoId($apoderado_id);
    $resultado = $obj->alumnos_list();

    if($resultado){
        Funciones::imprimeJSON(200, "",$resultado);
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}