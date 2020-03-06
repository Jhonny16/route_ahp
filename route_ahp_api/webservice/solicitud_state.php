<?php

require_once '../model/solicitud.php';
require_once '../util/funciones/Funciones.clase.php';
require_once 'tokenvalidar.php';

if (!isset($_SERVER["HTTP_TOKEN"])) {
    Funciones::imprimeJSON(500, "Debe especificar un token", "");
    exit();
}

$referencia_id = json_decode(file_get_contents("php://input"))->referencia_id;


try {
    $obj = new solicitud();
    $obj->setReferenciaId($referencia_id);

    $resultado = $obj->update();

    if($resultado){

        Funciones::imprimeJSON(200, "Has aceptado la solicitud",$resultado);
    }

} catch (Exception $exc) {

    Funciones::imprimeJSON(500, $exc->getMessage(), "");
}
