<?php

require_once '../model/persona.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$empresa_id = json_decode(file_get_contents("php://input"))->empresa_id;
$chofer_id = json_decode(file_get_contents("php://input"))->chofer_id;


try {
    $obj = new persona();
    $obj->setEmpresa($empresa_id);
    $resultado = $obj->choferes_list($chofer_id);

    if($resultado){
        Funciones::imprimeJSON(200, "",$resultado);
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}
